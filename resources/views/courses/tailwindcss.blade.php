@extends('layouts.landing')
@section('title','Bab 1.2 · Tailwind CSS Mastery')

@section('content')
{{-- <div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30"> --}}
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30 pt-20">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[1000px] h-[1000px] bg-cyan-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-sky-900/10 rounded-full blur-[100px]"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.03]"></div>
        <div id="cursor-glow"></div>
    </div>

            @include('layouts.partials.navbar')


    <div class="flex flex-1 overflow-hidden relative z-20">

        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            {{-- Sticky Header --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-[#020617]/80 backdrop-blur-2xl border-b border-white/5 px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500/20 to-transparent border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-400">1.2</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Konsep Dasar Tailwind</h1>
                        <p class="text-[10px] text-white/50">Core Utilities & Workflow</p>
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
                
                <div class="mb-24">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-cyan-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-cyan-500/10 text-cyan-400 flex items-center justify-center shrink-0 font-bold text-xs">1</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Filosofi Utility</h4><p class="text-[11px] text-white/50 leading-relaxed">Mengapa class atomik lebih cepat dari CSS biasa.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-sky-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-sky-500/10 text-sky-400 flex items-center justify-center shrink-0 font-bold text-xs">2</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Design Tokens</h4><p class="text-[11px] text-white/50 leading-relaxed">Menguasai sistem warna dan tipografi bawaan.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-teal-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-teal-500/10 text-teal-400 flex items-center justify-center shrink-0 font-bold text-xs">3</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Box Model</h4><p class="text-[11px] text-white/50 leading-relaxed">Padding, Margin, dan Sizing yang konsisten.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-blue-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-blue-500/10 text-blue-400 flex items-center justify-center shrink-0 font-bold text-xs">4</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Styling UI</h4><p class="text-[11px] text-white/50 leading-relaxed">Border radius dan shadow untuk estetika modern.</p></div>
                        </div>
                        <div class="bg-gradient-to-br from-cyan-900/40 to-blue-900/40 border border-cyan-500/30 p-5 rounded-xl flex items-start gap-4 hover:shadow-[0_0_20px_rgba(34,211,238,0.2)] transition group h-full col-span-2 md:col-span-2">
                            <div class="w-8 h-8 rounded bg-white/10 text-white flex items-center justify-center shrink-0 font-bold text-xs">🏁</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Final Mission</h4><p class="text-[11px] text-white/70 leading-relaxed">Live Code: Membangun Notification Card.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    <section id="section-7" class="lesson-section scroll-mt-32" data-lesson-id="7">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-400 font-mono text-xs uppercase tracking-widest">Lesson 1.2.1</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Filosofi <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Utility-First</span>
                                </h2>
                            </div>
                            
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-cyan-600 flex items-center justify-center text-[10px] text-white">A</span> Jangan Tinggalkan HTML Anda</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Dalam pengembangan web tradisional, kita diajarkan untuk "memisahkan konten dan presentasi". Kita menulis HTML, memberi nama kelas semantik seperti <code>.profile-card-container</code>, lalu pergi ke file CSS untuk menulis gayanya. Meskipun terdengar rapi, praktik ini menyebabkan <strong>"Context Switching"</strong>.
                                    </p>
                                    <p>
                                        <strong>Tailwind CSS menawarkan paradigma berbeda.</strong> Ia menyediakan ribuan "utility classes" kecil (seperti <code>flex</code>, <code>p-4</code>, <code>text-center</code>). Dengan Tailwind, Anda membangun desain langsung di dalam markup HTML. Ini mempercepat pengembangan karena Anda tidak perlu lagi memikirkan nama kelas abstrak yang membingungkan.
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-blue-600 flex items-center justify-center text-[10px] text-white">B</span> Kemenangan Skalabilitas</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Masalah klasik CSS adalah ia selalu bertambah besar. Setiap fitur baru berarti baris CSS baru. Lama kelamaan, file CSS menjadi raksasa yang sulit dipelihara, penuh dengan "kode mati" yang tidak ada yang berani menghapusnya.
                                    </p>
                                    <p>
                                        Tailwind menyelesaikan ini dengan menggunakan ulang kelas yang sama. Sebuah tombol di halaman beranda dan tombol di halaman admin mungkin menggunakan kelas <code>bg-blue-500 rounded p-2</code> yang sama. Ukuran file CSS Anda akan tetap kecil dan stabil meskipun proyek membesar.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-[#0b0f19] border border-white/10 rounded-xl overflow-hidden shadow-2xl flex flex-col md:flex-row h-[350px]">
                                <div class="w-full md:w-1/2 bg-[#1e1e1e] p-6 font-mono text-xs flex flex-col">
                                    <div class="flex justify-between items-center mb-4 pb-2 border-b border-white/5">
                                        <span class="text-white/40 font-bold uppercase text-[10px]">Code Editor View</span>
                                        <div class="flex gap-2">
                                            <button onclick="setSim1('css')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-gray-400 hover:text-white transition text-[10px]">CSS Biasa</button>
                                            <button onclick="setSim1('tw')" class="px-3 py-1 bg-cyan-600/20 border border-cyan-500/50 text-cyan-400 rounded transition text-[10px]">Tailwind</button>
                                        </div>
                                    </div>
                                    <div id="sim1-code" class="flex-1 overflow-auto text-blue-300 leading-relaxed whitespace-pre font-mono bg-black/20 p-4 rounded border border-white/5"></div>
                                </div>
                                <div class="w-full md:w-1/2 bg-[#111] flex flex-col items-center justify-center border-l border-white/5 relative">
                                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
                                    <button class="bg-blue-600 text-white font-bold py-2 px-6 rounded-full shadow-lg hover:scale-105 transition transform">
                                        Beli Sekarang
                                    </button>
                                    <p class="mt-6 text-xs text-white/30 font-mono text-center px-8">Output visual sama persis.<br>Perbedaannya ada di efisiensi penulisan.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-8" class="lesson-section scroll-mt-32" data-lesson-id="8">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-400 font-mono text-xs uppercase tracking-widest">Lesson 1.2.2</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Warna & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Tipografi</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-cyan-600 flex items-center justify-center text-[10px] text-white">A</span> Sistem Warna 50-950</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Tailwind menyediakan <strong>Sistem Desain Terkurasi</strong>. Setiap warna memiliki spektrum ketebalan dari <code>50</code> (paling terang) hingga <code>950</code> (paling gelap). Formatnya: <code>{properti}-{warna}-{ketebalan}</code>.
                                    </p>
                                    <p>
                                        Contoh praktis:
                                        <br>• <strong>Background:</strong> <code>bg-slate-900</code> untuk tema gelap modern.
                                        <br>• <strong>Teks:</strong> <code>text-cyan-400</code> untuk highlight yang mencolok.
                                        <br>• <strong>Border:</strong> <code>border-white/20</code> menggunakan modifier opasitas (slash syntax).
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-blue-600 flex items-center justify-center text-[10px] text-white">B</span> Menguasai Teks</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Tipografi membangun hierarki visual. Tailwind memungkinkan kita mengontrol ukuran, ketebalan, dan spasi dengan sangat presisi menggunakan satuan <code>rem</code> (responsif).
                                    </p>
                                    <ul class="list-disc pl-5 space-y-1 text-base mt-2">
                                        <li><strong>Ukuran:</strong> <code>text-xs</code>, <code>text-sm</code>, <code>text-base</code>, <code>text-xl</code>, <code>text-9xl</code>.</li>
                                        <li><strong>Ketebalan:</strong> <code>font-light</code>, <code>font-bold</code>, <code>font-black</code>.</li>
                                        <li><strong>Alignment:</strong> <code>text-center</code>, <code>text-right</code>.</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="bg-[#1e1e1e] rounded-xl border border-white/10 p-6 flex flex-col md:flex-row gap-8 shadow-2xl relative overflow-hidden">
                                <div class="w-full md:w-1/2 space-y-6 relative z-10">
                                    <h4 class="text-xs font-bold text-white/50 uppercase">Styling Text Practice</h4>
                                    <div>
                                        <label class="text-[10px] text-cyan-400 block mb-2 font-bold">SIZE</label>
                                        <div class="flex gap-2">
                                            <button onclick="updateSim2('size', 'text-sm')" class="flex-1 py-2 bg-white/5 border border-white/10 rounded text-xs hover:bg-cyan-600/20 transition text-gray-300">text-sm</button>
                                            <button onclick="updateSim2('size', 'text-2xl')" class="flex-1 py-2 bg-white/5 border border-white/10 rounded text-xs hover:bg-cyan-600/20 transition text-gray-300">text-2xl</button>
                                            <button onclick="updateSim2('size', 'text-5xl')" class="flex-1 py-2 bg-white/5 border border-white/10 rounded text-xs hover:bg-cyan-600/20 transition text-gray-300">text-5xl</button>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-[10px] text-pink-400 block mb-2 font-bold">COLOR</label>
                                        <div class="flex gap-2">
                                            <button onclick="updateSim2('color', 'text-slate-300')" class="flex-1 py-2 bg-white/5 border border-white/10 rounded text-xs hover:bg-slate-600/20 transition text-gray-300">Slate</button>
                                            <button onclick="updateSim2('color', 'text-cyan-400')" class="flex-1 py-2 bg-white/5 border border-white/10 rounded text-xs hover:bg-cyan-600/20 transition text-cyan-400">Cyan</button>
                                            <button onclick="updateSim2('color', 'text-rose-500')" class="flex-1 py-2 bg-white/5 border border-white/10 rounded text-xs hover:bg-rose-600/20 transition text-rose-500">Rose</button>
                                        </div>
                                    </div>
                                    <div class="bg-black/40 p-3 rounded border border-white/5 font-mono text-[10px] text-gray-400 mt-4">
                                        &lt;h1 class="<span id="sim2-code" class="text-cyan-300">font-bold text-base text-white</span>"&gt;...&lt;/h1&gt;
                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 bg-black/40 rounded-xl flex items-center justify-center border border-white/5 min-h-[200px] relative z-10">
                                    <h1 id="sim2-target" class="text-base text-white transition-all duration-300 font-bold">Hello Tailwind</h1>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-9" class="lesson-section scroll-mt-32" data-lesson-id="9">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-400 font-mono text-xs uppercase tracking-widest">Lesson 1.2.3</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Spacing & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Sizing</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-cyan-600 flex items-center justify-center text-[10px] text-white">A</span> The 4-Point Grid System</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Di Tailwind, <strong>1 satuan = 0.25rem (4px)</strong>. Ini adalah aturan emas. Sistem ini memastikan konsistensi ritme spasial di seluruh aplikasi Anda.
                                    </p>
                                    <p>
                                        • <code>p-4</code> = Padding 16px (4 x 4). <br>
                                        • <code>m-8</code> = Margin 32px (8 x 4). <br>
                                        • <code>gap-4</code> = Jarak 16px antar elemen. <br>
                                        Untuk membuat komponen yang "lega", jangan takut menggunakan padding besar seperti <code>p-6</code> atau <code>p-8</code>.
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-blue-600 flex items-center justify-center text-[10px] text-white">B</span> Sizing & Constraints</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Mengatur dimensi elemen adalah kunci layout responsif. Tailwind menyediakan utilitas lebar (width) dan tinggi (height) yang fleksibel.
                                    </p>
                                    <p>
                                        • <strong>Fixed:</strong> <code>w-64</code> (16rem), <code>h-10</code> (2.5rem).
                                        <br>• <strong>Fluid:</strong> <code>w-full</code> (100%), <code>w-1/2</code> (50%).
                                        <br>• <strong>Constraints:</strong> <code>max-w-sm</code> (membatasi lebar maksimal kartu agar tidak "pecah" melebar di layar desktop).
                                    </p>
                                </div>
                            </div>

                            <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-8 relative shadow-2xl overflow-hidden flex flex-col items-center">
                                <div class="w-full max-w-md space-y-6 mb-8 relative z-10">
                                    <div>
                                        <div class="flex justify-between mb-2">
                                            <label class="text-xs text-cyan-400 font-bold uppercase">Padding (p-)</label>
                                            <span class="text-xs text-white/50 font-mono" id="sim3-label-p">class="p-4"</span>
                                        </div>
                                        <input type="range" min="0" max="12" value="4" oninput="updateSim3('p', this.value)" class="w-full accent-cyan-500 h-1 bg-white/10 rounded cursor-pointer">
                                    </div>
                                    <div>
                                        <div class="flex justify-between mb-2">
                                            <label class="text-xs text-blue-400 font-bold uppercase">Width (w-)</label>
                                            <span class="text-xs text-white/50 font-mono" id="sim3-label-w">class="w-32"</span>
                                        </div>
                                        <input type="range" min="16" max="64" value="32" oninput="updateSim3('w', this.value)" class="w-full accent-blue-500 h-1 bg-white/10 rounded cursor-pointer">
                                    </div>
                                </div>
                                
                                <div class="bg-[#1e1e1e] p-10 rounded-xl border border-white/5 w-full flex justify-center h-64 items-center relative z-10">
                                    <div id="sim3-target" class="bg-cyan-600 text-white font-bold text-center transition-all duration-300 shadow-xl overflow-hidden p-4 w-32 rounded-lg flex items-center justify-center border border-white/20">
                                        KONTEN
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-10" class="lesson-section scroll-mt-32" data-lesson-id="10">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-400 font-mono text-xs uppercase tracking-widest">Lesson 1.2.4</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Borders & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Effects</span>
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-cyan-600 flex items-center justify-center text-[10px] text-white">A</span> Rounded Corners</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        UI modern sangat menghindari sudut tajam. Tailwind menyediakan utilitas <code>rounded</code> untuk melembutkan sudut elemen.
                                    </p>
                                    <p>
                                        Gunakan <code>rounded-lg</code> atau <code>rounded-xl</code> untuk kartu agar terlihat ramah. Untuk elemen seperti avatar pengguna atau badge notifikasi, gunakan <code>rounded-full</code> untuk membuatnya menjadi lingkaran sempurna (pil).
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-blue-600 flex items-center justify-center text-[10px] text-white">B</span> Depth with Shadows</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Bayangan memberikan ilusi kedalaman. Elemen dengan bayangan akan terlihat "mengambang" di atas background, menciptakan hierarki visual.
                                    </p>
                                    <p>
                                        Gunakan <code>shadow-md</code> untuk elevasi rendah, atau <code>shadow-xl</code> untuk elevasi tinggi. Pada background gelap, bayangan sering dikombinasikan dengan border tipis (<code>border-white/10</code>) untuk memberikan definisi tepi yang lebih tajam.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-[#1e1e1e] border border-white/10 rounded-2xl p-8 flex flex-col md:flex-row gap-10 items-center shadow-2xl relative overflow-hidden">
                                <div class="w-full md:w-1/2 space-y-6 relative z-10">
                                    <h4 class="text-xs font-bold text-white/50 uppercase">Visual Effects Practice</h4>
                                    
                                    <div class="space-y-2">
                                        <p class="text-[10px] text-cyan-400 font-bold">ROUNDED</p>
                                        <div class="flex gap-2">
                                            <button onclick="updateSim4('rad', 'rounded-none')" class="flex-1 py-2 bg-white/5 border border-white/10 rounded text-xs hover:bg-cyan-600/20 text-gray-400 transition">None</button>
                                            <button onclick="updateSim4('rad', 'rounded-xl')" class="flex-1 py-2 bg-white/5 border border-white/10 rounded text-xs hover:bg-cyan-600/20 text-gray-400 transition">XL</button>
                                            <button onclick="updateSim4('rad', 'rounded-full')" class="flex-1 py-2 bg-white/5 border border-white/10 rounded text-xs hover:bg-cyan-600/20 text-gray-400 transition">Full</button>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <p class="text-[10px] text-blue-400 font-bold">SHADOW</p>
                                        <div class="flex gap-2">
                                            <button onclick="updateSim4('shadow', 'shadow-none')" class="flex-1 py-2 bg-white/5 border border-white/10 rounded text-xs hover:bg-cyan-600/20 text-gray-400 transition">Flat</button>
                                            <button onclick="updateSim4('shadow', 'shadow-lg')" class="flex-1 py-2 bg-white/5 border border-white/10 rounded text-xs hover:bg-cyan-600/20 text-gray-400 transition">Large</button>
                                            <button onclick="updateSim4('shadow', 'shadow-cyan-500/50')" class="flex-1 py-2 bg-white/5 border border-white/10 rounded text-xs hover:bg-cyan-600/20 text-gray-400 transition">Glow</button>
                                        </div>
                                    </div>

                                    <div class="bg-black/40 p-3 rounded border border-white/5 font-mono text-[10px] text-gray-400 mt-4">
                                        class="<span id="sim4-code" class="text-cyan-300">rounded-none shadow-none</span>"
                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 flex justify-center bg-[#0f141e] p-10 rounded-xl border border-white/5 relative z-10">
                                    <div id="sim4-target" class="w-32 h-32 bg-gradient-to-br from-cyan-500 to-blue-600 transition-all duration-500"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="section-11" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="11" data-type="activity">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl group hover:border-cyan-500/30 transition-all duration-500">
                            
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-cyan-600/20 blur-[100px] rounded-full pointer-events-none"></div>

                            <div class="flex items-center gap-4 mb-6 relative z-10">
                                <div class="p-3 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl text-white shadow-lg shadow-cyan-500/30">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-white">Coding Challenge: Notification Card</h2>
                                    <p class="text-cyan-300 text-sm">Tulis kode Tailwind asli di Editor untuk menyelesaikan misi.</p>
                                </div>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-6 max-w-6xl mx-auto h-[600px]">
                                
                                <div class="bg-[#1e1e1e] rounded-xl border border-white/10 flex flex-col overflow-hidden h-full relative shadow-2xl">
                                    
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-6 border-4 border-emerald-500/20 m-1 rounded-lg">
                                        <div class="w-24 h-24 bg-emerald-500/10 rounded-full flex items-center justify-center mb-6 border border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,0.3)] animate-bounce">
                                            <svg class="w-12 h-12 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h3 class="text-3xl font-black text-white mb-2 tracking-tight">SELESAI!</h3>
                                        <p class="text-base text-white/60 mb-8 max-w-xs">Subbab 1.2 Tuntas. Lanjut ke Latar Belakang Tailwind CSS</p>
                                        <button disabled class="px-8 py-3 rounded-full bg-white/5 border border-white/10 text-white/30 text-xs font-bold cursor-not-allowed uppercase tracking-widest">Review Mode</button>
                                    </div>

                                    <div class="bg-[#0f141e] px-4 py-2 border-b border-white/5 flex justify-between items-center">
                                        <span class="text-xs text-white/50 font-mono">Component.html</span>
                                        <button onclick="resetEditor()" class="text-[10px] text-red-400 hover:text-red-300 transition uppercase font-bold">Reset</button>
                                    </div>
                                    
                                    <div id="codeEditor" class="flex-1 w-full"></div>

                                    <div class="p-4 bg-[#0f141e] border-t border-white/5">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="text-[10px] uppercase font-bold text-white/30">Requirements</span>
                                            <span id="progressText" class="text-[10px] font-mono text-cyan-400">0/4 Selesai</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 text-[11px] font-mono text-white/50 mb-4">
                                            <div id="check-bg" class="flex items-center gap-2 transition-colors"><span class="w-3 h-3 rounded-full border border-white/20 flex items-center justify-center text-[8px]"></span> bg-slate-800</div>
                                            <div id="check-pad" class="flex items-center gap-2 transition-colors"><span class="w-3 h-3 rounded-full border border-white/20 flex items-center justify-center text-[8px]"></span> p-6</div>
                                            <div id="check-flex" class="flex items-center gap-2 transition-colors"><span class="w-3 h-3 rounded-full border border-white/20 flex items-center justify-center text-[8px]"></span> flex & gap-4</div>
                                            <div id="check-round" class="flex items-center gap-2 transition-colors"><span class="w-3 h-3 rounded-full border border-white/20 flex items-center justify-center text-[8px]"></span> rounded-xl</div>
                                        </div>
                                        <button id="submitExerciseBtn" onclick="submitExercise()" disabled class="w-full py-3 rounded-lg bg-emerald-600 text-white font-bold text-xs shadow-lg shadow-emerald-500/20 hover:bg-emerald-500 transition-all cursor-not-allowed opacity-50 flex items-center justify-center gap-2">
                                            <span>Selesaikan Misi</span>
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="bg-white rounded-xl border border-white/10 flex-1 flex flex-col relative overflow-hidden min-h-[500px]">
                                    <div class="bg-gray-100 px-4 py-2 border-b border-gray-200 flex items-center justify-between">
                                        <span class="text-[10px] text-gray-500 font-mono">Browser Preview</span>
                                        <span class="text-[10px] bg-green-100 text-green-600 px-2 py-0.5 rounded border border-green-200">Active</span>
                                    </div>
                                    <iframe id="previewFrame" class="w-full h-full border-0 bg-gray-50"></iframe>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center">
                    <a href="{{ route('courses.htmldancss') }}" class="group flex items-center gap-3 text-slate-400 hover:text-white transition">
                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-white/30 transition">←</div>
                        <div class="text-left"><div class="text-[10px] uppercase tracking-widest opacity-50">Sebelumnya</div><div class="font-bold text-sm">Konsep Dasar HTML dan CSS</div></div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-3 text-right text-slate-500 cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500">
                        <div class="text-right">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest opacity-50">Terkunci</div>
                            <div class="font-bold text-sm">Latar Belakang & Struktur</div>
                        </div>
                        <div id="nextIcon" class="w-10 h-10 rounded-full border border-white/5 flex items-center justify-center bg-white/5">🔒</div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<style>
    .nav-link.active { color: #22d3ee; position: relative; }
    .nav-link.active::after { content: ''; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: linear-gradient(to right,#22d3ee,#0ea5e9); box-shadow: 0 0 12px rgba(34,211,238,.8); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    #animated-bg{ background: radial-gradient(800px circle at 20% 20%, rgba(34,211,238,.15), transparent 40%), radial-gradient(800px circle at 80% 80%, rgba(14,165,233,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }
    .nav-item.active { color: #22d3ee; background: rgba(34,211,238,0.05); font-weight: 600; }
    .nav-item.active .dot { background: #22d3ee; box-shadow: 0 0 8px #22d3ee; transform: scale(1.2); }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>

<script>
    /* --- CONFIGURATION --- */
    window.LESSON_IDS = [7, 8, 9, 10, 11]; 
    window.COMPLETED_IDS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedSet = new Set(window.COMPLETED_IDS);
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    const ACTIVITY_ID = 2; 
    const ACTIVITY_LESSON_ID = 11; 

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initLessonObserver();
        initVisualEffects();
        initSidebarScroll();
        updateProgressUI();
        initMonaco(); // Initialize Real-time Editor
        setSim1('css'); 
        
        if (activityCompleted) {
            lockActivityUI();
            unlockNextChapter();
        }
    });

    /* --- SIMULATORS 1-4 (Visual) --- */
    function setSim1(mode) {
        const codeBox = document.getElementById('sim1-code');
        if (mode === 'css') {
            codeBox.innerHTML = `.btn {\n  background-color: blue;\n  color: white;\n  padding: 10px 20px;\n  border-radius: 5px;\n}`;
        } else {
            codeBox.innerHTML = `&lt;button class="bg-blue-600 text-white px-4 py-2 rounded"&gt;\n  Button\n&lt;/button&gt;`;
        }
    }

    let typeState = { size: 'text-base', color: 'text-white', weight: 'font-normal' };
    function updateSim2(cat, val) {
        const target = document.getElementById('sim2-target');
        const code = document.getElementById('sim2-code');
        if(cat === 'size') typeState.size = val;
        if(cat === 'color') typeState.color = val;
        if(cat === 'weight') typeState.weight = val;
        target.className = "transition-all duration-300 " + typeState.size + " " + typeState.color + " " + typeState.weight;
        code.innerText = `${typeState.weight} ${typeState.size} ${typeState.color}`;
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

    /* --- REALTIME CODING (Lesson 11) --- */
    let editor;
    const starterCode = `<div class="">
  
  <div class="p-3 bg-cyan-500/20 rounded-full">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
  </div>

  <div>
    <h4 class="font-bold text-lg text-white">Pesan Baru</h4>
    <p class="text-slate-400 text-sm">Anda mendapat pesan dari Tim.</p>
  </div>

</div>`;

    function initMonaco() {
        require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' } });
        require(['vs/editor/editor.main'], function () {
            editor = monaco.editor.create(document.getElementById('codeEditor'), {
                value: starterCode, language: 'html', theme: 'vs-dark', fontSize: 13,
                minimap: { enabled: false }, automaticLayout: true, padding: { top: 16 }, lineNumbers: 'off'
            });
            updatePreview(starterCode);
            editor.onDidChangeModelContent(() => {
                if(activityCompleted) return;
                const code = editor.getValue();
                updatePreview(code);
                validateCode(code);
            });
        });
    }

    function updatePreview(code) {
        const frame = document.getElementById('previewFrame');
        const content = `<!doctype html><html><head><script src="https://cdn.tailwindcss.com"><\/script><style>body { background-color: #f3f4f6; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; font-family: sans-serif; }</style></head><body>${code}</body></html>`;
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
        checks.forEach(check => {
            const el = document.getElementById(check.id);
            const dot = el.querySelector('span');
            if (check.regex.test(code)) {
                el.classList.remove('text-white/50'); el.classList.add('text-emerald-400', 'font-bold');
                dot.innerHTML = '✓'; dot.classList.add('bg-emerald-500', 'border-transparent', 'text-black');
                passedCount++;
            } else {
                el.classList.add('text-white/50'); el.classList.remove('text-emerald-400', 'font-bold');
                dot.innerHTML = ''; dot.classList.remove('bg-emerald-500', 'border-transparent', 'text-black');
            }
        });
        document.getElementById('progressText').innerText = `${passedCount}/4 Selesai`;
        const btn = document.getElementById('submitExerciseBtn');
        if (passedCount === 4) {
            btn.disabled = false; btn.classList.remove('cursor-not-allowed', 'opacity-50');
            btn.innerHTML = `<span>Selesaikan Misi</span><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
        } else {
            btn.disabled = true; btn.classList.add('cursor-not-allowed', 'opacity-50');
        }
    }

    function resetEditor() { if(editor && !activityCompleted) editor.setValue(starterCode); }

    /* --- SYSTEM --- */
    async function submitExercise() {
        const btn = document.getElementById('submitExerciseBtn');
        btn.innerHTML = "Menyimpan..."; btn.disabled = true;
        try {
            await fetch('/activity/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify({ activity_id: ACTIVITY_ID, score: 100 }) });
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            completedSet.add(ACTIVITY_LESSON_ID);
            activityCompleted = true; updateProgressUI(); lockActivityUI(); unlockNextChapter(); 
        } catch(e) { console.error(e); btn.innerHTML = "Gagal. Coba Lagi."; btn.disabled = false; }
    }

    function lockActivityUI() {
        document.getElementById('lockOverlay').classList.remove('hidden');
        if(editor) editor.updateOptions({ readOnly: true });
        const btn = document.getElementById('submitExerciseBtn'); btn.innerText = "Terkunci"; btn.disabled = true;
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-slate-500');
            btn.classList.add('text-cyan-400', 'hover:text-cyan-300', 'cursor-pointer');
            document.getElementById('nextLabel').innerText = "Selanjutnya"; document.getElementById('nextLabel').classList.remove('opacity-50');
            document.getElementById('nextIcon').innerHTML = "→"; document.getElementById('nextIcon').classList.add('bg-cyan-500/20', 'border-cyan-500/50');
            btn.onclick = () => window.location.href = "{{ route('dashboard') }}"; 
        }
    }

    function updateProgressUI() {
        const total = window.LESSON_IDS.length; 
        const done = window.LESSON_IDS.filter(id => completedSet.has(id)).length; 
        const percent = Math.round((done / total) * 100);
        document.getElementById('topProgressBar').style.width = percent + '%'; 
        document.getElementById('progressLabelTop').innerText = percent + '%';
        if(percent === 100) unlockNextChapter();
    }

    function initLessonObserver() {
        const obs = new IntersectionObserver(async entries => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    const id = Number(entry.target.dataset.lessonId);
                    if (id && entry.target.dataset.type !== 'activity' && !completedSet.has(id)) {
                        try { await saveLessonToDB(id); completedSet.add(id); updateProgressUI(); } catch(e) {}
                    }
                }
            }
        }, { threshold: 0.5, root: document.getElementById('mainScroll') });
        document.querySelectorAll('.lesson-section').forEach(s => obs.observe(s));
    }

    async function saveLessonToDB(id) { await fetch('/lesson/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: new URLSearchParams({ lesson_id: id }) }); }

    function initScrollSpy() {
        const mainScroll = document.getElementById('mainScroll'); const sections = document.querySelectorAll('.lesson-section'); const navLinks = document.querySelectorAll('.sidebar-nav-link');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = '#' + entry.target.id;
                    navLinks.forEach(link => {
                        const dot = link.querySelector('.dot-indicator'); if(!dot) return;
                        link.classList.remove('bg-white/5'); dot.classList.remove('bg-cyan-500', 'shadow-[0_0_8px_#22d3ee]', 'scale-125'); dot.classList.add('bg-slate-600');
                        if (link.dataset.target === id) { link.classList.add('bg-white/5'); dot.classList.remove('bg-slate-600'); dot.classList.add('bg-cyan-500', 'shadow-[0_0_8px_#22d3ee]', 'scale-125'); }
                    });
                }
            });
        }, { root: mainScroll, rootMargin: '-20% 0px -60% 0px', threshold: 0 });
        sections.forEach(section => observer.observe(section));
    }

    function initSidebarScroll(){const m=document.getElementById('mainScroll');const l=document.querySelectorAll('.accordion-content .nav-item');m.addEventListener('scroll',()=>{let c='';document.querySelectorAll('.lesson-section').forEach(s=>{if(m.scrollTop>=s.offsetTop-250)c='#'+s.id;});l.forEach(k=>{k.classList.remove('active');if(k.getAttribute('data-target')===c)k.classList.add('active')})});l.forEach(k=>k.addEventListener('click',(e)=>{const t=document.querySelector(k.getAttribute('data-target'));if(t)m.scrollTo({top:t.offsetTop-120,behavior:'smooth'})}));}
    function initVisualEffects(){const c=document.getElementById('stars'),x=c.getContext('2d');function r(){c.width=innerWidth;c.height=innerHeight}r();window.onresize=r;let s=[];for(let i=0;i<100;i++)s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.2,v:Math.random()*0.2+.1});(function a(){x.clearRect(0,0,c.width,c.height);x.fillStyle='rgba(255,255,255,.3)';s.forEach(t=>{x.beginPath();x.arc(t.x,t.y,t.r,0,6.28);x.fill();t.y+=t.v;if(t.y>c.height)t.y=0});requestAnimationFrame(a)})();$(window).on('mousemove',e=>{$('#cursor-glow').css({left:e.clientX,top:e.clientY})});}
</script>
@endsection