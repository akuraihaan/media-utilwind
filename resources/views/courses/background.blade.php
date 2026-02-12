@extends('layouts.landing')
@section('title', 'Bab 3.2 · Background Masterclass')

@section('content')
{{-- <div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-blue-500/30"> --}}
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30 pt-20">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-60"></div>
        <div id="gradient-wave" class="absolute inset-0"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.035]"></div>
        <div id="cursor-glow"></div>
    </div>

        @include('layouts.partials.navbar')


    <div class="flex flex-1 overflow-hidden relative z-20">

        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-[#020617]/80 backdrop-blur-2xl border-b border-white/5 px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500/20 to-transparent border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-400">3.2</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Background masterclass</h1>
                        <p class="text-[10px] text-white/50">Mastering background</p>
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
                <article class="space-y-40">
                    
                    <section id="attachment" class="lesson-section scroll-mt-32" data-lesson-id="52">
                        <div class="space-y-8">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-300 text-[10px] font-bold uppercase tracking-widest">
                                <!-- <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span> -->
                                Dasar Background
                            </div>
                            
                            <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                Attachment & <br> 
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Clipping Mask</span>
                            </h2>
                            
                            <div class="prose prose-invert max-w-none text-white/80 space-y-6">
                                <p class="text-lg leading-relaxed">
                                    Properti background tidak hanya sekadar warna. Tailwind memungkinkan Anda mengontrol bagaimana gambar latar bereaksi terhadap scroll (Attachment) dan bagaimana background dipotong relatif terhadap kontennya (Clip).
                                </p>
                                
                                <h3 class="text-xl font-bold text-white mt-8">1. Background Attachment (Parallax)</h3>
                                <p>
                                    Mengatur apakah gambar latar ikut bergerak saat halaman digulir atau tetap diam di posisinya. Ini adalah kunci membuat efek "Parallax".
                                </p>
                                <ul class="list-disc pl-5 space-y-2 marker:text-blue-500">
                                    <li><code class="text-blue-400">bg-scroll</code> (Default): Background menempel pada elemen, ikut bergerak saat halaman discroll.</li>
                                    <li><code class="text-blue-400">bg-fixed</code>: Background diam relatif terhadap viewport. Menciptakan efek kedalaman.</li>
                                    <li><code class="text-blue-400">bg-local</code>: Background ikut bergerak jika konten *di dalam* elemen tersebut discroll.</li>
                                </ul>

                                <div class="bg-[#0f141e] p-6 rounded-2xl border border-white/5 relative overflow-hidden group mt-6">
                                    <h4 class="text-xs font-bold text-white/40 uppercase tracking-widest mb-4">Simulator Attachment</h4>
                                    <div class="grid md:grid-cols-3 gap-6">
                                        <div class="bg-black/30 p-4 rounded-xl border border-white/5">
                                            <div class="h-32 overflow-y-scroll custom-scrollbar bg-fixed bg-[url('https://grainy-gradients.vercel.app/noise.svg')] bg-cover border border-white/10 mb-3 relative group/scroll">
                                                <div class="h-[200%] flex items-center justify-center">
                                                    <span class="text-xs text-white/50 group-hover/scroll:text-white transition bg-black/50 px-2 py-1 rounded">Scroll Saya! (Fixed)</span>
                                                </div>
                                            </div>
                                            <code class="text-xs text-blue-400 font-bold">bg-fixed</code>
                                            <p class="text-[10px] text-white/40 mt-1">Gambar diam (Parallax).</p>
                                        </div>
                                        <div class="bg-black/30 p-4 rounded-xl border border-white/5">
                                            <div class="h-32 overflow-y-scroll custom-scrollbar bg-local bg-[url('https://grainy-gradients.vercel.app/noise.svg')] bg-cover border border-white/10 mb-3 relative group/scroll">
                                                <div class="h-[200%] flex items-center justify-center">
                                                    <span class="text-xs text-white/50 group-hover/scroll:text-white transition bg-black/50 px-2 py-1 rounded">Scroll Saya! (Local)</span>
                                                </div>
                                            </div>
                                            <code class="text-xs text-blue-400 font-bold">bg-local</code>
                                            <p class="text-[10px] text-white/40 mt-1">Gambar ikut konten.</p>
                                        </div>
                                        <div class="bg-black/30 p-4 rounded-xl border border-white/5">
                                            <div class="h-32 overflow-y-scroll custom-scrollbar bg-scroll bg-[url('https://grainy-gradients.vercel.app/noise.svg')] bg-cover border border-white/10 mb-3 relative group/scroll">
                                                <div class="h-[200%] flex items-center justify-center">
                                                    <span class="text-xs text-white/50 group-hover/scroll:text-white transition bg-black/50 px-2 py-1 rounded">Scroll Saya! (Scroll)</span>
                                                </div>
                                            </div>
                                            <code class="text-xs text-blue-400 font-bold">bg-scroll</code>
                                            <p class="text-[10px] text-white/40 mt-1">Default behavior.</p>
                                        </div>
                                    </div>
                                </div>

                                <h3 class="text-xl font-bold text-white mt-12">2. Background Clip</h3>
                                <p>
                                    Digunakan untuk memotong background sesuai bentuk elemen (Border, Padding, atau Content). Paling sering digunakan untuk <strong class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Teks Gradasi</strong>.
                                </p>
                                
                                <div class="bg-[#0f141e] p-6 rounded-2xl border border-blue-500/30 shadow-lg shadow-blue-900/20 relative overflow-hidden group mt-6 text-center">
                                    <span class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-indigo-400 to-cyan-400 animate-pulse block mb-2">
                                        MAGIC TEXT
                                    </span>
                                    <code class="text-xs text-white/40 bg-white/5 px-2 py-1 rounded">bg-clip-text + text-transparent</code>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="position" class="lesson-section scroll-mt-32" data-lesson-id="53">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">3.2.2 Posisi & Warna</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <div class="prose prose-invert max-w-none text-white/80 space-y-6">
                            <p>
                                Mengontrol posisi gambar latar (Focal Point) sangat penting agar bagian utama gambar tidak terpotong pada layar kecil.
                            </p>
                            
                            <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-8 mb-12 relative overflow-hidden shadow-2xl">
                                <h3 class="text-lg font-bold text-white mb-6 text-center">Simulator Posisi</h3>
                                
                                <div class="flex justify-center gap-4 mb-6">
                                    <button onclick="setSimPos('bg-left-top')" class="px-3 py-1 rounded border border-white/10 bg-white/5 hover:bg-white/10 text-xs transition">Left Top</button>
                                    <button onclick="setSimPos('bg-center')" class="px-3 py-1 rounded border border-white/10 bg-white/5 hover:bg-white/10 text-xs transition">Center</button>
                                    <button onclick="setSimPos('bg-right-bottom')" class="px-3 py-1 rounded border border-white/10 bg-white/5 hover:bg-white/10 text-xs transition">Right Bottom</button>
                                </div>

                                <div id="demo-pos" class="h-48 w-full bg-black/40 rounded-xl border border-white/10 bg-[url('https://img.icons8.com/fluency/96/tailwind_css.png')] bg-no-repeat bg-center transition-all duration-500 relative shadow-inner">
                                    <div class="absolute inset-0 grid grid-cols-3 grid-rows-3 pointer-events-none opacity-20">
                                        <div class="border-r border-b border-white"></div><div class="border-r border-b border-white"></div><div class="border-b border-white"></div>
                                        <div class="border-r border-b border-white"></div><div class="border-r border-b border-white"></div><div class="border-b border-white"></div>
                                        <div class="border-r border-white"></div><div class="border-r border-white"></div><div></div>
                                    </div>
                                    <span class="absolute inset-0 flex items-center justify-center text-white/5 text-4xl font-black uppercase tracking-widest">CANVAS</span>
                                </div>
                                
                                <div class="mt-8 pt-6 border-t border-white/5 grid grid-cols-2 gap-6 text-center">
                                    <div>
                                        <code class="text-xs text-blue-400 bg-blue-500/10 px-2 py-1 rounded">bg-repeat</code>
                                        <p class="text-[10px] text-white/40 mt-1">Gambar diulang vertikal & horizontal (Default).</p>
                                    </div>
                                    <div>
                                        <code class="text-xs text-blue-400 bg-blue-500/10 px-2 py-1 rounded">bg-no-repeat</code>
                                        <p class="text-[10px] text-white/40 mt-1">Gambar hanya muncul satu kali.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="size" class="lesson-section scroll-mt-32" data-lesson-id="54">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">3.2.3 Ukuran & Gradien</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <div class="prose prose-invert max-w-none text-white/80 space-y-6">
                            <p>
                                <code>bg-size</code> menentukan seberapa besar gambar latar ditampilkan. Dua nilai paling penting adalah <code>cover</code> (memenuhi area) dan <code>contain</code> (memuat seluruh gambar).
                            </p>

                            <div class="grid md:grid-cols-2 gap-8">
                                <div class="bg-[#1e1e1e] p-6 rounded-2xl border border-white/5">
                                    <h4 class="text-white font-bold mb-4 flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span> Gradient Generator
                                    </h4>
                                    <div id="demo-grad" class="h-24 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-500 shadow-lg transition-all duration-500 flex items-center justify-center mb-4 border border-white/10">
                                        <span class="font-bold text-white text-sm drop-shadow-md">Preview</span>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <button onclick="setSimGrad('bg-gradient-to-r from-blue-500 to-indigo-500')" class="px-2 py-1 bg-white/5 rounded text-[10px] hover:bg-white/10 text-white transition border border-white/5">Blue-Indigo</button>
                                        <button onclick="setSimGrad('bg-gradient-to-br from-indigo-500 to-purple-500')" class="px-2 py-1 bg-white/5 rounded text-[10px] hover:bg-white/10 text-white transition border border-white/5">Indigo-Purple</button>
                                        <button onclick="setSimGrad('bg-gradient-to-t from-slate-900 to-blue-900')" class="px-2 py-1 bg-white/5 rounded text-[10px] hover:bg-white/10 text-white transition border border-white/5">Deep Space</button>
                                    </div>
                                </div>

                                <div class="bg-[#1e1e1e] p-6 rounded-2xl border border-white/5">
                                    <h4 class="text-white font-bold mb-4 flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span> Size Comparison
                                    </h4>
                                    <div class="space-y-4">
                                        <div class="group">
                                            <div class="flex justify-between mb-1 text-xs text-white/40">
                                                <code>bg-cover</code>
                                                <span>Zoom & Crop</span>
                                            </div>
                                            <div class="h-20 w-full bg-black/50 rounded overflow-hidden relative border border-white/10">
                                                <div class="absolute inset-0 bg-cover bg-center opacity-70" style="background-image: url('https://images.unsplash.com/photo-1550684848-fac1c5b4e853');"></div>
                                            </div>
                                        </div>
                                        <div class="group">
                                            <div class="flex justify-between mb-1 text-xs text-white/40">
                                                <code>bg-contain</code>
                                                <span>Fit All</span>
                                            </div>
                                            <div class="h-20 w-full bg-black/50 rounded overflow-hidden relative border border-white/10">
                                                <div class="absolute inset-0 bg-contain bg-center bg-no-repeat opacity-70" style="background-image: url('https://images.unsplash.com/photo-1550684848-fac1c5b4e853');"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="activity-expert" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="55" data-manual="true">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl text-center group hover:border-blue-500/30 transition-all duration-500">
                            
                            <div class="p-8 border-b border-white/10 bg-gradient-to-r from-blue-900/10 to-transparent relative">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-600 text-white text-[10px] font-bold uppercase mb-4 tracking-widest shadow-lg shadow-blue-600/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    Expert Challenge
                                </div>
                                <h2 class="text-3xl font-black text-white mb-4">Studi Kasus: The Bali Experience</h2>
                                <p class="text-white/60 text-sm max-w-2xl mx-auto leading-relaxed">
                                    Buat Hero Section website travel yang memukau. Syarat: <strong>Gambar (Bali Temple)</strong> harus memenuhi layar, menggunakan efek <strong>Parallax (Fixed)</strong>, dan teks harus terbaca jelas dengan <strong>Overlay Gradient</strong>.
                                </p>
                            </div>

                            <div class="grid lg:grid-cols-12 min-h-[600px] text-left border-x border-b border-white/5 rounded-b-[2rem] bg-[#0f141e]">
                                
                                <div class="lg:col-span-4 bg-[#0f141e] border-r border-white/5 p-6 flex flex-col h-full">
                                    <div class="flex items-center justify-between mb-6">
                                        <h3 class="text-xs font-bold text-white/40 uppercase tracking-widest">Configurator</h3>
                                        <div class="flex gap-1">
                                            <div class="w-2 h-2 rounded-full bg-red-500/50"></div>
                                            <div class="w-2 h-2 rounded-full bg-yellow-500/50"></div>
                                            <div class="w-2 h-2 rounded-full bg-green-500/50"></div>
                                        </div>
                                    </div>

                                    <div class="flex-1 space-y-6 overflow-y-auto custom-scrollbar pr-2" id="practice-controls">
                                        </div>
                                    
                                    <div class="pt-6 mt-6 border-t border-white/5">
                                        <button id="checkBtn" onclick="checkSolution()" class="w-full py-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-bold text-lg shadow-lg hover:shadow-indigo-500/25 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                                            Verifikasi Desain 🚀
                                        </button>
                                        <div id="feedback-area" class="mt-4 hidden p-4 rounded-lg text-center text-sm font-bold animate-pulse"></div>
                                    </div>
                                </div>

                                <div class="lg:col-span-8 bg-slate-100 text-slate-900 p-0 relative overflow-y-auto flex flex-col">
                                    <div class="absolute top-4 right-4 text-[10px] font-mono text-slate-400 bg-white/90 backdrop-blur px-2 py-1 rounded border border-slate-200 shadow-sm z-50">LIVE PREVIEW</div>

                                    <div class="flex-1 relative custom-scrollbar overflow-y-auto bg-slate-50" id="preview-viewport">
                                        
                                        <div id="target-hero" class="relative w-full h-[500px] flex items-center justify-center transition-all duration-700 ease-in-out border-b-8 border-blue-500 bg-slate-800 bg-center bg-no-repeat overflow-hidden">
                                            
                                            <div id="target-overlay" class="absolute inset-0 z-0 transition-all duration-500"></div>

                                            <div class="relative z-10 text-center px-4 max-w-4xl mt-10">
                                                <span class="text-blue-300 font-bold tracking-[0.3em] uppercase text-xs md:text-sm mb-6 block drop-shadow-md bg-blue-900/30 backdrop-blur-md px-4 py-2 rounded-full border border-blue-400/20 inline-block">Wonderful Indonesia</span>
                                                <h1 class="text-5xl md:text-8xl font-black text-white mb-8 drop-shadow-2xl leading-tight tracking-tight">
                                                    Discover <br> The Hidden Paradise
                                                </h1>
                                                <button class="px-8 py-4 bg-white text-blue-900 font-bold rounded-full shadow-xl hover:scale-105 transition-transform">Explore Now</button>
                                            </div>
                                        </div>

                                        <div class="bg-white py-16 px-8 max-w-5xl mx-auto space-y-12 w-full relative z-10">
                                            <div class="flex items-center gap-4 mb-8 opacity-50">
                                                <div class="h-12 w-12 rounded-full bg-slate-200"></div>
                                                <div>
                                                    <div class="h-4 bg-slate-200 rounded w-32 mb-2"></div>
                                                    <div class="h-3 bg-slate-100 rounded w-24"></div>
                                                </div>
                                            </div>
                                            <div class="space-y-6 opacity-50">
                                                <div class="h-4 bg-slate-200 rounded w-full"></div>
                                                <div class="h-4 bg-slate-200 rounded w-full"></div>
                                                <div class="h-4 bg-slate-200 rounded w-5/6"></div>
                                            </div>
                                            <div class="grid grid-cols-3 gap-6 opacity-50">
                                                <div class="aspect-square bg-slate-200 rounded-xl"></div>
                                                <div class="aspect-square bg-slate-200 rounded-xl"></div>
                                                <div class="aspect-square bg-slate-200 rounded-xl"></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </section>

                </article>

                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center">
                    <a href="{{ route('courses.typography') }}" class="group flex items-center gap-3 text-slate-400 hover:text-white transition">
                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-white/30 transition">←</div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest opacity-50">Sebelumnya</div>
                            <div class="font-bold text-sm">3.1 Tipografi</div>
                        </div>
                    </a>

                    <div id="nextChapterBtn" class="group flex items-center gap-3 text-right text-slate-500 cursor-not-allowed opacity-50 grayscale">
                        <div class="text-right">
                            <div class="text-[10px] uppercase tracking-widest opacity-30">Terkunci</div>
                            <div class="font-bold text-sm">Bab 4: Komponen</div>
                        </div>
                        <div class="w-10 h-10 rounded-full border border-white/5 flex items-center justify-center">🔒</div>
                    </div>
                </div>

                <div class="mt-16 text-center text-white/20 text-xs">
                    &copy; {{ date('Y') }} Flowwind Learn. Materi dilindungi hak cipta.
                </div>
            </div>
        </main>
    </div>
</div>

<style>
    /* UTILS & ANIMATION */
    .nav-link.active { color: #60a5fa; position: relative; } /* Blue-400 */
    .nav-link.active::after { content: ''; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: linear-gradient(to right,#60a5fa,#6366f1); box-shadow: 0 0 12px rgba(96,165,250,0.8); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }

    #animated-bg{ background: radial-gradient(600px circle at 20% 20%, rgba(59,130,246,.15), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(99,102,241,.15), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(139,92,246,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: rgba(255,255,255,0.5); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: #fff; background: rgba(255,255,255,0.03); }
    .nav-item.active { color: #60a5fa; background: rgba(96, 165, 250, 0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #334155; transition: all 0.3s; }
    .nav-item.active .dot { background: #60a5fa; box-shadow: 0 0 8px #60a5fa; transform: scale(1.2); }

    /* SIDEBAR COMPATIBILITY */
    .sb-group.open .accordion-content { max-height: 1000px; opacity: 1; }
    .sb-group:not(.open) .accordion-content { max-height: 0; opacity: 0; overflow: hidden; }
    .sb-group.open svg { transform: rotate(180deg); }
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: rgba(255,255,255,0.5); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: #fff; background: rgba(255,255,255,0.03); }
    .nav-item.active { color: #22d3ee; background: rgba(34,211,238,0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #334155; transition: all 0.3s; }
    .nav-item.active .dot { background: #22d3ee; box-shadow: 0 0 8px #22d3ee; transform: scale(1.2); }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    /* --- CONFIGURATION --- */
    window.SUBBAB_LESSON_IDS = [52, 53, 54]; 
    window.INIT_COMPLETED_LESSONS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedLessons = new Set(window.INIT_COMPLETED_LESSONS);
    
    // Activity ID = 11 (Background Challenge)
    const ACTIVITY_ID = {{ $activityId ?? 11 }};
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};

    /* --- 1. CORE SYSTEM --- */
    document.addEventListener('DOMContentLoaded', () => {
        updateProgressUI();
        initLessonObserver();
        initVisualEffects();
        initSidebarScroll();
        renderControls();
        updatePreview(); // Init preview state
        
        if (activityCompleted) {
            disableExpertUI();
        }
    });

    /* --- 2. SIMULATORS UI --- */
    function setSimPos(cls) { 
        document.getElementById('demo-pos').className = `h-48 w-full bg-black/40 rounded-xl border border-white/10 bg-[url('https://img.icons8.com/fluency/96/tailwind_css.png')] bg-no-repeat transition-all duration-500 relative shadow-inner ${cls} bg-center`; 
        if(cls !== 'bg-center') document.getElementById('demo-pos').classList.remove('bg-center');
    }
    
    function setSimGrad(cls) { 
        document.getElementById('demo-grad').className = `h-24 rounded-xl shadow-lg transition-all duration-500 flex items-center justify-center mb-4 border border-white/10 ${cls}`; 
    }

    /* --- 3. EXPERT CHALLENGE LOGIC --- */
    const challengeData = {
        image: {
            label: "1. Background Image",
            options: [
                { val: 'none', label: 'None', correct: false },
                { val: "https://images.unsplash.com/photo-1537996194471-e657df975ab4?q=80&w=2070&auto=format&fit=crop", label: 'Bali Temple', correct: true },
                { val: "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=2070&auto=format&fit=crop", label: 'Beach Sand', correct: false }
            ]
        },
        size: {
            label: "2. Background Size",
            options: [
                { val: 'bg-auto', label: 'Auto (Asli)', correct: false },
                { val: 'bg-contain', label: 'Contain (Muat)', correct: false },
                { val: 'bg-cover', label: 'Cover (Penuh)', correct: true }
            ]
        },
        attachment: {
            label: "3. Scroll Effect",
            options: [
                { val: 'bg-scroll', label: 'Scroll (Normal)', correct: false },
                { val: 'bg-fixed', label: 'Fixed (Parallax)', correct: true }
            ]
        },
        overlay: {
            label: "4. Overlay Gradient",
            options: [
                { val: 'hidden', label: 'No Overlay', correct: false },
                { val: 'bg-black/90', label: 'Solid Dark', correct: false },
                { val: 'bg-gradient-to-t from-[#0f141e] via-[#0f141e]/60 to-transparent', label: 'Gradient Up', correct: true }
            ]
        }
    };

    let userChoices = { image: 'none', size: 'bg-auto', attachment: 'bg-scroll', overlay: 'hidden' };

    function renderControls() {
        const container = $('#practice-controls');
        if(!container.length) return;
        
        Object.entries(challengeData).forEach(([key, data]) => {
            let html = `<div class="bg-black/40 p-5 rounded-2xl border border-white/5 mb-6 hover:border-blue-500/20 transition-colors">
                <h4 class="text-blue-400 font-bold mb-4 uppercase text-[10px] tracking-[0.2em] border-b border-white/5 pb-2 flex items-center gap-2">
                    <span class="w-1 h-4 bg-blue-500 rounded-full"></span>
                    ${data.label}
                </h4>
                <div class="grid gap-2">`;
            
            data.options.forEach(opt => {
                // Check if active
                const isActive = userChoices[key] === opt.val;
                const activeClass = isActive 
                    ? 'bg-blue-600 text-white border-blue-500 shadow-lg shadow-blue-900/20' 
                    : 'bg-white/5 text-white/60 border-white/10 hover:bg-white/10 hover:text-white';

                html += `<button onclick="selectOption('${key}','${opt.val}')" class="${activeClass} w-full text-left px-4 py-3 rounded-xl text-xs font-mono border transition-all duration-200 active:scale-[0.98] flex justify-between items-center group">
                    <span>${opt.label}</span>
                    ${isActive ? '<span class="text-white animate-pulse">●</span>' : ''}
                </button>`;
            });
            html += `</div></div>`;
            container.append(html);
        });
    }

    window.selectOption = function(key, val) {
        if(activityCompleted) return;
        userChoices[key] = val;
        
        // Re-render controls to update active state
        $('#practice-controls').empty();
        renderControls();
        
        updatePreview();
    }

    function updatePreview() {
        const hero = document.getElementById('target-hero');
        const overlay = document.getElementById('target-overlay');
        
        // 1. Reset Classes
        hero.className = `relative w-full h-[500px] flex items-center justify-center transition-all duration-700 ease-in-out border-b-8 border-blue-500 bg-center bg-no-repeat overflow-hidden group`;
        
        // 2. Apply Props
        hero.classList.add(userChoices.size);
        hero.classList.add(userChoices.attachment);
        
        // 3. Handle Image via Style (URL)
        if (userChoices.image !== 'none') {
            hero.style.backgroundImage = `url('${userChoices.image}')`;
            hero.classList.remove('bg-slate-800');
        } else {
            hero.style.backgroundImage = 'none';
            hero.classList.add('bg-slate-800');
        }
        
        // 4. Handle Overlay
        overlay.className = `absolute inset-0 z-0 transition-all duration-500 ${userChoices.overlay}`;
    }

    window.checkSolution = async function() {
        if(activityCompleted) return;
        let isCorrect = true;
        let errorMsg = "";

        // Check logic
        Object.entries(challengeData).forEach(([key, data]) => {
            const correctVal = data.options.find(o => o.correct).val;
            if(userChoices[key] !== correctVal) {
                isCorrect = false;
                // Specific Feedback
                if(key === 'image') errorMsg = "Pilih gambar 'Bali Temple'.";
                else if(key === 'size') errorMsg = "Ukuran harus 'Cover' agar penuh.";
                else if(key === 'attachment') errorMsg = "Efek harus 'Fixed' (Parallax).";
                else if(key === 'overlay') errorMsg = "Tambahkan 'Gradient Up' agar teks terbaca.";
            }
        });

        const fb = $('#feedback-area');
        fb.removeClass('hidden bg-red-500/10 text-red-400 bg-emerald-500/10 text-emerald-400 border-red-500/20 border-emerald-500/20 text-blue-400 animate-pulse');
        const btn = document.getElementById('checkBtn');
        
        if(isCorrect) {
            fb.addClass('bg-emerald-500/10 text-emerald-400 border-emerald-500/20').html(`
                <div class="text-3xl mb-2 animate-bounce">🎉</div>
                <div class="text-lg font-black">Sempurna!</div>
                <div class="text-xs opacity-90 mt-1 font-medium">Anda berhasil membuat Hero Parallax.</div>
            `);
            await finishChapter();
        } else {
            btn.classList.add('shake'); setTimeout(() => btn.classList.remove('shake'), 500);
            fb.addClass('bg-red-500/10 text-red-300 border-red-500/30').html(`
                <div class="text-3xl mb-2">🧐</div>
                <div class="text-lg font-bold">Cek Lagi</div>
                <div class="text-xs mt-1 font-mono bg-red-900/30 p-2 rounded mx-auto inline-block border border-red-500/20">${errorMsg}</div>
            `);
        }
        fb.removeClass('hidden');
    }

    async function finishChapter() {
        const btn = document.getElementById('checkBtn');
        btn.innerHTML = "Menyimpan...";
        try {
            await fetch('/activity/complete', { method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'}, body: JSON.stringify({ activity_id: ACTIVITY_ID, score: 100 }) });
            await saveLessonToDB(53); // Mark last lesson done
            completedLessons.add(53);
            activityCompleted = true;
            updateProgressUI();
            disableExpertUI();
        } catch(e) { console.error(e); btn.innerHTML = "Gagal. Coba lagi."; }
    }

    function disableExpertUI() {
        $('#practice-controls button').prop('disabled', true).addClass('opacity-50 cursor-not-allowed grayscale');
        const btn = document.getElementById('checkBtn');
        if(btn) {
            btn.innerHTML = "Bab Selesai ✔";
            btn.classList.remove('bg-gradient-to-r', 'from-blue-600', 'to-indigo-500');
            btn.classList.add('bg-emerald-600', 'cursor-default');
            btn.onclick = null;
        }
        $('#feedback-area').removeClass('hidden').addClass('bg-emerald-500/10 text-emerald-400 border-emerald-500/20').html('Misi Telah Selesai ✔');
        unlockNext();
    }

    /* --- 4. SYSTEM UTILS --- */
    function updateProgressUI() {
        const total = window.SUBBAB_LESSON_IDS.length;
        const done = window.SUBBAB_LESSON_IDS.filter(id => completedLessons.has(id)).length;
        let percent = Math.round((done / total) * 100);
        if (done === total && !activityCompleted) percent = 90;
        else if (done === total && activityCompleted) percent = 100;
        
        ['topProgressBar', 'sideProgressBar'].forEach(id => { const el = document.getElementById(id); if(el) el.style.width = percent + '%'; });
        ['progressLabelTop', 'progressLabelSide'].forEach(id => { const el = document.getElementById(id); if(el) el.innerText = percent + '%'; });
        if (percent === 100) unlockNext();
    }

    function initLessonObserver() {
        const obs = new IntersectionObserver(async entries => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    const id = Number(entry.target.dataset.lessonId);
                    const isManual = entry.target.getAttribute('data-manual') === 'true';
                    if (id && !isManual && !completedLessons.has(id)) {
                        try { await saveLessonToDB(id); completedLessons.add(id); updateProgressUI(); } catch (e) {}
                    }
                }
            }
        }, { threshold: 0.5, root: document.getElementById('mainScroll') });
        document.querySelectorAll('.lesson-section').forEach(s => obs.observe(s));
    }

    async function saveLessonToDB(id) {
        const form = new FormData(); form.append('lesson_id', id);
        await fetch('/lesson/complete', { method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}, body: form });
    }

    function unlockNext() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.className = "group flex items-center gap-3 text-right text-blue-400 hover:text-blue-300 transition cursor-pointer";
            btn.innerHTML = `<div class="text-right"><div class="text-[10px] uppercase tracking-widest opacity-50">Selanjutnya</div><div class="font-bold text-sm">Bab 4: Komponen</div></div><div class="w-10 h-10 rounded-full border border-blue-500/30 bg-blue-500/10 flex items-center justify-center">→</div>`;
        }
    }

    function initVisualEffects() { 
        const c=document.getElementById('stars'),x=c.getContext('2d');
        let s=[]; function r(){c.width=innerWidth;c.height=innerHeight}
        r();window.onresize=r;
        for(let i=0;i<80;i++)s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.5,v:Math.random()*0.1+.05});
        (function a(){x.clearRect(0,0,c.width,c.height);x.fillStyle='rgba(255,255,255,.3)';s.forEach(t=>{x.beginPath();x.arc(t.x,t.y,t.r,0,6.28);x.fill();t.y+=t.v;if(t.y>c.height)t.y=0});requestAnimationFrame(a);})();
        $(window).on('mousemove',e=>{ $('#cursor-glow').css({left:e.clientX,top:e.clientY}); });
    }
    
    function initSidebarScroll() {
        const main = document.getElementById('mainScroll');
        const links = document.querySelectorAll('.accordion-content .nav-item');
        main.addEventListener('scroll', () => {
            let current = '';
            document.querySelectorAll('.lesson-section').forEach(sec => { if (main.scrollTop >= sec.offsetTop - 250) current = '#' + sec.id; });
            links.forEach(link => { link.classList.remove('active'); if(link.getAttribute('data-target') === current) link.classList.add('active'); });
        });
        links.forEach(link => {
            link.addEventListener('click', () => {
                const target = document.querySelector(link.getAttribute('data-target'));
                if(target) main.scrollTo({ top: target.offsetTop - 120, behavior: 'smooth' });
            });
        });
    }
    
    function toggleAccordion(id) {
        const el = document.getElementById(id);
        const group = el.closest('.accordion-group');
        const arrow = document.getElementById(id.replace('content', 'arrow'));
        if(el.style.maxHeight){ el.style.maxHeight=null; group.classList.remove('open'); if(arrow) arrow.style.transform='rotate(0deg)'; }
        else{ el.style.maxHeight=el.scrollHeight+"px"; group.classList.add('open'); if(arrow) arrow.style.transform='rotate(180deg)'; }
    }
</script>
@endsection