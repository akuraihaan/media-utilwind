@extends('layouts.landing')
@section('title','Bab 2.2 · Layouting dengan Grid')

@section('content')
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-cyan-500/30">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-60"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[600px] h-[600px] bg-cyan-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="cursor-glow"></div>
    </div>

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
            
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-[#020617]/80 backdrop-blur-2xl border-b border-white/5 px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500/20 to-transparent border border-purple-500/20 flex items-center justify-center font-bold text-xs text-purple-400">2.2</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Layouting dengan Grid</h1>
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
                    
                    <section id="section-34" class="lesson-section scroll-mt-32" data-lesson-id="34">
                        <div class="space-y-8">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-[10px] font-bold uppercase tracking-widest">
                                 Dasar Layouting Grid
                            </div>
                            <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">Konsep Grid & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Membuat Kolom</span></h2>
                            
                            <div class="prose prose-invert max-w-none text-white/70">
                                <p>CSS Grid Layout adalah sistem tata letak dua dimensi yang paling kuat di web. Jika Flexbox bekerja dalam satu dimensi (baris <i>atau</i> kolom), Grid menangani keduanya secara bersamaan.</p>
                                <p>Di Tailwind, Anda memulai dengan class <code class="text-cyan-400">grid</code> pada elemen pembungkus (container). Kemudian, Anda mendefinisikan berapa banyak kolom yang diinginkan menggunakan <code class="text-cyan-400">grid-cols-{n}</code>.</p>
                            </div>

                            <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-8 shadow-2xl relative group hover:border-cyan-500/30 transition-all">
                                <div class="flex flex-col md:flex-row justify-between items-end mb-6 gap-6">
                                    <div>
                                        <h3 class="text-lg font-bold text-white mb-1">🏗️ Grid Column Simulator</h3>
                                        <p class="text-xs text-white/50">Klik tombol untuk mengubah struktur kolom.</p>
                                    </div>
                                    <div class="flex gap-2 bg-white/5 p-1 rounded-lg">
                                        <button onclick="updateGridCols(1, this)" class="col-btn px-4 py-2 text-xs rounded bg-white/5 text-white/50 hover:bg-white/10 transition">grid-cols-1</button>
                                        <button onclick="updateGridCols(2, this)" class="col-btn px-4 py-2 text-xs rounded bg-cyan-600 text-white shadow-lg transition active-btn">grid-cols-2</button>
                                        <button onclick="updateGridCols(3, this)" class="col-btn px-4 py-2 text-xs rounded bg-white/5 text-white/50 hover:bg-white/10 transition">grid-cols-3</button>
                                        <button onclick="updateGridCols(4, this)" class="col-btn px-4 py-2 text-xs rounded bg-white/5 text-white/50 hover:bg-white/10 transition">grid-cols-4</button>
                                    </div>
                                </div>
                                <div id="demo-grid-cols" class="grid grid-cols-2 gap-4 p-6 bg-black/40 rounded-xl border-2 border-dashed border-white/10 transition-all duration-500">
                                    <div class="h-20 bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center font-bold rounded-lg text-xl">1</div>
                                    <div class="h-20 bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center font-bold rounded-lg text-xl">2</div>
                                    <div class="h-20 bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center font-bold rounded-lg text-xl">3</div>
                                    <div class="h-20 bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center font-bold rounded-lg text-xl">4</div>
                                </div>
                                <div class="mt-4 text-center"><code id="code-grid-cols" class="bg-black/50 px-4 py-2 rounded text-cyan-300 text-xs font-mono border border-white/10">class="grid grid-cols-2 gap-4"</code></div>
                            </div>
                        </div>
                    </section>

                    <section id="section-35" class="lesson-section scroll-mt-32" data-lesson-id="35">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">2. Penjajaran (Alignment)</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>
                        <div class="prose prose-invert max-w-none text-white/70 mb-8">
                            <p>Sama seperti Flexbox, Grid memiliki utilitas untuk mengatur posisi konten di dalam selnya.</p>
                            <ul class="list-disc pl-5">
                                <li><code class="text-cyan-400">justify-items-*</code>: Mengatur posisi secara horizontal (sumbu baris).</li>
                                <li><code class="text-fuchsia-400">items-*</code>: Mengatur posisi secara vertikal (sumbu kolom).</li>
                            </ul>
                        </div>
                        
                        <div class="bg-[#0b0f19] p-8 rounded-2xl border border-white/10 shadow-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <h4 class="text-sm font-bold text-cyan-400">Justify (X Axis)</h4>
                                        <div class="flex gap-1 bg-white/5 p-1 rounded">
                                            <button onclick="updateJustify('justify-items-start')" class="p-1.5 hover:bg-white/10 rounded transition" title="Start"><div class="w-4 h-4 bg-cyan-500/50 rounded-sm"></div></button>
                                            <button onclick="updateJustify('justify-items-center')" class="p-1.5 hover:bg-white/10 rounded transition" title="Center"><div class="w-4 h-4 bg-cyan-500 rounded-sm mx-auto"></div></button>
                                            <button onclick="updateJustify('justify-items-end')" class="p-1.5 hover:bg-white/10 rounded transition" title="End"><div class="w-4 h-4 bg-cyan-500 rounded-sm ml-auto"></div></button>
                                        </div>
                                    </div>
                                    <div id="demo-justify" class="grid grid-cols-2 gap-2 justify-items-center h-32 bg-black/30 rounded-xl border border-dashed border-white/10 p-2">
                                        <div class="w-10 h-10 bg-cyan-600 rounded flex items-center justify-center text-xs">A</div>
                                        <div class="w-10 h-10 bg-cyan-600 rounded flex items-center justify-center text-xs">B</div>
                                        <div class="w-10 h-10 bg-cyan-600 rounded flex items-center justify-center text-xs">C</div>
                                        <div class="w-10 h-10 bg-cyan-600 rounded flex items-center justify-center text-xs">D</div>
                                    </div>
                                    <code class="text-[10px] text-cyan-400 block text-center" id="code-justify">justify-items-center</code>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <h4 class="text-sm font-bold text-fuchsia-400">Align (Y Axis)</h4>
                                        <div class="flex gap-1 bg-white/5 p-1 rounded">
                                            <button onclick="updateAlign('items-start')" class="p-1.5 hover:bg-white/10 rounded transition"><div class="w-4 h-2 bg-fuchsia-500/50 rounded-sm mb-auto"></div></button>
                                            <button onclick="updateAlign('items-center')" class="p-1.5 hover:bg-white/10 rounded transition"><div class="w-4 h-2 bg-fuchsia-500 rounded-sm my-auto"></div></button>
                                            <button onclick="updateAlign('items-end')" class="p-1.5 hover:bg-white/10 rounded transition"><div class="w-4 h-2 bg-fuchsia-500 rounded-sm mt-auto"></div></button>
                                        </div>
                                    </div>
                                    <div id="demo-align" class="grid grid-cols-2 gap-2 items-center h-32 bg-black/30 rounded-xl border border-dashed border-white/10 p-2">
                                        <div class="w-full h-8 bg-fuchsia-600 rounded flex items-center justify-center text-xs">A</div>
                                        <div class="w-full h-8 bg-fuchsia-600 rounded flex items-center justify-center text-xs">B</div>
                                    </div>
                                    <code class="text-[10px] text-fuchsia-400 block text-center" id="code-align">items-center</code>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-36" class="lesson-section scroll-mt-32" data-lesson-id="36">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">3. Span (Penggabungan)</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>
                        <div class="grid lg:grid-cols-2 gap-8">
                            <div class="space-y-4 text-white/70">
                                <p>Salah satu fitur terbaik Grid adalah kemampuannya membuat elemen melintasi beberapa kolom atau baris sekaligus, mirip dengan <code>colspan</code> dan <code>rowspan</code> pada tabel.</p>
                                <ul class="space-y-3 mt-4">
                                    <li class="flex items-center gap-3 bg-white/5 p-3 rounded-lg border border-white/5">
                                        <code class="text-orange-400 font-bold text-xs bg-black/30 px-2 py-1 rounded">col-span-{n}</code> 
                                        <span class="text-sm">Elemen melebar ke samping sebanyak n kolom.</span>
                                    </li>
                                    <li class="flex items-center gap-3 bg-white/5 p-3 rounded-lg border border-white/5">
                                        <code class="text-orange-400 font-bold text-xs bg-black/30 px-2 py-1 rounded">row-span-{n}</code> 
                                        <span class="text-sm">Elemen melebar ke bawah sebanyak n baris.</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-[#0b0f19] p-6 rounded-2xl border border-white/10 shadow-2xl relative group">
                                <div class="absolute -top-3 -right-3 bg-orange-600 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-lg">Bento Grid Demo</div>
                                <div class="grid grid-cols-3 grid-rows-3 gap-3 h-48">
                                    <div class="row-span-3 bg-indigo-600/20 border border-indigo-500/50 rounded-xl flex flex-col items-center justify-center text-indigo-400 font-bold p-2 text-center text-xs">Sidebar<span class="opacity-50 text-[9px] mt-1 font-mono">row-span-3</span></div>
                                    <div class="col-span-2 bg-pink-600/20 border border-pink-500/50 rounded-xl flex flex-col items-center justify-center text-pink-400 font-bold text-xs">Header <span class="opacity-50 text-[9px] mt-1 font-mono">col-span-2</span></div>
                                    <div class="bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-white/30 text-xs">1</div>
                                    <div class="bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-white/30 text-xs">2</div>
                                    <div class="col-span-2 bg-cyan-600/20 border border-cyan-500/50 rounded-xl flex items-center justify-center text-cyan-400 font-bold text-xs">Footer</div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-37" class="lesson-section scroll-mt-32" data-lesson-id="37">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">4. Grid Template Rows</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>
                        <div class="bg-[#1e1e1e] p-5 rounded-xl border border-white/10">
                            <p class="text-white/70 mb-4">Gunakan <code class="text-blue-400">grid-rows-{n}</code> untuk menentukan jumlah baris secara eksplisit. Ini sangat berguna ketika Anda ingin membuat grid yang mengalir secara vertikal (column flow) dengan jumlah baris yang tetap.</p>
                            <div class="flex justify-between mb-3"><span class="text-sm font-bold text-white">Preview</span><code class="text-[10px] text-blue-400 bg-white/5 px-2 py-1 rounded">grid-rows-3 grid-flow-col</code></div>
                            <div class="grid grid-rows-3 grid-flow-col gap-2 h-32">
                                <div class="bg-white/5 rounded border border-white/5 flex items-center justify-center text-xs">1</div>
                                <div class="bg-white/5 rounded border border-white/5 flex items-center justify-center text-xs">2</div>
                                <div class="bg-white/5 rounded border border-white/5 flex items-center justify-center text-xs">3</div>
                                <div class="bg-white/5 rounded border border-white/5 flex items-center justify-center text-xs">4</div>
                                <div class="bg-white/5 rounded border border-white/5 flex items-center justify-center text-xs">5</div>
                                <div class="bg-white/5 rounded border border-white/5 flex items-center justify-center text-xs">6</div>
                            </div>
                        </div>
                    </section>

                    <section id="section-38" class="lesson-section scroll-mt-32" data-lesson-id="38">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">5. Arbitrary Columns</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>
                        <div class="bg-[#1e1e1e] p-5 rounded-xl border border-white/10">
                            <p class="text-white/70 mb-4">Tailwind memungkinkan nilai kustom menggunakan kurung siku <code class="text-red-400">[]</code>. Ini berguna untuk layout presisi, misalnya sidebar 80px dan konten sisanya (1fr).</p>
                            <div class="flex justify-between mb-3"><span class="text-sm font-bold text-white">Preview</span><code class="text-[10px] text-red-400 bg-white/5 px-2 py-1 rounded">grid-cols-[80px_1fr_80px]</code></div>
                            <div class="grid grid-cols-[80px_1fr_80px] gap-2 h-32">
                                <div class="bg-red-500/10 border border-red-500/30 rounded flex flex-col items-center justify-center text-[10px] text-red-400">Fixed<br>80px</div>
                                <div class="bg-green-500/10 border border-green-500/30 rounded flex flex-col items-center justify-center text-[10px] text-green-400">Flexible<br>(1fr)</div>
                                <div class="bg-red-500/10 border border-red-500/30 rounded flex flex-col items-center justify-center text-[10px] text-red-400">Fixed<br>80px</div>
                            </div>
                        </div>
                    </section>

                    <section id="section-39" class="lesson-section scroll-mt-32" data-lesson-id="39">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">6. Grid Auto Flow</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="bg-[#1e1e1e] p-6 rounded-2xl border border-white/10 group hover:border-emerald-500/30 transition">
                                <div class="flex justify-between mb-4"><code class="text-emerald-400 font-bold bg-emerald-500/10 px-2 py-1 rounded">grid-flow-row</code><span class="text-[10px] text-white/50">Z-Pattern (Default)</span></div>
                                <div class="grid grid-rows-2 grid-flow-row gap-2 bg-black/20 p-4 rounded-xl h-40">
                                    <div class="bg-emerald-500/10 border border-emerald-500/30 rounded flex items-center justify-center text-emerald-400 font-bold">1</div>
                                    <div class="bg-emerald-500/10 border border-emerald-500/30 rounded flex items-center justify-center text-emerald-400 font-bold">2</div>
                                    <div class="bg-emerald-500/10 border border-emerald-500/30 rounded flex items-center justify-center text-emerald-400 font-bold">3</div>
                                    <div class="bg-emerald-500/10 border border-emerald-500/30 rounded flex items-center justify-center text-emerald-400 font-bold">4</div>
                                </div>
                            </div>
                            <div class="bg-[#1e1e1e] p-6 rounded-2xl border border-white/10 group hover:border-purple-500/30 transition">
                                <div class="flex justify-between mb-4"><code class="text-purple-400 font-bold bg-purple-500/10 px-2 py-1 rounded">grid-flow-col</code><span class="text-[10px] text-white/50">N-Pattern (Vertical)</span></div>
                                <div class="grid grid-rows-2 grid-flow-col gap-2 bg-black/20 p-4 rounded-xl h-40">
                                    <div class="bg-purple-500/10 border border-purple-500/30 rounded flex items-center justify-center text-purple-400 font-bold">1</div>
                                    <div class="bg-purple-500/10 border border-purple-500/30 rounded flex items-center justify-center text-purple-400 font-bold">2</div>
                                    <div class="bg-purple-500/10 border border-purple-500/30 rounded flex items-center justify-center text-purple-400 font-bold">3</div>
                                    <div class="bg-purple-500/10 border border-purple-500/30 rounded flex items-center justify-center text-purple-400 font-bold">4</div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-40" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="40" data-type="activity">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl group hover:border-cyan-500/30 transition-all duration-500">
                            
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-cyan-600/20 blur-[100px] rounded-full pointer-events-none"></div>

                            <div class="flex items-center gap-4 mb-6 relative z-10">
                                <div class="p-3 bg-blue-600 rounded-xl text-white shadow-lg shadow-blue-500/30">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-white">Aktivitas 2.2: Layout Challenge</h2>
                                    <p class="text-cyan-400 text-sm">Lengkapi kode untuk membuat layout galeri 2x2 yang berada di tengah.</p>
                                </div>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-6 max-w-6xl mx-auto">
                                <div class="bg-[#1e1e1e] rounded-xl border border-white/10 flex flex-col overflow-hidden h-full">
                                    <div class="bg-[#2d2d2d] px-4 py-3 border-b border-white/5 flex justify-between items-center">
                                        <span class="text-xs text-gray-400 font-mono">Code Editor</span>
                                        <span class="text-[10px] text-cyan-400">● Live Preview</span>
                                    </div>
                                    <div class="p-6 space-y-6 flex-1 overflow-y-auto custom-scrollbar">
                                        <form id="gridActivityForm">
                                            <div>
                                                <p class="text-[10px] uppercase text-white/30 font-bold tracking-widest mb-2">1. Display & Columns</p>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <button type="button" onclick="selectOption('layout', 'grid grid-cols-1', this)" class="opt-btn-layout px-3 py-2 rounded bg-white/5 text-gray-400 border-white/5 border text-xs text-left font-mono transition hover:bg-white/10">grid-cols-1</button>
                                                    <button type="button" onclick="selectOption('layout', 'grid grid-cols-2', this)" class="opt-btn-layout px-3 py-2 rounded bg-white/5 text-gray-400 border-white/5 border text-xs text-left font-mono transition hover:bg-white/10">grid-cols-2</button>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <p class="text-[10px] uppercase text-white/30 font-bold tracking-widest mb-2">2. Gap</p>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <button type="button" onclick="selectOption('gap', 'gap-2', this)" class="opt-btn-gap px-3 py-2 rounded bg-white/5 text-gray-400 border-white/5 border text-xs text-left font-mono transition hover:bg-white/10">gap-2</button>
                                                    <button type="button" onclick="selectOption('gap', 'gap-4', this)" class="opt-btn-gap px-3 py-2 rounded bg-white/5 text-gray-400 border-white/5 border text-xs text-left font-mono transition hover:bg-white/10">gap-4</button>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <p class="text-[10px] uppercase text-white/30 font-bold tracking-widest mb-2">3. Alignment</p>
                                                <div class="grid grid-cols-1 gap-2">
                                                    <button type="button" onclick="selectOption('align', 'justify-start items-start', this)" class="opt-btn-align px-3 py-2 rounded bg-white/5 text-gray-400 border-white/5 border text-xs text-left font-mono transition hover:bg-white/10">start</button>
                                                    <button type="button" onclick="selectOption('align', 'justify-center items-center', this)" class="opt-btn-align px-3 py-2 rounded bg-white/5 text-gray-400 border-white/5 border text-xs text-left font-mono transition hover:bg-white/10">center</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="p-4 bg-black/40 border-t border-white/5 flex justify-between items-center">
                                        <span class="text-[10px] text-white/30" id="status-text">Konfigurasi belum benar...</span>
                                        <button onclick="checkSolution()" id="submitBtn" class="px-6 py-2 rounded-lg bg-white/10 text-white/50 font-bold text-xs shadow-lg transition-all hover:bg-white/20 cursor-pointer">Jalankan</button>
                                    </div>
                                </div>

                                <div class="bg-[#1e1e1e] rounded-xl border border-white/10 flex-1 flex flex-col relative overflow-hidden min-h-[300px]">
                                    <div class="bg-[#2d2d2d] px-4 py-2 border-b border-white/5 flex items-center gap-2">
                                        <div class="flex gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-red-500"></div><div class="w-2.5 h-2.5 rounded-full bg-yellow-500"></div><div class="w-2.5 h-2.5 rounded-full bg-green-500"></div></div>
                                        <span class="text-[10px] text-gray-500 font-mono ml-2">Result Preview</span>
                                    </div>
                                    <div class="flex-1 bg-gray-900 p-6 relative flex items-center justify-center">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
                                        <div id="preview-container" class="w-full h-full bg-white/5 border-2 border-dashed border-white/10 rounded-lg p-4 transition-all duration-300">
                                            <div class="bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 flex items-center justify-center font-bold rounded h-16">A</div>
                                            <div class="bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 flex items-center justify-center font-bold rounded h-16">B</div>
                                            <div class="bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 flex items-center justify-center font-bold rounded h-16">C</div>
                                            <div class="bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 flex items-center justify-center font-bold rounded h-16">D</div>
                                        </div>
                                    </div>
                                    <div class="bg-black p-3 font-mono text-[10px] text-gray-500 h-24 border-t border-white/10 overflow-y-auto" id="console-output">
                                        <p>> System ready.</p>
                                        <p>> Waiting for configuration...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center">
                    <a href="{{ route('courses.flexbox') }}" class="group flex items-center gap-3 text-slate-400 hover:text-white transition">
                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-white/30 transition">←</div>
                        <div class="text-left"><div class="text-[10px] uppercase tracking-widest opacity-50">Sebelumnya</div><div class="font-bold text-sm">Flexbox</div></div>
                    </a>
                    <div id="nextChapterBtn" class="group flex items-center gap-3 text-right text-slate-500 cursor-not-allowed">
                        <div class="text-right"><div class="text-[10px] uppercase tracking-widest opacity-30">Terkunci</div><div class="font-bold text-sm">Layout Management</div></div>
                        <div class="w-10 h-10 rounded-full border border-white/5 flex items-center justify-center">🔒</div>
                    </div>
                </div>

                <div class="mt-16 text-center text-white/20 text-xs">&copy; {{ date('Y') }} Flowwind Learn. Materi dilindungi hak cipta.</div>
            </div>
        </main>
    </div>
</div>

<style>
    .nav-link.active { color: #22d3ee; position: relative; }
    nav-link.active { color: #22d3ee; position: relative; }
    .nav-link.active::after { content: ''; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: linear-gradient(to right,#22d3ee,#3b82f6); box-shadow: 0 0 12px rgba(34,211,238,.8); border-radius: 2px; }
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
    /* --- CONFIGURATION & TRACKING (ID 34-40) --- */
    window.LESSON_IDS = [34, 35, 36, 37, 38, 39, 40]; 
    window.COMPLETED_IDS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedSet = new Set(window.COMPLETED_IDS);
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    const ACTIVITY_LESSON_ID = 40; // ID 40 is the activity

    document.addEventListener('DOMContentLoaded', () => {
        initSidebarScroll();
        initLessonObserver();
        initVisualEffects();
        updateProgressUI();
        if (activityCompleted) markActivityCompleteUI();
    });

    /* --- 1. GRID BUILDER INTERACTIVE --- */
    function updateGridCols(n, btn) {
        const grid = document.getElementById('demo-grid-cols');
        const code = document.getElementById('code-grid-cols');
        grid.className = `grid grid-cols-${n} gap-4 p-6 bg-black/40 rounded-xl border-2 border-dashed border-white/10 transition-all duration-500 relative overflow-hidden`;
        code.innerText = `class="grid grid-cols-${n} gap-4"`;
        document.querySelectorAll('.col-btn').forEach(b => b.className = "col-btn px-4 py-2 text-xs rounded bg-white/5 text-white/50 hover:bg-white/10 transition");
        btn.className = "col-btn px-4 py-2 text-xs rounded bg-cyan-600 text-white shadow-lg transition active-btn";
        let html = '<div class="absolute inset-0 bg-[url(\'https://grainy-gradients.vercel.app/noise.svg\')] opacity-10 pointer-events-none"></div>';
        for(let i=1; i<=(n>2?6:4); i++) {
            html += `<div class="h-20 bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center font-bold rounded-lg text-xl fade-in transform hover:scale-105 transition">${i}</div>`;
        }
        grid.innerHTML = html;
    }

    /* --- 2. ALIGNMENT INTERACTIVE --- */
    function updateJustify(val) {
        const demo = document.getElementById('demo-justify');
        demo.classList.remove('justify-items-start', 'justify-items-center', 'justify-items-end');
        demo.classList.add(val);
    }
    function updateAlign(val) {
        const demo = document.getElementById('demo-align');
        demo.classList.remove('items-start', 'items-center', 'items-end');
        demo.classList.add(val);
    }

    /* --- 3. ACTIVITY LOGIC (REALTIME PREVIEW) --- */
    let currentConfig = { layout: '', gap: '', align: '' };

    function selectOption(category, value, btn) {
        if(activityCompleted) return;
        currentConfig[category] = value;
        document.querySelectorAll(`.opt-btn-${category}`).forEach(b => {
            b.className = `opt-btn-${category} px-3 py-2 rounded bg-white/5 text-gray-400 border-white/5 border text-xs text-left font-mono transition hover:bg-white/10`;
        });
        btn.className = `opt-btn-${category} px-3 py-2 rounded bg-cyan-600 text-white border-cyan-500 border text-xs text-left font-mono transition shadow-lg`;
        updatePreview();
    }

    function updatePreview() {
        const container = document.getElementById('preview-container');
        const btn = document.getElementById('submitBtn');
        const classes = `w-full h-full bg-white/5 border-2 border-dashed border-white/10 rounded-lg p-4 transition-all duration-300 ${currentConfig.layout} ${currentConfig.gap} ${currentConfig.align}`;
        container.className = classes;
        btn.classList.remove('bg-white/10', 'text-white/50');
        btn.classList.add('bg-cyan-600', 'text-white', 'hover:bg-cyan-500');
        
        // Console Log
        const time = new Date().toLocaleTimeString('en-US', { hour12: false });
        const consoleLog = document.getElementById('console-output');
        consoleLog.innerHTML += `<p>> [${time}] Apply: <span class="text-cyan-400">${currentConfig.layout} ${currentConfig.gap} ${currentConfig.align}</span></p>`;
        consoleLog.scrollTop = consoleLog.scrollHeight;
    }

    async function checkSolution() {
        if(activityCompleted) return;
        const btn = document.getElementById('submitBtn');
        const statusText = document.getElementById('status-text');
        const consoleLog = document.getElementById('console-output');
        btn.innerText = "Memeriksa..."; btn.disabled = true;

        const isLayoutCorrect = currentConfig.layout === 'grid grid-cols-2';
        const isGapCorrect = currentConfig.gap === 'gap-4';
        const isAlignCorrect = currentConfig.align === 'justify-center items-center';

        await new Promise(r => setTimeout(r, 800));

        if (isLayoutCorrect && isGapCorrect && isAlignCorrect) {
            consoleLog.innerHTML += `<p class="text-green-400 font-bold">> SYSTEM: SUCCESS! Matches Target.</p>`;
            statusText.innerText = "Sempurna!";
            statusText.className = "text-[10px] text-green-400 font-bold";
            await saveActivityData();
        } else {
            consoleLog.innerHTML += `<p class="text-red-400 font-bold">> SYSTEM: ERROR. Layout Mismatch.</p>`;
            btn.innerText = "Coba Lagi"; btn.disabled = false;
            btn.classList.add('shake'); setTimeout(() => btn.classList.remove('shake'), 500);
        }
        consoleLog.scrollTop = consoleLog.scrollHeight;
    }

    async function saveActivityData() {
        const btn = document.getElementById('submitBtn');
        try {
            await saveLessonToDB(ACTIVITY_LESSON_ID); // Trigger ID 40
            completedSet.add(ACTIVITY_LESSON_ID);
            activityCompleted = true;
            updateProgressUI();
            markActivityCompleteUI();
            unlockNext();
        } catch(e) { console.error(e); btn.innerText = "Gagal Simpan"; btn.disabled = false; }
    }

    function markActivityCompleteUI() {
        const btn = document.getElementById('submitBtn');
        btn.innerHTML = "Tersimpan ✔";
        btn.className = "w-full md:w-auto px-8 py-3 bg-green-600 text-white font-bold rounded-xl cursor-not-allowed";
        btn.disabled = true;
        document.getElementById('status-text').innerText = "Tantangan Selesai!";
        document.getElementById('status-text').className = "text-[10px] text-green-400 font-bold";
        const container = document.getElementById('preview-container');
        container.className = "w-full h-full bg-white/5 border-2 border-dashed border-white/10 rounded-lg p-4 transition-all duration-300 grid grid-cols-2 gap-4 justify-center items-center";
    }

    /* --- 4. SYSTEM FUNCTIONS --- */
    function initSidebarScroll() {
        const main = document.getElementById('mainScroll');
        const links = document.querySelectorAll('.accordion-content .nav-item'); 
        if(!main) return;
        links.forEach(link => {
            link.addEventListener('click', (e) => {
                const targetId = link.getAttribute('data-target');
                if (targetId) {
                    e.preventDefault();
                    const cleanId = targetId.replace('#', '');
                    const targetEl = document.getElementById(cleanId);
                    if(targetEl) {
                        main.scrollTo({ top: targetEl.offsetTop - 120, behavior: 'smooth' });
                        links.forEach(l => l.classList.remove('active'));
                        link.classList.add('active');
                    }
                }
            });
        });
        main.addEventListener('scroll', () => {
            let currentId = '';
            document.querySelectorAll('.lesson-section').forEach(sec => {
                if (main.scrollTop >= sec.offsetTop - 250) currentId = sec.id;
            });
            if(currentId) {
                links.forEach(link => {
                    link.classList.remove('active');
                    if(link.getAttribute('data-target') === '#'+currentId) link.classList.add('active');
                });
            }
        });
    }

    function initLessonObserver() {
        const obs = new IntersectionObserver(async entries => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    const id = Number(entry.target.dataset.lessonId);
                    const type = entry.target.dataset.type;
                    if (id && type !== 'activity' && !completedSet.has(id)) {
                        try {
                            await saveLessonToDB(id);
                            completedSet.add(id);
                            updateProgressUI();
                        } catch(e) {}
                    }
                }
            }
        }, { threshold: 0.5, root: document.getElementById('mainScroll') });
        document.querySelectorAll('.lesson-section').forEach(s => obs.observe(s));
    }

    async function saveLessonToDB(id) {
        await fetch('/lesson/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: new URLSearchParams({ lesson_id: id }) });
    }

    function updateProgressUI() {
        const total = window.LESSON_IDS.length;
        const done = window.LESSON_IDS.filter(id => completedSet.has(id)).length;
        const percent = Math.round((done / total) * 100);
        
        const bar = document.getElementById('topProgressBar');
        const label = document.getElementById('progressLabelTop');
        
        if(bar) bar.style.width = percent + '%';
        if(label) label.innerText = percent + '%';
        
        if(percent === 100) unlockNext();
    }

    function unlockNext() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.className = "group flex items-center gap-3 text-right text-cyan-400 hover:text-cyan-300 transition cursor-pointer";
            btn.innerHTML = `<div class="text-right"><div class="text-[10px] uppercase tracking-widest opacity-50">Selanjutnya</div><div class="font-bold text-sm">Layout Management</div></div><div class="w-10 h-10 rounded-full border border-cyan-500/30 bg-cyan-500/10 flex items-center justify-center">→</div>`;
            btn.onclick = () => window.location.href = "{{ route('courses.layout-mgmt') }}"; 
        }
    }

    function initVisualEffects() { 
        const c=document.getElementById('stars'); if(!c) return;
        const x=c.getContext('2d');
        function r(){c.width=innerWidth;c.height=innerHeight} r(); window.onresize=r;
        let s=[]; for(let i=0;i<100;i++)s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random(),v:Math.random()*0.5});
        (function a(){x.clearRect(0,0,c.width,c.height);x.fillStyle='rgba(255,255,255,.3)';s.forEach(t=>{x.beginPath();x.arc(t.x,t.y,t.r,0,6.28);x.fill();t.y+=t.v;if(t.y>c.height)t.y=0});requestAnimationFrame(a)})();
        $(window).on('mousemove',e=>{ $('#cursor-glow').css({left:e.clientX,top:e.clientY}); });
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