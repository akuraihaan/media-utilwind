@extends('layouts.landing')
@section('title','Bab 2.1 · Layouting dengan Flexbox')

@section('content')
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-purple-500/30">

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
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500/20 to-transparent border border-purple-500/20 flex items-center justify-center font-bold text-xs text-purple-400">2.1</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Layouting dengan Flexbox</h1>
                        <p class="text-[10px] text-white/50">Estimasi: 35 Menit</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-24 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-purple-500 to-fuchsia-500 w-0 transition-all duration-500 shadow-[0_0_10px_#d946ef]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-purple-400 font-bold text-xs">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                <article class="space-y-32">
                    
                    <section id="2.1.1" class="lesson-section scroll-mt-32" data-lesson-id="29">
                        <div class="space-y-8">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-300 text-[10px] font-bold uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-pulse"></span>
                                Fondasi Layout
                            </div>
                            
                            <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                Menguasai Sumbu <br> 
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-fuchsia-400">Flexbox</span>
                            </h2>
                            
                            <p class="text-lg text-white/70 leading-relaxed max-w-3xl">
                                Flexbox bekerja berdasarkan dua sumbu: <strong>Main Axis</strong> (Sumbu Utama) dan <strong>Cross Axis</strong> (Sumbu Silang). Arah sumbu ini ditentukan oleh properti <code>flex-direction</code>.
                            </p>

                            <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-8 shadow-2xl relative overflow-hidden group hover:border-purple-500/30 transition-all">
                                <div class="flex flex-col md:flex-row justify-between items-end mb-6 gap-6">
                                    <div>
                                        <h3 class="text-lg font-bold text-white mb-1">🎛️ Flex Playground</h3>
                                        <p class="text-xs text-white/50">Atur arah (direction) dan distribusi (justify).</p>
                                    </div>
                                    <div class="flex flex-wrap gap-4">
                                        <div class="bg-white/5 p-1 rounded-lg flex gap-1">
                                            <button onclick="setPlaygroundState('dir', 'flex-row')" id="btn-row" class="dir-btn px-4 py-1.5 text-xs rounded bg-purple-600 text-white shadow-[0_0_10px_#9333ea] transition">Row</button>
                                            <button onclick="setPlaygroundState('dir', 'flex-col')" id="btn-col" class="dir-btn px-4 py-1.5 text-xs rounded bg-transparent text-white/50 hover:bg-white/10 transition">Col</button>
                                        </div>
                                        <div class="bg-white/5 p-1 rounded-lg flex gap-1">
                                            <button onclick="setPlaygroundState('just', 'justify-start')" id="btn-start" class="jus-btn px-4 py-1.5 text-xs rounded bg-purple-600 text-white shadow-[0_0_10px_#9333ea] transition">Start</button>
                                            <button onclick="setPlaygroundState('just', 'justify-center')" id="btn-center" class="jus-btn px-4 py-1.5 text-xs rounded bg-transparent text-white/50 hover:bg-white/10 transition">Center</button>
                                            <button onclick="setPlaygroundState('just', 'justify-between')" id="btn-between" class="jus-btn px-4 py-1.5 text-xs rounded bg-transparent text-white/50 hover:bg-white/10 transition">Between</button>
                                        </div>
                                    </div>
                                </div>

                                <div id="flex-container" class="w-full h-64 bg-black/40 rounded-xl border-2 border-dashed border-white/10 flex flex-row justify-start p-6 gap-4 transition-all duration-500 relative">
                                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 pointer-events-none"></div>
                                    <div class="w-16 h-16 bg-gradient-to-br from-fuchsia-500 to-purple-600 rounded-xl flex items-center justify-center font-bold text-white shadow-lg border border-white/10 transform transition-all hover:scale-110 z-10">1</div>
                                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center font-bold text-white shadow-lg border border-white/10 transform transition-all hover:scale-110 z-10">2</div>
                                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center font-bold text-white shadow-lg border border-white/10 transform transition-all hover:scale-110 z-10">3</div>
                                </div>
                                <div class="mt-4 text-center">
                                    <code id="flex-code" class="bg-black/50 px-4 py-2 rounded text-fuchsia-300 text-xs font-mono border border-white/10">class="flex flex-row justify-start"</code>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="2.1.2" class="lesson-section scroll-mt-32" data-lesson-id="30">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">2. Flex Wrap (Pembungkusan)</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <p class="text-white/70 mb-6">
                            Secara default, Flexbox akan memaksa elemen berada dalam satu baris (menyebabkan penyusutan/overflow). Gunakan <code>flex-wrap</code> untuk mengizinkan elemen "turun" ke baris baru ketika ruang habis.
                        </p>

                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <div class="p-4 bg-[#1e1e1e] rounded-xl border border-white/10 flex justify-between items-center group hover:border-purple-500/30 transition">
                                    <code class="text-purple-400 font-bold">flex-nowrap</code>
                                    <span class="text-[10px] bg-white/10 px-2 py-1 rounded text-white/50">Default (Paksa Satu Baris)</span>
                                </div>
                                <div class="p-4 bg-[#1e1e1e] rounded-xl border border-white/10 flex justify-between items-center group hover:border-emerald-500/30 transition">
                                    <code class="text-emerald-400 font-bold">flex-wrap</code>
                                    <span class="text-[10px] text-white/50">Bungkus ke Bawah</span>
                                </div>
                                <div class="p-4 bg-[#1e1e1e] rounded-xl border border-white/10 flex justify-between items-center group hover:border-blue-500/30 transition">
                                    <code class="text-blue-400 font-bold">flex-wrap-reverse</code>
                                    <span class="text-[10px] text-white/50">Bungkus ke Atas</span>
                                </div>
                            </div>

                            <div class="bg-[#0b0f19] p-6 rounded-2xl border border-white/10 flex flex-col relative overflow-hidden">
                                <div class="flex justify-between mb-4 z-10 relative">
                                    <span class="text-sm font-bold text-white">Simulator Wrap</span>
                                    <button onclick="toggleWrap()" id="wrap-btn" class="text-xs px-3 py-1.5 rounded bg-white/10 hover:bg-white/20 text-white transition border border-white/5">Mode: No Wrap</button>
                                </div>
                                <div id="wrap-container" class="flex flex-nowrap gap-2 overflow-hidden border border-white/20 p-2 h-40 bg-black/20 rounded-lg relative transition-all content-start">
                                    <div class="w-1/3 h-12 bg-purple-500/20 rounded border border-purple-500/40 flex-shrink-0 flex items-center justify-center text-xs text-purple-300">Item 1</div>
                                    <div class="w-1/3 h-12 bg-purple-500/20 rounded border border-purple-500/40 flex-shrink-0 flex items-center justify-center text-xs text-purple-300">Item 2</div>
                                    <div class="w-1/3 h-12 bg-purple-500/20 rounded border border-purple-500/40 flex-shrink-0 flex items-center justify-center text-xs text-purple-300">Item 3</div>
                                    <div class="w-1/3 h-12 bg-purple-500/20 rounded border border-purple-500/40 flex-shrink-0 flex items-center justify-center text-xs text-purple-300">Item 4</div>
                                    <div class="w-1/3 h-12 bg-purple-500/20 rounded border border-purple-500/40 flex-shrink-0 flex items-center justify-center text-xs text-purple-300">Item 5</div>
                                </div>
                                <p class="text-[10px] text-white/30 mt-2 text-center" id="wrap-desc">Items dipaksa masuk satu baris (overflow).</p>
                            </div>
                        </div>
                    </section>

                    <section id="2.1.4" class="lesson-section scroll-mt-32" data-lesson-id="31">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">3. Kendali Ukuran (Sizing)</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <p class="text-white/70 mb-6">
                            Kontrol seberapa banyak ruang yang diambil item flex menggunakan <code>grow</code> (tumbuh) dan <code>shrink</code> (menyusut).
                        </p>

                        <div class="bg-[#0b0f19] p-8 rounded-2xl border border-white/10">
                            <h3 class="text-sm font-bold text-white mb-4">Studi Kasus: Sidebar + Content</h3>
                            
                            <div class="mb-6">
                                <p class="text-xs text-white/50 mb-2"><code>flex-1</code>: Mengambil semua ruang sisa.</p>
                                <div class="flex gap-2 w-full bg-white/5 p-2 rounded-lg">
                                    <div class="w-16 h-10 bg-purple-600 rounded flex items-center justify-center text-[10px] flex-none">Sidebar</div>
                                    <div class="h-10 bg-indigo-600 rounded flex items-center justify-center text-[10px] flex-1">Content (flex-1)</div>
                                </div>
                            </div>

                            <div class="mb-6">
                                <p class="text-xs text-white/50 mb-2"><code>flex-none</code>: Ukuran tetap, tidak berubah.</p>
                                <div class="flex gap-2 w-full bg-white/5 p-2 rounded-lg">
                                    <div class="w-16 h-10 bg-purple-600 rounded flex items-center justify-center text-[10px] flex-none">Fixed</div>
                                    <div class="h-10 bg-indigo-600/50 rounded flex items-center justify-center text-[10px] flex-1">Sisa Ruang</div>
                                    <div class="w-16 h-10 bg-purple-600 rounded flex items-center justify-center text-[10px] flex-none">Fixed</div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="2.1.7" class="lesson-section scroll-mt-32" data-lesson-id="32">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">4. Pengurutan (Order)</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <p class="text-white/70 mb-6">
                            Anda dapat mengubah urutan tampilan elemen tanpa mengubah struktur HTML menggunakan utilitas <code>order-{n}</code>. Ini sangat berguna untuk desain responsif (misal: gambar di atas teks pada mobile, tapi di samping pada desktop).
                        </p>

                        <div class="bg-[#0b0f19] p-8 rounded-2xl border border-white/10 text-center">
                            <h3 class="text-lg font-bold text-white mb-6">Visualisasi Property Order</h3>
                            
                            <div class="flex justify-center gap-4 mb-8 p-4 bg-white/5 rounded-xl" id="order-container">
                                <div id="ord-1" class="w-20 h-20 bg-blue-600 rounded-xl flex items-center justify-center font-bold text-2xl shadow-lg transition-all duration-500 order-1 relative group">
                                    <span>1</span>
                                    <span class="absolute -bottom-6 text-[10px] text-white/50 opacity-0 group-hover:opacity-100 transition">HTML: 1</span>
                                </div>
                                <div id="ord-2" class="w-20 h-20 bg-purple-600 rounded-xl flex items-center justify-center font-bold text-2xl shadow-lg transition-all duration-500 order-2 relative group">
                                    <span>2</span>
                                    <span class="absolute -bottom-6 text-[10px] text-white/50 opacity-0 group-hover:opacity-100 transition">HTML: 2</span>
                                </div>
                                <div id="ord-3" class="w-20 h-20 bg-fuchsia-600 rounded-xl flex items-center justify-center font-bold text-2xl shadow-lg transition-all duration-500 order-3 relative group">
                                    <span>3</span>
                                    <span class="absolute -bottom-6 text-[10px] text-white/50 opacity-0 group-hover:opacity-100 transition">HTML: 3</span>
                                </div>
                            </div>
                            
                            <div class="flex justify-center gap-3">
                                <button onclick="swapOrder(1)" class="px-4 py-2 rounded bg-white/5 hover:bg-white/10 border border-white/10 text-xs transition">1 ke Akhir</button>
                                <button onclick="swapOrder(2)" class="px-4 py-2 rounded bg-white/5 hover:bg-white/10 border border-white/10 text-xs transition">2 ke Awal</button>
                                <button onclick="resetOrder()" class="px-4 py-2 rounded border border-white/20 hover:bg-white/10 text-xs text-white/60 transition">Reset</button>
                            </div>
                        </div>
                    </section>

                    <section id="quiz-2-1" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="33" data-manual="true">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl group hover:border-purple-500/30 transition-all duration-500">
                            
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-2/3 h-1 bg-gradient-to-r from-transparent via-purple-500 to-transparent opacity-50 blur-sm"></div>

                            <div class="text-center mb-8">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-300 text-[10px] font-bold uppercase mb-4 tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-pulse"></span>
                                    Final Challenge
                                </div>
                                <h2 class="text-3xl font-black text-white mb-2">Fix The Navbar</h2>
                                <p class="text-white/60 text-sm max-w-lg mx-auto">
                                    Navbar di bawah ini rusak. Tugas Anda adalah memilih kombinasi kelas yang tepat agar tampilannya sesuai dengan <strong>Target</strong> di sebelah kanan.
                                </p>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-6 max-w-6xl mx-auto">
                                
                                <div class="bg-[#1e1e1e] rounded-xl border border-white/10 flex flex-col overflow-hidden h-full">
                                    <div class="bg-[#2d2d2d] px-4 py-3 border-b border-white/5 flex justify-between items-center">
                                        <span class="text-xs text-gray-400 font-mono">CSS Controls</span>
                                        <span class="text-[10px] text-purple-400">● Live Editing</span>
                                    </div>
                                    
                                    <div class="p-6 space-y-6 flex-1 overflow-y-auto custom-scrollbar">
                                        
                                        <div>
                                            <p class="text-[10px] uppercase text-white/30 font-bold tracking-widest mb-2">1. Display & Direction</p>
                                            <div class="grid grid-cols-2 gap-2">
                                                <button onclick="selectOption('layout', 'block', this)" class="opt-btn-layout px-3 py-2 rounded bg-purple-600 text-white border-purple-500 border text-xs text-left font-mono transition">block</button>
                                                <button onclick="selectOption('layout', 'flex flex-col', this)" class="opt-btn-layout px-3 py-2 rounded bg-white/5 text-gray-400 border-white/5 border text-xs text-left font-mono transition hover:bg-white/10">flex flex-col</button>
                                                <button onclick="selectOption('layout', 'flex flex-row', this)" class="opt-btn-layout px-3 py-2 rounded bg-white/5 text-gray-400 border-white/5 border text-xs text-left font-mono transition hover:bg-white/10">flex flex-row</button>
                                                <button onclick="selectOption('layout', 'grid grid-cols-3', this)" class="opt-btn-layout px-3 py-2 rounded bg-white/5 text-gray-400 border-white/5 border text-xs text-left font-mono transition hover:bg-white/10">grid grid-cols-3</button>
                                            </div>
                                        </div>

                                        <div>
                                            <p class="text-[10px] uppercase text-white/30 font-bold tracking-widest mb-2">2. Justify Content</p>
                                            <div class="grid grid-cols-2 gap-2">
                                                <button onclick="selectOption('justify', '', this)" class="opt-btn-justify px-3 py-2 rounded bg-purple-600 text-white border-purple-500 border text-xs text-left font-mono transition">none</button>
                                                <button onclick="selectOption('justify', 'justify-center', this)" class="opt-btn-justify px-3 py-2 rounded bg-white/5 text-gray-400 border-white/5 border text-xs text-left font-mono transition hover:bg-white/10">justify-center</button>
                                                <button onclick="selectOption('justify', 'justify-between', this)" class="opt-btn-justify px-3 py-2 rounded bg-white/5 text-gray-400 border-white/5 border text-xs text-left font-mono transition hover:bg-white/10">justify-between</button>
                                                <button onclick="selectOption('justify', 'justify-end', this)" class="opt-btn-justify px-3 py-2 rounded bg-white/5 text-gray-400 border-white/5 border text-xs text-left font-mono transition hover:bg-white/10">justify-end</button>
                                            </div>
                                        </div>

                                        <div>
                                            <p class="text-[10px] uppercase text-white/30 font-bold tracking-widest mb-2">3. Align Items</p>
                                            <div class="grid grid-cols-2 gap-2">
                                                <button onclick="selectOption('items', '', this)" class="opt-btn-items px-3 py-2 rounded bg-purple-600 text-white border-purple-500 border text-xs text-left font-mono transition">none</button>
                                                <button onclick="selectOption('items', 'items-center', this)" class="opt-btn-items px-3 py-2 rounded bg-white/5 text-gray-400 border-white/5 border text-xs text-left font-mono transition hover:bg-white/10">items-center</button>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="p-4 bg-black/40 border-t border-white/5 flex justify-between items-center">
                                        <span class="text-[10px] text-white/30" id="status-text">Konfigurasi belum benar...</span>
                                        <button onclick="checkSolution()" id="submitBtn" class="px-6 py-2 rounded-lg bg-white/10 text-white/50 font-bold text-xs shadow-lg transition-all hover:bg-white/20 cursor-pointer">
                                            Jalankan & Periksa
                                        </button>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-6">
                                    
                                    <div class="bg-[#1e1e1e] rounded-xl border border-white/10 p-4 opacity-70">
                                        <p class="text-[10px] uppercase text-green-400 font-bold tracking-widest mb-2">Target (Goals)</p>
                                        <div class="w-full h-16 bg-white rounded-lg flex flex-row justify-between items-center px-4 shadow-sm select-none pointer-events-none opacity-80">
                                            <div class="font-bold text-gray-800 text-sm">Brand</div>
                                            <div class="flex gap-2">
                                                <div class="w-8 h-2 bg-gray-300 rounded"></div>
                                                <div class="w-8 h-2 bg-gray-300 rounded"></div>
                                            </div>
                                            <div class="px-3 py-1.5 bg-blue-600 text-white text-[10px] font-bold rounded">Login</div>
                                        </div>
                                    </div>

                                    <div class="bg-[#1e1e1e] rounded-xl border border-white/10 flex-1 flex flex-col relative overflow-hidden min-h-[300px]">
                                        <div class="bg-[#2d2d2d] px-4 py-2 border-b border-white/5 flex items-center gap-2">
                                            <div class="flex gap-1.5">
                                                <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                                                <div class="w-2.5 h-2.5 rounded-full bg-yellow-500"></div>
                                                <div class="w-2.5 h-2.5 rounded-full bg-green-500"></div>
                                            </div>
                                            <span class="text-[10px] text-gray-500 font-mono ml-2">Browser Preview</span>
                                        </div>

                                        <div class="flex-1 bg-gray-900 p-6 relative">
                                            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
                                            
                                            <nav id="preview-navbar" class="w-full bg-white rounded-lg p-4 shadow-xl min-h-[80px] block transition-all duration-300">
                                                <div class="bg-gray-200 p-2 mb-1 text-gray-800 font-bold text-sm inline-block rounded">Brand</div>
                                                
                                                <div class="bg-gray-100 p-2 mb-1 text-gray-500 text-xs inline-block rounded">
                                                    <span class="bg-gray-300 w-8 h-2 inline-block rounded mr-1"></span>
                                                    <span class="bg-gray-300 w-8 h-2 inline-block rounded"></span>
                                                </div>
                                                
                                                <div class="bg-blue-100 p-2 text-blue-600 font-bold text-xs inline-block rounded border border-blue-200">
                                                    Login Button
                                                </div>
                                            </nav>

                                        </div>

                                        <div class="bg-black p-3 font-mono text-[10px] text-gray-500 h-32 border-t border-white/10 overflow-y-auto" id="console-output">
                                            <p>> System ready.</p>
                                            <p>> Waiting for configuration...</p>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </section>

                </article>

                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center">
                    <a href="{{ route('courses.installation') }}" class="group flex items-center gap-3 text-slate-400 hover:text-white transition">
                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-white/30 transition">←</div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest opacity-50">Sebelumnya</div>
                            <div class="font-bold text-sm">Instalasi</div>
                        </div>
                    </a>

                    <div id="nextChapterBtn" class="group flex items-center gap-3 text-right text-slate-500 cursor-not-allowed">
                        <div class="text-right">
                            <div class="text-[10px] uppercase tracking-widest opacity-30">Terkunci</div>
                            <div class="font-bold text-sm">Grid Layout (Segera)</div>
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
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    /* --- CONFIGURATION --- */
    window.SUBBAB_LESSON_IDS = {!! json_encode($subbab21LessonIds ?? []) !!}; 
    window.INIT_COMPLETED_LESSONS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedLessons = new Set(window.INIT_COMPLETED_LESSONS);
    
    // Manual boolean parsing
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};

    const QUIZ_LESSON_ID = 33; // Activity 2.1 ID

    document.addEventListener('DOMContentLoaded', () => {
        updateProgressUI();
        initLessonObserver();
        initVisualEffects();
        initSidebarScroll();
        if (activityCompleted) {
            // Restore Completed State
            const btn = document.getElementById('submitBtn');
            btn.innerText = "Selesai ✔";
            btn.classList.remove('bg-white/10', 'text-white/50');
            btn.classList.add('bg-green-600', 'text-white', 'cursor-not-allowed');
            document.getElementById('status-text').innerText = "Tantangan Selesai!";
            document.getElementById('status-text').className = "text-[10px] text-green-400 font-bold";
            
            // Set Navbar to Correct State
            const nav = document.getElementById('preview-navbar');
            nav.className = "w-full bg-white rounded-lg p-4 shadow-xl min-h-[80px] transition-all duration-300 flex flex-row justify-between items-center";
            // nav.querySelectorAll('div').forEach(d => { d.classList.remove('mb-1'); });
        }
    });

    /* --- 1. FLEX PLAYGROUND LOGIC --- */
    let currentDir = 'flex-row';
    let currentJust = 'justify-start';

    function setPlaygroundState(type, value) {
        if (type === 'dir') currentDir = value;
        if (type === 'just') currentJust = value;

        // Update Container Class
        const container = document.getElementById('flex-container');
        container.className = `w-full h-64 bg-black/40 rounded-xl border-2 border-dashed border-white/10 p-6 gap-4 transition-all duration-500 relative flex ${currentDir} ${currentJust}`;
        document.getElementById('flex-code').innerText = `class="flex ${currentDir} ${currentJust}"`;

        // Update Buttons (Active State)
        updateButtonHighlights();
    }

    function updateButtonHighlights() {
        const setActive = (id, isActive) => {
            const el = document.getElementById(id);
            if (isActive) {
                el.className = "px-4 py-1.5 text-xs rounded transition-all duration-300 bg-purple-600 text-white shadow-[0_0_10px_#9333ea]";
            } else {
                el.className = "px-4 py-1.5 text-xs rounded transition-all duration-300 bg-transparent text-white/50 hover:bg-white/10";
            }
        };
        // Use separate functions or logic for each group if IDs are unique
        setActive('btn-row', currentDir === 'flex-row');
        setActive('btn-col', currentDir === 'flex-col');
        setActive('btn-start', currentJust === 'justify-start');
        setActive('btn-center', currentJust === 'justify-center');
        setActive('btn-between', currentJust === 'justify-between');
    }

    /* --- 2. WRAP SIMULATOR --- */
    let isWrap = false;
    function toggleWrap() {
        isWrap = !isWrap;
        const box = document.getElementById('wrap-container');
        const btn = document.getElementById('wrap-btn');
        const desc = document.getElementById('wrap-desc');

        if(isWrap) {
            box.classList.remove('flex-nowrap'); box.classList.add('flex-wrap');
            btn.innerText = "Mode: Wrap (ON)";
            btn.classList.add('bg-purple-600');
        } else {
            box.classList.remove('flex-wrap'); box.classList.add('flex-nowrap');
            btn.innerText = "Mode: No Wrap";
            btn.classList.remove('bg-purple-600');
        }
    }

    /* --- 3. ORDER SIMULATOR --- */
    function swapOrder(item) {
        const el = document.getElementById('ord-'+item);
        if(item===1) el.classList.toggle('order-last');
        if(item===2) el.classList.toggle('order-first');
    }
    function resetOrder() {
        [1,2,3].forEach(i => {
            document.getElementById('ord-'+i).classList.remove('order-first', 'order-last');
        });
    }

    /* --- 4. ACTIVITY LOGIC (LIVE PREVIEW) --- */
    let currentConfig = {
        layout: 'block',    // Default: Salah
        justify: '',        // Default: Kosong
        items: ''           // Default: Kosong
    };

    function selectOption(category, value, btn) {
        if(activityCompleted) return;

        // 1. Update State
        currentConfig[category] = value;

        // 2. Update Visual Tombol
        const buttons = document.querySelectorAll(`.opt-btn-${category}`);
        buttons.forEach(b => {
            b.className = `opt-btn-${category} px-3 py-2 rounded bg-white/5 text-gray-400 border-white/5 border text-xs text-left font-mono transition hover:bg-white/10`;
        });
        btn.className = `opt-btn-${category} px-3 py-2 rounded bg-purple-600 text-white border-purple-500 border text-xs text-left font-mono transition shadow-[0_0_10px_#9333ea]`;

        // 3. Update Preview
        updatePreviewNavbar();
    }

    function updatePreviewNavbar() {
        const navbar = document.getElementById('preview-navbar');
        const consoleLog = document.getElementById('console-output');

        // Reset & Apply
        const baseClasses = "w-full bg-white rounded-lg p-4 shadow-xl min-h-[80px] transition-all duration-300";
        navbar.className = `${baseClasses} ${currentConfig.layout} ${currentConfig.justify} ${currentConfig.items}`;

        // Console Feedback
        const time = new Date().toLocaleTimeString('en-US', { hour12: false });
        consoleLog.innerHTML += `<p>> [${time}] Applied: <span class="text-purple-400">${currentConfig.layout} ${currentConfig.justify} ${currentConfig.items}</span></p>`;
        consoleLog.scrollTop = consoleLog.scrollHeight;
        
        // Activate Submit Button
        const btn = document.getElementById('submitBtn');
        btn.classList.remove('bg-white/10', 'text-white/50');
        btn.classList.add('bg-purple-600', 'text-white', 'hover:bg-purple-500');
    }

    async function checkSolution() {
        if(activityCompleted) return;

        const btn = document.getElementById('submitBtn');
        const consoleLog = document.getElementById('console-output');
        const statusText = document.getElementById('status-text');

        btn.innerText = "Memeriksa...";
        btn.disabled = true;

        // Validasi Jawaban
        const isLayoutCorrect = currentConfig.layout === 'flex flex-row';
        const isJustifyCorrect = currentConfig.justify === 'justify-between';
        const isItemsCorrect = currentConfig.items === 'items-center';

        await new Promise(r => setTimeout(r, 800)); // Simulasi

        if (isLayoutCorrect && isJustifyCorrect && isItemsCorrect) {
            // BENAR
            consoleLog.innerHTML += `<p class="text-green-400 font-bold">> SYSTEM: SUCCESS! Layout Matches Target.</p>`;
            statusText.innerText = "Sempurna!";
            statusText.className = "text-[10px] text-green-400 font-bold";
            
            // Simpan ke Database
            await saveActivityData();
            
        } else {
            // SALAH
            consoleLog.innerHTML += `<p class="text-red-400 font-bold">> SYSTEM: ERROR. Layout Mismatch.</p>`;
            
            if(!isLayoutCorrect) consoleLog.innerHTML += `<p class="text-white/50 ml-2">- Hint: Layout harus horizontal (row).</p>`;
            else if(!isJustifyCorrect) consoleLog.innerHTML += `<p class="text-white/50 ml-2">- Hint: Jarak elemen belum pas (between).</p>`;
            else if(!isItemsCorrect) consoleLog.innerHTML += `<p class="text-white/50 ml-2">- Hint: Posisi vertikal belum tengah.</p>`;

            consoleLog.scrollTop = consoleLog.scrollHeight;
            btn.innerText = "Coba Lagi";
            btn.disabled = false;
            
            btn.classList.add('shake');
            setTimeout(() => btn.classList.remove('shake'), 500);
        }
    }

    async function saveActivityData() {
        const btn = document.getElementById('submitBtn');
        try {
            await fetch('/activity/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify({ activity_id: 7, score: 100 }) });
            await saveLessonToDB(QUIZ_LESSON_ID);
            completedLessons.add(QUIZ_LESSON_ID);
            activityCompleted = true;
            updateProgressUI();
            btn.innerHTML = "Tersimpan ✔";
            btn.classList.remove('bg-purple-600', 'hover:bg-purple-500');
            btn.classList.add('bg-green-600', 'cursor-default');
            unlockNext();
        } catch(e) { 
            console.error(e); 
            btn.innerText = "Gagal Simpan"; 
            btn.disabled = false; 
        }
    }

    /* --- CORE SYSTEM --- */
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
        await fetch('/lesson/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: form });
    }

    function unlockNext() {
        const icon = document.querySelector('.sb-group.open .icon-status');
        if(icon) { icon.innerHTML = '✔'; icon.className = 'icon-status w-6 h-6 rounded-lg border flex items-center justify-center transition-colors bg-purple-500/20 text-purple-400 border-purple-500/20'; }
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.className = "group flex items-center gap-3 text-right text-purple-400 hover:text-purple-300 transition cursor-pointer";
            btn.innerHTML = `<div class="text-right"><div class="text-[10px] uppercase tracking-widest opacity-50">Selanjutnya</div><div class="font-bold text-sm">Grid Layout</div></div><div class="w-10 h-10 rounded-full border border-purple-500/30 bg-purple-500/10 flex items-center justify-center">→</div>`;
            btn.onclick = () => window.location.href = "{{ route('courses.grid') }}"; 
        }
    }

    function initVisualEffects() { 
        const c=document.getElementById('stars'),x=c.getContext('2d');
        let s=[]; function r(){c.width=innerWidth;c.height=innerHeight}
        r();window.onresize=r;
        for(let i=0;i<100;i++)s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.2,v:Math.random()*0.2+.1});
        (function a(){x.clearRect(0,0,c.width,c.height);x.fillStyle='rgba(255,255,255,.4)';s.forEach(t=>{x.beginPath();x.arc(t.x,t.y,t.r,0,6.28);x.fill();t.y+=t.v;if(t.y>c.height)t.y=0});requestAnimationFrame(a);})();
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