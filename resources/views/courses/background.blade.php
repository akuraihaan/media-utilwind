@extends('layouts.landing')
@section('title', 'Background Tailwind CSS')

@section('content')

<script>
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;700&display=swap');

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

    body { font-family: 'Inter', sans-serif; background-color: var(--bg-main); color: var(--text-main); transition: background-color 0.4s ease, color 0.4s ease; }
    .font-mono { font-family: 'JetBrains Mono', monospace; }

    .bg-adaptive { background-color: var(--bg-main); transition: background-color 0.4s ease; }
    .text-adaptive { color: var(--text-main); transition: color 0.4s ease; }
    .text-heading { color: var(--text-heading); transition: color 0.4s ease; }
    .text-muted { color: var(--text-muted); transition: color 0.4s ease; }
    .border-adaptive { border-color: var(--border-color); transition: border-color 0.4s ease; }
    .card-adaptive { background-color: var(--card-bg); border-color: var(--glass-border); transition: all 0.3s; }
    .card-adaptive:hover { border-color: var(--accent-glow); }
    .sim-bg-adaptive { background-color: var(--simulator-bg); transition: background-color 0.4s ease; }
    .code-adaptive { background-color: var(--code-bg); border-color: var(--glass-border); transition: all 0.4s ease; }

    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    
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
    
    @media (max-width: 1023px) {
        #courseSidebar { position: fixed; top: 64px; left: -100%; height: calc(100vh - 64px); transition: left 0.3s ease-in-out; z-index: 40; }
        #courseSidebar.mobile-open { left: 0; box-shadow: 10px 0 30px rgba(0,0,0,0.5); }
        #mobileOverlay { display: none; position: fixed; inset: 0; top: 64px; background: rgba(0,0,0,0.6); z-index: 30; }
        #mobileOverlay.show { display: block; }
    }
    
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: var(--text-muted); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: var(--text-main); background: var(--card-hover); }
    .nav-item.active { color: #06b6d4; background: rgba(6, 182, 212, 0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; transition: all 0.3s; }
    .dark .dot { background: #334155; }
    .nav-item.active .dot { background: #06b6d4; box-shadow: 0 0 8px #06b6d4; transform: scale(1.2); }
    .dark .nav-item.active .dot { background: #60a5fa; box-shadow: 0 0 8px #60a5fa; }

    .insight-box { animation: fadeIn 0.4s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

    .bg-checkered {
        background-image: 
            linear-gradient(45deg, #ccc 25%, transparent 25%), 
            linear-gradient(-45deg, #ccc 25%, transparent 25%), 
            linear-gradient(45deg, transparent 75%, #ccc 75%), 
            linear-gradient(-45deg, transparent 75%, #ccc 75%);
        background-size: 20px 20px;
        background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
    }
    .dark .bg-checkered {
        background-image: 
            linear-gradient(45deg, #333 25%, transparent 25%), 
            linear-gradient(-45deg, #333 25%, transparent 25%), 
            linear-gradient(45deg, transparent 75%, #333 75%), 
            linear-gradient(-45deg, transparent 75%, #333 75%);
    }
</style>



<div id="courseRoot" class="relative h-screen bg-slate-50 dark:bg-[#020617] text-slate-800 dark:text-white font-sans overflow-hidden flex flex-col selection:bg-blue-500/30 pt-20 transition-colors duration-500">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50 transition-opacity duration-500"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[600px] h-[600px] bg-blue-300/30 dark:bg-blue-900/10 rounded-full blur-[120px] animate-pulse transition-colors duration-500"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-cyan-300/30 dark:bg-cyan-900/10 rounded-full blur-[100px] transition-colors duration-500"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20">
        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-white/80 dark:bg-[#020617]/80 backdrop-blur-2xl border-b border-slate-200 dark:border-white/5 px-4 md:px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-gradient-to-br dark:from-blue-500/20 dark:to-transparent border border-blue-200 dark:border-blue-500/20 flex items-center justify-center font-bold text-xs text-blue-600 dark:text-blue-400 shrink-0 transition-colors">3.2</div>
                    <div>
                        <h1 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1 transition-colors">Background Masterclass</h1>
                        <p class="text-[10px] text-slate-500 dark:text-white/50 line-clamp-1 transition-colors">Resolusi Latar, Gradasi, dan Masking</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="hidden sm:block w-24 md:w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-blue-400 to-cyan-500 dark:from-blue-500 dark:to-cyan-500 w-0 transition-all duration-500 shadow-[0_0_10px_#3b82f6]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-blue-600 dark:text-blue-400 font-bold text-xs transition-colors">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                <div class="mb-24">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5 text-blue-500 dark:text-blue-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        
                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-blue-400 dark:hover:border-blue-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-blue-100 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">1</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Perilaku Scroll</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Mengenal efek Parallax menggunakan utilitas <code class="font-mono text-blue-500">bg-fixed</code> dan <code class="font-mono text-blue-500">bg-scroll</code> pada desain web modern.</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-indigo-400 dark:hover:border-indigo-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-indigo-100 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">2</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Dimensi Layar</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Mencegah gambar terpotong atau terdistorsi dengan manajemen rasio menggunakan <code class="font-mono text-indigo-500">bg-cover</code>.</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-cyan-400 dark:hover:border-cyan-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-cyan-100 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">3</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Aksesibilitas Kontras</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Menambahkan lapisan pelindung transparan (Overlay) agar teks dapat terbaca jelas di atas gambar yang terang.</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#1e1e1e] border border-slate-200 dark:border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-teal-400 dark:hover:border-teal-500/30 shadow-sm dark:shadow-none transition group h-full">
                            <div class="w-8 h-8 rounded bg-teal-100 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 font-bold text-xs transition-colors">4</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Teks Gradien</h4>
                                <p class="text-[11px] text-slate-500 dark:text-white/50 leading-relaxed transition-colors">Berlatih memotong ruang warna agar menyatu ke dalam bentuk tipografi menggunakan <code class="font-mono text-teal-500">bg-clip-text</code>.</p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-cyan-50 to-blue-50 dark:from-cyan-900/40 dark:to-blue-900/40 border border-cyan-200 dark:border-cyan-500/30 p-5 rounded-xl flex items-start gap-4 hover:shadow-md dark:hover:shadow-[0_0_20px_rgba(34,211,238,0.2)] transition group h-full col-span-1 sm:col-span-2 md:col-span-4 cursor-default">
                            <div class="w-8 h-8 rounded bg-cyan-100 dark:bg-white/10 text-cyan-600 dark:text-white flex items-center justify-center shrink-0 font-bold text-xs transition-colors">🏁</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-1 transition-colors">Final Mission</h4>
                                <p class="text-[11px] text-slate-600 dark:text-white/70 leading-relaxed transition-colors">Menggabungkan seluruh teknik manipulasi background untuk membangun area paling atas (Hero Section) pada halaman website, langsung di dalam editor interaktif.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <article class="space-y-40">
                    
                    <section id="section-52" class="lesson-section scroll-mt-32" data-lesson-id="52">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-blue-500 pl-6">
                                <span class="text-blue-600 dark:text-blue-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 3.2.1</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Perilaku Scroll <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-cyan-500 dark:from-blue-400 dark:to-cyan-400">(Background Attachment)</span>
                                </h2>
                            </div>
                            
                            <div class="space-y-6">
                                <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors text-justify">
                                    <p>
                                        Pernah melihat website yang gambar latarnya seolah "tertinggal" diam di tempat saat kamu men-scroll halaman ke bawah? Efek visual ini sering disebut sebagai <strong>Parallax</strong>. 
                                        Secara bawaan web, gambar background akan selalu ikut bergerak mengikuti elemen pembungkusnya. Tailwind menyediakan utilitas Background Attachment untuk mengubah perilaku ini dengan sangat mudah tanpa perlu repot menulis JavaScript.
                                    </p>
                                    
                                    <ul class="list-disc pl-5 space-y-3 text-sm md:text-base text-left mt-6 border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/5 p-6 rounded-xl transition-colors">
                                        <li><strong><code class="text-blue-600 dark:text-blue-400 font-bold transition-colors">bg-scroll</code>:</strong> Ini adalah perilaku normal. Gambar menempel pada elemen. Saat halaman di-scroll, gambar akan ikut bergerak naik/turun bersama elemennya.</li>
                                        <li><strong><code class="text-blue-600 dark:text-blue-400 font-bold transition-colors">bg-fixed</code>:</strong> Mengunci posisi gambar langsung ke jendela layar perangkatmu (viewport). Saat halaman di-scroll, elemen pembungkusnya akan lewat, namun gambar di belakangnya tetap diam. Inilah yang menciptakan ilusi Parallax!</li>
                                        <li><strong><code class="text-blue-600 dark:text-blue-400 font-bold transition-colors">bg-local</code>:</strong> Berguna jika kamu memiliki kotak teks yang bisa di-scroll sendiri di dalamnya. Gambar akan ikut bergerak mengikuti scroll yang ada di dalam elemen tersebut.</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group hover:border-blue-400 dark:hover:border-blue-500/30 transition-all mt-8">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator: Pengujian Perilaku Latar</h4>
                                
                                <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/30 rounded-xl p-4 mb-8 text-sm text-blue-700 dark:text-blue-300 relative z-10 shadow-sm dark:shadow-inner transition-colors">
                                    <p class="font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                                        Panduan Pengujian
                                    </p>
                                    <p class="m-0 opacity-90 leading-relaxed text-xs md:text-sm text-blue-800/80 dark:text-blue-100/80 transition-colors">
                                        Pilih salah satu kelas di menu kiri. Kemudian, arahkan kursor ke area pratinjau di sebelah kanan dan coba lakukan scroll ke bawah untuk melihat bagaimana gambar merespon.
                                    </p>
                                </div>

                                <div class="flex flex-col lg:flex-row justify-between items-start mb-6 gap-4 lg:gap-6 relative z-10">
                                    <div class="flex flex-col gap-2 w-full lg:w-1/3">
                                        <button onclick="updateSimAttach(this, 'scroll')" class="btn-sim-1 w-full text-left px-5 py-4 rounded-xl bg-blue-600 text-white shadow-lg border border-blue-400 transition text-sm font-bold flex flex-col gap-1 focus:outline-none">
                                            <span>bg-scroll</span>
                                            <span class="text-[10px] font-normal opacity-80 tracking-wide">Bergerak bersama elemen</span>
                                        </button>
                                        <button onclick="updateSimAttach(this, 'fixed')" class="btn-sim-1 w-full text-left px-5 py-4 rounded-xl bg-slate-100 dark:bg-white/5 border border-transparent text-slate-600 dark:text-white/50 hover:bg-slate-200 dark:hover:bg-white/10 transition text-sm font-bold flex flex-col gap-1 focus:outline-none">
                                            <span>bg-fixed</span>
                                            <span class="text-[10px] font-normal opacity-60 tracking-wide">Terkunci pada jendela layar</span>
                                        </button>
                                        <button onclick="updateSimAttach(this, 'local')" class="btn-sim-1 w-full text-left px-5 py-4 rounded-xl bg-slate-100 dark:bg-white/5 border border-transparent text-slate-600 dark:text-white/50 hover:bg-slate-200 dark:hover:bg-white/10 transition text-sm font-bold flex flex-col gap-1 focus:outline-none">
                                            <span>bg-local</span>
                                            <span class="text-[10px] font-normal opacity-60 tracking-wide">Ikut scroll area internal</span>
                                        </button>
                                    </div>
                                    <div class="w-full lg:w-2/3 flex flex-col items-center justify-center bg-slate-200 dark:bg-black/60 rounded-2xl border border-slate-300 dark:border-white/10 relative overflow-hidden h-[400px] shadow-inner p-1">
                                        
                                        <div class="absolute inset-1 custom-scrollbar overflow-y-scroll bg-scroll bg-cover bg-center transition-all duration-300 rounded-xl" style="background-image: url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=1200&auto=format&fit=crop');" id="area-attach">
                                            
                                            <div class="h-[250%] w-full relative z-10 flex flex-col items-center pt-24 pb-24 px-4">
                                                <div class="bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl px-8 py-6 rounded-2xl text-slate-900 dark:text-white font-black shadow-2xl border border-white/50 dark:border-white/10 text-center w-full max-w-sm mb-auto group transition-transform duration-500 cursor-ns-resize hover:-translate-y-1">
                                                    <span class="block text-xl mb-1">Coba Scroll Di Sini!</span>
                                                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-widest flex items-center justify-center gap-2">
                                                        <svg class="w-4 h-4 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                                        Geser ke arah bawah
                                                    </span>
                                                </div>
                                                <div class="bg-blue-600/90 dark:bg-blue-500/80 backdrop-blur-md px-8 py-4 rounded-full text-white font-bold shadow-xl border border-blue-400/50 mt-auto mb-auto text-sm flex items-center justify-center gap-3 w-full max-w-sm">
                                                    <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                                                    Pusat Konten
                                                </div>
                                                <div class="bg-slate-900/95 dark:bg-black/90 backdrop-blur-xl px-8 py-6 rounded-2xl text-white font-black shadow-2xl border border-slate-700 dark:border-white/10 text-center w-full max-w-sm mt-auto">
                                                    <span class="block text-slate-400 text-xs tracking-widest uppercase mb-2">Batas Ketinggian Maksimal</span>
                                                    Guliran telah berakhir.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800/50 p-4 rounded-xl text-sm text-blue-900 dark:text-blue-100 flex items-start gap-4 shadow-sm z-20 transition-colors">
                                    <div class="p-2 bg-blue-200 dark:bg-blue-800/50 rounded-lg shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p id="demo-attach-insight" class="insight-box m-0 leading-relaxed font-medium">Perhatikan saat kamu menggunakan <code class="font-bold bg-white dark:bg-black/40 px-1 rounded text-blue-600 dark:text-blue-300">bg-scroll</code>, gambar akan ikut menggulung ke atas saat elemen digeser. Ini adalah perilaku alami web yang paling sering kita lihat sehari-hari.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-53" class="lesson-section scroll-mt-32" data-lesson-id="53">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 3.2.2</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Dimensi Gambar & <br> Manajemen Titik Fokus
                                </h2>
                            </div>

                            <div class="space-y-6">
                                <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors text-justify">
                                    <p>
                                        Pernah merasa kesal karena gambar yang kamu jadikan background terlihat gepeng, berulang tanpa aturan, atau malah bagian pentingnya terpotong di layar HP? Hal ini terjadi karena perbedaan rasio antara gambar asli dengan layar perangkat pengguna. Tailwind memberikan utilitas praktis untuk memastikan gambarmu selalu tampil rapi.
                                    </p>
                                    
                                    <ul class="list-disc pl-5 space-y-4 mt-4 border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/5 p-6 rounded-xl transition-colors text-sm md:text-base text-left">
                                        <li><strong><code class="text-indigo-600 dark:text-indigo-300 font-bold transition-colors">bg-cover</code>:</strong> Utilitas ini akan memperbesar/memperkecil gambar <i>secara proporsional</i> agar area elemen tertutup sepenuhnya tanpa menyisakan ruang kosong. Meski sebagian tepi gambar mungkin terpotong, rasionya tetap aman (tidak gepeng). Sangat cocok untuk gambar besar di area atas web (Hero Banner).</li>
                                        <li><strong><code class="text-indigo-600 dark:text-indigo-300 font-bold transition-colors">bg-contain</code>:</strong> Memaksa agar <i>seluruh</i> bagian gambar tetap terlihat. Efek sampingnya, jika rasionya berbeda, akan ada area kosong di sisi gambar. Biasanya ini dipakai untuk Logo.</li>
                                        <li><strong><code class="text-indigo-600 dark:text-indigo-300 font-bold transition-colors">bg-[posisi]</code>:</strong> Menentukan titik fokus pemotongan gambar. Misalnya, menggunakan <code class="font-mono text-sm text-indigo-500">bg-center</code> memastikan area tengah tetap jadi prioritas saat layar mengecil, atau <code class="font-mono text-sm text-indigo-500">bg-top</code> untuk memprioritaskan bagian atas gambar.</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group hover:border-indigo-400 dark:hover:border-indigo-500/30 transition-all mt-8">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator: Penyesuaian Ruang Grafis</h4>

                                <div class="flex flex-col xl:flex-row w-full gap-6 relative z-10 mt-8">
                                    <div class="w-full xl:w-1/3 flex flex-col gap-6 bg-slate-50 dark:bg-black/30 border border-slate-200 dark:border-white/10 p-5 rounded-2xl shadow-inner">
                                        
                                        <div>
                                            <div class="text-[10px] font-black text-indigo-700 dark:text-indigo-300 uppercase tracking-widest mb-3 flex items-center gap-2"><span class="w-2 h-2 rounded bg-indigo-500 block"></span> 1. Pengendali Ukuran (Size)</div>
                                            <div class="grid grid-cols-2 gap-2">
                                                <button onclick="updateSimSizePos(this, 'size', 'bg-cover')" class="btn-sim-2-size py-2.5 rounded-lg bg-indigo-600 text-white shadow-md border border-indigo-400 transition text-xs font-bold focus:outline-none">bg-cover</button>
                                                <button onclick="updateSimSizePos(this, 'size', 'bg-contain')" class="btn-sim-2-size py-2.5 rounded-lg bg-white dark:bg-white/5 text-slate-600 dark:text-white/50 border border-slate-200 dark:border-white/10 hover:bg-slate-100 dark:hover:bg-white/10 transition text-xs font-bold focus:outline-none">bg-contain</button>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <div class="text-[10px] font-black text-indigo-700 dark:text-indigo-300 uppercase tracking-widest mb-3 flex items-center gap-2"><span class="w-2 h-2 rounded bg-indigo-500 block"></span> 2. Pengaturan Pengulangan</div>
                                            <div class="grid grid-cols-2 gap-2">
                                                <button onclick="updateSimSizePos(this, 'repeat', 'bg-no-repeat')" class="btn-sim-2-repeat py-2.5 rounded-lg bg-indigo-600 text-white shadow-md border border-indigo-400 transition text-xs font-bold focus:outline-none">no-repeat</button>
                                                <button onclick="updateSimSizePos(this, 'repeat', 'bg-repeat')" class="btn-sim-2-repeat py-2.5 rounded-lg bg-white dark:bg-white/5 text-slate-600 dark:text-white/50 border border-slate-200 dark:border-white/10 hover:bg-slate-100 dark:hover:bg-white/10 transition text-xs font-bold focus:outline-none">repeat</button>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-[10px] font-black text-indigo-700 dark:text-indigo-300 uppercase tracking-widest mb-3 flex items-center gap-2"><span class="w-2 h-2 rounded bg-indigo-500 block"></span> 3. Manajemen Titik Posisi</div>
                                            <div class="grid grid-cols-3 gap-1.5 p-1.5 bg-slate-200 dark:bg-black/50 rounded-xl border border-slate-300 dark:border-white/5">
                                                <button onclick="updateSimSizePos(this, 'pos', 'bg-left-top')" class="btn-sim-2-pos py-2 text-[10px] font-bold rounded-lg bg-white dark:bg-white/10 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-100 dark:hover:bg-white/20 transition focus:outline-none">Kiri Atas</button>
                                                <button onclick="updateSimSizePos(this, 'pos', 'bg-top')" class="btn-sim-2-pos py-2 text-[10px] font-bold rounded-lg bg-white dark:bg-white/10 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-100 dark:hover:bg-white/20 transition focus:outline-none">Atas</button>
                                                <button onclick="updateSimSizePos(this, 'pos', 'bg-right-top')" class="btn-sim-2-pos py-2 text-[10px] font-bold rounded-lg bg-white dark:bg-white/10 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-100 dark:hover:bg-white/20 transition focus:outline-none">Kanan Atas</button>
                                                
                                                <button onclick="updateSimSizePos(this, 'pos', 'bg-left')" class="btn-sim-2-pos py-2 text-[10px] font-bold rounded-lg bg-white dark:bg-white/10 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-100 dark:hover:bg-white/20 transition focus:outline-none">Kiri</button>
                                                <button onclick="updateSimSizePos(this, 'pos', 'bg-center')" class="btn-sim-2-pos py-2 text-[10px] font-bold rounded-lg bg-indigo-600 text-white shadow-md border border-indigo-500 transition relative overflow-hidden focus:outline-none"><span class="absolute inset-0 bg-white/20 rounded-lg animate-pulse"></span>Tengah</button>
                                                <button onclick="updateSimSizePos(this, 'pos', 'bg-right')" class="btn-sim-2-pos py-2 text-[10px] font-bold rounded-lg bg-white dark:bg-white/10 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-100 dark:hover:bg-white/20 transition focus:outline-none">Kanan</button>
                                                
                                                <button onclick="updateSimSizePos(this, 'pos', 'bg-left-bottom')" class="btn-sim-2-pos py-2 text-[10px] font-bold rounded-lg bg-white dark:bg-white/10 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-100 dark:hover:bg-white/20 transition focus:outline-none">Kiri Bawah</button>
                                                <button onclick="updateSimSizePos(this, 'pos', 'bg-bottom')" class="btn-sim-2-pos py-2 text-[10px] font-bold rounded-lg bg-white dark:bg-white/10 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-100 dark:hover:bg-white/20 transition focus:outline-none">Bawah</button>
                                                <button onclick="updateSimSizePos(this, 'pos', 'bg-right-bottom')" class="btn-sim-2-pos py-2 text-[10px] font-bold rounded-lg bg-white dark:bg-white/10 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-100 dark:hover:bg-white/20 transition focus:outline-none">Kanan Bawah</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-full xl:w-2/3 p-4 md:p-6 flex flex-col items-center bg-slate-100 dark:bg-black/40 rounded-2xl min-h-[350px] border border-dashed border-slate-300 dark:border-white/10 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-checkered opacity-20 dark:opacity-30 mix-blend-overlay"></div>
                                        
                                        <div class="w-full flex flex-col sm:flex-row items-center justify-between mb-4 relative z-10 px-2 gap-2">
                                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Konfigurasi Aktif: <code id="code-sp" class="text-indigo-600 dark:text-indigo-400 bg-white dark:bg-black/50 px-2 py-1 rounded shadow-sm border border-slate-200 dark:border-white/10 ml-1">bg-cover bg-no-repeat bg-center</code></span>
                                            <span class="flex items-center gap-2 text-[10px] text-slate-400 bg-white/80 dark:bg-black/50 px-2 py-1 rounded shadow-sm"><div class="w-3 h-3 border-2 border-dashed border-rose-500 bg-rose-500/10"></div> Referensi Ukuran Layar</span>
                                        </div>

                                        <div class="w-full max-w-lg aspect-video bg-slate-200 dark:bg-slate-800/80 rounded-xl border-4 border-dashed border-indigo-300 dark:border-indigo-500/50 relative shadow-xl overflow-hidden flex-shrink-0 flex items-center justify-center p-2 backdrop-blur-sm">
                                            <div id="area-sp" class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1542038784456-1ea8e935640e?q=80&w=800&auto=format&fit=crop')] transition-all duration-[600ms] shadow-inner border border-white/20 bg-cover bg-no-repeat bg-center"></div>
                                            
                                            <div class="w-16 h-16 border border-white/40 rounded-full flex items-center justify-center pointer-events-none relative z-10 mix-blend-difference opacity-50">
                                                <div class="w-2 h-2 bg-white rounded-full"></div>
                                                <div class="absolute w-full h-[1px] bg-white/20"></div>
                                                <div class="absolute h-full w-[1px] bg-white/20"></div>
                                            </div>
                                        </div>

                                        <div class="mt-6 w-full max-w-lg bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800/50 p-4 rounded-xl text-sm text-indigo-900 dark:text-indigo-100 flex items-start gap-4 shadow-sm z-20 transition-colors">
                                            <div class="p-2 bg-indigo-200 dark:bg-indigo-800/50 rounded-lg shrink-0">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            </div>
                                            <p id="demo-sp-insight" class="insight-box m-0 leading-relaxed font-medium text-xs md:text-sm">Kombinasi <code class="bg-white dark:bg-black/40 text-indigo-600 dark:text-indigo-300 px-1 rounded font-bold">bg-cover</code> memastikan gambar memenuhi seluruh area bingkai tanpa mengubah rasionya. Titik fokus diatur pada <code class="bg-white dark:bg-black/40 text-indigo-600 dark:text-indigo-300 px-1 rounded font-bold">bg-center</code> agar bagian tengah gambar selalu diutamakan untuk tampil, betapapun mengecilnya ukuran layar.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-54" class="lesson-section scroll-mt-32" data-lesson-id="54">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest transition-colors">Lesson 3.2.3</span>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-[1.1] transition-colors">
                                    Aksesibilitas Kontras (Overlay) & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-teal-500 dark:from-cyan-400 dark:to-teal-400">Teks Gradien</span>
                                </h2>
                            </div>

                            <div class="space-y-6">
                                <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-white/70 text-base md:text-lg leading-relaxed transition-colors text-justify">
                                    <p>
                                        Menaruh teks putih tepat di atas foto yang dominan terang adalah "musuh utama" desain UI modern, karena membuat teks sangat sulit dibaca (menyalahi aksesibilitas web). Standar industri web menyiasatinya dengan memberikan lapisan pelindung transparan yang disebut <strong>Overlay</strong>.
                                    </p>
                                    
                                    <ul class="list-disc pl-5 space-y-4 mt-4 border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/5 p-6 rounded-xl transition-colors text-sm md:text-base text-left">
                                        <li><strong>Teknik Overlay:</strong> Letakkan div kosong berukuran penuh (<code class="text-cyan-600 dark:text-cyan-300 font-bold transition-colors">absolute inset-0</code>) tepat di atas gambarmu. Tambahkan warna gradien halus, seperti <code class="text-cyan-600 dark:text-cyan-300 font-bold transition-colors">bg-gradient-to-t from-black/80 to-transparent</code>. Teknik ini akan meredupkan bagian belakang teks secara elegan tanpa membuat keseluruhan gambar menjadi gelap gulita.</li>
                                        <li><strong>Teknik Teks Gradien:</strong> Ingin teksmu diwarnai gradasi keren? Cukup gunakan tiga langkah ini: 
                                            <br>1) Berikan latar gradasi pada teks, misal <code class="text-teal-600 dark:text-teal-300 font-bold transition-colors">bg-gradient-to-r from-cyan-400 to-blue-500</code>.
                                            <br>2) Tambahkan utilitas ajaib <code class="text-teal-600 dark:text-teal-300 font-bold transition-colors">bg-clip-text</code> agar gradasi tersebut "dicetak" mengikuti bentuk huruf.
                                            <br>3) Berikan kelas <code class="text-teal-600 dark:text-teal-300 font-bold transition-colors">text-transparent</code> agar warna asli font hilang dan memunculkan latar gradasi di baliknya!</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-white/10 rounded-2xl p-6 lg:p-8 shadow-xl dark:shadow-2xl relative group hover:border-cyan-400 dark:hover:border-cyan-500/30 transition-all mt-8">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-muted uppercase mb-4 text-center transition-colors tracking-widest">Simulator: Regulasi Aksesibilitas & Teks</h4>

                                <div class="flex flex-col xl:flex-row w-full gap-6 relative z-10 mt-8">
                                    <div class="w-full xl:w-1/3 flex flex-col gap-6 bg-slate-50 dark:bg-black/30 border border-slate-200 dark:border-white/10 p-5 rounded-2xl shadow-inner">
                                        
                                        <div>
                                            <div class="text-[10px] font-black text-cyan-700 dark:text-cyan-300 uppercase tracking-widest mb-3 flex items-center gap-2"><span class="w-2 h-2 rounded bg-cyan-500 block"></span> 1. Konstruksi Lapisan Overlay</div>
                                            <div class="grid grid-cols-1 gap-2">
                                                <button onclick="updateSimOverlayMask(this, 'overlay', 'bg-transparent')" class="btn-sim-3-overlay px-4 py-2.5 rounded-lg bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-300 dark:hover:bg-white/10 transition text-xs font-bold text-left flex justify-between items-center focus:outline-none">
                                                    Polos (Tanpa Proteksi)
                                                    <span class="text-[9px] uppercase tracking-widest opacity-50 font-normal text-rose-500">Kritis</span>
                                                </button>
                                                <button onclick="updateSimOverlayMask(this, 'overlay', 'bg-black/60')" class="btn-sim-3-overlay px-4 py-2.5 rounded-lg bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-300 dark:hover:bg-white/10 transition text-xs font-bold text-left flex justify-between items-center focus:outline-none">
                                                    Warna Hitam Statis 60%
                                                    <span class="text-[9px] uppercase tracking-widest opacity-50 font-normal">Memadai</span>
                                                </button>
                                                <button onclick="updateSimOverlayMask(this, 'overlay', 'bg-gradient-to-t from-black/90 via-black/40 to-transparent')" class="btn-sim-3-overlay px-4 py-2.5 rounded-lg bg-cyan-600 text-white shadow-md border border-cyan-400 transition text-xs font-bold text-left flex justify-between items-center focus:outline-none">
                                                    Gradasi ke Arah Atas
                                                    <span class="text-[9px] uppercase tracking-widest opacity-80 font-normal text-cyan-200">Optimal</span>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="border-t border-slate-200 dark:border-white/10 my-2"></div>

                                        <div>
                                            <div class="text-[10px] font-black text-cyan-700 dark:text-cyan-300 uppercase tracking-widest mb-3 flex items-center gap-2"><span class="w-2 h-2 rounded bg-cyan-500 block"></span> 2. Pemrosesan Efek Teks</div>
                                            <div class="grid grid-cols-2 gap-2">
                                                <button onclick="updateSimOverlayMask(this, 'clip', 'false')" class="btn-sim-3-clip py-2.5 rounded-lg bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-white/50 border border-slate-200 dark:border-white/10 hover:bg-slate-100 dark:hover:bg-white/10 transition text-xs font-bold focus:outline-none">Warna Dasar (Putih)</button>
                                                <button onclick="updateSimOverlayMask(this, 'clip', 'true')" class="btn-sim-3-clip py-2.5 rounded-lg bg-cyan-600 text-white shadow-md border border-cyan-400 transition text-xs font-bold focus:outline-none">Gunakan bg-clip-text</button>
                                            </div>
                                        </div>
                                        
                                        <div id="gradient-controls" class="transition-all duration-300 overflow-hidden" style="max-height: 100px; opacity: 1;">
                                            <div class="text-[10px] font-black text-cyan-700 dark:text-cyan-300 uppercase tracking-widest mb-3 flex items-center gap-2"><span class="w-2 h-2 rounded bg-teal-500 block"></span> 3. Arah Gradien Teks</div>
                                            <div class="grid grid-cols-3 gap-1.5 p-1.5 bg-slate-200 dark:bg-black/50 rounded-xl border border-slate-300 dark:border-white/5">
                                                <button onclick="updateSimOverlayMask(this, 'dir', 'bg-gradient-to-r')" class="btn-sim-3-dir py-2.5 text-[10px] font-bold rounded-lg bg-teal-600 text-white shadow-md border border-teal-500 transition focus:outline-none">Kanan (to-r)</button>
                                                <button onclick="updateSimOverlayMask(this, 'dir', 'bg-gradient-to-b')" class="btn-sim-3-dir py-2.5 text-[10px] font-bold rounded-lg bg-white dark:bg-white/10 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-100 dark:hover:bg-white/20 transition focus:outline-none">Bawah (to-b)</button>
                                                <button onclick="updateSimOverlayMask(this, 'dir', 'bg-gradient-to-br')" class="btn-sim-3-dir py-2.5 text-[10px] font-bold rounded-lg bg-white dark:bg-white/10 text-slate-600 dark:text-white/50 border border-transparent hover:bg-slate-100 dark:hover:bg-white/20 transition focus:outline-none">Sudut (to-br)</button>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="w-full xl:w-2/3 p-4 md:p-6 flex flex-col items-center bg-slate-100 dark:bg-black/40 rounded-2xl min-h-[450px] border border-dashed border-slate-300 dark:border-white/10 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-checkered opacity-20 dark:opacity-30 mix-blend-overlay"></div>
                                        
                                        <div class="w-full h-full bg-slate-800 rounded-xl relative shadow-2xl overflow-hidden flex flex-col justify-end p-8 border border-slate-700">
                                            
                                            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1518509562904-e7ef99cdcc86?q=80&w=1000')] bg-cover bg-center transition-transform duration-[2s] hover:scale-105 z-0"></div>
                                            
                                            <div id="area-overlay" class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent transition-all duration-700 ease-in-out z-10"></div>
                                            
                                            <div class="relative z-20 w-full max-w-lg mb-4">
                                                <p class="text-xs font-bold tracking-widest uppercase mb-3 text-sky-400 drop-shadow-md">Eksplorasi Antarmuka UI</p>
                                                <h2 id="text-mask" class="text-5xl sm:text-6xl font-black transition-all duration-700 tracking-tighter drop-shadow-sm text-transparent bg-clip-text bg-gradient-to-r from-sky-300 to-teal-400 mb-4 pb-2 leading-tight">
                                                    SENI TIPOGRAFI
                                                </h2>
                                                <p class="text-sm font-medium text-white/90 leading-relaxed drop-shadow-md">
                                                    Tanpa penerapan elemen pelindung berupa overlay di latar belakang, rasio pencahayaan dari foto akan menyatu dengan karakter teks putih, membuat pesannya tidak dapat terbaca secara nyaman.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 w-full bg-cyan-50 dark:bg-cyan-900/30 border border-cyan-100 dark:border-cyan-800/50 p-4 rounded-xl text-sm text-cyan-900 dark:text-cyan-100 flex items-start gap-4 shadow-sm z-20 transition-colors">
                                    <div class="p-2 bg-cyan-200 dark:bg-cyan-800/50 rounded-lg shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </div>
                                    <div class="w-full">
                                        <p id="demo-overlay-insight" class="insight-box m-0 leading-relaxed font-medium text-xs md:text-sm mb-3 border-b border-cyan-200 dark:border-cyan-800 pb-3"><strong>Catatan Overlay:</strong> Utilitas <code class="font-bold bg-white dark:bg-black/40 px-1 rounded text-cyan-600 dark:text-cyan-300">bg-gradient-to-t</code> memberikan bayangan perlahan memudar ke atas. Teks terbaca jelas di bawah tanpa harus merusak warna asli langit di bagian atas foto.</p>
                                        <p id="demo-mask-insight" class="insight-box m-0 leading-relaxed font-medium text-xs md:text-sm"><strong>Catatan Teks Gradien:</strong> Hurufnya berhasil menerima warna pelangi dari background karena kita menyetel potongannya melalui utilitas <code class="font-bold bg-white dark:bg-black/40 px-1 rounded text-teal-600 dark:text-teal-300">bg-clip-text</code> yang dibantu dengan pewarnaan tembus pandang <code class="font-bold bg-white dark:bg-black/40 px-1 rounded text-teal-600 dark:text-teal-300">text-transparent</code>.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-55" class="lesson-section scroll-mt-32 pt-10 border-t border-slate-200 dark:border-white/10 transition-colors" data-lesson-id="55" data-type="activity">
                        <div class="relative rounded-[2rem] md:rounded-[2.5rem] bg-white dark:bg-[#050b14] border border-slate-200 dark:border-white/10 p-6 md:p-10 overflow-hidden shadow-xl dark:shadow-2xl group hover:border-blue-400 dark:hover:border-blue-500/30 transition-all duration-500 flex flex-col">
                            
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-400/20 dark:bg-blue-600/20 blur-[100px] rounded-full pointer-events-none transition-colors"></div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 mb-8 relative z-10 shrink-0">
                                <div class="p-3 sm:p-4 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl text-white shadow-lg border border-blue-400/50 shrink-0">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-2">
                                        <h2 class="text-xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight transition-colors">Aktivitas Praktik: Desain Hero Parallax</h2>
                                        <span class="px-2 py-0.5 rounded text-[9px] sm:text-[10px] font-bold bg-rose-100 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-500/30 uppercase tracking-wider shadow-sm transition-colors">Evaluasi Kode Langsung</span>
                                    </div>
                                    <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm leading-relaxed max-w-3xl text-justify transition-colors">
                                        Mari satukan semua yang telah dipelajari! Tugas kamu adalah melengkapi kode HTML pada editor di bawah ini menggunakan utilitas Tailwind. Bangun sebuah komponen antarmuka yang interaktif, elegan, dan pastinya bisa di-scroll dengan efek Parallax yang halus. Sistem akan memvalidasi jawabanmu secara <i>real-time</i>. Semangat!
                                    </p>
                                    
                                    <div class="mt-4 flex flex-wrap items-center gap-2">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mr-1 transition-colors">Utilitas Wajib:</span>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 px-2 py-1 rounded shadow-sm transition-colors">bg-cover</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 px-2 py-1 rounded shadow-sm transition-colors">bg-fixed</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-cyan-600 dark:text-cyan-400 bg-cyan-100 dark:bg-cyan-500/10 border border-cyan-200 dark:border-cyan-500/20 px-2 py-1 rounded shadow-sm transition-colors">bg-gradient-to-t</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-cyan-600 dark:text-cyan-400 bg-cyan-100 dark:bg-cyan-500/10 border border-cyan-200 dark:border-cyan-500/20 px-2 py-1 rounded shadow-sm transition-colors">from-black/80</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-teal-600 dark:text-teal-400 bg-teal-100 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/20 px-2 py-1 rounded shadow-sm transition-colors">text-transparent</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-teal-600 dark:text-teal-400 bg-teal-100 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/20 px-2 py-1 rounded shadow-sm transition-colors">bg-clip-text</code>
                                        <code class="text-[10px] md:text-xs font-mono font-bold text-teal-600 dark:text-teal-400 bg-teal-100 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/20 px-2 py-1 rounded shadow-sm transition-colors">bg-gradient-to-r</code>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col xl:grid xl:grid-cols-2 gap-0 border border-slate-200 dark:border-white/10 rounded-2xl overflow-hidden shadow-lg dark:shadow-2xl relative z-10 flex-1 transition-colors">
                                
                                <div class="bg-slate-50 dark:bg-[#151515] border-b xl:border-b-0 xl:border-r border-slate-200 dark:border-white/10 flex flex-col relative w-full xl:w-auto min-h-[500px] xl:min-h-[600px] transition-colors">
                                    
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-white/95 dark:bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-8 transition-colors border-2 border-emerald-500/20">
                                        <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mb-6 border border-emerald-300 dark:border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,0.2)] animate-bounce transition-colors">
                                            <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mb-2 tracking-tight transition-colors">KERJA BAGUS! TUGAS SELESAI</h3>
                                        <p class="text-xs sm:text-sm font-bold text-slate-500 dark:text-white/60 mb-8 max-w-xs transition-colors">Kamu berhasil menerapkan utilitas background Tailwind dengan sempurna pada komponen interaktif ini.</p>
                                        <button disabled class="w-full sm:w-auto px-8 py-3 rounded-full bg-slate-200 dark:bg-white/5 border border-slate-300 dark:border-white/10 text-slate-400 dark:text-white/30 text-[10px] sm:text-xs font-bold cursor-not-allowed uppercase tracking-widest transition-colors">Aktivitas Dikunci Permanen</button>
                                    </div>

                                    <div class="bg-slate-100 dark:bg-[#1e1e1e] px-4 py-3 border-b border-slate-200 dark:border-white/5 flex justify-between items-center shrink-0 transition-colors">
                                        <span class="text-[10px] sm:text-xs text-slate-500 dark:text-white/50 font-mono font-bold transition-colors">Terminal Kode CSS HTML</span>
                                        <button onclick="resetEditor()" class="text-[10px] text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 uppercase font-bold focus:outline-none bg-red-100 dark:bg-red-500/10 px-3 py-1.5 rounded shadow-sm border border-red-200 dark:border-red-500/20 active:scale-95 transition">Reset Format</button>
                                    </div>
                                    
                                    <div id="codeEditor" class="flex-1 w-full border-b border-slate-200 dark:border-white/5 min-h-[250px] relative transition-colors"></div>

                                    <div class="p-5 bg-slate-50 dark:bg-[#0f141e] flex flex-col shrink-0 h-auto sm:h-[260px] transition-colors">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="text-[10px] uppercase font-bold text-slate-400 dark:text-white/30 tracking-widest transition-colors">Kriteria Pengerjaan Modul</span>
                                            <span id="progressText" class="text-[9px] sm:text-[10px] font-mono font-bold text-blue-700 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30 px-2 py-0.5 rounded border border-blue-200 dark:border-blue-500/20 shadow-inner transition-colors">0/3 Indikator Terpenuhi</span>
                                        </div>
                                        <div class="flex flex-col gap-4 text-xs sm:text-[13px] font-sans text-slate-700 dark:text-white/70 mb-4 flex-1 overflow-y-auto custom-scrollbar p-4 bg-white dark:bg-black/20 rounded-lg shadow-sm dark:shadow-inner border border-slate-200 dark:border-white/5 transition-colors">
    <div id="check-bg" class="flex items-start gap-3">
        <span class="w-4 h-4 mt-0.5 rounded-full border-2 border-slate-300 dark:border-white/20 flex items-center justify-center text-[10px] shrink-0 transition-colors font-bold"></span> 
        <div class="leading-relaxed">
            <b class="block mb-1 text-slate-800 dark:text-white/90 transition-colors font-extrabold text-sm">1. Dimensi Gambar & Parallax:</b> 
            Pada tag dengan id <code class="text-xs bg-slate-100 dark:bg-white/10 px-1 rounded">#hero-section</code>, terapkan kelas <code class="text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-1 rounded">bg-cover</code> agar latar memenuhi dimensi elemen, dan <code class="text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-1 rounded">bg-fixed</code> untuk mengaktifkan ilusi scroll Parallax.
        </div>
    </div>
    
    <div id="check-overlay" class="flex items-start gap-3">
        <span class="w-4 h-4 mt-0.5 rounded-full border-2 border-slate-300 dark:border-white/20 flex items-center justify-center text-[10px] shrink-0 transition-colors font-bold"></span> 
        <div class="leading-relaxed">
            <b class="block mb-1 text-slate-800 dark:text-white/90 transition-colors font-extrabold text-sm">2. Overlay Aksesibilitas:</b> 
            Pada id <code class="text-xs bg-slate-100 dark:bg-white/10 px-1 rounded">#hero-overlay</code>, tambahkan arah gradasi ke atas (<code class="text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-1 rounded">bg-gradient-to-t</code>), yang bermula dari warna hitam pekat (<code class="text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-1 rounded">from-black/80</code>) dan memudar (<code class="text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-1 rounded">to-transparent</code>).
        </div>
    </div>
    
    <div id="check-title" class="flex items-start gap-3">
        <span class="w-4 h-4 mt-0.5 rounded-full border-2 border-slate-300 dark:border-white/20 flex items-center justify-center text-[10px] shrink-0 transition-colors font-bold"></span> 
        <div class="leading-relaxed">
            <b class="block mb-1 text-slate-800 dark:text-white/90 transition-colors font-extrabold text-sm">3. Masking Tipografi:</b> 
            Pada judul utama <code class="text-xs bg-slate-100 dark:bg-white/10 px-1 rounded">#hero-title</code>, pastikan warna dasarnya hilang (<code class="text-xs font-bold text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-900/20 px-1 rounded">text-transparent</code>), batasi potongannya pada teks (<code class="text-xs font-bold text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-900/20 px-1 rounded">bg-clip-text</code>), lalu arahkan warnanya ke kanan (<code class="text-xs font-bold text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-900/20 px-1 rounded">bg-gradient-to-r</code>). Jangan lupa hapus kelas <code class="text-xs text-rose-500 line-through decoration-rose-500">text-white</code> agar warna gradien muncul.
        </div>
    </div>
</div>
                                        <button id="submitExerciseBtn" onclick="submitExercise()" disabled class="w-full py-2.5 sm:py-3 rounded-lg bg-emerald-600 text-white font-bold text-[11px] sm:text-xs shadow-lg shadow-emerald-500/20 hover:bg-emerald-500 hover:-translate-y-0.5 transition-all cursor-not-allowed opacity-50 flex items-center justify-center gap-2 focus:outline-none active:scale-95">
                                            <span>Selesaikan Kriteria Di Atas Untuk Mengirim</span>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="bg-slate-100 dark:bg-[#1e1e1e] flex flex-col relative overflow-hidden w-full xl:w-auto h-[400px] xl:h-auto transition-colors">
                                    <div class="bg-slate-200 dark:bg-[#2d2d2d] px-4 py-3 border-b border-slate-300 dark:border-white/5 flex items-center justify-between shrink-0 transition-colors">
                                        <span class="text-[10px] text-slate-500 dark:text-gray-400 font-mono font-bold transition-colors">Pratinjau Langsung (Real-time Preview)</span>
                                        <span class="text-[9px] sm:text-[10px] bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-500/20 font-bold uppercase tracking-widest flex items-center gap-1.5 shadow-sm transition-colors">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_5px_#10b981]"></span> Auto-Sync Aktif
                                        </span>
                                    </div>
                                    <div class="flex-1 bg-slate-100 dark:bg-gray-900 relative w-full h-full p-0 transition-colors">
                                        <div class="absolute inset-0 bg-checkered opacity-20 dark:opacity-30 pointer-events-none mix-blend-overlay"></div>
                                        <iframe id="previewFrame" class="w-full h-full border-0 bg-transparent relative z-10 custom-scrollbar"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                <div class="mt-32 pt-8 border-t border-slate-200 dark:border-white/10 flex flex-col sm:flex-row justify-between items-center gap-4 sm:gap-0 transition-colors">
                    <a href="{{ route('courses.typography') ?? '#' }}" class="group flex items-center gap-4 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center bg-slate-100 dark:bg-transparent group-hover:bg-slate-200 dark:group-hover:bg-white/5 transition shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-center sm:text-left">
                            <div class="text-[10px] sm:text-xs uppercase tracking-widest font-bold opacity-50 mb-0.5">Sebelumnya</div>
                            <div class="font-bold text-xs md:text-sm line-clamp-1">Tipografi </div>
                        </div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-slate-500 cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end flex-row-reverse sm:flex-row">
                        <div class="text-center sm:text-right">
                            <div id="nextLabel" class="text-[10px] sm:text-xs uppercase tracking-widest font-bold opacity-50 mb-0.5 text-rose-500 dark:text-rose-400 transition-colors">Berikutnya</div>
                            <div class="font-bold text-xs md:text-sm line-clamp-1">Borders & Rings</div>
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

<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>

<script>
    window.LESSON_IDS = [52, 53, 54, 55]; 
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? '[]') !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id)); 
    let completedSet = new Set(window.COMPLETED_IDS);
    
    const ACTIVITY_LESSON_ID = 55; 
    let activityCompleted = completedSet.has(ACTIVITY_LESSON_ID) || {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};

    document.addEventListener('DOMContentLoaded', () => {
        initSidebarScroll();
        initVisualEffects();
        
        updateProgressUI(false); 
        
        window.simSpData = { size: 'bg-cover', repeat: 'bg-no-repeat', pos: 'bg-center' };
        window.simOverlayMaskData = { overlay: 'bg-gradient-to-t from-black/90 via-black/40 to-transparent', clip: 'true', dir: 'bg-gradient-to-r' };

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
                    dot.outerHTML = `<svg class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
                } else {
                    dot.outerHTML = `<svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
                }
            }
        }
    }

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

    let editor;
    const starterCode = `<div class="w-full min-h-screen bg-slate-900 font-sans">
  
  <div id="hero-section" class="relative h-[450px] bg-[url('https://images.unsplash.com/photo-1541888045610-d7e57c6382b6?q=80&w=2070')] bg-center bg-no-repeat">
    
    <div id="hero-overlay" class="absolute inset-0"></div>
    
    <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-4 pt-10">
      
      <h1 id="hero-title" class="text-5xl md:text-7xl font-black uppercase tracking-tight mb-6 text-white">
        Tech Conf 2026
      </h1>
      <p class="text-white font-bold tracking-widest uppercase mb-8 drop-shadow-md">Konferensi Implementasi Pemrograman Frontend Nusantara</p>
      
      <button class="bg-blue-600 text-white font-bold py-4 px-8 rounded-full shadow-lg hover:scale-105 transition-transform duration-300">Pendaftaran Sesi Dibuka</button>
    </div>
  </div>

  <div class="h-[600px] bg-slate-100 p-12 text-center flex items-start justify-center">
     <p class="text-slate-500 font-bold text-xl border-2 border-dashed border-slate-300 p-8 rounded-xl w-full max-w-lg mt-10">Lakukan verifikasi efek Parallax CSS yang kamu buat dengan menggulir (scroll) kotak pratinjau ini ke bawah secara perlahan.</p>
  </div>
</div>`;

    function initMonaco() {
        require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' } });
        require(['vs/editor/editor.main'], function () {
            
            editor = monaco.editor.create(document.getElementById('codeEditor'), {
                value: starterCode, 
                language: 'html', 
                theme: 'vs-dark',
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
                validateCodeDOM(code);
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
                .custom-scrollbar::-webkit-scrollbar { width: 5px; }
                .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
            </style>
        </head>
        <body class="w-full h-full flex items-start justify-center m-0 custom-scrollbar overflow-x-hidden">
            ${code}
        </body>
        </html>`;
        frame.srcdoc = content;
    }

    function validateCodeDOM(code) {
        let passed = 0;
        
        const parser = new DOMParser();
        const doc = parser.parseFromString(code, 'text/html');
        
        const heroEl = doc.getElementById('hero-section');
        const overlayEl = doc.getElementById('hero-overlay');
        const titleEl = doc.getElementById('hero-title');

        const heroCls = heroEl ? heroEl.className : '';
        const overlayCls = overlayEl ? overlayEl.className : '';
        const titleCls = titleEl ? titleEl.className : '';

        const checks = [
            { id: 'check-bg', valid: heroCls.includes('bg-cover') && heroCls.includes('bg-fixed') },
            { id: 'check-overlay', valid: overlayCls.includes('bg-gradient-to-t') && overlayCls.includes('from-black/80') && overlayCls.includes('to-transparent') },
            { id: 'check-title', valid: titleCls.includes('text-transparent') && titleCls.includes('bg-clip-text') && titleCls.includes('bg-gradient-to-r') && !titleCls.includes('text-white') }
        ];

        checks.forEach(c => {
            const el = document.getElementById(c.id);
            if (!el) return;
            const dot = el.querySelector('span'); 
            const textDiv = el.querySelector('div');
            
            if(c.valid) {
                textDiv.classList.add('text-emerald-500', 'dark:text-emerald-400');
                textDiv.querySelector('b').classList.add('text-emerald-600', 'dark:text-emerald-300');
                textDiv.querySelector('b').classList.remove('text-slate-800', 'dark:text-white/90');
                dot.innerHTML = '✓'; 
                dot.classList.remove('border-slate-300', 'dark:border-white/20');
                dot.classList.add('bg-emerald-500', 'border-transparent', 'text-white');
                passed++;
            } else {
                textDiv.classList.remove('text-emerald-500', 'dark:text-emerald-400');
                textDiv.querySelector('b').classList.remove('text-emerald-600', 'dark:text-emerald-300');
                textDiv.querySelector('b').classList.add('text-slate-800', 'dark:text-white/90');
                dot.innerHTML = ''; 
                dot.classList.remove('bg-emerald-500', 'border-transparent', 'text-white');
                dot.classList.add('border-slate-300', 'dark:border-white/20');
            }
        });

        document.getElementById('progressText').innerText = passed + '/3 Status Validasi Terpenuhi';
        
        const btn = document.getElementById('submitExerciseBtn');
        if (passed === 3) {
            btn.disabled = false;
            btn.classList.remove('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = '<span>Verifikasi Berhasil! Simpan Komponen ke Pangkalan Data.</span><svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
            
            document.getElementById('progressText').classList.replace('text-blue-700', 'text-white');
            document.getElementById('progressText').classList.replace('dark:text-blue-400', 'text-white');
            document.getElementById('progressText').classList.replace('bg-blue-100', 'bg-emerald-500');
            document.getElementById('progressText').classList.replace('dark:bg-blue-900/30', 'bg-emerald-500');
            document.getElementById('progressText').classList.replace('border-blue-200', 'border-emerald-400');
            document.getElementById('progressText').classList.replace('dark:border-blue-500/20', 'border-emerald-400');

        } else {
            btn.disabled = true;
            btn.classList.add('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = '<span>Selesaikan Kriteria Di Atas Untuk Mengirim</span><svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>';
            
            document.getElementById('progressText').classList.replace('text-white', 'text-blue-700');
            document.getElementById('progressText').classList.replace('text-white', 'dark:text-blue-400');
            document.getElementById('progressText').classList.replace('bg-emerald-500', 'bg-blue-100');
            document.getElementById('progressText').classList.replace('bg-emerald-500', 'dark:bg-blue-900/30');
            document.getElementById('progressText').classList.replace('border-emerald-400', 'border-blue-200');
            document.getElementById('progressText').classList.replace('border-emerald-400', 'dark:border-blue-500/20');
        }
    }

    function resetEditor() { 
        if(editor && !activityCompleted) { 
            editor.setValue(starterCode); 
            validateCodeDOM(starterCode); 
        } 
    }

    async function submitExercise() {
        const btn = document.getElementById('submitExerciseBtn');
        btn.innerHTML = '<span class="animate-pulse">Menghubungi Validasi Server Sinkronisasi...</span>'; 
        btn.disabled = true;
        
        try {
            await fetch('/activity/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify({ activity_id: 12, score: 100 }) });
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            activityCompleted = true;
            lockActivityUI();   
            unlockNextChapter(); 
        } catch(e) { 
            console.error(e); 
            btn.innerHTML = "Gagal Mengirim Jawaban. Periksa Koneksi Kamu.";
            btn.disabled = false;
            btn.classList.add('shake'); setTimeout(() => btn.classList.remove('shake'), 500);
        }
    }

    function lockActivityUI() {
        document.getElementById('lockOverlay').classList.remove('hidden');
        if(editor) editor.updateOptions({ readOnly: true });
        
        const btn = document.getElementById('submitExerciseBtn'); 
        btn.innerText = "Modul Aktivitas Sukses Terevaluasi."; 
        btn.disabled = true;
        btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-500');
        btn.classList.add('bg-slate-200', 'dark:bg-slate-700', 'text-slate-500', 'dark:text-slate-400', 'cursor-not-allowed', 'shadow-none');
        
        if(editor && activityCompleted) {
            editor.setValue(`<div class="w-full min-h-screen bg-slate-900 font-sans">\n  \n  \n  <div id="hero-section" class="relative h-[450px] bg-[url('https://images.unsplash.com/photo-1541888045610-d7e57c6382b6?q=80&w=2070')] bg-center bg-no-repeat bg-cover bg-fixed">\n    \n    \n    <div id="hero-overlay" class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>\n    \n    <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-4 pt-10">\n      \n      \n      <h1 id="hero-title" class="text-5xl md:text-7xl font-black uppercase tracking-tight mb-6 text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-600">\n        Tech Conf 2026\n      </h1>\n      <p class="text-white font-bold tracking-widest uppercase mb-8 drop-shadow-md">Belajar Membangun Media Interaktif Berbasis Cloud</p>\n      \n      <button class="bg-blue-600 text-white font-bold py-4 px-8 rounded-full shadow-lg hover:scale-105 transition-transform duration-300">Konfirmasi Hadir</button>\n    </div>\n  </div>\n\n  <div class="h-[600px] bg-slate-100 p-12 text-center flex items-start justify-center">\n     <p class="text-slate-500 font-bold text-xl border-2 border-dashed border-slate-300 p-8 rounded-xl w-full max-w-lg mt-10">Keren! Coba scroll ke bawah untuk melihat efek Parallax yang kamu bangun.</p>\n  </div>\n</div>`);
            validateCodeDOM(editor.getValue());
        }
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-slate-500');
            btn.classList.add('text-blue-600', 'dark:text-blue-400', 'hover:text-blue-700', 'dark:hover:text-white', 'cursor-pointer');
            
            document.getElementById('nextLabel').innerText = "Berikutnya";
            document.getElementById('nextLabel').classList.remove('opacity-50', 'text-rose-500', 'dark:text-rose-400');
            document.getElementById('nextLabel').classList.add('text-blue-600', 'dark:text-blue-400', 'opacity-100');
            
            const icon = document.getElementById('nextIcon');
            icon.innerHTML = `<svg class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
            icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
            icon.classList.add('bg-blue-100', 'dark:bg-blue-500/10', 'border-blue-300', 'dark:border-blue-500/30', 'text-blue-600', 'dark:text-blue-400', 'shadow-md', 'dark:shadow-lg');
            
            btn.onclick = () => window.location.href = "{{ route('courses.borders') ?? '#' }}"; 
        }
    }

    window.sim1Attach = 'bg-scroll';
    window.updateSimAttach = function(btn, mode) {
        window.sim1Attach = 'bg-' + mode;
        const target = document.getElementById('area-attach');
        target.className = `absolute inset-1 custom-scrollbar overflow-y-scroll bg-cover bg-center transition-all duration-300 rounded-xl border border-white/20 ${window.sim1Attach}`;

        const insight = document.getElementById('demo-attach-insight');
        if(mode === 'scroll') insight.innerHTML = "Perhatikan saat kamu menggunakan <code class='font-bold bg-white dark:bg-black/40 px-1 rounded text-blue-600 dark:text-blue-300'>bg-scroll</code>, gambar akan ikut menggulung ke atas saat elemen digeser. Ini adalah perilaku alami web yang paling sering kita lihat sehari-hari.";
        if(mode === 'fixed') insight.innerHTML = "Keren! Dengan <code class='font-bold bg-white dark:bg-black/40 px-1 rounded text-blue-600 dark:text-blue-300'>bg-fixed</code>, gambar seolah-olah menempel di layar komputermu, sementara halamannya bergerak melewatinya. Ini adalah trik rahasia di balik efek visual Parallax yang mewah.";
        if(mode === 'local') insight.innerHTML = "Utilitas <code class='font-bold bg-white dark:bg-black/40 px-1 rounded text-blue-600 dark:text-blue-300'>bg-local</code> ini lumayan unik. Dia memastikan gambar bergerak selaras dengan scroll yang terjadi di dalam elemen itu sendiri, bukan scroll halaman utama.";
        
        document.querySelectorAll('.btn-sim-1').forEach(function(el) {
            el.classList.remove('bg-blue-600', 'text-white', 'shadow-lg', 'border-blue-400');
            el.classList.add('bg-slate-100', 'dark:bg-white/5', 'text-slate-600', 'dark:text-white/50', 'border-transparent', 'hover:bg-slate-200', 'dark:hover:bg-white/10');
            const spanLast = el.querySelector('span:last-child');
            if(spanLast) { spanLast.classList.remove('opacity-80'); spanLast.classList.add('opacity-60'); }
        });
        
        btn.classList.remove('bg-slate-100', 'dark:bg-white/5', 'text-slate-600', 'dark:text-white/50', 'border-transparent', 'hover:bg-slate-200', 'dark:hover:bg-white/10');
        btn.classList.add('bg-blue-600', 'text-white', 'shadow-lg', 'border-blue-400');
        const btnSpanLast = btn.querySelector('span:last-child');
        if(btnSpanLast) { btnSpanLast.classList.remove('opacity-60'); btnSpanLast.classList.add('opacity-80'); }

        insight.classList.remove('insight-box'); void insight.offsetWidth; insight.classList.add('insight-box');
    };

    window.updateSimSizePos = function(btn, type, cls) {
        window.simSpData[type] = cls;
        const target = document.getElementById('area-sp');
        const code = document.getElementById('code-sp');

        target.className = `absolute inset-0 transition-all duration-[600ms] shadow-inner border border-white/20 bg-[url('https://images.unsplash.com/photo-1542038784456-1ea8e935640e?q=80&w=800&auto=format&fit=crop')] ${window.simSpData.size} ${window.simSpData.repeat} ${window.simSpData.pos}`;
        
        code.innerText = `${window.simSpData.size} ${window.simSpData.repeat} ${window.simSpData.pos}`;

        document.querySelectorAll(`.btn-sim-2-${type}`).forEach(function(el) {
            el.classList.remove('bg-indigo-600', 'text-white', 'shadow-md', 'border-indigo-400', 'shadow-indigo-500/40', 'relative', 'overflow-hidden');
            el.classList.add('bg-white', 'dark:bg-white/5', 'text-slate-600', 'dark:text-white/50', 'border-slate-200', 'dark:border-white/10', 'hover:bg-slate-100', 'dark:hover:bg-white/10', 'border-transparent', 'hover:border-slate-300');
            const spanPulse = el.querySelector('span');
            if(spanPulse) spanPulse.remove();
        });
        
        if (type === 'pos') {
            document.querySelectorAll(`.btn-sim-2-pos`).forEach(function(el) {
                el.classList.remove('bg-indigo-600', 'text-white', 'shadow-md', 'border-indigo-500', 'shadow-indigo-500/40', 'relative', 'overflow-hidden');
                el.classList.add('bg-white', 'dark:bg-white/10', 'text-slate-600', 'dark:text-white/50', 'border-transparent', 'hover:bg-slate-100', 'dark:hover:bg-white/20');
            });

            btn.classList.remove('bg-white', 'dark:bg-white/10', 'text-slate-600', 'dark:text-white/50', 'border-transparent', 'hover:bg-slate-100', 'dark:hover:bg-white/20');
            btn.classList.add('bg-indigo-600', 'text-white', 'shadow-md', 'border-indigo-500', 'shadow-indigo-500/40', 'relative', 'overflow-hidden');
            
            const pulseHTML = document.createElement('span');
            pulseHTML.className = 'absolute inset-0 bg-white/20 rounded-lg animate-pulse';
            btn.prepend(pulseHTML);
        } else {
             btn.classList.remove('bg-white', 'dark:bg-white/5', 'text-slate-600', 'dark:text-white/50', 'border-slate-200', 'dark:border-white/10', 'hover:bg-slate-100', 'dark:hover:bg-white/10', 'border-transparent');
             btn.classList.add('bg-indigo-600', 'text-white', 'shadow-md', 'border-indigo-400');
        }

        const insight = document.getElementById('demo-sp-insight');
        let tSize = window.simSpData.size === 'bg-cover' ? 'memastikan gambar memenuhi area tanpa merubah rasionya' : 'memaksa gambar masuk seluruhnya (jangan kaget kalau melihat ada sisi kosong)';
        let tRep = window.simSpData.repeat === 'bg-repeat' ? 'mengulang gambar yang sama berkali-kali untuk mengisi sisi putih yang kosong' : 'mencegah gambar berulang';
        let tPos = 'Titik fokus pemotongannya bertumpu di posisi';
        
        insight.innerHTML = `Dengan menggunakan <code class="bg-white dark:bg-black/40 px-1 rounded font-bold text-indigo-600 dark:text-indigo-300">${window.simSpData.size}</code>, kamu ${tSize}. Ditambah dengan <code class="bg-white dark:bg-black/40 px-1 rounded font-bold text-indigo-600 dark:text-indigo-300">${window.simSpData.repeat}</code> untuk ${tRep}. ${tPos} <code class="bg-white dark:bg-black/40 px-1 rounded font-bold text-indigo-600 dark:text-indigo-300">${window.simSpData.pos}</code>, agar bagian itulah yang akan diprioritaskan tampil meski layar diperkecil.`;
        
        insight.classList.remove('insight-box'); void insight.offsetWidth; insight.classList.add('insight-box');
    };

    window.updateSimOverlayMask = function(btn, type, cls) {
        window.simOverlayMaskData[type] = cls;
        const areaOverlay = document.getElementById('area-overlay');
        const textMask = document.getElementById('text-mask');
        const insightOverlay = document.getElementById('demo-overlay-insight');
        const insightMask = document.getElementById('demo-mask-insight');
        const controlsGrad = document.getElementById('gradient-controls');

        document.querySelectorAll(`.btn-sim-3-${type}`).forEach(function(el) {
            el.classList.remove('bg-cyan-600', 'bg-teal-600', 'text-white', 'shadow-md', 'shadow-cyan-500/30', 'border-cyan-400', 'border-teal-500');
            el.classList.add('bg-slate-200', 'dark:bg-white/5', 'text-slate-600', 'dark:text-white/50', 'border-transparent', 'hover:bg-slate-300', 'dark:hover:bg-white/10');
        });
        
        if (type === 'dir') {
             btn.classList.remove('bg-slate-200', 'dark:bg-white/5', 'text-slate-600', 'dark:text-white/50', 'border-transparent', 'hover:bg-slate-300', 'dark:hover:bg-white/10');
             btn.classList.add('bg-teal-600', 'text-white', 'shadow-md', 'border-teal-500');
        } else {
             btn.classList.remove('bg-slate-200', 'dark:bg-white/5', 'text-slate-600', 'dark:text-white/50', 'border-transparent', 'hover:bg-slate-300', 'dark:hover:bg-white/10');
             btn.classList.add('bg-cyan-600', 'text-white', 'shadow-md', 'shadow-cyan-500/30', 'border-cyan-400');
        }

        if(type === 'overlay') {
            areaOverlay.className = `absolute inset-0 transition-all duration-700 z-10 ${cls}`;
            
            if(cls === 'bg-transparent') {
                insightOverlay.innerHTML = "<strong>Catatan Overlay:</strong> Coba perhatikan, karena kamu menghapus pelindung transparan (<code class='font-bold bg-white dark:bg-black/40 px-1 rounded text-cyan-600 dark:text-cyan-300'>bg-transparent</code>), teks putih bertabrakan langsung dengan awan yang terang. Ini membuat audiens sangat kesulitan membacanya.";
            } else if(cls.includes('bg-black')) {
                insightOverlay.innerHTML = "<strong>Catatan Overlay:</strong> Menyelimuti foto dengan warna solid hitam transparan (<code class='font-bold bg-white dark:bg-black/40 px-1 rounded text-cyan-600 dark:text-cyan-300'>bg-black/60</code>) memang menjamin keterbacaan teks yang sempurna. Tapi, kadang ini membuat pemandangan aslinya jadi terlihat terlalu gelap dan muram.";
            } else {
                insightOverlay.innerHTML = "<strong>Catatan Overlay:</strong> Utilitas <code class='font-bold bg-white dark:bg-black/40 px-1 rounded text-cyan-600 dark:text-cyan-300'>bg-gradient-to-t</code> adalah trik cerdas. Dia memberikan bayangan yang perlahan memudar ke atas, sehingga teks di bawah terbaca jelas tanpa merusak cerahnya warna langit di bagian atas foto.";
            }
            insightOverlay.classList.remove('insight-box'); void insightOverlay.offsetWidth; insightOverlay.classList.add('insight-box');
        }

        if(type === 'clip' || type === 'dir') {
            if(window.simOverlayMaskData.clip === 'true') {
                controlsGrad.style.maxHeight = '100px';
                controlsGrad.style.opacity = '1';
                textMask.className = `text-5xl sm:text-6xl font-black transition-all duration-700 tracking-tighter drop-shadow-sm text-transparent bg-clip-text mb-4 pb-2 leading-tight ${window.simOverlayMaskData.dir} from-cyan-400 to-blue-600`;
                insightMask.innerHTML = `<strong>Catatan Teks Gradien:</strong> Dengan menghilangkan warna asli font (<code class='font-bold bg-white dark:bg-black/40 px-1 rounded text-teal-600 dark:text-teal-300'>text-transparent</code>), utilitas <code class='font-bold bg-white dark:bg-black/40 px-1 rounded text-teal-600 dark:text-teal-300'>bg-clip-text</code> dapat memotong warna gradien background agar mengikuti tepian huruf secara presisi. Sekarang arah gradasinya adalah <code class='font-bold bg-white dark:bg-black/40 px-1 rounded text-teal-600 dark:text-teal-300'>${window.simOverlayMaskData.dir}</code>.`;
            } else {
                controlsGrad.style.maxHeight = '0px';
                controlsGrad.style.opacity = '0';
                textMask.className = `text-5xl sm:text-6xl font-black transition-all duration-700 tracking-tighter drop-shadow-sm text-white mb-4 pb-2 leading-tight`;
                insightMask.innerHTML = "<strong>Catatan Teks Gradien:</strong> Karena kita memaksakan warna teks kembali menjadi solid (<code class='font-bold bg-white dark:bg-black/40 px-1 rounded text-teal-600 dark:text-teal-300'>text-white</code>), warna gradien pada background tidak lagi tembus ke dalam karakter tulisan.";
            }
            insightMask.classList.remove('insight-box'); void insightMask.offsetWidth; insightMask.classList.add('insight-box');
        }
    };

    function highlightAnchor(id) {
        const anchors = document.querySelectorAll('.sidebar-anchor');

        anchors.forEach(a => {
            a.classList.remove('bg-slate-100', 'dark:bg-white/5', 'border-blue-500', 'border-amber-500');
            a.classList.add('border-transparent');
            
            const dot = a.querySelector('.anchor-dot');
            const isActivity = a.dataset.type === 'activity';

            if(dot) dot.classList.remove('scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#3b82f6]', 'dark:shadow-[0_0_10px_#f59e0b]', 'bg-blue-500', 'dark:bg-blue-400', 'bg-cyan-500', 'dark:bg-cyan-400');
            
            if (isActivity) {
                if(dot) { dot.classList.remove('bg-amber-400'); dot.classList.add('bg-slate-300', 'dark:bg-slate-600'); }
            } else {
                if(dot) { dot.classList.remove('bg-blue-500', 'dark:bg-blue-400'); dot.classList.add('bg-slate-300', 'dark:bg-slate-600'); }
            }

            const text = a.querySelector('.anchor-text');
            if(text) { text.classList.remove('text-slate-900', 'dark:text-white', 'font-bold'); text.classList.add('text-slate-500', 'dark:text-slate-500'); }
        });

        const activeAnchor = document.querySelector(`.sidebar-anchor[data-target="${id}"]`);
        if (activeAnchor) {
            const isActivity = activeAnchor.dataset.type === 'activity';
            
            activeAnchor.classList.add('bg-slate-100', 'dark:bg-white/5');
            activeAnchor.classList.add(isActivity ? 'border-amber-500' : 'border-blue-500');
            activeAnchor.classList.remove('border-transparent');
            
            const dot = activeAnchor.querySelector('.anchor-dot');
            if(dot) {
                dot.classList.remove('bg-slate-300', 'dark:bg-slate-600');
                if (isActivity) {
                    dot.classList.add('bg-amber-500', 'dark:bg-amber-400', 'scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#f59e0b]');
                } else {
                    dot.classList.add('bg-blue-600', 'dark:bg-blue-400', 'scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#3b82f6]');
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