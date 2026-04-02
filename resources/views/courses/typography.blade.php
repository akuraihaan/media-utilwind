@extends('layouts.landing')
@section('title', 'Tipografi Masterclass')

@section('content')

{{-- KONFIGURASI TEMA AWAL --}}
<script>
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>

<style>
    /* KONFIGURASI TEMA ADAPTIF */
    :root { 
        --bg-main: #f8fafc;
        --text-main: #0f172a;
        --glass-bg: rgba(255, 255, 255, 0.85); 
        --glass-border: rgba(0, 0, 0, 0.05);
        --glass-header: rgba(255, 255, 255, 0.85);
        --card-bg: #ffffff;
        --border-color: rgba(0, 0, 0, 0.1);
        --text-muted: #64748b;
        --text-heading: #0f172a;
        --code-bg: #f1f5f9;
        --simulator-bg: #ffffff;
        --accent: #6366f1;
        
        --sb-bg: rgba(255, 255, 255, 0.95);
        --sb-border: rgba(0, 0, 0, 0.05);
        --sb-text: #0f172a;
        --sb-muted: #64748b;
        --sb-hover: rgba(0, 0, 0, 0.03);
        --sb-active-bg: rgba(99, 102, 241, 0.1);
        --sb-active-border: rgba(99, 102, 241, 0.3);
        --sb-active-text: #6366f1;
    }

    .dark {
        --bg-main: #020617;
        --text-main: #e2e8f0;
        --glass-bg: rgba(10, 14, 23, 0.85); 
        --glass-border: rgba(255, 255, 255, 0.05);
        --glass-header: rgba(2, 6, 23, 0.80);
        --card-bg: #1e1e1e;
        --border-color: rgba(255, 255, 255, 0.1);
        --text-muted: rgba(255, 255, 255, 0.5);
        --text-heading: #ffffff;
        --code-bg: #252525;
        --simulator-bg: #0b0f19;

        --sb-bg: rgba(2, 6, 23, 0.95);
        --sb-border: rgba(255, 255, 255, 0.1);
        --sb-text: #ffffff;
        --sb-muted: rgba(255, 255, 255, 0.5);
        --sb-hover: rgba(255, 255, 255, 0.02);
        --sb-active-bg: rgba(99, 102, 241, 0.15);
        --sb-active-border: rgba(99, 102, 241, 0.4);
        --sb-active-text: #818cf8;
    }

    body { font-family: 'Inter', sans-serif; background-color: var(--bg-main); color: var(--text-main); transition: background-color 0.4s, color 0.4s; }
    .font-mono { font-family: 'JetBrains Mono', monospace; }

    .bg-adaptive { background-color: var(--bg-main); }
    .text-adaptive { color: var(--text-main); }
    .text-heading { color: var(--text-heading); }
    .text-muted { color: var(--text-muted); }
    .border-adaptive { border-color: var(--border-color); }
    .card-adaptive { background-color: var(--card-bg); border-color: var(--glass-border); transition: all 0.3s; }
    .sim-bg-adaptive { background-color: var(--simulator-bg); }
    .code-adaptive { background-color: var(--code-bg); border-color: var(--glass-border); }

    .hl-term {
        background-color: rgba(99, 102, 241, 0.15);
        color: #4f46e5;
        padding: 0.125rem 0.375rem;
        border-radius: 0.375rem;
        font-weight: 600;
    }
    .dark .hl-term {
        background-color: rgba(99, 102, 241, 0.2);
        color: #818cf8;
    }

    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
    
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
    
    @media (max-width: 1023px) {
        #courseSidebar {
            position: fixed;
            top: 64px;
            left: -100%;
            height: calc(100vh - 64px);
            transition: left 0.3s ease-in-out;
        }
        #courseSidebar.mobile-open { left: 0; box-shadow: 10px 0 30px rgba(0,0,0,0.5); }
        #mobileOverlay { display: none; position: fixed; inset: 0; top: 64px; background: rgba(0,0,0,0.5); backdrop-filter: blur(2px); z-index: 30; }
        #mobileOverlay.show { display: block; }
    }
    
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: var(--sb-muted); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: var(--sb-text); background: var(--sb-hover); }
    .nav-item.active { color: var(--sb-active-text); background: var(--sb-active-bg); font-weight: 600; border: 1px solid var(--sb-active-border); }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; transition: all 0.3s; }
    .dark .dot { background: #334155; }
    .nav-item.active .dot { background: var(--sb-active-text); box-shadow: 0 0 8px var(--sb-active-text); transform: scale(1.2); }
</style>


<div id="courseRoot" class="relative h-screen bg-adaptive text-adaptive font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30 pt-20 transition-colors duration-500">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50 transition-opacity"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20 h-full">

        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            <div id="stickyHeader" class="sticky top-0 z-30 w-full backdrop-blur-2xl border-b border-adaptive px-4 md:px-8 py-4 flex items-center justify-between transition-colors duration-500" style="background-color: var(--glass-header);">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-600 dark:text-indigo-400">3.1</div>
                    <div>
                        <h1 class="text-sm font-bold text-heading line-clamp-1">Tipografi Masterclass</h1>
                        <p class="text-[10px] text-muted line-clamp-1">Hierarki Visual Tingkat Lanjut</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 shrink-0">
                    <div class="hidden sm:block w-24 md:w-32 h-1.5 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 w-0 transition-all duration-500"></div>
                    </div>
                    <span id="progressLabelTop" class="text-indigo-600 dark:text-indigo-400 font-bold text-xs">0%</span>
                </div>
            </div>

            <div class="p-4 sm:p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                <div class="mb-16 md:mb-24">
                    <h3 class="text-xl font-bold text-heading mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="card-adaptive p-5 rounded-xl border flex items-start gap-4">
                            <div class="w-8 h-8 rounded bg-indigo-100 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex justify-center items-center font-bold text-xs shrink-0">1</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Klasifikasi Huruf</h4><p class="text-[11px] text-muted leading-relaxed">Pemanfaatan huruf bawaan sistem untuk performa antarmuka modern yang tanpa jeda muat.</p></div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl border flex items-start gap-4">
                            <div class="w-8 h-8 rounded bg-purple-100 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 flex justify-center items-center font-bold text-xs shrink-0">2</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Skala Proporsional</h4><p class="text-[11px] text-muted leading-relaxed">Penggunaan skala ukuran berbasis relatif yang presisi dan adaptif untuk semua layar.</p></div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl border flex items-start gap-4">
                            <div class="w-8 h-8 rounded bg-pink-100 dark:bg-pink-500/10 text-pink-600 dark:text-pink-400 flex justify-center items-center font-bold text-xs shrink-0">3</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Ketebalan Font</h4><p class="text-[11px] text-muted leading-relaxed">Menyusun hierarki prioritas konten melalui manipulasi bobot huruf secara semantik.</p></div>
                        </div>
                        <div class="card-adaptive p-5 rounded-xl border flex items-start gap-4">
                            <div class="w-8 h-8 rounded bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex justify-center items-center font-bold text-xs shrink-0">4</div>
                            <div><h4 class="text-sm font-bold text-heading mb-1">Ruang dan Spasi</h4><p class="text-[11px] text-muted leading-relaxed">Meningkatkan kenyamanan membaca melalui manajemen jarak antar baris dan kerapatan karakter.</p></div>
                        </div>
                        <div class="bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/40 dark:to-purple-900/40 border border-indigo-200 dark:border-indigo-500/30 p-5 rounded-xl flex items-center gap-4 shadow-sm col-span-1 lg:col-span-2 cursor-pointer transition hover:shadow-md" onclick="scrollToSection('section-51')">
                            <div class="w-10 h-10 rounded-full bg-white/50 dark:bg-white/10 text-indigo-700 dark:text-white flex justify-center items-center font-bold text-lg shrink-0">🎯</div>
                            <div><h4 class="text-sm font-bold text-indigo-900 dark:text-white mb-1">Misi Akhir</h4><p class="text-[11px] text-indigo-800 dark:text-white/70 leading-relaxed">Terapkan seluruh teori untuk merekonstruksi desain artikel portal berita interaktif.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-32 md:space-y-40">
                    
                    {{-- LESSON 46: FONT FAMILY --}}
                    <section id="section-46" class="lesson-section scroll-mt-32" data-lesson-id="46">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-4 md:pl-6">
                                <span class="text-indigo-600 dark:text-indigo-400 font-mono text-xs uppercase tracking-widest">Pelajaran 3.1.1</span>
                                <h2 class="text-3xl md:text-4xl font-black text-heading leading-[1.1]">
                                    Keluarga Huruf Bawaan Sistem
                                </h2>
                            </div>
                            
                            <div class="space-y-4">
                                <h3 class="text-lg md:text-xl font-bold text-heading flex items-center gap-2"><span class="w-5 h-5 rounded bg-indigo-500 flex justify-center items-center text-[10px] text-white shrink-0">A</span> Kecepatan dan Ketajaman Teks</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-base leading-relaxed text-justify">
                                    <p>
                                        Memanggil huruf kustom dari pihak ketiga dapat memperlambat proses muat halaman dan menyebabkan layar berkedip kosong sejenak. Tailwind mengatasi masalah ini dengan menggunakan koleksi huruf bawaan sistem pengguna secara otomatis. 
                                    </p>
                                    <p>
                                        Metode ini memastikan teks di dalam sistem MacOS akan dirender dengan huruf San Francisco yang tajam, sistem Windows menggunakan Segoe UI, dan Android memakai Roboto. Hal ini menjamin situs Anda memuat dalam waktu seketika dan terlihat menyatu secara alami dengan perangkat penonton.
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg md:text-xl font-bold text-heading flex items-center gap-2"><span class="w-5 h-5 rounded bg-indigo-500 flex justify-center items-center text-[10px] text-white shrink-0">B</span> Tiga Kategori Desain Utama</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-base leading-relaxed text-justify">
                                    <p>Terdapat tiga kelas utama yang mencakup kebutuhan rancangan antarmuka:</p>
                                    <ul class="list-disc pl-5 space-y-2 mt-2">
                                        <li><strong><code class="text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-1 rounded">font-sans</code>:</strong> Huruf modern bersudut bersih tanpa lekukan. Keterbacaannya yang sangat tinggi menjadikannya pilihan utama untuk tombol, menu navigasi, dan komponen aplikasi web interaktif.</li>
                                        <li><strong><code class="text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/30 px-1 rounded">font-serif</code>:</strong> Huruf dengan ornamen kaitan di ujung setiap garisnya. Memberikan nuansa elegan dan klasik. Pilihan sempurna untuk merancang tampilan artikel panjang atau judul majalah digital.</li>
                                        <li><strong><code class="text-pink-600 dark:text-pink-400 bg-pink-50 dark:bg-pink-900/30 px-1 rounded">font-mono</code>:</strong> Huruf bertipe mesin tik di mana setiap karakternya memakan ruang lebar yang sejajar dan presisi. Sangat esensial untuk menampilkan cuplikan kode pemrograman atau angka tabel statistik.</li>
                                    </ul>
                                </div>
                            </div>

                            {{-- SIMULATOR 1 --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl flex flex-col shadow-xl transition-colors mt-8 overflow-hidden">
                                <div class="w-full bg-indigo-600/95 text-white p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-indigo-400 dark:border-indigo-700">
                                    <div class="flex items-center gap-2 text-sm font-bold">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        Simulator: Karakteristik Tema Huruf
                                    </div>
                                    <p class="text-xs opacity-90 mt-1 sm:mt-0">Klik tombol di bawah untuk melihat perbedaan visual.</p>
                                </div>
                                <div class="flex flex-col sm:flex-row w-full flex-1">
                                    <div class="w-full sm:w-1/3 p-6 bg-slate-50 dark:bg-[#18181b] border-b sm:border-b-0 sm:border-r border-adaptive flex flex-col justify-center gap-3">
                                        <button onclick="updateSimFont(this, 'font-sans')" class="btn-sim-1 px-4 py-3 bg-indigo-500 text-white rounded-lg text-sm font-bold shadow-md transition">Sans (Modern)</button>
                                        <button onclick="updateSimFont(this, 'font-serif')" class="btn-sim-1 px-4 py-3 bg-white dark:bg-black/30 border border-adaptive rounded-lg text-sm font-bold text-slate-700 dark:text-gray-300 hover:border-indigo-500 transition">Serif (Klasik)</button>
                                        <button onclick="updateSimFont(this, 'font-mono')" class="btn-sim-1 px-4 py-3 bg-white dark:bg-black/30 border border-adaptive rounded-lg text-sm font-bold text-slate-700 dark:text-gray-300 hover:border-indigo-500 transition">Mono (Kode)</button>
                                    </div>
                                    <div class="w-full sm:w-2/3 p-6 flex flex-col items-center justify-center bg-slate-100 dark:bg-[#111] min-h-[250px] relative">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
                                        <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-xl shadow-sm border border-adaptive w-full max-w-sm relative z-10 transition-colors">
                                            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center mb-4">
                                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </div>
                                            <h4 id="demo-font-title" class="text-xl font-bold text-heading font-sans transition-all duration-300 mb-2">Desain Cerdas</h4>
                                            <p id="demo-font-body" class="text-sm text-muted font-sans transition-all duration-300">
                                                Pemilihan jenis keluarga huruf sangat krusial dalam membentuk emosi dan tingkat profesionalisme sebuah produk digital.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 47: SIZE & HIERARCHY --}}
                    <section id="section-47" class="lesson-section scroll-mt-32" data-lesson-id="47">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-purple-500 pl-4 md:pl-6">
                                <span class="text-purple-600 dark:text-purple-400 font-mono text-xs uppercase tracking-widest">Pelajaran 3.1.2</span>
                                <h2 class="text-3xl md:text-4xl font-black text-heading leading-[1.1]">
                                    Skala Modular Tipografi
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg md:text-xl font-bold text-heading flex items-center gap-2"><span class="w-5 h-5 rounded bg-purple-500 flex justify-center items-center text-[10px] text-white shrink-0">A</span> Hitungan Estetika Tetap</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-base leading-relaxed text-justify">
                                    <p>
                                        Membuat teks dengan ukuran angka acak seringkali membuat halaman situs terlihat amatir. Karena itu Tailwind menerapkan sistem Skala Modular yang membatasi pilihan desainer hanya pada daftar ukuran yang sudah dihitung secara proporsional.
                                    </p>
                                    <p>
                                        Skala dimulai dari patokan tengah <code>text-base</code>. Dari sana, Anda dapat mendaki ke ukuran wajar seperti <code>text-lg</code> hingga ukuran tajuk yang amat mencolok mata seperti <code>text-5xl</code>.
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg md:text-xl font-bold text-heading flex items-center gap-2"><span class="w-5 h-5 rounded bg-purple-500 flex justify-center items-center text-[10px] text-white shrink-0">B</span> Otomasi Jarak Baris</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-base leading-relaxed text-justify">
                                    <p>
                                        Setiap kali Anda menerapkan kelas ukuran pada elemen tulisan, mesin komputasi Tailwind secara pintar juga akan menyetel proporsi ruang di antara baris kalimat tersebut. 
                                    </p>
                                    <p>
                                        Secara desain optikal, judul yang berukuran raksasa wajib memiliki renggang jarak baris yang amat rapat agar kalimatnya terihat solid. Sebaliknya, paragraf berukuran kecil membutuhkan ruang napas ekstra di antara barisnya agar nyaman dibaca berlama-lama. Tailwind menangani kerumitan perhitungan ini di balik layar secara instan.
                                    </p>
                                </div>
                            </div>

                            {{-- SIMULATOR 2 --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl flex flex-col shadow-xl transition-colors mt-8 overflow-hidden">
                                <div class="w-full bg-purple-600/95 text-white p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-purple-400 dark:border-purple-700">
                                    <div class="flex items-center gap-2 text-sm font-bold">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                        Simulator: Dimensi dan Relasi Baris
                                    </div>
                                </div>
                                <div class="flex flex-col md:flex-row w-full flex-1">
                                    <div class="w-full md:w-1/2 p-6 bg-slate-50 dark:bg-[#18181b] border-b md:border-b-0 md:border-r border-adaptive flex flex-col justify-center gap-4">
                                        <div onclick="updateSimSize(this, 'text-sm')" class="btn-sim-2 group border-l-4 border-transparent hover:border-purple-400 pl-4 cursor-pointer transition">
                                            <code class="text-xs text-purple-600 dark:text-purple-400 block mb-1">text-sm</code>
                                            <p class="text-sm font-bold text-heading group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">Ukuran Teks Pelengkap</p>
                                        </div>
                                        <div onclick="updateSimSize(this, 'text-base')" class="btn-sim-2 group border-l-4 border-purple-500 pl-4 cursor-pointer transition">
                                            <code class="text-xs text-purple-600 dark:text-purple-400 block mb-1">text-base</code>
                                            <p class="text-base font-bold text-heading group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">Standar Bacaan Nyaman</p>
                                        </div>
                                        <div onclick="updateSimSize(this, 'text-2xl')" class="btn-sim-2 group border-l-4 border-transparent hover:border-purple-400 pl-4 cursor-pointer transition">
                                            <code class="text-xs text-purple-600 dark:text-purple-400 block mb-1">text-2xl</code>
                                            <p class="text-2xl font-bold text-heading group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">Tajuk Menengah</p>
                                        </div>
                                        <div onclick="updateSimSize(this, 'text-4xl')" class="btn-sim-2 group border-l-4 border-transparent hover:border-purple-400 pl-4 cursor-pointer transition">
                                            <code class="text-xs text-purple-600 dark:text-purple-400 block mb-1">text-4xl</code>
                                            <p class="text-4xl font-bold text-heading group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">Judul Raksasa</p>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-1/2 p-6 flex flex-col items-center justify-center bg-slate-100 dark:bg-[#111] min-h-[300px] relative">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
                                        <div class="bg-white dark:bg-[#1e1e1e] border border-adaptive p-6 rounded-xl shadow-sm w-full max-w-sm relative z-10">
                                            <p id="demo-size" class="text-base text-heading font-bold transition-all duration-300 bg-purple-50 dark:bg-purple-900/20 border border-purple-100 dark:border-purple-800/50 p-2 rounded">
                                                Kalimat contoh ini membuktikan bagaimana mesin penyetel mengatur kerapatan spasi baris secara otomatis.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 48: WEIGHT & ANTIALIASING --}}
                    <section id="section-48" class="lesson-section scroll-mt-32" data-lesson-id="48">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-pink-500 pl-4 md:pl-6">
                                <span class="text-pink-600 dark:text-pink-400 font-mono text-xs uppercase tracking-widest">Pelajaran 3.1.3</span>
                                <h2 class="text-3xl md:text-4xl font-black text-heading leading-[1.1]">
                                    Ketebalan Karakter dan Resolusi Piksel
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg md:text-xl font-bold text-heading flex items-center gap-2"><span class="w-5 h-5 rounded bg-pink-500 flex justify-center items-center text-[10px] text-white shrink-0">A</span> Tingkat Bobot Visual</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-base leading-relaxed text-justify">
                                    <p>
                                        Daripada membesarkan ukuran huruf untuk menarik perhatian, desainer pro lebih gemar memisahkan tingkat prioritas informasi dengan cara menebalkan atau menipiskan wujud huruf tersebut. 
                                    </p>
                                    <p>
                                        Tailwind mengubah kode kuno bernilai angka menjadi nama deskriptif yang lugas: mulai dari yang paling tipis <code>font-light</code>, lalu moderat <code>font-normal</code>, agak menonjol <code>font-semibold</code>, tebal <code>font-bold</code>, hingga sangat dominan <code>font-black</code>.
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg md:text-xl font-bold text-heading flex items-center gap-2"><span class="w-5 h-5 rounded bg-pink-500 flex justify-center items-center text-[10px] text-white shrink-0">B</span> Penghalusan Teks Layar Gelap</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-base leading-relaxed text-justify">
                                    <p>
                                        Ketika tulisan warna putih menyala menimpa layar dengan warna latar gelap pekat, mata manusia sering menangkap wujud huruf tersebut seolah membengkak gemuk akibat pendaran cahaya terang di sekeliling pinggiran abjad.
                                    </p>
                                    <p>
                                        Untuk mencegah cacat pendaran optis ini, terapkan perisai utilitas <code>antialiased</code>. Kelas penting ini akan memaksa peramban merender ujung piksel teks dengan ketipisan presisi maksimal, membuat wujud kalimat lebih jernih dipandang mata.
                                    </p>
                                </div>
                            </div>

                            {{-- SIMULATOR 3 --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl flex flex-col shadow-xl transition-colors mt-8 overflow-hidden">
                                <div class="w-full bg-pink-600/95 text-white p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-pink-400 dark:border-pink-700">
                                    <div class="flex items-center gap-2 text-sm font-bold">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                                        Simulator: Manipulasi Bobot
                                    </div>
                                </div>
                                <div class="flex flex-col md:flex-row w-full flex-1">
                                    <div class="w-full md:w-1/2 p-6 bg-slate-50 dark:bg-[#18181b] border-b md:border-b-0 md:border-r border-adaptive flex flex-wrap gap-3 content-center justify-center">
                                        <button onclick="updateSimWeight(this, 'font-light')" class="btn-sim-3 px-4 py-2 border border-adaptive rounded text-sm font-bold text-slate-700 dark:text-gray-300 hover:border-pink-500 transition bg-white dark:bg-black/30">font-light</button>
                                        <button onclick="updateSimWeight(this, 'font-normal')" class="btn-sim-3 px-4 py-2 border border-pink-400 rounded text-sm font-bold text-white transition bg-pink-500 shadow-md">font-normal</button>
                                        <button onclick="updateSimWeight(this, 'font-bold')" class="btn-sim-3 px-4 py-2 border border-adaptive rounded text-sm font-bold text-slate-700 dark:text-gray-300 hover:border-pink-500 transition bg-white dark:bg-black/30">font-bold</button>
                                        <button onclick="updateSimWeight(this, 'font-black')" class="btn-sim-3 px-4 py-2 border border-adaptive rounded text-sm font-bold text-slate-700 dark:text-gray-300 hover:border-pink-500 transition bg-white dark:bg-black/30">font-black</button>
                                    </div>
                                    <div class="w-full md:w-1/2 p-6 flex items-center justify-center bg-slate-100 dark:bg-[#111] min-h-[250px] relative">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
                                        <div class="bg-[#1e1e1e] p-8 rounded-xl shadow-lg border border-white/10 w-full max-w-sm text-center relative z-10 flex flex-col justify-center">
                                            <p id="demo-weight" class="text-4xl text-white font-normal transition-all duration-300 antialiased">
                                                Kontras Visual
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 49: MICRO TYPOGRAPHY --}}
                    <section id="section-49" class="lesson-section scroll-mt-32" data-lesson-id="49">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-emerald-500 pl-4 md:pl-6">
                                <span class="text-emerald-600 dark:text-emerald-400 font-mono text-xs uppercase tracking-widest">Pelajaran 3.1.4</span>
                                <h2 class="text-3xl md:text-4xl font-black text-heading leading-[1.1]">
                                    Modifikasi Spasi dan Penambat
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg md:text-xl font-bold text-heading flex items-center gap-2"><span class="w-5 h-5 rounded bg-emerald-500 flex justify-center items-center text-[10px] text-white shrink-0">A</span> Jarak Udara Antar Karakter</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-base leading-relaxed text-justify">
                                    <p>
                                        Kerapatan jarak antara susunan satu huruf dengan huruf lain dapat direnggangkan atau dikompres melalui utilitas <code>tracking</code>. Kaidah emas tipografi mewajibkan: 
                                    </p>
                                    <p>
                                        Setiap kata berukuran kecil yang diketik kapital semua wajib diberikan jarak napas ekstra menggunakan <code>tracking-wider</code> agar tidak menyatu dempet. Sebaliknya, kata berukuran raksasa sebaiknya dikompres rapat dengan <code>tracking-tighter</code> demi menjadikannya tampak tebal, kokoh, bagai poster film yang padat.
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg md:text-xl font-bold text-heading flex items-center gap-2"><span class="w-5 h-5 rounded bg-emerald-500 flex justify-center items-center text-[10px] text-white shrink-0">B</span> Orientasi Sisi Blok Teks</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-base leading-relaxed text-justify">
                                    <p>
                                        Banyak pemula tergoda menggunakan perataan penuh pinggir (Justify) pada paragraf situs mereka. Secara teknis layar digital, perintah ini merusak paragraf karena mencabik-cabik spasi antar kata membentuk ruang kosong yang menyiksa jalur baca mata penonton.
                                    </p>
                                    <p>
                                        Prioritaskan utilitas perataan kiri (<code>text-left</code>) untuk mendominasi bagian tulisan badan panjang agar alur pembacaan tetap selaras natural. Simpan utilitas rata tengah (<code>text-center</code>) murni untuk blok teks pendek seperti pernyataan sambutan atau kutipan tunggal.
                                    </p>
                                </div>
                            </div>

                            {{-- SIMULATOR 4 --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl flex flex-col shadow-xl transition-colors mt-8 overflow-hidden">
                                <div class="w-full bg-emerald-600/95 text-white p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-emerald-400 dark:border-emerald-700">
                                    <div class="flex items-center gap-2 text-sm font-bold">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        Simulator: Spasi dan Penambat
                                    </div>
                                </div>
                                <div class="flex flex-col md:flex-row w-full flex-1">
                                    <div class="w-full md:w-1/3 p-6 bg-slate-50 dark:bg-[#18181b] border-b md:border-b-0 md:border-r border-adaptive flex flex-col gap-4">
                                        <div class="text-xs font-bold text-muted uppercase tracking-widest mb-1">Renggang Karakter</div>
                                        <div class="flex flex-wrap gap-2">
                                            <button onclick="updateSimTrackAlign(this, 'track', 'tracking-tighter')" class="btn-sim-4-track px-3 py-1.5 border border-adaptive rounded text-xs font-bold text-slate-700 dark:text-gray-300 hover:border-emerald-500 transition bg-white dark:bg-black/30">Tighter</button>
                                            <button onclick="updateSimTrackAlign(this, 'track', 'tracking-normal')" class="btn-sim-4-track px-3 py-1.5 border border-emerald-400 rounded text-xs font-bold text-white transition bg-emerald-500 shadow-md">Normal</button>
                                            <button onclick="updateSimTrackAlign(this, 'track', 'tracking-widest')" class="btn-sim-4-track px-3 py-1.5 border border-adaptive rounded text-xs font-bold text-slate-700 dark:text-gray-300 hover:border-emerald-500 transition bg-white dark:bg-black/30">Widest</button>
                                        </div>
                                        <div class="border-t border-adaptive my-2"></div>
                                        <div class="text-xs font-bold text-muted uppercase tracking-widest mb-1">Arah Teks</div>
                                        <div class="flex flex-wrap gap-2">
                                            <button onclick="updateSimTrackAlign(this, 'align', 'text-left')" class="btn-sim-4-align px-3 py-1.5 border border-emerald-400 rounded text-xs font-bold text-white transition bg-emerald-500 shadow-md">Kiri</button>
                                            <button onclick="updateSimTrackAlign(this, 'align', 'text-center')" class="btn-sim-4-align px-3 py-1.5 border border-adaptive rounded text-xs font-bold text-slate-700 dark:text-gray-300 hover:border-emerald-500 transition bg-white dark:bg-black/30">Tengah</button>
                                            <button onclick="updateSimTrackAlign(this, 'align', 'text-justify')" class="btn-sim-4-align px-3 py-1.5 border border-adaptive rounded text-xs font-bold text-slate-700 dark:text-gray-300 hover:border-emerald-500 transition bg-white dark:bg-black/30">Sama Sisi</button>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-2/3 p-6 flex flex-col justify-center bg-slate-100 dark:bg-[#111] min-h-[300px] relative">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
                                        <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-xl shadow-sm border border-adaptive w-full relative z-10 transition-all duration-300">
                                            <h4 id="demo-track" class="text-3xl font-black text-heading mb-4 uppercase tracking-normal transition-all duration-300 border-b border-adaptive pb-4">
                                                Sorotan Berita
                                            </h4>
                                            <p id="demo-align" class="text-sm text-muted text-left transition-all duration-300 leading-relaxed">
                                                Penentuan batas pelurusan tepi kalimat adalah elemen kunci pembentukan keseimbangan visual layar web. Simulasikan modifikasi untuk melihat rentang ruang yang ditinggalkan akibat dorongan perataan paragraf secara asimetris di bagian kiri.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 50: ORNAMENT --}}
                    <section id="section-50" class="lesson-section scroll-mt-32" data-lesson-id="50">
                        <div class="space-y-8 md:space-y-10">
                            <div class="space-y-4 border-l-4 border-cyan-500 pl-4 md:pl-6">
                                <span class="text-cyan-600 dark:text-cyan-400 font-mono text-xs uppercase tracking-widest">Pelajaran 3.1.5</span>
                                <h2 class="text-3xl md:text-4xl font-black text-heading leading-[1.1]">
                                    Hiasan Dekoratif Transformasi
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg md:text-xl font-bold text-heading flex items-center gap-2"><span class="w-5 h-5 rounded bg-cyan-500 flex justify-center items-center text-[10px] text-white shrink-0">A</span> Rekayasa Kapitalisasi</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-base leading-relaxed text-justify">
                                    <p>
                                        Mengetik judul dengan menahan tuas Capslock di papan ketik adalah cara masa lalu. Secara modern, tampilan huruf kapital harus direkayasa menggunakan kode pembungkus agar teks sumber di sistem pelacak tetap netral.
                                    </p>
                                    <p>
                                        Implementasikan <code>uppercase</code> untuk membakar kalimat menjadi kapital absolut, <code>lowercase</code> untuk menciutkannya, atau panggil kelas sakti <code>capitalize</code> untuk otomatis membesarkan setiap ujung awalan huruf dalam tiap kata secara mandiri.
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg md:text-xl font-bold text-heading flex items-center gap-2"><span class="w-5 h-5 rounded bg-cyan-500 flex justify-center items-center text-[10px] text-white shrink-0">B</span> Modifikasi Ornamen Garis Bawah</h3>
                                <div class="prose prose-slate dark:prose-invert max-w-none text-adaptive opacity-80 text-base leading-relaxed text-justify">
                                    <p>
                                        Kelemahan terbesar hiasan garis bawah standar bawaan peramban adalah tabrakan garis batas yang menyiksa kaki abjad (seperti huruf y atau g). Tailwind merombak batasan klasik ini dengan utilitas mutakhir.
                                    </p>
                                    <p>
                                        Semayamkan kelas <code>underline</code> yang ditemani <code>underline-offset-4</code> untuk melonggarkan batas garis agar merosot turun dan tidak memotong perut kata. Anda juga berkuasa menebalkan tinta garis hiasan dengan komando <code>decoration-2</code>, bahkan memberinya tekstur gaya bergelombang via <code>decoration-wavy</code>.
                                    </p>
                                </div>
                            </div>

                            {{-- SIMULATOR 5 --}}
                            <div class="sim-bg-adaptive border border-adaptive rounded-xl flex flex-col shadow-xl transition-colors mt-8 overflow-hidden">
                                <div class="w-full bg-cyan-600/95 text-white p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-cyan-400 dark:border-cyan-700">
                                    <div class="flex items-center gap-2 text-sm font-bold">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                                        Simulator: Modifikasi Hiasan
                                    </div>
                                </div>
                                <div class="flex flex-col md:flex-row w-full flex-1">
                                    <div class="w-full md:w-1/3 p-6 bg-slate-50 dark:bg-[#18181b] border-b md:border-b-0 md:border-r border-adaptive flex flex-col gap-4">
                                        <div class="text-xs font-bold text-muted uppercase tracking-widest mb-1">Konversi Teks</div>
                                        <div class="flex flex-wrap gap-2">
                                            <button onclick="updateSimTransDecor(this, 'trans', 'normal-case')" class="btn-sim-5-trans px-3 py-1.5 border border-cyan-400 rounded text-xs font-bold text-white transition bg-cyan-500 shadow-md">Asli</button>
                                            <button onclick="updateSimTransDecor(this, 'trans', 'uppercase')" class="btn-sim-5-trans px-3 py-1.5 border border-adaptive rounded text-xs font-bold text-slate-700 dark:text-gray-300 hover:border-cyan-500 transition bg-white dark:bg-black/30">Kapital</button>
                                            <button onclick="updateSimTransDecor(this, 'trans', 'capitalize')" class="btn-sim-5-trans px-3 py-1.5 border border-adaptive rounded text-xs font-bold text-slate-700 dark:text-gray-300 hover:border-cyan-500 transition bg-white dark:bg-black/30">Awalan</button>
                                        </div>
                                        <div class="border-t border-adaptive my-2"></div>
                                        <div class="text-xs font-bold text-muted uppercase tracking-widest mb-1">Garis Ornamen</div>
                                        <div class="flex flex-wrap gap-2">
                                            <button onclick="updateSimTransDecor(this, 'decor', '')" class="btn-sim-5-decor px-3 py-1.5 border border-cyan-400 rounded text-xs font-bold text-white transition bg-cyan-500 shadow-md">Tanpa Garis</button>
                                            <button onclick="updateSimTransDecor(this, 'decor', 'underline underline-offset-4 decoration-2 decoration-cyan-500')" class="btn-sim-5-decor px-3 py-1.5 border border-adaptive rounded text-xs font-bold text-slate-700 dark:text-gray-300 hover:border-cyan-500 transition bg-white dark:bg-black/30">Garis Offset</button>
                                            <button onclick="updateSimTransDecor(this, 'decor', 'underline decoration-wavy decoration-pink-500')" class="btn-sim-5-decor px-3 py-1.5 border border-adaptive rounded text-xs font-bold text-slate-700 dark:text-gray-300 hover:border-cyan-500 transition bg-white dark:bg-black/30">Gelombang</button>
                                        </div>
                                    </div>
                                    <div class="w-full md:w-2/3 p-6 flex flex-col justify-center items-center bg-slate-100 dark:bg-[#111] min-h-[250px] relative">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
                                        <p id="demo-trans-decor" class="text-3xl font-bold text-heading transition-all duration-300 normal-case relative z-10 py-2">
                                            bunga penyangga taman
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- FINAL MISSION (EXPERT CHALLENGE - BUTTON PANEL) --}}
                    <section id="section-51" class="lesson-section scroll-mt-32 pt-10 border-t border-adaptive" data-lesson-id="51" data-manual="true">
                        <div class="relative rounded-[1.5rem] md:rounded-[2.5rem] sim-bg-adaptive border border-adaptive p-4 sm:p-6 lg:p-10 overflow-hidden shadow-xl dark:shadow-2xl group hover:border-indigo-500/30 transition-all duration-500 flex flex-col">
                            
                            <div class="absolute -top-24 -right-24 w-40 h-40 sm:w-64 sm:h-64 bg-indigo-600/10 dark:bg-indigo-600/20 blur-[100px] rounded-full pointer-events-none transition-colors"></div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 mb-6 sm:mb-8 relative z-10 shrink-0">
                                <div class="p-3 sm:p-4 bg-gradient-to-br from-indigo-500 to-fuchsia-600 rounded-xl sm:rounded-2xl text-white shadow-lg shadow-indigo-500/30 shrink-0">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </div>
                                <div>
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 mb-1">
                                        <h2 class="text-xl sm:text-2xl lg:text-3xl font-black text-heading tracking-tight transition-colors">Misi Penugasan Ahli</h2>
                                        <span class="w-max px-2 py-0.5 rounded text-[9px] sm:text-[10px] font-bold bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/30 uppercase tracking-wider transition-colors">Studi Kasus Editorial</span>
                                    </div>
                                    <p class="text-slate-600 dark:text-indigo-200/60 text-xs sm:text-sm leading-relaxed max-w-2xl transition-colors mt-2 text-justify">
                                        Perusahaan media ternama menugaskan Anda memperbaiki tipografi halaman artikel harian mereka agar terlihat elegan, kokoh, dan tidak menyakiti mata. <br><br>
                                        <strong>Instruksi Pembenahan Judul:</strong> Pastikan jenis huruf bersifat kaku kuno klasik, berukuran ekstra jumbo, tebal membaja, bertinta hitam dominan tajam. <br>
                                        <strong>Instruksi Pembenahan Isi:</strong> Ratakan barisan tepi penuh, rentangkan napas spasi setara longgar, warnai tulisan menjadi pudar kenyamanan menengah.
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-col lg:grid lg:grid-cols-12 gap-6 border border-adaptive rounded-2xl overflow-hidden min-h-[600px] shadow-lg transition-colors relative z-10 flex-1 bg-[#0f141e]">
                                
                                {{-- CONTROL PANEL EXPERT --}}
                                <div class="lg:col-span-5 border-b lg:border-b-0 lg:border-r border-white/10 p-5 sm:p-6 flex flex-col h-full bg-[#0a0d14]">
                                    <h3 class="text-[11px] font-bold text-white/50 uppercase tracking-widest mb-6 border-b border-white/10 pb-3">Konsol Manajemen Kelas</h3>
                                    
                                    <div class="flex-1 space-y-6 overflow-y-auto custom-scrollbar pr-2" id="practice-controls">
                                        </div>
                                    
                                    <div class="pt-6 mt-6 border-t border-white/10">
                                        <button id="checkBtn" onclick="checkExpertSolution()" class="w-full py-4 rounded-xl bg-gradient-to-r from-indigo-600 to-fuchsia-600 text-white font-bold text-sm shadow-[0_4px_15px_rgba(99,102,241,0.3)] hover:shadow-[0_6px_20px_rgba(99,102,241,0.5)] transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">
                                            Kirim Validasi Konfigurasi Resolusi
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </button>
                                        <div id="feedback-area" class="mt-4 hidden p-4 rounded-xl text-center text-sm font-bold border"></div>
                                    </div>
                                </div>

                                {{-- PREVIEW EXPERT --}}
                                <div class="lg:col-span-7 bg-white p-6 sm:p-10 flex flex-col items-center relative overflow-y-auto custom-scrollbar">
                                    <div class="absolute top-4 right-4 text-[9px] font-mono text-slate-400 bg-slate-100 px-2 py-1 rounded shadow-sm">SIMULASI BROWSER KLIEN</div>

                                    <div class="w-full max-w-xl mt-8">
                                        
                                        <div class="relative group mb-6">
                                            <div class="absolute -left-3 -top-6 sm:-left-8 sm:top-2 bg-indigo-600 text-white text-[9px] font-bold px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition shadow-md whitespace-nowrap z-20">BLOK JUDUL</div>
                                            <div class="p-4 border-2 border-dashed border-indigo-200 rounded-xl bg-indigo-50/30 group-hover:border-indigo-500 transition-colors relative z-10">
                                                <h1 id="target-title" class="font-sans text-xl font-normal text-slate-300 transition-all duration-300 leading-tight">
                                                    Sistem Antarmuka Evolusioner Mendobrak Batas Estetika Tradisional
                                                </h1>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3 text-xs text-slate-400 mb-8 border-b border-slate-200 pb-4 font-sans">
                                            <span class="font-bold text-rose-600 uppercase tracking-widest">Opini Pakar</span>
                                            <span>&bull;</span> <span>{{ date('d M Y') }}</span>
                                        </div>

                                        <div class="relative group">
                                            <div class="absolute -left-3 -top-6 sm:-left-8 sm:top-2 bg-fuchsia-600 text-white text-[9px] font-bold px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition shadow-md whitespace-nowrap z-20">BLOK PARAGRAF</div>
                                            <div class="p-4 border-2 border-dashed border-fuchsia-200 rounded-xl bg-fuchsia-50/30 group-hover:border-fuchsia-500 transition-colors relative z-10">
                                                <p id="target-body" class="font-sans text-left leading-none text-slate-900 transition-all duration-300">
                                                    Arsitek rancangan dunia maya pada abad modern dipaksa menelan pil pahit kebenaran bahwa pengguna perangkat genggam tidak lagi membaca deretan paragraf melainkan menyapu memindai pola teks. Penerapan pemecahan spasi hiruk-pikuk yang terlalu sesak akan menghancurkan retensi minat secara seketika dan menaikkan persentase pentalan lalu lintas penonton web korporat Anda menuju jurang kehancuran bisnis komersial absolut tanpa celah ampun sama sekali di medan persaingan global ini.
                                                </p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                <div class="mt-20 md:mt-32 pt-8 border-t border-adaptive flex flex-col sm:flex-row justify-between items-center gap-4 sm:gap-0 transition-colors">
                    <a href="{{ route('courses.layout-mgmt') ?? '#' }}" class="group flex items-center gap-4 text-muted hover:text-heading transition-colors w-full sm:w-auto justify-center sm:justify-start">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center group-hover:bg-slate-100 dark:group-hover:bg-white/5 transition-colors shadow-sm dark:shadow-none shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </div>
                        <div class="text-center sm:text-left">
                            <div class="text-[10px] sm:text-xs uppercase tracking-widest font-bold opacity-60 mb-0.5">Kembali</div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Layouting Tailwind CSS</div>
                        </div>
                    </a>
                    
                    <div id="nextChapterBtn" class="group flex items-center gap-4 text-right text-muted cursor-not-allowed opacity-50 pointer-events-none transition-all duration-500 w-full sm:w-auto justify-center sm:justify-end flex-row-reverse sm:flex-row">
                        <div class="text-center sm:text-right">
                            <div id="nextLabel" class="text-[10px] sm:text-xs uppercase tracking-widest font-bold opacity-60 mb-0.5 text-rose-500 dark:text-red-400">Selanjutnya</div>
                            <div class="font-black text-xs md:text-sm line-clamp-1">Background </div>
                        </div>
                        <div id="nextIcon" class="w-10 h-10 md:w-12 md:h-12 rounded-full border border-adaptive flex items-center justify-center bg-slate-100 dark:bg-white/5 transition-colors shadow-sm dark:shadow-none shrink-0">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    /* --- 1. KONFIGURASI BASIS DATA --- */
    window.LESSON_IDS = [46, 47, 48, 49, 50, 51]; 
    
    let rawCompletedIds = {!! json_encode($completedLessonIds ?? []) !!};
    window.COMPLETED_IDS = rawCompletedIds.map(id => Number(id)); 
    let completedSet = new Set(window.COMPLETED_IDS);
    
    const ACTIVITY_LESSON_ID = 51; 
    let activityCompleted = completedSet.has(ACTIVITY_LESSON_ID);

    document.addEventListener('DOMContentLoaded', () => {
        initScrollSpy();
        initSidebarScroll();
        
        updateProgressUI(false); 
        
        initExpertChallenge();

        if (activityCompleted) {
            lockExpertUI();
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

    /* --- 2. MANAJEMEN STATUS & NAVIGASI --- */
    function updateProgressUI(animate = true) {
        const total = window.LESSON_IDS.length; 
        const done = window.LESSON_IDS.filter(id => completedSet.has(Number(id))).length; 
        const percent = Math.round((done / total) * 100);
        
        const bar = document.getElementById('topProgressBar');
        const label = document.getElementById('progressLabelTop');
        
        if(!animate && bar) bar.style.transition = 'none';
        if(bar) bar.style.width = percent + '%'; 
        if(!animate && bar) setTimeout(() => bar.style.transition = 'all 0.5s', 50);
        
        if(label) label.innerText = percent + '%';
        if(percent === 100 && activityCompleted) unlockNextChapter();
    }

    function markSidebarDone(lessonId) {
        const navItem = document.querySelector(`.nav-item[data-target="#section-${lessonId}"]`);
        if(navItem) {
            const dot = navItem.querySelector('.dot');
            if(dot) {
                dot.outerHTML = `<svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>`;
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
            console.error('Pemutusan penyambungan lokal:', e);
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
                        const isActivity = entry.target.dataset.manual === 'true';

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

    function unlockNextChapter() {
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.classList.remove('cursor-not-allowed', 'opacity-50', 'pointer-events-none', 'text-muted');
            btn.classList.add('text-indigo-600', 'dark:text-indigo-400', 'cursor-pointer');
            
            const label = document.getElementById('nextLabel');
            if(label) {
                label.innerText = "Berikutnya";
                label.classList.remove('opacity-60', 'text-rose-500', 'dark:text-red-400');
                label.classList.add('text-indigo-600', 'dark:text-indigo-400', 'opacity-100');
            }
            
            const icon = document.getElementById('nextIcon');
            if(icon) {
                icon.innerHTML = `<svg class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
                icon.classList.remove('bg-slate-100', 'dark:bg-white/5');
                icon.classList.add('bg-indigo-100', 'dark:bg-indigo-500/20', 'border-indigo-300', 'dark:border-indigo-500/50', 'text-indigo-600', 'dark:text-indigo-400', 'shadow-lg');
            }
            
            btn.onclick = () => window.location.href = "{{ route('courses.backgrounds') ?? '#' }}"; 
        }
    }

    /* --- 3. LOGIKA FUNGSI SIMULATOR MATERI LOKAL --- */
    window.updateSimFont = function(btn, cls) {
        $('.btn-sim-1').removeClass('bg-indigo-500 text-white shadow-md border-indigo-400').addClass('bg-white dark:bg-black/30 text-slate-700 dark:text-gray-300 hover:border-indigo-500 border-adaptive');
        $(btn).removeClass('bg-white dark:bg-black/30 text-slate-700 dark:text-gray-300 hover:border-indigo-500 border-adaptive').addClass('bg-indigo-500 text-white shadow-md border-indigo-400');
        
        const el = document.getElementById('demo-font-title');
        const body = document.getElementById('demo-font-body');
        el.className = `text-xl font-bold text-heading transition-all duration-300 mb-2 ${cls}`;
        body.className = `text-sm text-muted transition-all duration-300 ${cls}`;
    };

    window.updateSimSize = function(btn, size) {
        $('.btn-sim-2').removeClass('border-purple-500').addClass('border-transparent hover:border-purple-400');
        $(btn).removeClass('border-transparent hover:border-purple-400').addClass('border-purple-500');

        const el = document.getElementById('demo-size');
        el.className = `font-bold text-heading transition-all duration-300 bg-purple-50 dark:bg-purple-900/20 border border-purple-100 dark:border-purple-800/50 p-2 rounded ${size}`;
    };

    window.updateSimWeight = function(btn, cls) {
        $('.btn-sim-3').removeClass('bg-pink-500 text-white border-pink-400 shadow-md').addClass('bg-white dark:bg-black/30 text-slate-700 dark:text-gray-300 hover:border-pink-500 border-adaptive');
        $(btn).removeClass('bg-white dark:bg-black/30 text-slate-700 dark:text-gray-300 hover:border-pink-500 border-adaptive').addClass('bg-pink-500 text-white border-pink-400 shadow-md');

        const el = document.getElementById('demo-weight');
        el.className = `text-4xl text-white transition-all duration-300 antialiased ${cls}`;
    };

    window.updateSimTrackAlign = function(btn, type, cls) {
        $(`.btn-sim-4-${type}`).removeClass('bg-emerald-500 text-white border-emerald-400 shadow-md').addClass('bg-white dark:bg-black/30 text-slate-700 dark:text-gray-300 hover:border-emerald-500 border-adaptive');
        $(btn).removeClass('bg-white dark:bg-black/30 text-slate-700 dark:text-gray-300 hover:border-emerald-500 border-adaptive').addClass('bg-emerald-500 text-white border-emerald-400 shadow-md');

        if(type === 'track') {
            const el = document.getElementById('demo-track');
            el.className = `text-3xl font-black text-heading mb-4 uppercase transition-all duration-300 border-b border-adaptive pb-4 ${cls}`;
        } else {
            const el = document.getElementById('demo-align');
            el.className = el.className.replace(/text-(left|center|right|justify)/g, '');
            el.classList.add(cls);
        }
    };

    window.updateSimTransDecor = function(btn, type, cls) {
        $(`.btn-sim-5-${type}`).removeClass('bg-cyan-500 text-white border-cyan-400 shadow-md').addClass('bg-white dark:bg-black/30 text-slate-700 dark:text-gray-300 hover:border-cyan-500 border-adaptive');
        $(btn).removeClass('bg-white dark:bg-black/30 text-slate-700 dark:text-gray-300 hover:border-cyan-500 border-adaptive').addClass('bg-cyan-500 text-white border-cyan-400 shadow-md');

        const el = document.getElementById('demo-trans-decor');
        if(type === 'trans') {
            el.className = el.className.replace(/normal-case|uppercase|lowercase|capitalize/g, '');
            el.classList.add(cls === '' ? 'normal-case' : cls);
        } else {
            el.className = el.className.replace(/underline[\w-]*|decoration-[\w-]*|line-through/g, '');
            if(cls !== '') {
                const parts = cls.split(' ');
                parts.forEach(p => el.classList.add(p));
            }
        }
    };

    /* --- 4. LOGIKA MESIN AKTIVITAS FINAL (EXPERT CHALLENGE) --- */
    const studyCaseConfig = {
        title: {
            id: 'target-title',
            label: 'Perombakan Baris Judul',
            categories: {
                font: { title: 'Langgam Karakter', options: [{cls: 'font-sans', name: 'Modern'}, {cls: 'font-serif', name: 'Klasik Kuno', correct: true}, {cls: 'font-mono', name: 'Mesin Ketik'}] },
                size: { title: 'Rasio Ukuran', options: [{cls: 'text-xl', name: 'Sederhana'}, {cls: 'text-3xl', name: 'Biasa'}, {cls: 'text-6xl', name: 'Ekstra Jumbo', correct: true}] },
                weight: { title: 'Ketegasan Tinta', options: [{cls: 'font-normal', name: 'Normal'}, {cls: 'font-bold', name: 'Tebal'}, {cls: 'font-black', name: 'Baja Padat', correct: true}] },
                color: { title: 'Nuansa Gelap', options: [{cls: 'text-slate-900', name: 'Hitam Penuh', correct: true}, {cls: 'text-indigo-600', name: 'Biru Laut'}, {cls: 'text-slate-300', name: 'Kelabu Memudar'}] }
            }
        },
        body: {
            id: 'target-body',
            label: 'Perombakan Baris Paragraf',
            categories: {
                align: { title: 'Pedoman Pinggir', options: [{cls: 'text-left', name: 'Rata Sisi Kiri'}, {cls: 'text-center', name: 'Pusat Tengah'}, {cls: 'text-justify', name: 'Rata Tepi Penuh', correct: true}] },
                leading: { title: 'Celah Pernapasan', options: [{cls: 'leading-none', name: 'Sangat Menjerat'}, {cls: 'leading-normal', name: 'Sedang'}, {cls: 'leading-relaxed', name: 'Longgar Leluasa', correct: true}] },
                color: { title: 'Pigmen Kenyamanan', options: [{cls: 'text-slate-900', name: 'Menikam Mata'}, {cls: 'text-slate-500', name: 'Kenyamanan Menengah', correct: true}, {cls: 'text-red-500', name: 'Merah Darah'}] }
            }
        }
    };

    let userChoices = { 
        title: { font: 'font-sans', size: 'text-xl', weight: 'font-normal', color: 'text-slate-300' }, 
        body: { align: 'text-left', leading: 'leading-none', color: 'text-slate-900' } 
    };

    function initExpertChallenge() {
        const container = $('#practice-controls');
        if(!container.length) return;
        
        Object.entries(studyCaseConfig).forEach(([sectionKey, sectionData]) => {
            let html = `<div class="bg-white/5 p-5 rounded-2xl border border-white/10 mb-6 shadow-md relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <h4 class="text-indigo-300 font-bold mb-5 uppercase text-[11px] tracking-[0.15em] border-b border-white/10 pb-3 flex items-center gap-2 relative z-10">
                    <span class="w-1.5 h-4 bg-indigo-500 rounded-full shadow-[0_0_10px_#6366f1]"></span> ${sectionData.label}
                </h4>`;
                
            Object.entries(sectionData.categories).forEach(([catKey, catData]) => {
                html += `<div class="mb-5 last:mb-0 relative z-10">
                    <label class="text-[10px] text-white/50 mb-2 block font-bold tracking-widest uppercase">${catData.title}</label>
                    <div class="flex flex-wrap gap-2">`;
                
                catData.options.forEach(opt => {
                    const isInit = userChoices[sectionKey][catKey] === opt.cls;
                    const btnClass = isInit ? 'bg-indigo-600 text-white border-indigo-500 shadow-md shadow-indigo-600/30' : 'text-white/60 border-white/10 hover:border-indigo-400 hover:bg-indigo-500/20 bg-white/5';
                    html += `<button onclick="selectExpertOption('${sectionKey}','${catKey}','${opt.cls}',this)" class="btn-expert-${sectionKey}-${catKey} px-3 py-2 rounded-lg text-xs font-bold border transition-all active:scale-95 ${btnClass}">${opt.name}</button>`;
                });
                
                html += `</div></div>`;
            });
            html += `</div>`;
            container.append(html);
        });
    }

    window.selectExpertOption = function(sKey, cKey, cls, btn) {
        if(activityCompleted) return;
        
        $(`.btn-expert-${sKey}-${cKey}`).removeClass('bg-indigo-600 text-white border-indigo-500 shadow-md shadow-indigo-600/30').addClass('text-white/60 border-white/10 bg-white/5');
        $(btn).removeClass('text-white/60 border-white/10 bg-white/5').addClass('bg-indigo-600 text-white border-indigo-500 shadow-md shadow-indigo-600/30');
        
        userChoices[sKey][cKey] = cls;
        
        const target = $(`#${studyCaseConfig[sKey].id}`);
        const allCls = studyCaseConfig[sKey].categories[cKey].options.map(o=>o.cls);
        target.removeClass(allCls.join(' ')).addClass(cls);
    };

    window.checkExpertSolution = async function() {
        if(activityCompleted) return;
        
        let isCorrect = true;
        Object.entries(studyCaseConfig).forEach(([sKey, sData]) => {
            Object.entries(sData.categories).forEach(([cKey, catData]) => {
                const pick = userChoices[sKey][cKey];
                const correctOpt = catData.options.find(o => o.correct);
                if(correctOpt && pick !== correctOpt.cls) isCorrect = false;
            });
        });

        const fb = $('#feedback-area');
        fb.removeClass('hidden bg-rose-500/10 text-rose-400 border-rose-500/30 bg-emerald-500/10 text-emerald-400 border-emerald-500/30 shadow-emerald-500/20 shadow-rose-500/20');
        
        if(isCorrect) {
            fb.addClass('bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-md shadow-emerald-500/20').html(`
                <div class="text-4xl mb-3 animate-bounce mt-2">🏅</div>
                <div class="text-lg font-black mb-1">Estetika Sempurna Diakui</div>
                <div class="text-[11px] opacity-80 leading-relaxed max-w-xs mx-auto mb-2">Tatanan piksel karya Anda telah selaras. Membuka blokir penguncian rute...</div>
            `);
            await finishExpertChallenge();
        } else {
            fb.addClass('bg-rose-500/10 text-rose-400 border border-rose-500/30 shadow-md shadow-rose-500/20').html(`
                <div class="text-4xl mb-3 animate-pulse mt-2">⚖️</div>
                <div class="text-lg font-black mb-1">Ketidakseimbangan Rasio Deteksi</div>
                <div class="text-[11px] opacity-80 leading-relaxed max-w-xs mx-auto mb-2">Amati instruksi di atas kembali. Pastikan perpaduan ketebalan ekstrim pada judul diseimbangkan dengan kelembutan pudar di paragraf.</div>
            `);
        }
        fb.removeClass('hidden');
    };

    async function finishExpertChallenge() {
        const btn = document.getElementById('checkBtn');
        btn.innerHTML = '<span class="animate-pulse">Menghubungi Pusat Satelit Verifikasi...</span>'; 
        btn.disabled = true;
        
        try {
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
            activityCompleted = true;
            lockExpertUI();   
            unlockNextChapter(); 
        } catch(e) { 
            console.error(e); 
            btn.innerHTML = "Tautan Satelit Gugur. Ulangi Penekanan.";
            btn.disabled = false;
            btn.classList.add('shake'); setTimeout(() => btn.classList.remove('shake'), 500);
        }
    }

    function lockExpertUI() {
        $('#practice-controls button').prop('disabled', true).addClass('opacity-50 cursor-not-allowed grayscale');
        
        const btn = document.getElementById('checkBtn'); 
        if(btn) {
            btn.innerText = "Sektor Penugasan Ini Telah Diasuransikan Permanen (Selesai)"; 
            btn.disabled = true;
            btn.classList.remove('from-indigo-600', 'to-fuchsia-600', 'hover:-translate-y-0.5', 'hover:shadow-[0_6px_20px_rgba(99,102,241,0.5)]');
            btn.classList.add('bg-emerald-600', 'cursor-not-allowed', 'shadow-none');
        }

        if(activityCompleted) {
            // Force valid final UI state
            const titleTarget = $('#target-title');
            titleTarget.removeClass().addClass('p-4 border-2 border-dashed border-indigo-200 rounded-xl bg-indigo-50/30 group-hover:border-indigo-500 transition-colors relative z-10 font-serif text-6xl font-black text-slate-900 leading-tight');
            
            const bodyTarget = $('#target-body');
            bodyTarget.removeClass().addClass('p-4 border-2 border-dashed border-fuchsia-200 rounded-xl bg-fuchsia-50/30 group-hover:border-fuchsia-500 transition-colors relative z-10 font-sans text-justify leading-relaxed text-slate-500 transition-all duration-300');
            
            const fb = $('#feedback-area');
            fb.removeClass('hidden').addClass('bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-md shadow-emerald-500/20').html(`
                <div class="text-2xl mb-2 mt-2">🛡️</div>
                <div class="text-sm font-black mb-1">Rancangan Terkunci Abadi</div>
                <div class="text-[10px] opacity-80 leading-relaxed">Nilai estetika Anda telah dipertahankan di pusat bank data pengarsipan peladen utama kami.</div>
            `);
        }
    }

    /* --- 5. LOGIKA PENDUKUNG NAVIGASI SAMPING --- */
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
</script>
@endsection