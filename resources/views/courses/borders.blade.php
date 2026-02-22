@extends('layouts.landing')
@section('title', 'Bab 3.3 · Borders & Effects Masterclass')

@section('content')
{{-- ... (BAGIAN HTML TETAP SAMA SEPERTI YANG ANDA BERIKAN) ... --}}
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30 pt-20">

    {{-- Background Effects --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[1000px] h-[1000px] bg-indigo-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-fuchsia-900/10 rounded-full blur-[100px]"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.035]"></div>
        <div id="cursor-glow"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20">

        {{-- Sidebar --}}
        @include('layouts.partials.course-sidebar')

        {{-- Main Content --}}
        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            {{-- Sticky Header --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-[#020617]/80 backdrop-blur-2xl border-b border-white/5 px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500/20 to-transparent border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-400">3.3</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Borders & Effects</h1>
                        <p class="text-[10px] text-white/50">Structure, Ring & Dividers</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-32 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-indigo-500 to-fuchsia-500 w-0 transition-all duration-500 shadow-[0_0_10px_#818cf8]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-indigo-400 font-bold text-xs">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40"> 
                
                {{-- Hero & Objectives --}}
                <div class="mb-24">
                    

                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-indigo-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-indigo-500/10 text-indigo-400 flex items-center justify-center shrink-0 font-bold text-xs">1</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Memahami Radius</h4><p class="text-[11px] text-white/50 leading-relaxed">Menganalisis dampak psikologis sudut tajam vs melengkung.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-fuchsia-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-fuchsia-500/10 text-fuchsia-400 flex items-center justify-center shrink-0 font-bold text-xs">2</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Struktur Garis</h4><p class="text-[11px] text-white/50 leading-relaxed">Memahami penggunaan border width per-sisi untuk hierarki konten.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-emerald-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0 font-bold text-xs">3</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Aksentuasi Ring</h4><p class="text-[11px] text-white/50 leading-relaxed">Memahami perbedaan Ring dan Border untuk status aktif/fokus.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 56: RADIUS & WIDTH --}}
                    <section id="radius-width" class="lesson-section scroll-mt-32" data-lesson-id="56">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 3.3.1</span>
                                <h2 class="text-3xl lg:text-4xl font-black text-white leading-[1.1]">Radius & Ketebalan Garis</h2>
                            </div>

                            <div class="space-y-16">
                                {{-- Segmen 1 --}}
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-[10px]">1</span> Psikologi Border Radius</h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed mb-8">
                                        <p>
                                            Sudut yang melengkung (rounded) memberikan kesan ramah, organik, dan modern karena di alam jarang terdapat sudut 90 derajat yang tajam sempurna. Dalam desain UI, <code>rounded</code> digunakan untuk membedakan antara elemen struktural (seperti kontainer) dan elemen aksi (seperti tombol).
                                        </p>
                                        <p>
                                            Tailwind menyediakan skala lengkap:
                                            <br>• <code>rounded-sm</code> (2px): Untuk elemen kecil atau desain yang rapat.
                                            <br>• <code>rounded-lg</code> (8px): Standar industri untuk kartu (Cards) dan modal.
                                            <br>• <code>rounded-full</code> (9999px): Membuat elemen menjadi berbentuk pil atau lingkaran sempurna (Avatar).
                                        </p>
                                    </div>
                                </div>

                                {{-- Segmen 2 --}}
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-[10px]">2</span> Ketebalan Garis (Width)</h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed mb-8">
                                        <p>
                                            Border width mendefinisikan batas antar komponen. <code>border</code> (1px) adalah standar untuk kartu dan input agar terlihat halus dan elegan. Gunakan <code>border-0</code> untuk menghapus border bawaan browser yang tidak diinginkan (reset).
                                        </p>
                                        <p>
                                            Anda juga bisa menargetkan sisi tertentu secara spesifik. Contohnya, teknik <strong>3D Button</strong> klasik sering menggunakan <code>border-b-4</code> dengan warna yang lebih gelap untuk memberi kesan tombol memiliki ketebalan fisik saat ditekan. Ini memberikan <em>affordance</em> (petunjuk visual) yang lebih baik kepada pengguna.
                                        </p>
                                    </div>
                                </div>

                                {{-- Segmen 3: Simulator --}}
                                <div class="bg-[#0f141e] border border-white/10 rounded-2xl p-8 shadow-2xl">
                                    <h4 class="text-xs font-bold text-white/40 uppercase tracking-widest mb-6">Simulator: Shape & Structure</h4>
                                    <div class="grid md:grid-cols-2 gap-8 items-center">
                                        <div class="flex justify-center">
                                            <div id="sim56-target" class="w-40 h-40 bg-gradient-to-br from-indigo-500 to-purple-600 border-4 border-white/20 flex items-center justify-center text-white font-bold transition-all duration-500 rounded-none">
                                                SHAPE
                                            </div>
                                        </div>
                                        <div class="space-y-6">
                                            <div>
                                                <label class="text-[10px] text-indigo-400 font-bold block mb-2">RADIUS</label>
                                                <div class="flex flex-wrap gap-2">
                                                    <button onclick="updateSim56('radius', 'rounded-none')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs hover:bg-indigo-500 transition text-white">None</button>
                                                    <button onclick="updateSim56('radius', 'rounded-xl')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs hover:bg-indigo-500 transition text-white">XL</button>
                                                    <button onclick="updateSim56('radius', 'rounded-full')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs hover:bg-indigo-500 transition text-white">Full</button>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="text-[10px] text-purple-400 font-bold block mb-2">WIDTH</label>
                                                <div class="flex flex-wrap gap-2">
                                                    <button onclick="updateSim56('width', 'border-0')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs hover:bg-purple-500 transition text-white">0</button>
                                                    <button onclick="updateSim56('width', 'border-4')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs hover:bg-purple-500 transition text-white">4</button>
                                                    <button onclick="updateSim56('width', 'border-x-8')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs hover:bg-purple-500 transition text-white">X-8</button>
                                                </div>
                                            </div>
                                            <div class="bg-black/40 p-3 rounded font-mono text-[11px] text-gray-400">
                                                class="<span id="code56-r" class="text-indigo-400">rounded-none</span> <span id="code56-w" class="text-purple-400">border-4</span>"
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 57: COLORS & STYLES --}}
                    <section id="colors-styles" class="lesson-section scroll-mt-32" data-lesson-id="57">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 3.3.2</span>
                                <h2 class="text-3xl lg:text-4xl font-black text-white leading-[1.1]">Estetika Garis</h2>
                            </div>

                            <div class="space-y-16">
                                {{-- Segmen 1 --}}
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-[10px]">1</span> Harmonisasi Warna</h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed mb-8">
                                        <p>
                                            Kesalahan pemula adalah menggunakan border hitam pekat (<code>border-black</code>) atau abu-abu default yang terlalu kontras pada tema terang. Ini membuat desain terlihat kaku dan seperti "wireframe".
                                        </p>
                                        <p>
                                            <strong>Solusi Pro:</strong> Gunakan border dengan opasitas (misal <code>border-white/10</code> atau <code>border-slate-200</code>) agar menyatu secara halus dengan background. Border seharusnya mendefinisikan area, bukan mendominasi visual.
                                        </p>
                                    </div>
                                </div>

                                {{-- Segmen 2 --}}
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-[10px]">2</span> Semantik Gaya (Style)</h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed mb-8">
                                        <p>
                                            Gaya garis mengkomunikasikan fungsi elemen kepada pengguna:
                                            <br>• <strong>Solid (Default):</strong> Menandakan struktur permanen dan solid (Card, Input, Button).
                                            <br>• <strong>Dashed (Putus-putus):</strong> Menandakan area interaktif sementara atau placeholder, seperti area "Drop Files Here" atau slot iklan kosong.
                                            <br>• <strong>Dotted (Titik-titik):</strong> Sering digunakan untuk elemen dekoratif sekunder atau garis potong pada kupon.
                                        </p>
                                    </div>
                                </div>

                                {{-- Segmen 3: Simulator --}}
                                <div class="bg-[#0f141e] border border-white/10 rounded-2xl p-8 shadow-2xl flex flex-col items-center">
                                    <h4 class="text-xs font-bold text-white/40 uppercase tracking-widest mb-6 self-start">Simulator: Style & Context</h4>
                                    
                                    <div id="sim57-target" class="w-full max-w-md h-32 border-2 border-white/20 bg-white/5 rounded-xl flex flex-col items-center justify-center text-white/40 font-mono text-sm transition-all duration-300">
                                        <svg class="w-8 h-8 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                        <span class="text-xs font-mono">Drag files here</span>
                                    </div>

                                    <div class="flex gap-4 mt-8">
                                        <button onclick="updateSim57('border-solid')" class="px-4 py-2 bg-white/5 border border-white/10 rounded text-xs text-white hover:bg-indigo-500 transition">Solid</button>
                                        <button onclick="updateSim57('border-dashed')" class="px-4 py-2 bg-white/5 border border-white/10 rounded text-xs text-white hover:bg-indigo-500 transition">Dashed</button>
                                        <button onclick="updateSim57('border-dotted')" class="px-4 py-2 bg-white/5 border border-white/10 rounded text-xs text-white hover:bg-indigo-500 transition">Dotted</button>
                                    </div>
                                    <div class="mt-4 bg-black/40 p-3 rounded font-mono text-[11px] text-indigo-300">
                                        class="border-2 <span id="code57" class="text-fuchsia-400">border-solid</span> border-white/20"
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 58: DIVIDE UTILITIES --}}
                    <section id="divide" class="lesson-section scroll-mt-32" data-lesson-id="58">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 3.3.3</span>
                                <h2 class="text-3xl lg:text-4xl font-black text-white leading-[1.1]">Divide Utilities</h2>
                            </div>

                            <div class="space-y-16">
                                {{-- Segmen 1 --}}
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-[10px]">1</span> Masalah Border Manual</h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed mb-8">
                                        <p>
                                            Saat membuat daftar (list), cara tradisional adalah memberi <code>border-b</code> pada setiap item. Masalahnya, item terakhir juga akan memiliki border bawah, yang seringkali tidak diinginkan secara visual. Anda terpaksa menambahkan kelas tambahan seperti <code>last:border-0</code>. Ini membuat kode menjadi repetitif dan kotor.
                                        </p>
                                    </div>
                                </div>

                                {{-- Segmen 2 --}}
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-[10px]">2</span> Solusi Cerdas: Divide</h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed mb-8">
                                        <p>
                                            Tailwind menyediakan utilitas <code>divide-y</code> (vertikal) dan <code>divide-x</code> (horizontal). Utilitas ini ditempatkan pada elemen induk (parent), bukan pada elemen anak.
                                        </p>
                                        <p>
                                            Secara otomatis, ia menyuntikkan border <strong>di antara</strong> elemen anak. Elemen pertama tidak mendapat border atas, dan elemen terakhir tidak mendapat border bawah. Hasilnya adalah pembagian yang sangat bersih dan kode yang efisien tanpa perlu memikirkan elemen terakhir.
                                        </p>
                                    </div>
                                </div>

                                {{-- Segmen 3: Simulator --}}
                                <div class="bg-[#0f141e] border border-white/10 rounded-2xl p-8 shadow-2xl flex flex-col items-center">
                                    <h4 class="text-xs font-bold text-white/40 uppercase tracking-widest mb-6 self-start">Simulator: List Management</h4>
                                    
                                    <div id="sim58-target" class="w-full max-w-xs bg-slate-800 rounded-xl transition-all duration-500 divide-y-0 divide-indigo-500/50 border border-slate-700">
                                        <div class="p-4 text-sm text-slate-300">Account Settings</div>
                                        <div class="p-4 text-sm text-slate-300">Privacy & Security</div>
                                        <div class="p-4 text-sm text-rose-400">Sign Out</div>
                                    </div>

                                    <div class="flex gap-4 mt-8">
                                        <button onclick="updateSim58('divide-y-0')" class="px-4 py-2 bg-white/5 border border-white/10 rounded text-xs text-white hover:bg-indigo-500 transition">No Divide</button>
                                        <button onclick="updateSim58('divide-y')" class="px-4 py-2 bg-white/5 border border-white/10 rounded text-xs text-white hover:bg-indigo-500 transition">Divide Y</button>
                                        <button onclick="updateSim58('divide-y-4')" class="px-4 py-2 bg-white/5 border border-white/10 rounded text-xs text-white hover:bg-indigo-500 transition">Thick Divide</button>
                                    </div>
                                    <div class="bg-black/40 p-3 rounded font-mono text-[11px] text-indigo-300 mt-6">
                                        &lt;div class="<span id="code58" class="text-fuchsia-400">divide-y-0</span>"&gt;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 59: RINGS --}}
                    <section id="rings" class="lesson-section scroll-mt-32">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 3.3.4</span>
                                <h2 class="text-3xl lg:text-4xl font-black text-white leading-[1.1]">Rings (Modern Outlines)</h2>
                            </div>

                            <div class="space-y-16">
                                {{-- Segmen 1 --}}
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-[10px]">1</span> Ring vs Border</h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed mb-8">
                                        <p>
                                            Ring di Tailwind menggunakan properti CSS <code>box-shadow</code>, bukan <code>border</code>. Keuntungan utamanya: <strong>Ring tidak memakan ruang layout</strong>. 
                                        </p>
                                        <p>
                                            Jika Anda menambahkan border 2px saat hover, elemen akan membesar dan menggeser tetangganya (ini disebut "layout shift" atau "janky layout"). Ring berada di "luar" atau "di atas" elemen tanpa mengubah dimensinya, membuat transisi interaksi (seperti hover atau focus) menjadi sangat mulus.
                                        </p>
                                    </div>
                                </div>

                                {{-- Segmen 2 --}}
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-[10px]">2</span> Offset Ring</h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed mb-8">
                                        <p>
                                            Untuk efek fokus yang aksesibel dan estetik (seperti gaya fokus default di macOS), gunakan <code>ring-offset-{size}</code>. Ini menambahkan celah transparan (atau berwarna sesuai background) antara elemen dan ring, membuat elemen terlihat menonjol dan jelas status fokusnya tanpa menempel langsung.
                                        </p>
                                    </div>
                                </div>

                                {{-- Segmen 3: Simulator --}}
                                <div class="bg-[#0f141e] border border-white/10 rounded-2xl p-8 shadow-2xl text-center">
                                    <h4 class="text-xs font-bold text-white/40 uppercase tracking-widest mb-10 text-left">Simulator: Focus State</h4>
                                    
                                    <button id="sim59-target" class="px-8 py-3 bg-white text-slate-900 font-bold rounded-lg transition-all duration-300 ring-0 ring-indigo-500 ring-offset-0 ring-offset-slate-900 outline-none transform active:scale-95">
                                        Click Me
                                    </button>

                                    <div class="flex flex-wrap justify-center gap-3 mt-10">
                                        <button onclick="updateSim59('ring-0')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs text-white hover:bg-indigo-500 transition">No Ring</button>
                                        <button onclick="updateSim59('ring-4')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs text-white hover:bg-indigo-500 transition">Basic Ring</button>
                                        <button onclick="updateSim59('ring-4 ring-offset-4')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs text-white hover:bg-indigo-500 transition">Offset Ring</button>
                                    </div>
                                    <div class="bg-black/40 p-3 rounded font-mono text-[11px] text-indigo-300 mt-6 inline-block">
                                        class="<span id="code59" class="text-fuchsia-400">ring-0</span>"
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- FINAL CHALLENGE --}}
                    <section id="activity-challenge" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="59" data-manual="true">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl text-center group hover:border-indigo-500/30 transition-all duration-500">
                            <div class="p-8 border-b border-white/10 bg-gradient-to-r from-indigo-900/10 to-transparent relative text-center">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-600 text-white text-[10px] font-bold uppercase mb-4 tracking-widest shadow-lg shadow-indigo-600/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    Final Challenge
                                </div>
                                <h2 class="text-3xl font-black text-white mb-4">Studi Kasus: Profile Component</h2>
                                <p class="text-white/60 text-sm max-w-2xl mx-auto leading-relaxed">
                                    Selesaikan tantangan berikut dengan mengkonfigurasi komponen kartu profil agar memiliki struktur visual yang kokoh. Pastikan **Avatar** memiliki ring, **Tombol** memiliki efek 3D border, dan **List Skill** memiliki divide.
                                </p>
                            </div>

                            <div class="grid lg:grid-cols-12 min-h-[550px] text-left border-x border-b border-white/5 rounded-b-[2rem] bg-[#0f141e]">
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
                                        {{-- JS Injects Controls Here --}}
                                    </div>
                                    
                                    <div class="pt-6 mt-6 border-t border-white/5">
                                        <button id="checkBtn" onclick="checkSolution()" class="w-full py-4 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold text-lg shadow-lg hover:shadow-indigo-500/25 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                                            Verifikasi Desain 🚀
                                        </button>
                                        <div id="feedback-area" class="mt-4 hidden p-4 rounded-lg text-center text-sm font-bold animate-pulse"></div>
                                    </div>
                                </div>

                                <div class="lg:col-span-8 bg-slate-900 flex items-center justify-center p-8 relative">
                                    <div class="absolute top-4 right-4 text-[10px] font-mono text-slate-400 bg-slate-800 px-2 py-1 rounded border border-slate-700 shadow-sm">PREVIEW</div>
                                    
                                    <div class="w-72 bg-slate-800 rounded-2xl overflow-hidden shadow-2xl border border-slate-700 transition-all duration-500">
                                        {{-- Cover --}}
                                        <div class="h-20 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
                                            <div class="absolute inset-0 bg-white/10"></div>
                                        </div>
                                        
                                        <div class="px-6 pb-6 relative text-center">
                                            {{-- Avatar --}}
                                            <div class="flex justify-center -mt-8 mb-4">
                                                <img id="target-avatar" src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&h=150" 
                                                    class="w-16 h-16 bg-slate-700 transition-all duration-500 object-cover rounded-none border-4 border-slate-800">
                                            </div>
                                            
                                            <h3 class="text-white font-bold text-lg">Alex Dev</h3>
                                            <p class="text-slate-400 text-xs mb-6">Backend Engineer</p>
                                            
                                            {{-- Button --}}
                                            <button id="target-btn" class="w-full py-2.5 bg-indigo-600 text-white text-xs font-bold mb-6 transition-all duration-300 rounded-none active:translate-y-1">
                                                FOLLOW ME
                                            </button>
                                            
                                            {{-- List --}}
                                            <div id="target-list" class="text-[10px] text-slate-300 font-mono transition-all duration-500 rounded-lg bg-black/20 text-left">
                                                <div class="p-3 flex justify-between"><span>Posts</span> <span class="font-bold text-white">34</span></div>
                                                <div class="p-3 flex justify-between"><span>Followers</span> <span class="font-bold text-white">1.2k</span></div>
                                                <div class="p-3 flex justify-between"><span>Following</span> <span class="font-bold text-white">85</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                {{-- FOOTER NAV --}}
                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center">
                    <a href="{{ route('courses.backgrounds') }}" class="group flex items-center gap-3 text-slate-400 hover:text-white transition">
                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-white/30 transition">←</div>
                        <div class="text-left"><div class="text-[10px] opacity-50 uppercase">Sebelumnya</div><div class="font-bold text-sm">3.2 Backgrounds</div></div>
                    </a>
                    
                    {{-- TOMBOL NEXT TERKUNCI --}}
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
    .nav-link.active { color: #818cf8; position: relative; } /* Indigo-400 */
    .nav-link.active::after { content: ''; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: linear-gradient(to right,#818cf8,#c084fc); box-shadow: 0 0 12px rgba(129,140,248,0.8); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    
    #animated-bg{ background: radial-gradient(600px circle at 20% 20%, rgba(99,102,241,.15), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(168,85,247,.15), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(236,72,153,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    /* --- CONFIGURATION --- */
    window.SUBBAB_LESSON_IDS = [56, 57, 58, 59]; 
    window.INIT_COMPLETED_LESSONS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedLessons = new Set(window.INIT_COMPLETED_LESSONS);
    
    // Activity ID = 12 (Borders & Effects Challenge)
    const ACTIVITY_ID = {{ $activityId ?? 12 }};
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};

    /* --- 1. CORE SYSTEM --- */
    document.addEventListener('DOMContentLoaded', () => {
        updateProgressUI();
        initLessonObserver();
        initVisualEffects();
        initSidebarScroll();
        renderControls();
        initChallenge(); // Init specific challenge logic
        
        if (activityCompleted) {
            disableExpertUI();
        }
    });

    /* --- 2. MICRO SIMULATORS UI (Lesson 56-59) --- */
    
    // Lesson 56 Sim: Radius & Width
    window.updateSim56 = function(type, val) {
        const target = document.getElementById('sim56-target');
        const codeR = document.getElementById('code56-r');
        const codeW = document.getElementById('code56-w');

        if(type === 'radius') {
            target.classList.remove('rounded-none', 'rounded-xl', 'rounded-full');
            target.classList.add(val);
            codeR.innerText = val;
        } else {
            target.classList.remove('border-0', 'border-4', 'border-x-8');
            target.classList.add(val);
            codeW.innerText = val;
        }
    }

    // Lesson 57 Sim: Styles
    window.updateSim57 = function(val) {
        const target = document.getElementById('sim57-target');
        const code = document.getElementById('code57');
        target.classList.remove('border-solid', 'border-dashed', 'border-dotted');
        target.classList.add(val);
        code.innerText = val;
    }

    // Lesson 58 Sim: Divide
    window.updateSim58 = function(val) {
        const target = document.getElementById('sim58-target');
        const code = document.getElementById('code58');
        target.classList.remove('divide-y-0', 'divide-y', 'divide-y-4');
        target.classList.add(val);
        code.innerText = val;
    }

    // Lesson 59 Sim: Rings
    window.updateSim59 = function(val) {
        const target = document.getElementById('sim59-target');
        const code = document.getElementById('code59');
        target.classList.remove('ring-0', 'ring-4', 'ring-offset-4');
        
        // Remove individual classes if present to be clean
        target.classList.remove('ring-offset-4'); 
        
        // Add new classes
        const classes = val.split(' ');
        classes.forEach(c => target.classList.add(c));
        
        code.innerText = val;
    }


    /* --- 3. FINAL CHALLENGE LOGIC --- */
    const challengeData = {
        avatar: { 
            id: 'target-avatar', 
            label: '1. Avatar Ring', 
            options: [
                {val: 'rounded-full ring-4 ring-indigo-500 ring-offset-2 ring-offset-slate-800', label: 'Ring Active', correct: true}, 
                {val: 'rounded-none border-4 border-slate-800', label: 'Square Border', correct: false}
            ]
        },
        button: { 
            id: 'target-btn', 
            label: '2. 3D Button', 
            options: [
                {val: 'rounded-lg border-b-4 border-indigo-800 active:border-b-0 active:translate-y-1', label: '3D Border', correct: true}, 
                {val: 'rounded-none border-0', label: 'Flat', correct: false}
            ]
        },
        list: { 
            id: 'target-list', 
            label: '3. List Divider', 
            options: [
                {val: 'divide-y divide-slate-700', label: 'Divide Y', correct: true}, 
                {val: 'divide-y-0', label: 'None', correct: false}
            ]
        }
    };

    let userChoices = { avatar: '', button: '', list: '' };

    // Function specifically to render controls for the Final Challenge
    window.initChallenge = function() {
        const container = $('#practice-controls');
        if(!container.length) return;
        
        // Set default selection (wrong ones to start)
        userChoices.avatar = 'rounded-none border-4 border-slate-800';
        userChoices.button = 'rounded-none border-0';
        userChoices.list = 'divide-y-0';

        Object.entries(challengeData).forEach(([key, data]) => {
            let html = `<div class="bg-black/40 p-4 rounded-xl border border-white/5 mb-4 hover:border-indigo-500/20 transition-colors">
                <h4 class="text-indigo-400 font-bold mb-3 uppercase text-[10px] tracking-[0.2em] flex items-center gap-2">
                    <span class="w-1 h-4 bg-indigo-500 rounded-full"></span>
                    ${data.label}
                </h4>
                <div class="flex gap-2">`;
            
            data.options.forEach(opt => {
                html += `<button onclick="selectOption('${key}','${opt.val}', this)" class="btn-opt-${key} flex-1 py-3 px-2 bg-white/5 border border-white/10 rounded-lg text-[10px] text-white/60 hover:bg-indigo-600 hover:text-white transition-all active:scale-95 text-center font-mono leading-tight">
                    ${opt.label}
                </button>`;
            });
            html += `</div></div>`;
            container.append(html);
        });
    }
    
    // Generic renderControls placeholder for initChallenge to be called
    function renderControls() { /* Handled by initChallenge */ }

    window.selectOption = function(key, val, btnElement) {
        if(activityCompleted) return;
        
        // Update State
        userChoices[key] = val;
        
        // Update UI Buttons
        $(`.btn-opt-${key}`).removeClass('bg-indigo-600 border-indigo-500 text-white shadow-lg shadow-indigo-500/20').addClass('bg-white/5 text-white/60');
        $(btnElement).addClass('bg-indigo-600 border-indigo-500 text-white shadow-lg shadow-indigo-500/20').removeClass('bg-white/5 text-white/60');

        // Update Preview Element
        updatePreview(key, val);
    }

    function updatePreview(key, val) {
        const el = document.getElementById(challengeData[key].id);
        
        // Reset base classes then add selected
        if(key === 'avatar') {
            el.className = `w-16 h-16 bg-slate-700 transition-all duration-500 object-cover ${val}`;
        } else if(key === 'button') {
            el.className = `w-full py-2.5 bg-indigo-600 text-white text-xs font-bold mb-6 transition-all duration-300 ${val}`;
        } else if(key === 'list') {
            el.className = `text-[10px] text-slate-300 font-mono transition-all duration-500 rounded-lg bg-black/20 text-left ${val}`;
        }
    }

    window.checkSolution = async function() {
        if(activityCompleted) return;
        let isCorrect = true;
        let errorMsg = "";

        Object.entries(challengeData).forEach(([key, data]) => {
            const correctVal = data.options.find(o => o.correct).val;
            if(userChoices[key] !== correctVal) {
                isCorrect = false;
                if(key === 'avatar') errorMsg = "Avatar butuh Ring & Rounded Full.";
                else if(key === 'button') errorMsg = "Tombol butuh efek 3D Border.";
                else if(key === 'list') errorMsg = "List butuh Divider antar item.";
            }
        });

        const fb = $('#feedback-area');
        fb.removeClass('hidden bg-red-500/10 text-red-400 bg-emerald-500/10 text-emerald-400 border-red-500/20 border-emerald-500/20 text-indigo-400 animate-pulse');
        const btn = document.getElementById('checkBtn');
        
        if(isCorrect) {
            fb.addClass('bg-emerald-500/10 text-emerald-400 border-emerald-500/20').html(`
                <div class="text-3xl mb-2 animate-bounce">🎉</div>
                <div class="text-lg font-black">Sempurna!</div>
                <div class="text-xs opacity-90 mt-1 font-medium">Anda berhasil menguasai Borders & Effects.</div>
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
            await saveLessonToDB(59); // Mark last lesson done
            completedLessons.add(59);
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
            btn.classList.remove('bg-gradient-to-r', 'from-indigo-600', 'to-purple-600');
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
        
        ['topProgressBar'].forEach(id => { const el = document.getElementById(id); if(el) el.style.width = percent + '%'; });
        ['progressLabelTop'].forEach(id => { const el = document.getElementById(id); if(el) el.innerText = percent + '%'; });
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
            btn.className = "group flex items-center gap-3 text-right text-indigo-400 hover:text-indigo-300 transition cursor-pointer";
            btn.innerHTML = `<div class="text-right"><div class="text-[10px] uppercase tracking-widest opacity-50">Selanjutnya</div><div class="font-bold text-sm">Bab 4: Komponen</div></div><div class="w-10 h-10 rounded-full border border-indigo-500/30 bg-indigo-500/10 flex items-center justify-center">→</div>`;
            // Add actual link logic here if needed
             btn.onclick = function() { window.location.href = "{{ route('courses.effects') }}"; }; // Example Route
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