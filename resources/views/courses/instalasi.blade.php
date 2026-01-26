@extends('layouts.landing')
@section('title','Bab 1.6 · Instalasi dan Konfigurasi')

@section('content')
{{-- KONSISTENSI TEMA: Menggunakan base color ungu/fuchsia dan cyan --}}
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-fuchsia-500/30">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        {{-- Gradients disesuaikan ke Ungu/Pink/Cyan --}}
        <div id="animated-bg" class="absolute inset-0 opacity-60"></div>
        <div id="gradient-wave" class="absolute inset-0"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.035]"></div>
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
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500/20 to-transparent border border-purple-500/20 flex items-center justify-center font-bold text-xs text-purple-400">1.6</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Instalasi dan Konfigurasi</h1>
                        <p class="text-[10px] text-white/50">Estimasi: 30 Menit</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-24 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        {{-- Progress bar gradient ungu ke cyan --}}
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-purple-500 to-cyan-500 w-0 transition-all duration-500 shadow-[0_0_10px_#d946ef]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-purple-400 font-bold text-xs">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                <article class="space-y-32">
                    
                    <section id="prereq" class="lesson-section scroll-mt-32" data-lesson-id="{{ $subbab16LessonIds[0] ?? 24 }}">
                        <div class="space-y-8">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-300 text-[10px] font-bold uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-pulse"></span>
                                Prasyarat Utama
                            </div>
                            
                            <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                Lingkungan Kerja: <br> 
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400">Node.js & NPM</span>
                            </h2>
                            
                            <p class="text-lg text-white/70 leading-relaxed max-w-3xl">
                                Sebelum memulai instalasi, sangat penting untuk memahami prasyarat utama. Tailwind CSS didistribusikan sebagai paket NPM, yang berarti sistem Anda harus memiliki <strong>Node.js</strong> yang berjalan.
                            </p>

                            <div class="grid md:grid-cols-2 gap-6 mt-6">
                                <div class="bg-[#0f141e] p-6 rounded-2xl border border-white/5 hover:border-purple-500/30 transition group relative overflow-hidden">
                                    <div class="absolute top-0 right-0 p-4 opacity-20 text-5xl font-black text-purple-500 group-hover:opacity-40 transition">JS</div>
                                    <div class="relative z-10">
                                        <h3 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
                                            <span class="text-purple-400">●</span> Node.js
                                        </h3>
                                        <p class="text-sm text-white/60">
                                            Lingkungan runtime untuk mengeksekusi kode JavaScript di luar browser. Ini adalah fondasi untuk menjalankan alat build modern.
                                        </p>
                                    </div>
                                </div>
                                <div class="bg-[#0f141e] p-6 rounded-2xl border border-white/5 hover:border-cyan-500/30 transition group relative overflow-hidden">
                                    <div class="absolute top-0 right-0 p-4 opacity-20 text-5xl font-black text-cyan-500 group-hover:opacity-40 transition">NPM</div>
                                    <div class="relative z-10">
                                        <h3 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
                                            <span class="text-cyan-400">●</span> NPM (Node Package Manager)
                                        </h3>
                                        <p class="text-sm text-white/60">
                                            Manajer paket standar Node.js. Kita menggunakannya untuk mengunduh Tailwind CSS dan dependensinya ke dalam proyek.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="cli-steps" class="lesson-section scroll-mt-32" data-lesson-id="{{ $subbab16LessonIds[1] ?? 25 }}">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">1.6.1 Langkah Instalasi (CLI)</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <p class="text-white/70 mb-6">
                            Kita akan menggunakan Tailwind CSS Command Line Interface (CLI). Ikuti simulasi terminal di bawah ini untuk memahami alurnya. Klik tombol perintah secara berurutan.
                        </p>

                        <div class="bg-[#1e1e1e] border border-white/10 rounded-xl overflow-hidden shadow-2xl font-mono text-sm relative shadow-purple-500/10">
                            <div class="bg-[#2d2d2d] px-4 py-2 flex gap-2 items-center border-b border-white/5">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                <span class="ml-2 text-xs text-gray-400">Terminal — TailwindCSSProject</span>
                            </div>
                            
                            <div class="p-6 h-[300px] overflow-y-auto bg-black/50 custom-scrollbar" id="terminal-body">
                                <div class="text-gray-500 mb-4 select-none"># Pastikan Node.js sudah terinstal. Mari mulai!</div>
                                <div id="terminal-output" class="space-y-2"></div>
                                <div class="flex items-center mt-2">
                                    <span class="text-purple-400 mr-2">➜</span>
                                    <span class="text-cyan-300 mr-2">~/project</span>
                                    <span class="animate-pulse text-white">_</span>
                                </div>
                            </div>

                            <div class="bg-[#0f141e] p-4 border-t border-white/5 grid grid-cols-1 md:grid-cols-3 gap-3 relative z-10">
                                <button onclick="runTerm(1)" id="btn-step-1" class="term-btn px-4 py-3 rounded bg-white/5 hover:bg-white/10 border border-white/10 text-xs text-left transition group relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500/10 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                                    <span class="block text-purple-400 font-bold mb-1 relative z-10">1. Cek Versi Node/NPM</span>
                                    <code class="text-gray-400 group-hover:text-white relative z-10">node -v && npm -v</code>
                                </button>
                                <button onclick="runTerm(2)" id="btn-step-2" class="term-btn px-4 py-3 rounded bg-white/5 border border-white/10 text-xs text-left transition opacity-50 cursor-not-allowed group relative overflow-hidden" disabled>
                                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500/10 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                                    <span class="block text-purple-400 font-bold mb-1 relative z-10">2. Install Paket</span>
                                    <code class="text-gray-400 group-hover:text-white relative z-10">npm install -D tailwindcss</code>
                                </button>
                                <button onclick="runTerm(3)" id="btn-step-3" class="term-btn px-4 py-3 rounded bg-white/5 border border-white/10 text-xs text-left transition opacity-50 cursor-not-allowed group relative overflow-hidden" disabled>
                                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500/10 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                                    <span class="block text-purple-400 font-bold mb-1 relative z-10">3. Buat Config</span>
                                    <code class="text-gray-400 group-hover:text-white relative z-10">npx tailwindcss init</code>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex items-start gap-4 p-4 bg-purple-500/10 rounded-lg border border-purple-500/20">
                            <span class="text-2xl">📂</span>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-1">Hasil Instalasi</h4>
                                <p class="text-xs text-white/60">Setelah langkah di atas, folder <code>node_modules</code> (berisi dependensi) dan file <code>tailwind.config.js</code> akan terbentuk di proyek Anda.</p>
                            </div>
                        </div>
                    </section>

                    <section id="compilation" class="lesson-section scroll-mt-32" data-lesson-id="{{ $subbab16LessonIds[2] ?? 26 }}">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">1.6.2 Integrasi & Kompilasi</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <div class="grid lg:grid-cols-2 gap-8">
                            <div class="bg-[#0f141e] p-6 rounded-2xl border border-white/10 relative group hover:border-purple-500/30 transition">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="bg-purple-500/20 text-purple-400 px-3 py-1 rounded-full text-xs font-bold">Langkah 1</span>
                                    <h3 class="text-white font-bold">Buat File CSS Sumber</h3>
                                </div>
                                <p class="text-sm text-white/60 mb-4">Buat file (misal: <code>src/style.css</code>) dan tambahkan 3 direktif utama ini untuk mengimpor fitur Tailwind:</p>
                                <div class="bg-black/50 p-5 rounded-xl font-mono text-sm text-cyan-300 border-l-4 border-purple-500 shadow-inner">
                                    @tailwind base;<br>
                                    @tailwind components;<br>
                                    @tailwind utilities;
                                </div>
                            </div>

                            <div class="bg-[#0f141e] p-6 rounded-2xl border border-white/10 relative group hover:border-cyan-500/30 transition">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="bg-cyan-500/20 text-cyan-400 px-3 py-1 rounded-full text-xs font-bold">Langkah 2</span>
                                    <h3 class="text-white font-bold">Proses Build (Kompilasi)</h3>
                                </div>
                                <p class="text-sm text-white/60 mb-4">Jalankan perintah ini di terminal untuk mengubah kode Tailwind menjadi CSS standar browser:</p>
                                <div class="bg-black/50 p-5 rounded-xl font-mono text-sm text-white border-l-4 border-cyan-500 overflow-x-auto shadow-inner">
                                    npx tailwindcss -i ./src/style.css -o ./dist/output.css --watch
                                </div>
                                <ul class="mt-4 space-y-2 text-xs text-white/50 font-mono">
                                    <li class="flex gap-2"><span class="text-cyan-400">-i</span> : File Input (sumber)</li>
                                    <li class="flex gap-2"><span class="text-cyan-400">-o</span> : File Output (hasil jadi)</li>
                                    <li class="flex gap-2"><span class="text-cyan-400">--watch</span> : Mode pantau (otomatis update)</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <section id="config" class="lesson-section scroll-mt-32" data-lesson-id="{{ $subbab16LessonIds[3] ?? 27 }}">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">1.6.4 Konfigurasi Tema</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>
                        <p class="text-white/70 mb-6">
                            Tailwind memungkinkan penyesuaian warna, font, dan lainnya melalui file <code>tailwind.config.js</code>. Berikut contoh menambahkan warna kustom:
                        </p>
                        <div class="bg-[#1e1e1e] p-6 rounded-2xl border border-white/10 font-mono text-sm text-gray-300 shadow-lg relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-2 text-xs text-purple-400 bg-purple-500/10 rounded-bl-xl border-b border-l border-purple-500/20">tailwind.config.js</div>
                            <span class="text-purple-400">module</span>.exports = {<br>
                            &nbsp;&nbsp;theme: {<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;extend: {<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;colors: {<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="text-cyan-400">'brand-primary': '#8b5cf6',</span> <span class="text-gray-500">// Menambah warna baru</span><br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;}<br>
                            &nbsp;&nbsp;}<br>
                            }
                        </div>
                    </section>

                    <section id="quiz-1-6" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="{{ $subbab16LessonIds[4] ?? 28 }}" data-manual="true">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl group hover:border-purple-500/30 transition-all duration-500">
                            
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-2/3 h-1 bg-gradient-to-r from-transparent via-purple-500 to-transparent opacity-50 blur-sm"></div>

                            <div class="text-center mb-8">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-300 text-[10px] font-bold uppercase mb-4 tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-pulse"></span>
                                    Aktivitas 1.6
                                </div>
                                <h2 class="text-3xl font-black text-white mb-2">Lengkapi Perintah</h2>
                                <p class="text-white/60 text-sm">Isi bagian kosong pada perintah terminal berikut untuk menyelesaikan instalasi.</p>
                            </div>

                            <div id="quiz-container" class="max-w-xl mx-auto min-h-[350px] relative">
                                
                                <div class="flex justify-between mb-8 px-2 relative z-10">
                                    <div class="h-1 flex-1 bg-white/10 rounded-full overflow-hidden mr-2">
                                        <div id="quiz-progress" class="h-full bg-gradient-to-r from-purple-500 to-cyan-500 w-[25%] transition-all duration-500"></div>
                                    </div>
                                    <span id="question-counter" class="text-[10px] text-purple-400 font-bold">1/4</span>
                                </div>

                                <div id="q1" class="quiz-step absolute inset-0 transition-all duration-500 flex flex-col">
                                    <p class="font-bold text-white mb-4 text-lg">1. Perintah untuk menginstal paket Tailwind CSS:</p>
                                    <div class="bg-black/40 p-6 rounded-xl border border-purple-500/30 font-mono text-base text-center mb-8 shadow-inner">
                                        npm install -D <span class="border-b-2 border-cyan-400 text-cyan-300 px-2 animate-pulse">???</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 mt-auto">
                                        <button onclick="checkAnswer(1, false)" class="quiz-opt p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm font-bold transition group relative overflow-hidden">
                                            <span class="relative z-10">base</span>
                                            <div class="absolute inset-0 bg-red-500/20 opacity-0 group-hover:opacity-100 transition"></div>
                                        </button>
                                        <button onclick="checkAnswer(1, true)" class="quiz-opt p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm font-bold transition group relative overflow-hidden">
                                            <span class="relative z-10">tailwindcss</span>
                                            <div class="absolute inset-0 bg-green-500/20 opacity-0 group-hover:opacity-100 transition"></div>
                                        </button>
                                    </div>
                                </div>

                                <div id="q2" class="quiz-step absolute inset-0 translate-x-[120%] opacity-0 pointer-events-none transition-all duration-500 flex flex-col">
                                    <p class="font-bold text-white mb-4 text-lg">2. Perintah untuk membuat file konfigurasi awal:</p>
                                    <div class="bg-black/40 p-6 rounded-xl border border-purple-500/30 font-mono text-base text-center mb-8 shadow-inner">
                                        npx <span class="border-b-2 border-cyan-400 text-cyan-300 px-2 animate-pulse">???</span> init
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 mt-auto">
                                        <button onclick="checkAnswer(2, true)" class="quiz-opt p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm font-bold transition group relative overflow-hidden">
                                            <span class="relative z-10">tailwindcss</span>
                                            <div class="absolute inset-0 bg-green-500/20 opacity-0 group-hover:opacity-100 transition"></div>
                                        </button>
                                        <button onclick="checkAnswer(2, false)" class="quiz-opt p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm font-bold transition group relative overflow-hidden">
                                            <span class="relative z-10">node</span>
                                            <div class="absolute inset-0 bg-red-500/20 opacity-0 group-hover:opacity-100 transition"></div>
                                        </button>
                                    </div>
                                </div>

                                <div id="q3" class="quiz-step absolute inset-0 translate-x-[120%] opacity-0 pointer-events-none transition-all duration-500 flex flex-col">
                                    <p class="font-bold text-white mb-4 text-lg">3. Melengkapi direktif utama di file CSS sumber:</p>
                                    <div class="bg-black/40 p-6 rounded-xl border border-purple-500/30 font-mono text-base text-center mb-8 shadow-inner leading-loose">
                                        @tailwind base;<br>@tailwind <span class="border-b-2 border-cyan-400 text-cyan-300 px-2 animate-pulse">???</span>;<br>@tailwind utilities;
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 mt-auto">
                                        <button onclick="checkAnswer(3, true)" class="quiz-opt p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm font-bold transition group relative overflow-hidden">
                                            <span class="relative z-10">components</span>
                                            <div class="absolute inset-0 bg-green-500/20 opacity-0 group-hover:opacity-100 transition"></div>
                                        </button>
                                        <button onclick="checkAnswer(3, false)" class="quiz-opt p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm font-bold transition group relative overflow-hidden">
                                            <span class="relative z-10">styles</span>
                                            <div class="absolute inset-0 bg-red-500/20 opacity-0 group-hover:opacity-100 transition"></div>
                                        </button>
                                    </div>
                                </div>

                                <div id="q4" class="quiz-step absolute inset-0 translate-x-[120%] opacity-0 pointer-events-none transition-all duration-500 flex flex-col">
                                    <p class="font-bold text-white mb-4 text-lg">4. Flag (opsi) agar proses build berjalan otomatis saat file berubah:</p>
                                    <div class="bg-black/40 p-6 rounded-xl border border-purple-500/30 font-mono text-base text-center mb-8 shadow-inner">
                                        npx tailwindcss -i ... -o ... --<span class="border-b-2 border-cyan-400 text-cyan-300 px-2 animate-pulse">???</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 mt-auto">
                                        <button onclick="checkAnswer(4, true)" class="quiz-opt p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm font-bold transition group relative overflow-hidden">
                                            <span class="relative z-10">watch</span>
                                            <div class="absolute inset-0 bg-green-500/20 opacity-0 group-hover:opacity-100 transition"></div>
                                        </button>
                                        <button onclick="checkAnswer(4, false)" class="quiz-opt p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-sm font-bold transition group relative overflow-hidden">
                                            <span class="relative z-10">auto</span>
                                            <div class="absolute inset-0 bg-red-500/20 opacity-0 group-hover:opacity-100 transition"></div>
                                        </button>
                                    </div>
                                </div>

                                <div id="quiz-success" class="absolute inset-0 translate-y-10 opacity-0 pointer-events-none transition-all duration-500 text-center flex flex-col justify-center items-center">
                                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-500 to-cyan-500 flex items-center justify-center mb-6 shadow-lg shadow-purple-500/30 scale-0 transition-transform duration-500 delay-200" id="success-icon">
                                        <span class="text-5xl">🎉</span>
                                    </div>
                                    <h3 class="text-3xl font-bold text-white mb-2">Aktivitas Selesai!</h3>
                                    <p class="text-white/60 mb-8">Anda telah berhasil melengkapi semua perintah instalasi.</p>
                                    <button id="finishBtn" onclick="finishChapter()" class="px-8 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-cyan-600 text-white font-bold shadow-lg shadow-purple-900/40 hover:scale-105 transition-transform">Selesaikan Bab 1.6 →</button>
                                </div>

                            </div>
                        </div>
                    </section>

                </article>

                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center">
                    <a href="{{ route('courses.advantages') }}" class="group flex items-center gap-3 text-slate-400 hover:text-white transition">
                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-white/30 transition">←</div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest opacity-50">Sebelumnya</div>
                            <div class="font-bold text-sm">Keunggulan</div>
                        </div>
                    </a>

                    <div id="nextChapterBtn" class="group flex items-center gap-3 text-right text-slate-500 cursor-not-allowed">
                        <div class="text-right">
                            <div class="text-[10px] uppercase tracking-widest opacity-30">Terkunci</div>
                            <div class="font-bold text-sm">Evaluasi Bab 1</div>
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
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }

    /* Background Gradient Ungu/Pink/Cyan */
    #animated-bg{ background: radial-gradient(600px circle at 20% 20%, rgba(168,85,247,.15), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(217,70,239,.15), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(34,211,238,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    
    /* Sidebar Active State Ungu */
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
    window.SUBBAB_LESSON_IDS = {!! json_encode($subbab16LessonIds ?? []) !!}; 
    window.INIT_COMPLETED_LESSONS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedLessons = new Set(window.INIT_COMPLETED_LESSONS);
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    const QUIZ_LESSON_ID = 28; // ID untuk Aktivitas 1.6

    document.addEventListener('DOMContentLoaded', () => {
        updateProgressUI();
        initLessonObserver();
        initVisualEffects();
        initSidebarScroll();
        if (activityCompleted) disableQuizUI();
    });

    /* --- TERMINAL SIMULATOR --- */
    function runTerm(step) {
        const output = document.getElementById('terminal-output');
        const nextBtn = document.getElementById('btn-step-' + (step + 1));
        const currBtn = document.getElementById('btn-step-' + step);

        // Simulated Response
        let cmdText = '';
        let response = '';
        
        if (step === 1) {
            cmdText = 'node -v && npm -v';
            response = `v18.16.0<br>9.5.1 <span class="text-green-400">(OK)</span>`;
        } else if (step === 2) {
            cmdText = 'npm install -D tailwindcss';
            response = `<span class="text-purple-400">added 84 packages</span> in 2s`;
        } else if (step === 3) {
            cmdText = 'npx tailwindcss init';
            response = `Created Tailwind CSS config file: <span class="text-cyan-300">tailwind.config.js</span>`;
        }

        output.innerHTML += `
            <div class="mb-1 mt-3"><span class="text-purple-400">➜</span> <span class="text-cyan-300">~/project</span> ${cmdText}</div>
            <div class="text-gray-400 mb-2 pl-4 border-l border-white/10">${response}</div>
        `;

        // Update Buttons
        currBtn.disabled = true;
        currBtn.classList.add('opacity-50', 'cursor-not-allowed');
        currBtn.querySelector('span').innerText += ' ✔';
        
        if (nextBtn) {
            nextBtn.disabled = false;
            nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            nextBtn.classList.add('bg-white/10');
        }

        // Scroll Down
        const termBody = document.getElementById('terminal-body');
        termBody.scrollTop = termBody.scrollHeight;
    }

    /* --- QUIZ LOGIC (4 Steps - Fill in the blank) --- */
    let currentStep = 1;
    function checkAnswer(step, isCorrect) {
        if(activityCompleted) return;
        
        const card = document.getElementById('q' + step);
        const nextCard = document.getElementById(step === 4 ? 'quiz-success' : 'q' + (step + 1));
        
        if (!isCorrect) {
            // Visual Shake & Red Flash
            card.classList.add('shake', 'border-red-500/50');
            setTimeout(() => card.classList.remove('shake', 'border-red-500/50'), 500);
            return;
        }

        // Correct feedback
        card.classList.add('border-green-500/50');

        setTimeout(() => {
            // Advance Step
            currentStep++;
            
            // Slide Animation
            card.classList.add('-translate-x-[120%]', 'opacity-0', 'pointer-events-none');
            nextCard.classList.remove('translate-x-[120%]', 'opacity-0', 'pointer-events-none');
            
            // Update Progress
            if (step < 4) {
                document.getElementById('quiz-progress').style.width = ((step + 1) / 4 * 100) + '%';
                document.getElementById('question-counter').innerText = (step + 1) + '/4';
            } else {
                // Finish
                document.getElementById('quiz-progress').parentElement.classList.add('opacity-0');
                document.getElementById('question-counter').classList.add('opacity-0');
                document.getElementById('success-icon').classList.remove('scale-0');
            }
        }, 600);
    }

    async function finishChapter() {
        const btn = document.getElementById('finishBtn');
        btn.innerHTML = "Menyimpan...";
        btn.disabled = true;
        try {
            // Save Activity ID 6
            await fetch('/activity/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify({ activity_id: 6, score: 100 }) });
            // Save Lesson ID 28
            await saveLessonToDB(QUIZ_LESSON_ID);
            completedLessons.add(QUIZ_LESSON_ID);
            
            activityCompleted = true;
            updateProgressUI();
            btn.innerHTML = "Tersimpan ✔";
            btn.classList.add('bg-purple-600', 'cursor-default');
            unlockNext();
        } catch(e) { console.error(e); btn.innerText = "Gagal. Coba lagi."; btn.disabled = false; }
    }

    function disableQuizUI() {
        document.getElementById('q1').classList.add('hidden');
        document.getElementById('quiz-success').classList.remove('translate-x-[120%]', 'opacity-0', 'pointer-events-none');
        document.getElementById('success-icon').classList.remove('scale-0');
        document.getElementById('quiz-progress').parentElement.classList.add('hidden');
        document.getElementById('question-counter').classList.add('hidden');
        const btn = document.getElementById('finishBtn');
        btn.innerHTML = "Bab Selesai ✔";
        btn.classList.add('bg-purple-600', 'cursor-default');
        btn.onclick = null;
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
    // 1. Update Ikon di Sidebar (Menjadi Centang)
    const icon = document.querySelector('.sb-group.open .icon-status');
    if(icon) { 
        icon.innerHTML = '✔'; 
        icon.className = 'icon-status w-6 h-6 rounded-lg border flex items-center justify-center transition-colors bg-fuchsia-500/20 text-fuchsia-400 border-fuchsia-500/20'; 
    }

    // 2. Update Tombol "Selanjutnya" di Footer
    const btn = document.getElementById('nextChapterBtn');
    if(btn) {
        // Ubah Style agar terlihat aktif
        btn.className = "group flex items-center gap-3 text-right text-fuchsia-400 hover:text-fuchsia-300 transition cursor-pointer";
        
        // Ubah Teks & Ikon
        btn.innerHTML = `
            <div class="text-right">
                <div class="text-[10px] uppercase tracking-widest opacity-50">Langkah Berikutnya</div>
                <div class="font-bold text-sm">Evaluasi Bab 1</div>
            </div>
            <div class="w-10 h-10 rounded-full border border-fuchsia-500/30 bg-fuchsia-500/10 flex items-center justify-center group-hover:bg-fuchsia-500/20 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        `;

        // 3. LOGIKA ROUTE KE KUIS BAB 1
        btn.onclick = () => {
            window.location.href = "{{ route('quiz.intro', ['chapterId' => '1']) }}";
        };
        
        // Hapus atribut disabled jika ada
        btn.removeAttribute('disabled');
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