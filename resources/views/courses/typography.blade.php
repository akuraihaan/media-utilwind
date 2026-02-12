@extends('layouts.landing')
@section('title', 'Bab 3.1 · Tipografi Masterclass')

@section('content')
{{-- <div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-fuchsia-500/30"> --}}
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
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500/20 to-transparent border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-400">3.1</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Tipografi</h1>
                        <p class="text-[10px] text-white/50">Live interactive mode</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-32 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 w-0 transition-all duration-500 shadow-[0_0_10px_#6366f1]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-indigo-400 font-bold text-xs">0%</span>
                </div>
            </div>
<!-- mengatur kelebaran konten -->
            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40"> 
                <article class="space-y-40">
                    
                    <section id="fonts" class="lesson-section scroll-mt-32" data-lesson-id="46">
                        <div class="space-y-8">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-[10px] font-bold uppercase tracking-widest">
                                <!-- <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span> -->
                                Dasar Tipografi
                            </div>
                            
                            <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                Keluarga Font & <br> 
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-fuchsia-400">Skala Ukuran</span>
                            </h2>
                            
                            <div class="prose prose-invert max-w-none text-white/80 space-y-6">
                                <p class="text-lg leading-relaxed">
                                    Tipografi adalah elemen paling fundamental dalam desain antarmuka. Tailwind CSS menyediakan pendekatan utilitas yang memungkinkan Anda mengontrol jenis huruf, ukuran, dan perataan langsung di HTML tanpa menulis CSS kustom.
                                </p>
                                
                                <h3 class="text-xl font-bold text-white mt-8">1. Font Family (Jenis Huruf)</h3>
                                <p>
                                    Tailwind menyertakan tiga tumpukan font (font stack) standar yang mencakup font sistem modern. Ini memastikan teks dirender dengan cepat dan terlihat native di setiap sistem operasi (Windows, macOS, iOS, Android).
                                </p>
                                <ul class="list-disc pl-5 space-y-2 marker:text-cyan-500">
                                    <li><code class="text-cyan-400">font-sans</code> (Default): Digunakan untuk UI umum. Contoh: Inter, Roboto, Helvetica, Arial.</li>
                                    <li><code class="text-cyan-400">font-serif</code>: Digunakan untuk kesan elegan atau formal (seperti koran). Contoh: Georgia, Cambria, Times New Roman.</li>
                                    <li><code class="text-cyan-400">font-mono</code>: Digunakan untuk kode program atau data tabular. Contoh: Consolas, Monaco, Courier New.</li>
                                </ul>

                                <div class="bg-[#0f141e] p-6 rounded-2xl border border-white/5 relative overflow-hidden group mt-6">
                                    <h4 class="text-xs font-bold text-white/40 uppercase tracking-widest mb-4">Simulator Font Family</h4>
                                    <div class="space-y-3">
                                        <button onclick="setFont('font-sans')" class="w-full text-left p-3 rounded bg-white/5 hover:bg-white/10 border border-white/5 transition flex justify-between items-center group/btn">
                                            <span class="font-sans text-white/80">Sans-serif (Modern UI)</span>
                                            <code class="text-[10px] text-cyan-400 bg-cyan-900/30 px-2 py-0.5 rounded">font-sans</code>
                                        </button>
                                        <button onclick="setFont('font-serif')" class="w-full text-left p-3 rounded bg-white/5 hover:bg-white/10 border border-white/5 transition flex justify-between items-center group/btn">
                                            <span class="font-serif text-white/80">Serif (Editorial/Formal)</span>
                                            <code class="text-[10px] text-purple-400 bg-purple-900/30 px-2 py-0.5 rounded">font-serif</code>
                                        </button>
                                        <button onclick="setFont('font-mono')" class="w-full text-left p-3 rounded bg-white/5 hover:bg-white/10 border border-white/5 transition flex justify-between items-center group/btn">
                                            <span class="font-mono text-white/80">Monospace (Coding)</span>
                                            <code class="text-[10px] text-emerald-400 bg-emerald-900/30 px-2 py-0.5 rounded">font-mono</code>
                                        </button>
                                    </div>
                                    <div class="mt-6 p-6 bg-black/40 rounded-xl border border-white/10 h-32 flex items-center justify-center text-center">
                                        <p id="demo-font" class="text-3xl text-white font-sans transition-all duration-300">The quick brown fox jumps over the lazy dog.</p>
                                    </div>
                                </div>

                                <h3 class="text-xl font-bold text-white mt-12">2. Font Size (Ukuran Teks)</h3>
                                <p>
                                    Tailwind menggunakan sistem skala ukuran T-shirt (xs, sm, base, lg, xl, dst). Setiap utilitas ukuran font juga secara otomatis mengatur <strong>line-height</strong> (jarak antar baris) yang proporsional agar teks tetap nyaman dibaca.
                                </p>
                                
                                <div class="bg-[#0f141e] p-6 rounded-2xl border border-cyan-500/30 shadow-lg shadow-cyan-900/20 relative overflow-hidden group mt-6">
                                    <div class="space-y-4">
                                        <div class="flex items-baseline justify-between border-b border-white/5 pb-2">
                                            <span class="text-xs text-white/50 w-24">Caption / Label</span>
                                            <span class="text-xs text-cyan-400 font-mono w-24">text-xs</span>
                                            <span class="text-xs text-white/30">0.75rem (12px)</span>
                                        </div>
                                        <div class="flex items-baseline justify-between border-b border-white/5 pb-2">
                                            <span class="text-base text-white w-24">Body Text</span>
                                            <span class="text-base text-cyan-400 font-mono w-24">text-base</span>
                                            <span class="text-xs text-white/30">1rem (16px)</span>
                                        </div>
                                        <div class="flex items-baseline justify-between border-b border-white/5 pb-2">
                                            <span class="text-2xl font-bold text-white w-24">Sub-Heading</span>
                                            <span class="text-xl text-cyan-400 font-mono w-24">text-2xl</span>
                                            <span class="text-xs text-white/30">1.5rem (24px)</span>
                                        </div>
                                        <div class="flex items-baseline justify-between">
                                            <span class="text-4xl font-black text-white w-24">Hero Title</span>
                                            <span class="text-2xl text-cyan-400 font-mono w-24">text-4xl</span>
                                            <span class="text-xs text-white/30">2.25rem (36px)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="weight" class="lesson-section scroll-mt-32" data-lesson-id="47">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">3.1.2 Ketebalan & Gaya</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <div class="prose prose-invert max-w-none text-white/80 space-y-6">
                            <p>
                                Mengontrol ketebalan font (Font Weight) adalah kunci untuk menciptakan <strong>hierarki visual</strong>. Judul biasanya lebih tebal untuk menarik perhatian, sementara teks pendukung mungkin lebih tipis.
                            </p>
                            
                            <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-8 mb-12 relative overflow-hidden shadow-2xl">
                                <h3 class="text-lg font-bold text-white mb-6 text-center">Spektrum Ketebalan (Weight Spectrum)</h3>
                                <p class="text-center text-sm text-white/50 mb-8">Tailwind memetakan nilai numerik CSS (100-900) ke dalam nama kelas yang mudah diingat.</p>
                                
                                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-center">
                                    <div class="bg-white/5 p-4 rounded-xl hover:bg-white/10 transition group border border-transparent hover:border-cyan-500/30">
                                        <span class="block text-4xl mb-3 font-thin text-white group-hover:scale-110 transition duration-300">Ag</span>
                                        <code class="text-[10px] text-cyan-400 block mb-1">font-thin</code>
                                        <span class="text-[9px] text-white/30">Weight 100</span>
                                    </div>
                                    <div class="bg-white/5 p-4 rounded-xl hover:bg-white/10 transition group border border-transparent hover:border-cyan-500/30">
                                        <span class="block text-4xl mb-3 font-light text-white group-hover:scale-110 transition duration-300">Ag</span>
                                        <code class="text-[10px] text-cyan-400 block mb-1">font-light</code>
                                        <span class="text-[9px] text-white/30">Weight 300</span>
                                    </div>
                                    <div class="bg-white/5 p-4 rounded-xl hover:bg-white/10 transition group border border-transparent hover:border-cyan-500/30">
                                        <span class="block text-4xl mb-3 font-normal text-white group-hover:scale-110 transition duration-300">Ag</span>
                                        <code class="text-[10px] text-cyan-400 block mb-1">font-normal</code>
                                        <span class="text-[9px] text-white/30">Weight 400</span>
                                    </div>
                                    <div class="bg-white/5 p-4 rounded-xl hover:bg-white/10 transition group border border-cyan-500/30 shadow-[0_0_15px_rgba(6,182,212,0.1)]">
                                        <span class="block text-4xl mb-3 font-bold text-white group-hover:scale-110 transition duration-300">Ag</span>
                                        <code class="text-[10px] text-cyan-400 block mb-1">font-bold</code>
                                        <span class="text-[9px] text-white/30">Weight 700</span>
                                    </div>
                                    <div class="bg-white/5 p-4 rounded-xl hover:bg-white/10 transition group border border-transparent hover:border-cyan-500/30">
                                        <span class="block text-4xl mb-3 font-black text-white group-hover:scale-110 transition duration-300">Ag</span>
                                        <code class="text-[10px] text-cyan-400 block mb-1">font-black</code>
                                        <span class="text-[9px] text-white/30">Weight 900</span>
                                    </div>
                                </div>
                                
                                <div class="mt-8 pt-6 border-t border-white/5 grid grid-cols-2 gap-6">
                                    <div class="text-center">
                                        <p class="italic text-xl text-white mb-2 font-serif">"Emphasis through style"</p>
                                        <code class="text-xs text-fuchsia-400 bg-fuchsia-500/10 px-2 py-1 rounded">italic</code>
                                        <p class="text-[10px] text-white/40 mt-1">Digunakan untuk penekanan atau kutipan.</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="not-italic text-xl text-white mb-2 font-serif">"Back to normal state"</p>
                                        <code class="text-xs text-fuchsia-400 bg-fuchsia-500/10 px-2 py-1 rounded">not-italic</code>
                                        <p class="text-[10px] text-white/40 mt-1">Mengembalikan teks miring menjadi tegak.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="spacing" class="lesson-section scroll-mt-32" data-lesson-id="48">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">3.1.3 Spasi, Baris & Perataan</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <div class="prose prose-invert max-w-none text-white/80 space-y-6">
                            <p>
                                Keterbacaan (Readability) sangat dipengaruhi oleh ruang putih. Tailwind menyediakan kontrol granular untuk <strong>Letter Spacing</strong> (Tracking), <strong>Line Height</strong> (Leading), dan <strong>Alignment</strong>.
                            </p>

                            <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-8 mb-8 relative overflow-hidden shadow-2xl">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-lg font-bold text-white">Simulator Text Alignment</h3>
                                    <div class="flex gap-2 bg-white/5 p-1 rounded-lg border border-white/5">
                                        <button onclick="setAlign('text-left')" class="p-2 rounded hover:bg-white/10 text-white/50 hover:text-white transition" title="Rata Kiri"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h16"></path></svg></button>
                                        <button onclick="setAlign('text-center')" class="p-2 rounded hover:bg-white/10 text-white/50 hover:text-white transition" title="Rata Tengah"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M4 18h16"></path></svg></button>
                                        <button onclick="setAlign('text-right')" class="p-2 rounded hover:bg-white/10 text-white/50 hover:text-white transition" title="Rata Kanan"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M10 12h10M4 18h16"></path></svg></button>
                                        <button onclick="setAlign('text-justify')" class="p-2 rounded hover:bg-white/10 text-white/50 hover:text-white transition" title="Rata Kiri Kanan"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></button>
                                    </div>
                                </div>
                                <div class="bg-white text-slate-800 p-6 rounded-xl min-h-[120px] shadow-inner transition-all duration-300">
                                    <p id="demo-align" class="text-left leading-relaxed text-sm">
                                        Tailwind CSS memudahkan pengaturan teks dengan kelas utilitas intuitif. Anda dapat meratakan teks ke kiri (default), tengah untuk judul, kanan untuk data numerik, atau justify untuk artikel formal agar tepi kiri dan kanan rata. Cobalah tombol di atas untuk melihat efeknya secara langsung pada paragraf ini.
                                    </p>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-8">
                                <div class="bg-[#1e1e1e] p-6 rounded-2xl border border-white/5">
                                    <h4 class="text-white font-bold mb-4 flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-cyan-500"></span> Letter Spacing (Tracking)
                                    </h4>
                                    <div class="space-y-4">
                                        <div class="group">
                                            <div class="flex justify-between mb-1 text-xs text-white/40">
                                                <code>tracking-tighter</code>
                                                <span>-0.05em</span>
                                            </div>
                                            <p class="tracking-tighter text-xl font-black bg-black/30 p-3 rounded text-white group-hover:text-cyan-300 transition uppercase">HEADLINE RAPAT</p>
                                            <p class="text-[10px] text-white/30 mt-1">Cocok untuk judul besar agar terlihat padat.</p>
                                        </div>
                                        <div class="group">
                                            <div class="flex justify-between mb-1 text-xs text-white/40">
                                                <code>tracking-widest</code>
                                                <span>0.1em</span>
                                            </div>
                                            <p class="tracking-widest text-xl font-black bg-black/30 p-3 rounded text-white group-hover:text-cyan-300 transition uppercase">MENU ELEGANT</p>
                                            <p class="text-[10px] text-white/30 mt-1">Cocok untuk teks kapital kecil atau menu navigasi.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-[#1e1e1e] p-6 rounded-2xl border border-white/5">
                                    <h4 class="text-white font-bold mb-4 flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-fuchsia-500"></span> Line Height (Leading)
                                    </h4>
                                    <div class="space-y-4">
                                        <div class="bg-black/30 p-3 rounded group hover:bg-black/50 transition">
                                            <div class="flex justify-between mb-1 text-xs text-white/40"><code>leading-none</code></div>
                                            <p class="leading-none text-sm text-white/80">Jarak baris ini sangat rapat (1). Biasanya digunakan untuk judul besar agar tidak memakan banyak tempat vertikal.</p>
                                        </div>
                                        <div class="bg-black/30 p-3 rounded group hover:bg-black/50 transition">
                                            <div class="flex justify-between mb-1 text-xs text-white/40"><code>leading-loose</code></div>
                                            <p class="leading-loose text-sm text-white/80">Jarak baris ini sangat longgar (2). Sangat nyaman untuk paragraf panjang di artikel blog agar mata tidak lelah.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="decoration" class="lesson-section scroll-mt-32" data-lesson-id="49">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">3.1.4 Warna & Dekorasi</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <div class="prose prose-invert max-w-none text-white/80 space-y-6">
                            <p>
                                Selain bentuk huruf, Anda dapat memberikan warna dan dekorasi visual seperti garis bawah, coretan, atau efek gradasi pada teks.
                            </p>

                            <div class="bg-[#0f141e] border border-white/10 rounded-2xl p-8 text-center relative overflow-hidden">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
                                    <div class="p-4 rounded-xl bg-white/5 group hover:bg-white/10 transition border border-transparent hover:border-blue-500/30">
                                        <p class="text-5xl font-black text-blue-500 mb-3 group-hover:scale-110 transition duration-300 drop-shadow-lg">Aa</p>
                                        <code class="text-[10px] text-white/50 bg-black/40 px-2 py-1 rounded">text-blue-500</code>
                                    </div>
                                    <div class="p-4 rounded-xl bg-white/5 group hover:bg-white/10 transition border border-transparent hover:border-emerald-500/30">
                                        <p class="text-5xl font-black text-emerald-400 mb-3 group-hover:scale-110 transition duration-300 drop-shadow-lg">Aa</p>
                                        <code class="text-[10px] text-white/50 bg-black/40 px-2 py-1 rounded">text-emerald-400</code>
                                    </div>
                                    <div class="p-4 rounded-xl bg-white/5 group hover:bg-white/10 transition border border-transparent hover:border-rose-500/30">
                                        <p class="text-5xl font-black text-rose-500 mb-3 group-hover:scale-110 transition duration-300 drop-shadow-lg">Aa</p>
                                        <code class="text-[10px] text-white/50 bg-black/40 px-2 py-1 rounded">text-rose-500</code>
                                    </div>
                                    <div class="p-4 rounded-xl bg-white/5 group hover:bg-white/10 transition border border-transparent hover:border-fuchsia-500/30">
                                        <p class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-500 to-cyan-500 mb-3 group-hover:scale-110 transition duration-300">Aa</p>
                                        <code class="text-[10px] text-white/50 bg-black/40 px-2 py-1 rounded">bg-clip-text</code>
                                    </div>
                                </div>
                                
                                <div class="h-px w-full bg-white/5 mb-8"></div>

                                <h3 class="text-xs font-bold text-white/40 uppercase tracking-widest mb-6">Text Decoration Utilities</h3>
                                <div class="flex flex-wrap justify-center gap-8">
                                    <div class="group">
                                        <span class="text-2xl text-white underline decoration-white decoration-2 underline-offset-4 group-hover:decoration-cyan-400 transition-all">Underline</span>
                                        <code class="block text-[10px] text-white/30 mt-2">underline</code>
                                    </div>
                                    <div class="group">
                                        <span class="text-2xl text-white/50 line-through decoration-red-500 decoration-2">Deleted</span>
                                        <code class="block text-[10px] text-white/30 mt-2">line-through</code>
                                    </div>
                                    <div class="group">
                                        <span class="text-2xl text-white overline decoration-blue-500 decoration-2">Overline</span>
                                        <code class="block text-[10px] text-white/30 mt-2">overline</code>
                                    </div>
                                    <div class="group">
                                        <span class="text-2xl text-white underline decoration-wavy decoration-yellow-400 decoration-2">Wavy</span>
                                        <code class="block text-[10px] text-white/30 mt-2">decoration-wavy</code>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="transform" class="lesson-section scroll-mt-32" data-lesson-id="50">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">3.1.5 Transformasi & Overflow</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <h3 class="text-lg font-bold text-cyan-400">Text Transform</h3>
                                <p class="text-sm text-white/60">Mengubah kapitalisasi teks tanpa mengubah data aslinya di HTML.</p>
                                
                                <div class="bg-white/5 p-4 rounded-xl border border-white/5 flex justify-between items-center group hover:bg-white/10 transition">
                                    <span class="uppercase font-bold text-white tracking-widest">uppercase mode</span>
                                    <code class="text-[10px] text-fuchsia-400 bg-fuchsia-900/20 px-2 py-1 rounded">uppercase</code>
                                </div>
                                <div class="bg-white/5 p-4 rounded-xl border border-white/5 flex justify-between items-center group hover:bg-white/10 transition">
                                    <span class="lowercase font-bold text-white tracking-widest">LOWERCASE MODE</span>
                                    <code class="text-[10px] text-fuchsia-400 bg-fuchsia-900/20 px-2 py-1 rounded">lowercase</code>
                                </div>
                                <div class="bg-white/5 p-4 rounded-xl border border-white/5 flex justify-between items-center group hover:bg-white/10 transition">
                                    <span class="capitalize font-bold text-white tracking-widest">judul setiap kata</span>
                                    <code class="text-[10px] text-fuchsia-400 bg-fuchsia-900/20 px-2 py-1 rounded">capitalize</code>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg font-bold text-cyan-400">Overflow Handling</h3>
                                <p class="text-sm text-white/60">Mengontrol teks yang terlalu panjang untuk wadahnya.</p>

                                <div class="bg-black/30 p-5 rounded-xl border border-white/5 space-y-4">
                                    <div>
                                        <div class="flex justify-between mb-2">
                                            <span class="text-xs text-white/50 font-bold">Truncate (Satu Baris)</span>
                                            <code class="text-cyan-400 text-xs bg-cyan-900/20 px-2 py-0.5 rounded">truncate</code>
                                        </div>
                                        <p class="truncate w-full bg-white/10 p-2 rounded text-white/80 text-sm border border-white/5">
                                            Teks ini sangat panjang sekali dan akan otomatis dipotong dengan tanda elipsis (...) di akhir baris karena lebar wadah terbatas.
                                        </p>
                                    </div>
                                    <div>
                                        <div class="flex justify-between mb-2">
                                            <span class="text-xs text-white/50 font-bold">Break All (Paksa Putus)</span>
                                            <code class="text-cyan-400 text-xs bg-cyan-900/20 px-2 py-0.5 rounded">break-all</code>
                                        </div>
                                        <p class="break-all w-full bg-white/10 p-2 rounded text-white/80 text-sm border border-white/5">
                                            SupercalifragilisticexpialidociousIsAVeryLongWordThatNeedsBreakingToFit
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="activity-expert" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="50" data-manual="true">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl text-center group hover:border-purple-500/30 transition-all duration-500">
                            
                            <div class="p-8 border-b border-white/10 bg-gradient-to-r from-blue-900/10 to-transparent relative">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-600 text-white text-[10px] font-bold uppercase mb-4 tracking-widest shadow-lg shadow-blue-600/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    Expert Challenge
                                </div>
                                <h2 class="text-3xl font-black text-white mb-4">Studi Kasus: Redesign Portal Berita</h2>
                                <p class="text-white/60 text-sm max-w-2xl mx-auto leading-relaxed">
                                    Editor senior mengeluh artikel berita di website saat ini sulit dibaca dan tidak profesional. Tugas Anda adalah memperbaiki tipografi <strong>Judul Utama</strong> dan <strong>Isi Berita</strong> agar memenuhi standar jurnalisme digital: <em>Tegas (Bold/Serif), Jelas, dan Nyaman Dibaca (Relaxed).</em>
                                </p>
                            </div>

                            <div class="grid lg:grid-cols-12 min-h-[600px] text-left border-x border-b border-white/5 rounded-b-[2rem] bg-[#0f141e]">
                                
                                <div class="lg:col-span-4 bg-[#0f141e] border-r border-white/5 p-6 flex flex-col h-full">
                                    <div class="flex items-center justify-between mb-6">
                                        <h3 class="text-xs font-bold text-white/40 uppercase tracking-widest">Control Panel</h3>
                                        <div class="flex gap-1">
                                            <div class="w-2 h-2 rounded-full bg-red-500/50"></div>
                                            <div class="w-2 h-2 rounded-full bg-yellow-500/50"></div>
                                            <div class="w-2 h-2 rounded-full bg-green-500/50"></div>
                                        </div>
                                    </div>

                                    <div class="flex-1 space-y-6 overflow-y-auto custom-scrollbar pr-2" id="practice-controls">
                                        </div>
                                    
                                    <div class="pt-6 mt-6 border-t border-white/5">
                                        <button id="checkBtn" onclick="checkSolution()" class="w-full py-4 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-500 text-white font-bold text-lg shadow-lg hover:shadow-cyan-500/25 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                                            Verifikasi Desain 🚀
                                        </button>
                                        <div id="feedback-area" class="mt-4 hidden p-4 rounded-lg text-center text-sm font-bold animate-pulse"></div>
                                    </div>
                                </div>

                                <div class="lg:col-span-8 bg-slate-100 text-slate-900 p-8 md:p-12 relative overflow-y-auto flex flex-col items-center">
                                    <div class="absolute top-4 right-4 text-[10px] font-mono text-slate-400 bg-white px-2 py-1 rounded border border-slate-200 shadow-sm">BROWSER PREVIEW</div>

                                    <div class="w-full max-w-2xl bg-white min-h-[500px] shadow-xl p-10 border border-slate-200 mt-6 relative">
                                        <div class="relative group mb-6">
                                            <div class="absolute -left-8 top-2 bg-blue-600 text-white text-[9px] font-bold px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition shadow-md">JUDUL</div>
                                            <div id="target-title" class="p-4 border-2 border-dashed border-blue-300/50 rounded-xl hover:border-blue-500 transition-colors cursor-help bg-blue-50/20">
                                                BREAKING: Tailwind CSS Merevolusi Desain Web Modern
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-4 text-sm text-slate-400 mb-8 font-sans border-b border-slate-100 pb-4">
                                            <span class="font-bold text-red-600 uppercase tracking-widest text-xs">Teknologi</span>
                                            <span>•</span> <span>28 Jan 2026</span>
                                            <span>•</span> <span>5 Menit Baca</span>
                                        </div>

                                        <div class="relative group">
                                            <div class="absolute -left-8 top-2 bg-cyan-600 text-white text-[9px] font-bold px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition shadow-md">KONTEN</div>
                                            <div id="target-body" class="p-4 border-2 border-dashed border-cyan-300/50 rounded-xl hover:border-cyan-500 transition-colors cursor-help bg-cyan-50/20">
                                                Framework utility-first ini telah mengubah cara developer membangun antarmuka pengguna. Dengan menyediakan kelas-kelas tingkat rendah, Tailwind memungkinkan pembuatan desain kustom tanpa meninggalkan HTML Anda. Tidak ada lagi perpindahan konteks antara file markup dan style sheet yang memakan waktu.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </section>

                </article>

                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center">
                    <a href="{{ route('courses.layout-mgmt') }}" class="group flex items-center gap-3 text-slate-400 hover:text-white transition">
                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-white/30 transition">←</div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest opacity-50">Sebelumnya</div>
                            <div class="font-bold text-sm">Layout Mgmt</div>
                        </div>
                    </a>

                    <div id="nextChapterBtn" class="group flex items-center gap-3 text-right text-slate-500 cursor-not-allowed">
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
    /* --- CONFIGURATION --- */
    window.SUBBAB_LESSON_IDS = [46, 47, 48, 49, 50]; 
    window.INIT_COMPLETED_LESSONS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedLessons = new Set(window.INIT_COMPLETED_LESSONS);
    
    // Activity ID = 10 (Expert Challenge)
    const ACTIVITY_ID = {{ $expertActivity->id ?? 10 }};
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};

    /* --- 1. CORE SYSTEM --- */
    document.addEventListener('DOMContentLoaded', () => {
        updateProgressUI();
        initLessonObserver();
        initVisualEffects();
        initSidebarScroll();
        renderControls();
        
        if (activityCompleted) {
            disableExpertUI();
        }
    });

    /* --- 2. SIMULATORS UI --- */
    function setFont(cls) { document.getElementById('demo-font').className = `${cls} text-3xl text-white transition-all duration-300`; }
    function setAlign(cls) { document.getElementById('demo-align').className = `${cls} leading-relaxed text-sm transition-all duration-300`; }

    /* --- 3. EXPERT CHALLENGE LOGIC --- */
    const studyCaseConfig = {
        title: {
            elementId: 'target-title',
            label: '1. Gaya Judul (Headline)',
            options: {
                font: [{cls:'font-sans',label:'Sans'},{cls:'font-serif',label:'Serif (Koran)',correct:true},{cls:'font-mono',label:'Mono'}],
                size: [{cls:'text-xl',label:'Kecil'},{cls:'text-4xl',label:'Besar',correct:true},{cls:'text-6xl',label:'Raksasa'}],
                weight: [{cls:'font-normal',label:'Tipis'},{cls:'font-bold',label:'Tebal'},{cls:'font-black',label:'Berat',correct:true}],
                color: [{cls:'text-slate-900',label:'Hitam',correct:true},{cls:'text-blue-500',label:'Biru'},{cls:'text-gray-400',label:'Abu'}]
            }
        },
        body: {
            elementId: 'target-body',
            label: '2. Gaya Paragraf (Body)',
            options: {
                align: [{cls:'text-left',label:'Kiri'},{cls:'text-center',label:'Tengah'},{cls:'text-justify',label:'Rata Kanan-Kiri',correct:true}],
                leading: [{cls:'leading-none',label:'Rapat'},{cls:'leading-normal',label:'Normal'},{cls:'leading-loose',label:'Longgar',correct:true}],
                color: [{cls:'text-slate-600',label:'Abu Baca',correct:true},{cls:'text-red-500',label:'Merah'},{cls:'text-black',label:'Hitam'}]
            }
        }
    };

    let userChoices = { title: {font:'',size:'',weight:'',color:''}, body: {align:'',leading:'',color:''} };

    function renderControls() {
        const container = $('#practice-controls');
        if(!container.length) return;
        
        Object.entries(studyCaseConfig).forEach(([sectionKey, sectionData]) => {
            let html = `<div class="bg-black/40 p-5 rounded-2xl border border-white/5 mb-6 hover:border-cyan-500/20 transition-colors">
                <h4 class="text-cyan-400 font-bold mb-4 uppercase text-[10px] tracking-[0.2em] border-b border-white/5 pb-2 flex items-center gap-2">
                    <span class="w-1 h-4 bg-cyan-500 rounded-full"></span>
                    ${sectionData.label}
                </h4>`;
            Object.entries(sectionData.options).forEach(([catKey, options]) => {
                html += `<div class="mb-4 last:mb-0"><label class="text-[10px] text-white/40 mb-2 block capitalize font-bold font-mono">${catKey}</label><div class="flex flex-wrap gap-2">`;
                options.forEach(opt => {
                    html += `<button onclick="selectOption('${sectionKey}','${catKey}','${opt.cls}',this)" class="btn-opt-${sectionKey}-${catKey} px-3 py-2 rounded-lg text-xs font-mono border border-white/10 hover:border-cyan-500/50 hover:bg-cyan-500/10 text-white/60 transition-all active:scale-95" data-cls="${opt.cls}">${opt.label}</button>`;
                });
                html += `</div></div>`;
            });
            html += `</div>`;
            container.append(html);
        });
    }

    window.selectOption = function(sKey, cKey, cls, btn) {
        if(activityCompleted) return;
        $(`.btn-opt-${sKey}-${cKey}`).removeClass('bg-cyan-600 text-white border-cyan-500 shadow-lg shadow-cyan-500/20').addClass('text-white/60 border-white/10');
        $(btn).removeClass('text-white/60 border-white/10').addClass('bg-cyan-600 text-white border-cyan-500 shadow-lg shadow-cyan-500/20');
        userChoices[sKey][cKey] = cls;
        const target = $(`#${studyCaseConfig[sKey].elementId}`);
        const allCls = studyCaseConfig[sKey].options[cKey].map(o=>o.cls);
        target.removeClass(allCls.join(' ')).addClass(cls);
    }

    window.checkSolution = async function() {
        if(activityCompleted) return;
        let isCorrect = true;
        Object.entries(studyCaseConfig).forEach(([sKey, sData]) => {
            Object.entries(sData.options).forEach(([cKey, opts]) => {
                const pick = userChoices[sKey][cKey];
                if(pick !== opts.find(o=>o.correct).cls) isCorrect = false;
            });
        });

        const fb = $('#feedback-area');
        fb.removeClass('hidden bg-red-500/10 text-red-400 bg-emerald-500/10 text-emerald-400 border-red-500/20 border-emerald-500/20');
        
        if(isCorrect) {
            fb.addClass('bg-emerald-500/10 text-emerald-400 border border-emerald-500/20').html(`
                <div class="text-3xl mb-2 animate-bounce">🎉</div>
                <div class="text-lg font-bold">Sempurna!</div>
                <div class="text-xs opacity-70 mt-1">Anda memiliki insting desain jurnalisme.</div>
            `);
            await finishChapter();
        } else {
            fb.addClass('bg-red-500/10 text-red-400 border border-red-500/20').html(`
                <div class="text-3xl mb-2 animate-pulse">🧐</div>
                <div class="text-lg font-bold">Belum Tepat</div>
                <div class="text-xs opacity-70 mt-1">Petunjuk: Judul = Font Serif & Tebal. Body = Justify & Spasi Longgar.</div>
            `);
        }
        fb.removeClass('hidden');
    }

    async function finishChapter() {
        const btn = document.getElementById('checkBtn');
        btn.innerHTML = "Menyimpan...";
        try {
            await fetch('/activity/complete', { method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'}, body: JSON.stringify({ activity_id: ACTIVITY_ID, score: 100 }) });
            await saveLessonToDB(50); // Mark last lesson done
            completedLessons.add(50);
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
            btn.classList.remove('bg-gradient-to-r', 'from-blue-600', 'to-cyan-500');
            btn.classList.add('bg-emerald-600', 'cursor-default');
        }
        $('#feedback-area').removeClass('hidden').addClass('bg-emerald-500/10 text-emerald-400 border-emerald-500/20').html('Misi Telah Selesai ✔');
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
            btn.className = "group flex items-center gap-3 text-right text-cyan-400 hover:text-cyan-300 transition cursor-pointer";
            btn.innerHTML = `<div class="text-right"><div class="text-[10px] uppercase tracking-widest opacity-50">Selanjutnya</div><div class="font-bold text-sm">Bab 4: Komponen</div></div><div class="w-10 h-10 rounded-full border border-cyan-500/30 bg-cyan-500/10 flex items-center justify-center">→</div>`;
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