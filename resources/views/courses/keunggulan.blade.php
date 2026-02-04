@extends('layouts.landing')
@section('title','Bab 1.5 · Keunggulan Tailwind CSS')

@section('content')
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-cyan-500/30">

    {{-- Background Effects --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-20"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[1200px] h-[1200px] bg-cyan-900/20 rounded-full blur-[150px] animate-pulse"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[1000px] h-[1000px] bg-indigo-900/20 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03]"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
    </div>

    {{-- Navbar --}}
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
            
            {{-- Sticky Header with PROGRESS BAR --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-[#020617]/90 backdrop-blur-2xl border-b border-white/5 px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cyan-500/20 to-transparent border border-cyan-500/20 flex items-center justify-center font-bold text-xs text-cyan-400">1.5</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Keunggulan Tailwind</h1>
                        <p class="text-[10px] text-white/50">Speed, Consistency & Performance</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-40 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-cyan-500 to-blue-500 w-0 transition-all duration-500 shadow-[0_0_10px_#22d3ee]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-cyan-400 font-bold text-xs">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                {{-- Tujuan Pembelajaran --}}
                <div class="mb-24">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Tujuan Pembelajaran
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-[#151515] border border-white/5 p-6 rounded-xl flex items-start gap-4 hover:border-purple-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-purple-900/20 text-purple-400 flex items-center justify-center shrink-0 font-bold text-lg border border-purple-500/10">1</div>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-2 group-hover:text-purple-400 transition">Rapid Development</h4>
                                <p class="text-xs text-white/50 leading-relaxed">
                                    Memahami bagaimana <em>Utility-First</em> mempercepat pembuatan UI tanpa perlu menulis CSS custom.
                                </p>
                            </div>
                        </div>

                        <div class="bg-[#151515] border border-white/5 p-6 rounded-xl flex items-start gap-4 hover:border-cyan-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-cyan-900/20 text-cyan-400 flex items-center justify-center shrink-0 font-bold text-lg border border-cyan-500/10">2</div>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-2 group-hover:text-cyan-400 transition">Konsistensi Desain</h4>
                                <p class="text-xs text-white/50 leading-relaxed">
                                    Menghindari nilai acak (Magic Numbers) dengan menggunakan skala desain yang terstandarisasi.
                                </p>
                            </div>
                        </div>

                        <div class="bg-[#151515] border border-white/5 p-6 rounded-xl flex items-start gap-4 hover:border-emerald-500/30 transition group h-full">
                            <div class="w-10 h-10 rounded-lg bg-emerald-900/20 text-emerald-400 flex items-center justify-center shrink-0 font-bold text-lg border border-emerald-500/10">3</div>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-2 group-hover:text-emerald-400 transition">Optimasi Performa</h4>
                                <p class="text-xs text-white/50 leading-relaxed">
                                    Mempelajari bagaimana Tailwind menghasilkan file CSS produksi yang sangat kecil (Tiny Bundle).
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 1.5.1 --}}
                    <section id="section-1" class="lesson-section scroll-mt-32" data-lesson-id="20">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-6">
                                <span class="text-purple-400 font-mono text-xs uppercase tracking-widest">Lesson 1.5.1</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Kecepatan: <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-500">Rapid Prototyping</span>
                                </h2>
                            </div>
                            
                            {{-- MATERI A --}}
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-purple-600 flex items-center justify-center text-[10px] text-white">A</span> Styling Tanpa Jeda</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Buku <em>"Modern CSS with Tailwind"</em> menjelaskan bahwa hambatan terbesar dalam CSS tradisional adalah <strong>Context Switching</strong>. Anda harus terus-menerus berpindah antara file HTML (struktur) dan CSS (gaya).
                                    </p>
                                    <p>
                                        Dengan Tailwind, Anda membawa gaya langsung ke markup. Ini memungkinkan Anda membangun antarmuka yang kompleks dengan sangat cepat karena Anda tidak perlu berhenti untuk memikirkan nama class abstrak seperti <code>.sidebar-inner-wrapper</code> hanya untuk memberi padding.
                                    </p>
                                </div>
                            </div>

                            {{-- MATERI B --}}
                            <div class="space-y-4 pt-6">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-purple-600 flex items-center justify-center text-[10px] text-white">B</span> Iterasi Lebih Cepat</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Dalam <em>"Ultimate Tailwind CSS Handbook"</em>, disebutkan bahwa Tailwind menghilangkan rasa takut untuk mengubah desain. Pada CSS tradisional, menghapus kode CSS seringkali berisiko merusak halaman lain yang menggunakan class yang sama.
                                    </p>
                                    <p>
                                        Di Tailwind, gaya bersifat lokal pada elemen HTML. Anda bisa mengubah warna tombol di satu halaman tanpa khawatir tombol di halaman lain ikut berubah secara tidak sengaja. Ini memberikan kepercayaan diri penuh saat melakukan <em>refactoring</em>.
                                    </p>
                                </div>
                            </div>

                            {{-- SIMULATOR 1: FRICTION TEST --}}
                            <div class="bg-[#151515] p-8 rounded-2xl border border-white/10 relative shadow-2xl overflow-hidden mt-8">
                                <h4 class="text-xs font-bold text-white/50 uppercase mb-8 text-center relative z-10">Simulasi: Kecepatan Workflow (Manual vs Utility)</h4>
                                
                                <div class="grid md:grid-cols-2 gap-10 relative z-10">
                                    {{-- Kiri: Tradisional --}}
                                    <div class="space-y-4 bg-red-900/5 p-6 rounded-xl border border-red-500/10 hover:border-red-500/30 transition">
                                        <div class="flex justify-between text-xs text-red-400 font-bold mb-2">TRADISIONAL (BUAT CLASS)</div>
                                        <p class="text-[10px] text-gray-400">Anda harus berpikir nama class yang semantik dan unik:</p>
                                        
                                        <input type="text" placeholder="Ketik nama class yang panjang..." 
                                            class="w-full bg-black/50 border border-white/10 rounded px-3 py-2 text-xs text-white focus:border-red-500 outline-none transition"
                                            onkeyup="sim1UpdateMeter(this.value)">
                                        
                                        <div class="relative w-full h-2 bg-gray-800 rounded-full overflow-hidden">
                                            <div id="sim1-meter" class="absolute top-0 left-0 h-full bg-red-500 w-0 transition-all duration-200"></div>
                                        </div>
                                        <p id="sim1-text" class="text-[10px] text-red-300 h-4">Waktu Terbuang: 0%</p>
                                    </div>

                                    {{-- Kanan: Tailwind --}}
                                    <div class="space-y-4 bg-emerald-900/5 p-6 rounded-xl border border-emerald-500/10 hover:border-emerald-500/30 transition">
                                        <div class="flex justify-between text-xs text-emerald-400 font-bold mb-2">TAILWIND (GUNAKAN UTILITY)</div>
                                        <p class="text-[10px] text-gray-400">Langsung terapkan gaya tanpa berpikir nama:</p>
                                        
                                        <div class="flex gap-2 flex-wrap">
                                            <button onclick="sim1Tw(this, 'bg-blue-600')" class="px-3 py-1 bg-white/10 rounded text-[10px] hover:bg-emerald-500 hover:text-black transition border border-white/10">Warna Biru</button>
                                            <button onclick="sim1Tw(this, 'p-4')" class="px-3 py-1 bg-white/10 rounded text-[10px] hover:bg-emerald-500 hover:text-black transition border border-white/10">Padding</button>
                                            <button onclick="sim1Tw(this, 'rounded-xl')" class="px-3 py-1 bg-white/10 rounded text-[10px] hover:bg-emerald-500 hover:text-black transition border border-white/10">Sudut Tumpul</button>
                                        </div>
                                        
                                        {{-- Visual Result --}}
                                        <div id="sim1-box" class="w-full h-12 border border-dashed border-white/20 flex items-center justify-center text-[10px] text-gray-500 transition-all duration-300 mt-2 rounded">
                                            Preview Hasil
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 1.5.2 --}}
                    <section id="section-2" class="lesson-section scroll-mt-32" data-lesson-id="21">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                                <span class="text-cyan-400 font-mono text-xs uppercase tracking-widest">Lesson 1.5.2</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Konsistensi: <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Constraint-Based System</span>
                                </h2>
                            </div>

                            {{-- MATERI A --}}
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-cyan-600 flex items-center justify-center text-[10px] text-white">A</span> Menghindari Magic Numbers</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Salah satu keuntungan terbesar yang disebut dalam <em>"Tailwind CSS by SitePoint"</em> adalah standarisasi. Dalam CSS biasa, developer bebas memasukkan nilai apa saja, seperti <code>margin: 13px</code> atau <code>color: #4a3b2c</code>, yang menyebabkan desain tidak konsisten ("Magic Numbers").
                                    </p>
                                    <p>
                                        Tailwind bekerja dengan sistem <strong>Constraint-Based</strong>. Anda dibatasi pada skala yang telah ditentukan (seperti <code>m-4</code> untuk 1rem, atau <code>text-gray-500</code>). Batasan ini justru membebaskan Anda dari kebingungan memilih nilai piksel dan menjamin UI yang rapi.
                                    </p>
                                </div>
                            </div>

                            {{-- MATERI B --}}
                            <div class="space-y-4 pt-6">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-cyan-600 flex items-center justify-center text-[10px] text-white">B</span> Responsif & State Variants</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Mitos bahwa Tailwind sama dengan "Inline Styles" dipatahkan oleh kemampuannya menangani <em>state</em>. Inline style standar tidak bisa melakukan media query atau hover state.
                                    </p>
                                    <p>
                                        Tailwind menyediakan modifier seperti <code>hover:</code>, <code>focus:</code>, dan <code>lg:</code>. Anda bisa membuat desain yang sepenuhnya responsif dan interaktif langsung di HTML tanpa perlu menulis satu baris pun <code>@media</code> query yang rumit di file CSS terpisah.
                                    </p>
                                </div>
                            </div>

                            {{-- SIMULATOR 2: DESIGN VALIDATOR --}}
                            <div class="bg-[#151515] border border-white/10 rounded-xl overflow-hidden shadow-2xl p-8 relative mt-8">
                                <h4 class="text-xs font-bold text-white/50 uppercase mb-6 text-center">Simulator: Penjaga Konsistensi (Design System)</h4>
                                <div class="flex flex-col items-center gap-6 relative z-10">
                                    <div class="w-full max-w-md relative">
                                        <p class="text-[10px] text-gray-400 mb-2 text-center">Coba ketik nilai acak (cth: 17px) vs Utility Tailwind (cth: p-4)</p>
                                        <div class="relative group">
                                            <input type="text" id="sim2-input" placeholder="Ketik nilai styling..." 
                                                class="w-full bg-black/50 border border-white/20 rounded-lg py-3 px-12 text-white font-mono text-center focus:border-cyan-500 outline-none transition uppercase"
                                                onkeyup="checkDesignPolice(this.value)">
                                            <div id="sim2-icon" class="absolute right-4 top-3 text-xl transition-all opacity-50">🛡️</div>
                                        </div>
                                    </div>
                                    
                                    <div id="sim2-status" class="h-10 flex items-center justify-center text-xs text-gray-500 font-mono bg-white/5 w-full max-w-md rounded border border-dashed border-white/10">
                                        [STATUS: MENUNGGU INPUT...]
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 1.5.3 --}}
                    <section id="section-3" class="lesson-section scroll-mt-32" data-lesson-id="22">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-emerald-500 pl-6">
                                <span class="text-emerald-400 font-mono text-xs uppercase tracking-widest">Lesson 1.5.3</span>
                                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                    Performa: <br> 
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-500">Tiny Production Files</span>
                                </h2>
                            </div>

                            {{-- MATERI A --}}
                            <div class="space-y-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-emerald-600 flex items-center justify-center text-[10px] text-white">A</span> Masalah "Append-Only"</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Dalam pengembangan website tradisional, file CSS cenderung tumbuh secara linear seiring bertambahnya fitur. Setiap halaman baru berarti baris CSS baru. Developer takut menghapus CSS lama ("Append-Only"), membuat file membengkak hingga ratusan kilobyte.
                                    </p>
                                </div>
                            </div>
                            
                            {{-- MATERI B --}}
                            <div class="space-y-4 pt-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-emerald-600 flex items-center justify-center text-[10px] text-white">B</span> Solusi Purge & JIT</h3>
                                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                                    <p>
                                        Tailwind memecahkan masalah ini dengan konsep utilitas yang dapat digunakan kembali. Begitu Anda memiliki class <code>flex</code>, <code>pt-4</code>, dan <code>text-center</code>, Anda bisa menggunakannya di jutaan tempat tanpa menambah ukuran file CSS sedikitpun.
                                    </p>
                                    <p>
                                        Ditambah dengan fitur <strong>Purge/Tree-Shaking</strong>, Tailwind secara otomatis membuang semua class yang tidak Anda pakai saat build produksi. Hasilnya? File CSS yang sangat kecil (seringkali di bawah 10kB) meskipun situs Anda sangat besar.
                                    </p>
                                </div>
                            </div>

                            {{-- SIMULATOR 3: BLOAT MONITOR --}}
                            <div class="bg-[#151515] p-8 rounded-2xl border border-white/10 relative shadow-2xl overflow-hidden mt-8">
                                <h4 class="text-xs font-bold text-white/50 uppercase mb-8 text-center relative z-10">Simulasi: Grafik Pertumbuhan Ukuran File CSS</h4>
                                
                                <div class="space-y-8 relative z-10">
                                    <div>
                                        <div class="flex justify-between text-xs text-white/50 mb-2 font-mono">
                                            <span>Traditional CSS (Terus Tumbuh Linear)</span>
                                            <span id="txt-trad" class="text-red-400 font-bold">50 KB</span>
                                        </div>
                                        <div class="w-full bg-white/5 rounded-full h-4 overflow-hidden border border-white/5">
                                            <div id="bar-trad" class="bg-gradient-to-r from-red-600 to-red-400 h-full rounded-full w-[10%] transition-all duration-300 relative"></div>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="flex justify-between text-xs text-white/50 mb-2 font-mono">
                                            <span>Tailwind CSS (Stabil / Plateau)</span>
                                            <span id="txt-tw" class="text-emerald-400 font-bold">4.0 KB</span>
                                        </div>
                                        <div class="w-full bg-white/5 rounded-full h-4 overflow-hidden border border-white/5">
                                            <div id="bar-tw" class="bg-gradient-to-r from-emerald-600 to-emerald-400 h-full rounded-full w-[2%] shadow-[0_0_15px_#10b981] transition-all duration-300 relative"></div>
                                        </div>
                                        <p class="text-[10px] text-emerald-400/60 mt-2 italic">*Ukuran file berhenti tumbuh karena reuse utility class.</p>
                                    </div>
                                </div>

                                <div class="mt-10 flex justify-center relative z-10">
                                    <button onclick="addFeature()" id="add-feature-btn" class="px-8 py-3 bg-white/5 border border-white/10 rounded-xl text-xs font-bold hover:bg-white/10 transition text-white hover:scale-105 hover:border-emerald-500/30 flex items-center gap-2 group cursor-pointer active:scale-95 select-none">
                                        <span class="w-4 h-4 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-[10px] group-hover:bg-emerald-500 group-hover:text-black transition">+</span>
                                        TAMBAH 10 HALAMAN BARU
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- ACTIVITY SECTION: LIVE REFACTOR CHALLENGE --}}
                    {{-- PENTING: data-type="activity" agar tidak auto-complete saat scroll --}}
                    <section id="section-4" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="23" data-type="activity">
                        <div class="relative rounded-[2.5rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl group hover:border-cyan-500/30 transition-all duration-500">
                            
                            {{-- Activity Header --}}
                            <div class="flex flex-col md:flex-row items-start md:items-center gap-6 mb-8 relative z-10">
                                <div class="p-4 bg-gradient-to-br from-cyan-600 to-blue-800 rounded-2xl text-white shadow-lg shadow-cyan-500/20 shrink-0">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-3 mb-1">
                                        <h2 class="text-3xl font-black text-white tracking-tight">Final Mission: The Refactor</h2>
                                        <span id="status-badge" class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 uppercase tracking-wider">Live Code</span>
                                    </div>
                                    <p class="text-cyan-200/60 text-sm leading-relaxed max-w-2xl">
                                        <strong>Tantangan:</strong> Rasakan kecepatan development dengan mengubah kode CSS konvensional di bawah ini menjadi Utility Tailwind.
                                        Ketik class di editor untuk melihat perubahan <strong>Real-Time</strong>.
                                    </p>
                                </div>
                            </div>

                            {{-- The Live Editor Interface --}}
                            <div class="grid lg:grid-cols-2 gap-8 border-t border-white/5 pt-8">
                                
                                {{-- Left: Target & Preview --}}
                                <div class="space-y-6">
                                    {{-- Soal --}}
                                    <div class="p-4 bg-red-900/10 border border-red-500/20 rounded-xl relative overflow-hidden">
                                        <div class="absolute top-2 right-2 text-[8px] font-bold bg-red-500 text-black px-1 rounded">LEGACY CODE</div>
                                        <p class="text-[10px] text-red-400 font-bold mb-2 uppercase">UBAH KODE INI:</p>
                                        <code class="text-xs text-red-200 font-mono block bg-black/30 p-3 rounded leading-relaxed">
                                            style="background-color: blue; <br>
                                            padding: 1rem; <br>
                                            color: white; <br>
                                            border-radius: 0.5rem;"
                                        </code>
                                    </div>
                                    
                                    {{-- Live Preview Box (Hasil Render) --}}
                                    <div class="p-8 bg-[#1e1e1e] rounded-xl border border-white/10 flex flex-col items-center justify-center min-h-[200px] relative transition-all" id="preview-container">
                                        <p class="absolute top-3 left-3 text-[10px] text-gray-500 font-mono">LIVE PREVIEW</p>
                                        
                                        {{-- Tombol yang akan berubah --}}
                                        <button id="live-btn" class="transition-all duration-300 font-sans border border-dashed border-white/20 text-gray-500 p-2">
                                            Button Saya
                                        </button>

                                        {{-- Hint text di bawah --}}
                                        <p id="feedback-text" class="absolute bottom-3 text-[10px] text-gray-500 bg-black/50 px-2 py-1 rounded">
                                            Menunggu input...
                                        </p>
                                    </div>
                                </div>

                                {{-- Right: Editor Input --}}
                                <div class="space-y-4 flex flex-col">
                                    <div class="relative flex-1">
                                        <div class="absolute top-0 left-0 bg-cyan-900/20 text-cyan-400 text-[10px] px-3 py-1 rounded-br border-r border-b border-cyan-500/20 font-bold z-10">CODE EDITOR</div>
                                        
                                        <div class="w-full h-full bg-[#0f1117] rounded-xl border border-white/10 p-6 pt-10 font-mono text-sm relative group focus-within:border-cyan-500/50 transition">
                                            <span class="text-gray-500 select-none">&lt;button class="</span>
                                            
                                            {{-- INPUT UTAMA --}}
                                            <input type="text" id="code-input" 
                                                class="bg-transparent border-none outline-none text-white w-full placeholder-gray-700 font-bold" 
                                                placeholder="ketik class disini... (cth: bg-blue-500)" 
                                                autocomplete="off" spellcheck="false"
                                                onkeyup="handleInput(this.value)">
                                            
                                            <span class="text-gray-500 select-none">"&gt;Button Saya&lt;/button&gt;</span>
                                        </div>
                                    </div>
                                    
                                    {{-- Live Checklist Feedback --}}
                                    <div class="grid grid-cols-2 gap-2 text-[10px] text-gray-500">
                                        <div id="req-bg" class="flex items-center gap-1 transition-colors">
                                            <span class="w-2 h-2 rounded-full bg-gray-700"></span> Background Biru
                                        </div>
                                        <div id="req-p" class="flex items-center gap-1 transition-colors">
                                            <span class="w-2 h-2 rounded-full bg-gray-700"></span> Padding (p-4)
                                        </div>
                                        <div id="req-text" class="flex items-center gap-1 transition-colors">
                                            <span class="w-2 h-2 rounded-full bg-gray-700"></span> Teks Putih
                                        </div>
                                        <div id="req-round" class="flex items-center gap-1 transition-colors">
                                            <span class="w-2 h-2 rounded-full bg-gray-700"></span> Rounded (lg/md)
                                        </div>
                                    </div>

                                    <button id="submit-code" onclick="validateFinal()" class="w-full py-4 bg-white/5 text-gray-500 font-bold rounded-xl transition-all text-xs border border-white/5 flex items-center justify-center gap-2 cursor-not-allowed">
                                        <span>🔒</span> SELESAIKAN TANTANGAN
                                    </button>
                                    <div id="error-msg" class="text-center text-xs text-red-400 font-mono h-4 opacity-0 transition">Error message here</div>
                                </div>

                            </div>
                        </div>
                    </section>

                </article>

                {{-- Footer Navigation --}}
                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center">
                    <a href="{{ route('courses.implementation') }}" class="group flex items-center gap-3 text-slate-400 hover:text-white transition">
                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-white/30 transition">←</div>
                        <div class="text-left"><div class="text-[10px] uppercase tracking-widest opacity-50">Sebelumnya</div><div class="font-bold text-sm">Implementasi</div></div>
                    </a>
                    
                    {{-- Tombol Lanjut (Default Locked) --}}
                    <div id="nextChapterBtn" class="group flex items-center gap-3 text-right text-slate-500 cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500">
                        <div class="text-right">
                            <div id="nextLabel" class="text-[10px] uppercase tracking-widest opacity-50">Terkunci</div>
                            <div class="font-bold text-sm">Instalasi dan Konfigurasi</div>
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
    .nav-link.active::after { content: ''; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: linear-gradient(to right,#22d3ee,#3b82f6); box-shadow: 0 0 12px rgba(34,211,238,.8); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    #animated-bg{ background: radial-gradient(800px circle at 20% 20%, rgba(34,211,238,.15), transparent 40%), radial-gradient(800px circle at 80% 80%, rgba(59,130,246,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    .nav-item.active { color: #67e8f9; background: rgba(34,211,238,0.1); font-weight: 600; }
    .nav-item.active .dot { background: #22d3ee; box-shadow: 0 0 8px #22d3ee; transform: scale(1.2); }
    
    /* Code Font */
    #code-input { font-family: 'Fira Code', monospace; caret-color: #22d3ee; }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    /* --- CONFIGURATION --- */
    window.LESSON_IDS = [20, 21, 22, 23]; // Lesson 1, 2, 3 + Activity
    window.COMPLETED_IDS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedSet = new Set(window.COMPLETED_IDS);
    
    // Status Aktivitas (Mapping ID 5 = Activity Bab 1.5)
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};
    const ACTIVITY_ID = 5; 
    const ACTIVITY_LESSON_ID = 23; 

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initLessonObserver();
        initSidebarScroll();
        initVisualEffects();
        
        // --- LOGIKA UTAMA: Cek Status Terkini ---
        if (activityCompleted) { 
            completedSet.add(ACTIVITY_LESSON_ID); // Pastikan progress 100%
            lockActivityUI(); // Arsipkan
        } else {
            lockNextChapter(); // Kunci jika belum selesai
            if(completedSet.has(ACTIVITY_LESSON_ID)) {
                completedSet.delete(ACTIVITY_LESSON_ID);
            }
        }
        
        updateProgressUI(); 
    });

    /* --- LOGIC 1: PROGRESS & UNLOCKING --- */
    function updateProgressUI() {
        const total = window.LESSON_IDS.length; 
        const done = window.LESSON_IDS.filter(id => completedSet.has(id)).length; 
        const percent = Math.round((done / total) * 100);
        
        document.getElementById('topProgressBar').style.width = percent + '%'; 
        document.getElementById('progressLabelTop').innerText = percent + '%';
        
        if(percent === 100) {
            unlockNextChapter();
        } else {
            lockNextChapter();
        }
    }

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        const nextLabel = document.getElementById('nextLabel');
        const nextIcon = document.getElementById('nextIcon');

        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-slate-500');
            btn.classList.add('text-cyan-400', 'hover:text-cyan-300', 'cursor-pointer');
            
            if(nextLabel) { nextLabel.innerText = "Selanjutnya"; nextLabel.classList.remove('opacity-50'); }
            if(nextIcon) { nextIcon.innerHTML = "→"; nextIcon.classList.remove('bg-white/5'); nextIcon.classList.add('bg-cyan-500/20', 'border-cyan-500/50'); }
            
            btn.onclick = () => window.location.href = "{{ route('courses.installation') }}"; 
        }
    }

    function lockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.add('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-slate-500');
            btn.classList.remove('text-cyan-400', 'hover:text-cyan-300', 'cursor-pointer');
            btn.onclick = null;
        }
    }

    /* --- SIM 1: NAMING FATIGUE (METER) --- */
    function sim1UpdateMeter(val) {
        const meter = document.getElementById('sim1-meter');
        const text = document.getElementById('sim1-text');
        
        // Semakin panjang nama, semakin penuh meternya
        let percentage = Math.min(val.length * 5, 100);
        
        meter.style.width = percentage + '%';
        
        if(percentage < 30) {
            text.innerText = "Waktu Terbuang: Sedikit";
            meter.className = "absolute top-0 left-0 h-full bg-emerald-500 w-0 transition-all duration-200";
        } else if (percentage < 70) {
            text.innerText = "Waktu Terbuang: Sedang (Mikir Nama...)";
            meter.className = "absolute top-0 left-0 h-full bg-yellow-500 w-0 transition-all duration-200";
        } else {
            text.innerText = "⚠️ PROSES LAMBAT! Naming Fatigue.";
            meter.className = "absolute top-0 left-0 h-full bg-red-600 w-0 transition-all duration-200 animate-pulse";
        }
    }

    function sim1Tw(btn, className) {
        // Reset
        btn.parentElement.querySelectorAll('button').forEach(b => {
            b.classList.remove('bg-emerald-500', 'text-black');
            b.classList.add('bg-white/10');
        });
        
        // Active state
        btn.classList.remove('bg-white/10');
        btn.classList.add('bg-emerald-500', 'text-black');

        // Apply to box
        const box = document.getElementById('sim1-box');
        if(className === 'bg-blue-600') {
            box.className = "w-full h-12 bg-blue-600 flex items-center justify-center text-xs text-white font-bold transition-all duration-300 mt-2 rounded";
            box.innerText = "Background Applied";
        } else if(className === 'p-4') {
            box.className = "w-full h-auto p-4 border border-dashed border-white/20 flex items-center justify-center text-xs text-gray-400 transition-all duration-300 mt-2 rounded";
            box.innerText = "Padding Applied";
        } else if(className === 'rounded-xl') {
            box.className = "w-full h-12 border border-dashed border-white/20 flex items-center justify-center text-xs text-gray-400 transition-all duration-300 mt-2 rounded-xl";
            box.innerText = "Border Radius Applied";
        }
    }

    /* --- SIM 2: DESIGN POLICE --- */
    function checkDesignPolice(val) {
        const status = document.getElementById('sim2-status');
        const icon = document.getElementById('sim2-icon');
        const input = document.getElementById('sim2-input');

        if(val.length === 0) {
            status.innerText = "[SYSTEM: MENUNGGU INPUT...]";
            status.className = "h-12 flex items-center justify-center text-xs text-gray-500 font-mono bg-white/5 w-full max-w-md rounded-lg border border-dashed border-white/10 transition-all duration-300";
            return;
        }

        // Logic check: Magic number vs Utility
        if(/\d+px/.test(val)) {
            status.innerText = "⛔ MAGIC NUMBER: TIDAK KONSISTEN!";
            status.className = "h-12 flex items-center justify-center text-xs text-red-400 font-bold bg-red-900/20 w-full max-w-md rounded-lg border border-red-500/50 animate-pulse";
            icon.innerText = "⚠️";
            icon.className = "absolute right-4 top-4 text-2xl grayscale-0 transition-all animate-bounce";
            input.classList.add('border-red-500');
        } else if (/^[a-z]+-\d+$/.test(val)) {
            status.innerText = "✅ VALID: SESUAI DESIGN SYSTEM";
            status.className = "h-12 flex items-center justify-center text-xs text-emerald-400 font-bold bg-emerald-900/20 w-full max-w-md rounded-lg border border-emerald-500/50";
            icon.innerText = "🛡️";
            icon.className = "absolute right-4 top-4 text-2xl grayscale-0 transition-all";
            input.classList.remove('border-red-500');
            input.classList.add('border-emerald-500');
        } else {
            status.innerText = "⏳ ANALYZING...";
            status.className = "h-12 flex items-center justify-center text-xs text-cyan-400 font-mono bg-cyan-900/10 w-full max-w-md rounded-lg border border-cyan-500/30";
            icon.innerText = "🔎";
        }
    }

    /* --- SIM 3: BLOAT CLICKER --- */
    let featureCount = 0;
    function addFeature() {
        featureCount++;
        const btn = document.getElementById('add-feature-btn');
        const barTrad = document.getElementById('bar-trad');
        const barTw = document.getElementById('bar-tw');
        const txtTrad = document.getElementById('txt-trad');
        const txtTw = document.getElementById('txt-tw');

        // Trad linear growth
        let tradW = 10 + (featureCount * 15);
        if(tradW > 100) tradW = 100;
        barTrad.style.width = tradW + '%';
        txtTrad.innerText = (50 + (featureCount * 25)) + ' KB';

        // Tailwind plateau
        let twW = 5;
        let twSize = 10;
        if(featureCount < 3) {
            twW += featureCount * 2;
            twSize += featureCount * 1;
        } else {
            twW = 12; // Plateau visal
            twSize = 14; // Plateau size
        }
        barTw.style.width = twW + '%';
        txtTw.innerText = twSize + ' KB';

        btn.innerHTML = `<span class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-[12px] mr-2 transition group-hover:bg-emerald-500 group-hover:text-black">+</span> TAMBAH FITUR (${featureCount})`;

        if(featureCount > 6) {
            featureCount = 0;
            setTimeout(() => {
                barTrad.style.width = '10%'; txtTrad.innerText = '50 KB';
                barTw.style.width = '5%'; txtTw.innerText = '10 KB';
                btn.innerHTML = `<span class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-[12px] mr-2 transition group-hover:bg-emerald-500 group-hover:text-black">+</span> SIMULASI ULANG`;
            }, 500);
        }
    }

    /* --- LOGIC 3: LIVE REFACTOR CHALLENGE (The Core) --- */
    function handleInput(val) {
        updateLivePreview(val);
        checkLiveValidation(val);
    }

    function updateLivePreview(code) {
        if(activityCompleted) return;

        const btn = document.getElementById('live-btn');
        const feedback = document.getElementById('feedback-text');
        
        // Reset base class
        btn.className = "transition-all duration-300 font-sans ";
        
        // Safety & Syntax check
        if(code.includes('style') || code.includes('script') || code.includes(':') || code.includes(';')) {
            feedback.innerText = "❌ Warning: Gunakan Utility Class (cth: bg-blue-500), bukan CSS!";
            feedback.className = "absolute bottom-3 text-[10px] text-red-400 font-bold bg-black/80 px-2 py-1 rounded";
            return;
        }

        // Apply class
        btn.className += code; 

        // Update feedback status visually
        if(code.length > 0) {
            feedback.innerText = "Rendering: " + code;
            feedback.className = "absolute bottom-3 text-[10px] text-cyan-400 animate-pulse bg-black/50 px-2 py-1 rounded";
        } else {
            feedback.innerText = "Menunggu input...";
            feedback.className = "absolute bottom-3 text-[10px] text-gray-500 bg-black/50 px-2 py-1 rounded";
            btn.className += "border border-dashed border-white/20 text-gray-500 p-2"; 
        }
    }

    function checkLiveValidation(val) {
        // Real-time checklist update
        const reqBg = document.getElementById('req-bg');
        const reqP = document.getElementById('req-p');
        const reqText = document.getElementById('req-text');
        const reqRound = document.getElementById('req-round');
        const submitBtn = document.getElementById('submit-code');

        let score = 0;

        // Check Background Blue
        if(val.includes('bg-blue-')) {
            reqBg.classList.add('text-emerald-400', 'font-bold');
            reqBg.querySelector('span').classList.add('bg-emerald-500');
            score++;
        } else {
            reqBg.classList.remove('text-emerald-400', 'font-bold');
            reqBg.querySelector('span').classList.remove('bg-emerald-500');
        }

        // Check Padding (p-4)
        if(val.includes('p-4')) {
            reqP.classList.add('text-emerald-400', 'font-bold');
            reqP.querySelector('span').classList.add('bg-emerald-500');
            score++;
        } else {
            reqP.classList.remove('text-emerald-400', 'font-bold');
            reqP.querySelector('span').classList.remove('bg-emerald-500');
        }

        // Check Text White
        if(val.includes('text-white')) {
            reqText.classList.add('text-emerald-400', 'font-bold');
            reqText.querySelector('span').classList.add('bg-emerald-500');
            score++;
        } else {
            reqText.classList.remove('text-emerald-400', 'font-bold');
            reqText.querySelector('span').classList.remove('bg-emerald-500');
        }

        // Check Rounded
        if(val.includes('rounded')) {
            reqRound.classList.add('text-emerald-400', 'font-bold');
            reqRound.querySelector('span').classList.add('bg-emerald-500');
            score++;
        } else {
            reqRound.classList.remove('text-emerald-400', 'font-bold');
            reqRound.querySelector('span').classList.remove('bg-emerald-500');
        }

        // Enable Submit if all correct
        if(score >= 4 && !/\d+px/.test(val)) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-white/5', 'text-gray-400', 'cursor-not-allowed', 'border-white/5');
            submitBtn.classList.add('bg-cyan-600', 'text-white', 'hover:bg-cyan-500', 'cursor-pointer', 'shadow-lg', 'shadow-cyan-500/20');
            submitBtn.innerHTML = "<span>🎉</span> KODE VALID - SELESAIKAN MISI";
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-white/5', 'text-gray-400', 'cursor-not-allowed', 'border-white/5');
            submitBtn.classList.remove('bg-cyan-600', 'text-white', 'hover:bg-cyan-500', 'cursor-pointer', 'shadow-lg', 'shadow-cyan-500/20');
            submitBtn.innerHTML = "<span>🔒</span> LENGKAPI SEMUA SYARAT";
        }
    }

    function validateFinal() {
        const input = document.getElementById('code-input').value;
        // Final safety check
        if(input.includes('bg-blue-') && input.includes('p-4') && input.includes('text-white') && input.includes('rounded')) {
            finishChapter();
        }
    }

    /* --- LOGIC 4: SAVE & LOCK --- */
    async function finishChapter() {
        try {
            await fetch('/activity/complete', { 
                method: 'POST', 
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }, 
                body: JSON.stringify({ activity_id: ACTIVITY_ID, score: 100 }) 
            });

            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            completedSet.add(ACTIVITY_LESSON_ID);
            activityCompleted = true; 
            
            updateProgressUI(); 
            lockActivityUI(); 
            
        } catch(e) { console.error(e); }
    }

    function lockActivityUI() {
        const badge = document.getElementById('status-badge');
        badge.innerText = "MISSION ACCOMPLISHED";
        badge.className = "px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 uppercase tracking-wider";

        const input = document.getElementById('code-input');
        input.value = "bg-blue-500 p-4 text-white rounded-lg (Selesai)";
        input.disabled = true;
        
        // Force visual update
        document.getElementById('live-btn').className = "bg-blue-500 p-4 text-white rounded-lg transition-all duration-300 font-sans";
        
        const btn = document.getElementById('submit-code');
        btn.innerText = "AKTIVITAS SUDAH SELESAI (TERARSIP)";
        btn.disabled = true;
        btn.className = "w-full py-4 bg-gray-800 text-gray-500 font-bold rounded-xl text-xs cursor-not-allowed border border-gray-700";
    }

    // Standard Effects
    function initVisualEffects(){const c=document.getElementById('stars'),x=c.getContext('2d');function r(){c.width=innerWidth;c.height=innerHeight}r();window.onresize=r;let s=[];for(let i=0;i<100;i++)s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.2,v:Math.random()*0.2+.1});(function a(){x.clearRect(0,0,c.width,c.height);x.fillStyle='rgba(255,255,255,.3)';s.forEach(t=>{x.beginPath();x.arc(t.x,t.y,t.r,0,6.28);x.fill();t.y+=t.v;if(t.y>c.height)t.y=0});requestAnimationFrame(a)})();}
    
    async function saveLessonToDB(id) { await fetch('/lesson/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: new URLSearchParams({ lesson_id: id }) }); }
    
    function initScrollSpy() {
        const mainScroll = document.getElementById('mainScroll'); const sections = document.querySelectorAll('.lesson-section'); const navLinks = document.querySelectorAll('.sidebar-nav-link');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = '#' + entry.target.id;
                    navLinks.forEach(link => {
                        const dot = link.querySelector('.dot-indicator'); if(!dot) return;
                        link.classList.remove('bg-white/5'); dot.classList.remove('bg-purple-500', 'shadow-[0_0_8px_#a855f7]', 'scale-125'); dot.classList.add('bg-slate-600');
                        if (link.dataset.target === id) { link.classList.add('bg-white/5'); dot.classList.remove('bg-slate-600'); dot.classList.add('bg-purple-500', 'shadow-[0_0_8px_#a855f7]', 'scale-125'); }
                    });
                }
            });
        }, { root: mainScroll, rootMargin: '-20% 0px -60% 0px', threshold: 0 });
        sections.forEach(section => observer.observe(section));
    }

    function initLessonObserver() {
        const obs = new IntersectionObserver(async entries => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    const id = Number(entry.target.dataset.lessonId);
                    const type = entry.target.dataset.type; 
                    if (id && type !== 'activity' && !completedSet.has(id)) {
                        try { await saveLessonToDB(id); completedSet.add(id); updateProgressUI(); } catch(e) {}
                    }
                }
            }
        }, { threshold: 0.5, root: document.getElementById('mainScroll') });
        document.querySelectorAll('.lesson-section').forEach(s => obs.observe(s));
    }

    function initSidebarScroll(){const m=document.getElementById('mainScroll');const l=document.querySelectorAll('.accordion-content .nav-item');m.addEventListener('scroll',()=>{let c='';document.querySelectorAll('.lesson-section').forEach(s=>{if(m.scrollTop>=s.offsetTop-250)c='#'+s.id;});l.forEach(k=>{k.classList.remove('active');if(k.getAttribute('data-target')===c)k.classList.add('active')})});l.forEach(k=>k.addEventListener('click',(e)=>{const t=document.querySelector(k.getAttribute('data-target'));if(t)m.scrollTo({top:t.offsetTop-120,behavior:'smooth'})}));}
</script>
@endsection