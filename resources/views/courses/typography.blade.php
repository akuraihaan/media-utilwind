@extends('layouts.landing')
@section('title', 'Bab 3.1 · Tipografi Masterclass')

@section('content')
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30 pt-20">

    {{-- Background Effects --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[1000px] h-[1000px] bg-indigo-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-fuchsia-900/10 rounded-full blur-[100px]"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.03]"></div>
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
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500/20 to-transparent border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-400">3.1</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Tipografi Masterclass</h1>
                        <p class="text-[10px] text-white/50">Advanced Visual Hierarchy</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-32 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-indigo-500 to-fuchsia-500 w-0 transition-all duration-500 shadow-[0_0_10px_#a855f7]"></div>
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
                            <div><h4 class="text-sm font-bold text-white mb-1">Native Font Stack</h4><p class="text-[11px] text-white/50 leading-relaxed">Memahami klasifikasi Sans, Serif, dan Mono serta penggunaannya untuk performa UI.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-fuchsia-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-fuchsia-500/10 text-fuchsia-400 flex items-center justify-center shrink-0 font-bold text-xs">2</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Skala Modular</h4><p class="text-[11px] text-white/50 leading-relaxed">Menguasai sistem relasi matematis Tailwind antara font-size dan default line-height.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-emerald-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0 font-bold text-xs">3</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Volume & Ketebalan</h4><p class="text-[11px] text-white/50 leading-relaxed">Manipulasi hirarki informasi melalui `font-weight`, transformasi, dan antialiasing.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-cyan-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-cyan-500/10 text-cyan-400 flex items-center justify-center shrink-0 font-bold text-xs">4</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Mikro-Tipografi</h4><p class="text-[11px] text-white/50 leading-relaxed">Mengontrol leading, tracking, dan modifikasi dekorasi modern (underline offset).</p></div>
                        </div>
                        <div class="bg-gradient-to-br from-indigo-900/40 to-fuchsia-900/40 border border-indigo-500/30 p-5 rounded-xl flex items-start gap-4 hover:shadow-[0_0_20px_rgba(168,85,247,0.2)] transition group h-full col-span-2 md:col-span-2">
                            <div class="w-8 h-8 rounded bg-white/10 text-white flex items-center justify-center shrink-0 font-bold text-xs">🏁</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Final Mission: Editorial Layout</h4><p class="text-[11px] text-white/70 leading-relaxed">Studi Kasus: Redesign Portal Berita dengan standar tipografi Tailwind CSS tingkat produksi.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 46: FONT FAMILY --}}
                    <section id="fonts" class="lesson-section scroll-mt-32" data-lesson-id="46">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 3.1.1</span>
                                <h2 class="text-3xl lg:text-4xl font-black text-white leading-[1.1]">
                                    Keluarga & Native Font Stack
                                </h2>
                            </div>
                            
                            <div class="space-y-8">
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4">1. Strategi Native Font Stack</h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed">
                                        <p>
                                            Dalam buku <em>Tailwind CSS: Craft Beautiful, Flexible, and Responsive Designs</em>, ditekankan bahwa arsitektur bawaan Tailwind mengadopsi <strong>System-First</strong>. Alih-alih membebani peramban (browser) untuk mengunduh font kustom yang memicu <em>FOIT (Flash of Invisible Text)</em>, Tailwind memanggil font natif (bawaan) sistem operasi milik user.
                                        </p>
                                        <p>
                                            Di macOS, ia otomatis memanggil <em>San Francisco</em>. Di Windows, merender <em>Segoe UI</em>. Di Android memanggil <em>Roboto</em>. Hasilnya: waktu render 0 detik dengan ketajaman antarmuka sekelas aplikasi native. 
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4">2. Taksonomi Typeface Utama</h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed">
                                        <p>Tailwind menyediakan 3 klasifikasi keluarga font utama untuk menutupi hampir semua kebutuhan antarmuka:</p>
                                        <ul class="list-disc pl-5 space-y-2 mt-2">
                                            <li><strong>Sans-Serif (<code>font-sans</code>):</strong> Font bersih tanpa ornamen lengkung (kaki). Menjadi default mutlak di 99% <em>user interface</em> dan <em>dashboard</em> modern karena legibilitas geometrisnya di skala yang sangat kecil.</li>
                                            <li><strong>Serif (<code>font-serif</code>):</strong> Memiliki "kait" dekoratif di ujung huruf. Memberikan kesan berwibawa, literasi klasik, dan kredibilitas. Sempurna untuk "Reading Experience" seperti konten jurnalistik, artikel blog panjang, atau situs korporat kelas atas.</li>
                                            <li><strong>Monospace (<code>font-mono</code>):</strong> Font dengan spasi karakter sama (fixed-width). Digunakan khusus pada <em>tabular data</em> yang presisi seperti blok kode program, tabel harga, atau alamat IP sistem.</li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Simulator --}}
                                <div class="bg-[#0b0f19] p-6 rounded-2xl border border-white/10 relative overflow-hidden group mt-8 shadow-2xl">
                                    <h4 class="text-xs font-bold text-white/40 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        Simulator: Typeface Personality
                                    </h4>
                                    <div class="flex gap-2 mb-6">
                                        <button onclick="setFont('font-sans')" class="px-4 py-2 rounded-lg bg-white/5 hover:bg-indigo-500/20 text-xs font-bold transition border border-white/10 hover:border-indigo-500">Sans (Modern)</button>
                                        <button onclick="setFont('font-serif')" class="px-4 py-2 rounded-lg bg-white/5 hover:bg-fuchsia-500/20 text-xs font-bold transition border border-white/10 hover:border-fuchsia-500">Serif (Classic)</button>
                                        <button onclick="setFont('font-mono')" class="px-4 py-2 rounded-lg bg-white/5 hover:bg-emerald-500/20 text-xs font-bold transition border border-white/10 hover:border-emerald-500">Mono (Tech)</button>
                                    </div>
                                    <div class="p-8 bg-gradient-to-br from-black/60 to-black/20 rounded-xl border border-white/10 min-h-[140px] flex items-center justify-center text-center relative overflow-hidden">
                                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
                                        <p id="demo-font" class="text-2xl md:text-3xl text-white font-sans transition-all duration-500 ease-out relative z-10 leading-relaxed">
                                            "Desain adalah kecerdasan yang dibuat terlihat."
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 47: SIZE & HIERARCHY --}}
                    <section id="size" class="lesson-section scroll-mt-32" data-lesson-id="47">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 3.1.2</span>
                                <h2 class="text-3xl lg:text-4xl font-black text-white leading-[1.1]">
                                    Skala Modular & Hierarki Visual
                                </h2>
                            </div>

                            <div class="space-y-8">
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4">1. Matematika Skala Modular (rem)</h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed">
                                        <p>
                                            Menurut panduan <em>Modern CSS with Tailwind</em>, mendesain dengan unit statis pixel (px) sering memicu ketidakseimbangan visual (desain berantakan). Tailwind menerapkan <strong>Skala Modular</strong> menggunakan unit relatif `rem` (root em).
                                        </p>
                                        <p>
                                            Ukuran basis `text-base` sepadan dengan `1rem` (16px), lalu skalanya naik harmonis: `text-lg` (1.125rem), `text-xl` (1.25rem), `text-2xl` (1.5rem), hingga seterusnya.  Skala baku ini mengeliminasi *tebak-tebakan* desainer untuk ukuran font.
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4">2. Bawaan Smart Leading</h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed">
                                        <p>
                                            Sejak versi Tailwind v2+, utilitas ukuran font dirancang sangat revolusioner: <strong>Setiap penulisan class <code>text-[size]</code> secara otomatis menyuntikkan <code>line-height</code> proporsional.</strong>
                                        </p>
                                        <p>
                                            Jika Anda mengetik <code>text-xl</code>, Tailwind tidak hanya membesarkan font menjadi 1.25rem, tetapi otomatis mematok tinggi baris (line-height) ke 1.75rem. Tidak perlu lagi mendeklarasikan spasi baris manual pada elemen standar kecuali Anda menginginkan modifikasi ekstra kustom.
                                        </p>
                                    </div>
                                </div>

                                {{-- Simulator --}}
                                <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-8 relative overflow-hidden shadow-2xl">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                                        <div class="space-y-8">
                                            <div class="group border-l-2 border-white/10 pl-4 hover:border-indigo-500 transition duration-300 cursor-pointer" onclick="updateSizeSim('4xl')">
                                                <span class="text-xs text-indigo-400 font-mono mb-1 block opacity-50 group-hover:opacity-100">text-4xl (36px / LH: 40px)</span>
                                                <h1 class="text-4xl font-bold text-white group-hover:text-indigo-300 transition">Hero Headline</h1>
                                            </div>
                                            <div class="group border-l-2 border-white/10 pl-4 hover:border-indigo-500 transition duration-300 cursor-pointer" onclick="updateSizeSim('2xl')">
                                                <span class="text-xs text-indigo-400 font-mono mb-1 block opacity-50 group-hover:opacity-100">text-2xl (24px / LH: 32px)</span>
                                                <h2 class="text-2xl font-bold text-white group-hover:text-indigo-300 transition">Section Title</h2>
                                            </div>
                                            <div class="group border-l-2 border-white/10 pl-4 hover:border-indigo-500 transition duration-300 cursor-pointer" onclick="updateSizeSim('base')">
                                                <span class="text-xs text-indigo-400 font-mono mb-1 block opacity-50 group-hover:opacity-100">text-base (16px / LH: 24px)</span>
                                                <p class="text-base text-white/70 group-hover:text-white transition">Ukuran standar body text dengan keterbacaan alami.</p>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-white/5 p-6 rounded-xl border border-white/5 relative flex flex-col justify-center min-h-[250px]">
                                            <div class="absolute -top-3 -right-3 bg-indigo-600 text-white text-[10px] px-2 py-1 rounded font-bold shadow-lg">LIVE PREVIEW</div>
                                            <p id="sizeSimText" class="text-4xl font-bold text-white text-center transition-all duration-300">
                                                The Quick Brown Fox
                                            </p>
                                            <p id="sizeSimLabel" class="text-center text-indigo-400 font-mono text-xs mt-4">class="text-4xl"</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 48: WEIGHT & STYLE --}}
                    <section id="weight" class="lesson-section scroll-mt-32" data-lesson-id="48">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 3.1.3</span>
                                <h2 class="text-3xl lg:text-4xl font-black text-white leading-[1.1]">
                                    Ketebalan (Weight) & Font Smoothing
                                </h2>
                            </div>

                            <div class="space-y-8">
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4">1. Bobot Numerik Semantik</h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed">
                                        <p>
                                            Di CSS primitif, kita menggunakan angka `font-weight: 100` hingga `900`. Tailwind mengubah skala statis numerik ini menjadi utilitas bahasa manusia yang jauh lebih ekspresif dan lebih mudah diingat:
                                        </p>
                                        <ul class="grid grid-cols-2 gap-y-2 mt-2 text-sm font-mono text-indigo-300">
                                            <li>100 -> font-thin</li>
                                            <li>300 -> font-light</li>
                                            <li>400 -> font-normal</li>
                                            <li>500 -> font-medium</li>
                                            <li>600 -> font-semibold</li>
                                            <li>700 -> font-bold</li>
                                            <li>900 -> font-black</li>
                                        </ul>
                                        <p class="mt-2 text-sm text-white/50"><em>Catatan: Desain visual yang matang seringkali mengandalkan manipulasi font-weight alih-alih merubah ukuran teks untuk membagi hierarki. (Misal label form kecil dengan font-bold).</em></p>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4">2. Antialiasing (Font Smoothing)</h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed">
                                        <p>
                                            Ini adalah tipografi rahasia di sistem operasi Mac. Pada background gelap (Dark Mode), font tipis akan terlihat melebar (pendar cahaya ke mata). Utilitas <code>antialiased</code> milik Tailwind akan menginjeksi <code>-webkit-font-smoothing: antialiased</code>.
                                        </p>
                                        <p>
                                            Hal ini mencegah huruf yang tebal terlihat 'gembul' di atas warna latar gelap. Untuk kembali ke standar default subpixel Windows, tersedia <code>subpixel-antialiased</code>.
                                        </p>
                                    </div>
                                </div>

                                {{-- Simulator --}}
                                <div class="bg-[#0b0f19] p-8 rounded-2xl border border-white/10">
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                                        @foreach(['thin' => '100', 'light' => '300', 'normal' => '400', 'medium' => '500', 'semibold' => '600', 'bold' => '700', 'black' => '900'] as $name => $val)
                                            @if($loop->iteration <= 6)
                                            <div class="bg-[#1e1e1e] p-4 rounded-xl border border-white/5 text-center hover:border-fuchsia-500/50 transition duration-300 group flex flex-col items-center justify-center aspect-square shadow-lg cursor-pointer" onclick="updateWeightSim('font-{{ $name }}')">
                                                <span class="block text-4xl mb-2 text-white font-{{ $name }} group-hover:scale-125 transition duration-300">Ag</span>
                                                <code class="text-[9px] text-fuchsia-400 bg-fuchsia-900/20 px-2 py-1 rounded mb-1">font-{{ $name }}</code>
                                                <span class="block text-[9px] text-white/30 font-mono">{{ $val }}</span>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="mt-8 bg-white/5 p-6 rounded-xl border border-white/5 text-center">
                                        <p id="weightSimText" class="text-3xl text-white font-normal transition-all duration-300">Typographic Volume</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 49: MICRO TYPOGRAPHY --}}
                    <section id="micro" class="lesson-section scroll-mt-32" data-lesson-id="49">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 3.1.4</span>
                                <h2 class="text-3xl lg:text-4xl font-black text-white leading-[1.1]">
                                    Mikro-Tipografi: Leading & Tracking
                                </h2>
                            </div>

                            <div class="space-y-8">
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4">1. Leading (Tinggi Baris Optis)</h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed">
                                        <p>
                                            Leading (di Tailwind kelas `leading-*`) berfungsi sebagai "ruang napas" paragraf. Semakin lebar ukuran grid teks ke samping, mata pengguna akan membutuhkan <code>leading-relaxed</code> atau <code>leading-loose</code> agar tidak tersesat saat melompat ke baris berikutnya. 
                                        </p>
                                        <p>
                                            Kebalikannya, pada Judul tebal dan raksasa (`text-5xl`), spasi standar akan tampak pecah (kegedean). Untuk headline berbaris, selalu jepit dia menggunakan <code>leading-tight</code> atau <code>leading-none</code> untuk menjaga kontinuitas visualnya.
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4">2. Tracking (Kepadatan Spasi Karakter)</h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed">
                                        <p>
                                            Tracking (`tracking-*`) memodifikasi seberapa rapat kerning otomatis antar karakter huruf:
                                            <br>• <strong>Uppercase Rule:</strong> Elemen label/badge berskala kecil (`text-xs uppercase`) WAJIB ditambahkan ekstra spasi via <code>tracking-wide</code> atau <code>tracking-widest</code> agar bisa terbaca jelas.
                                            <br>• <strong>Headline Rule:</strong> Huruf font-bold jumbo (contoh pada Hero Section) dianjurkan memakai <code>tracking-tighter</code> untuk membuatnya tampil solid, padat, dan agresif memikat pembaca.
                                        </p>
                                    </div>
                                </div>

                                {{-- Simulator --}}
                                <div class="grid md:grid-cols-2 gap-8">
                                    <div class="bg-[#1e1e1e] p-6 rounded-2xl border border-white/5 hover:border-indigo-500/30 transition relative overflow-hidden group">
                                        <h4 class="text-indigo-400 font-bold mb-4 flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                            Line Height (Leading)
                                        </h4>
                                        <div class="space-y-4">
                                            <div class="group/item cursor-pointer" onclick="updateLeadingSim('leading-none')">
                                                <div class="flex justify-between text-xs text-white/40 mb-1"><code>leading-none</code> (Spasi Rapat)</div>
                                                <p class="leading-none text-white bg-black/40 p-4 rounded border border-white/5 text-2xl font-bold hover:border-indigo-500 transition">
                                                    Judul Utama Raksasa<br>Butuh Line-Height Rapat
                                                </p>
                                            </div>
                                            <div class="group/item cursor-pointer" onclick="updateLeadingSim('leading-loose')">
                                                <div class="flex justify-between text-xs text-white/40 mb-1"><code>leading-loose</code> (Spasi Longgar)</div>
                                                <p class="leading-loose text-white bg-black/40 p-4 rounded border border-white/5 text-sm hover:border-indigo-500 transition">
                                                    Paragraf artikel (body copy) yang sangat panjang membutuhkan ruang napas ekstra besar agar retensi dan kenyamanan mata pembaca tetap stabil.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-[#1e1e1e] p-6 rounded-2xl border border-white/5 hover:border-fuchsia-500/30 transition relative overflow-hidden group">
                                        <h4 class="text-fuchsia-400 font-bold mb-4 flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                            Letter Spacing (Tracking)
                                        </h4>
                                        <div class="space-y-6 mt-8">
                                            <div class="cursor-pointer" onclick="updateTrackingSim('tracking-tighter')">
                                                <div class="flex justify-between text-xs text-white/40 mb-1"><code>tracking-tighter</code> (-0.05em)</div>
                                                <p class="tracking-tighter text-3xl font-black text-white uppercase hover:text-fuchsia-400 transition">DISPLAY FONT</p>
                                            </div>
                                            <div class="cursor-pointer" onclick="updateTrackingSim('tracking-widest')">
                                                <div class="flex justify-between text-xs text-white/40 mb-1"><code>tracking-widest</code> (0.1em)</div>
                                                <p class="tracking-widest text-sm font-bold text-white uppercase border-b border-white/10 pb-2 hover:text-fuchsia-400 transition">Label Navigasi Web</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 50: ALIGNMENT & DECORATION --}}
                    <section id="alignment" class="lesson-section scroll-mt-32" data-lesson-id="50">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-indigo-500 pl-6">
                                <span class="text-indigo-400 font-mono text-xs uppercase tracking-widest">Lesson 3.1.5</span>
                                <h2 class="text-3xl lg:text-4xl font-black text-white leading-[1.1]">
                                    Perataan, Transformasi & Dekorasi
                                </h2>
                            </div>

                            <div class="space-y-8">
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4">1. Manajemen Text Alignment</h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed">
                                        <p>
                                            <em>Ultimate Tailwind CSS Handbook</em> memberikan peringatan penting pada pengembangan web modern: Hindari utilitas <code>text-justify</code> secara berlebihan. Tanpa modul pemenggalan kata *(hyphenation)* tingkat koran cetak, Justify sering menciptakan sungai putih *(rivers of white space)* yang memecah konsentrasi visual. 
                                        </p>
                                        <p>
                                            Gunakan <code>text-left</code> (rata kiri) untuk lebih dari 90% body teks antarmuka Anda karena memberikan struktur jangkar optis yang solid. Simpan <code>text-center</code> murni hanya untuk heading, kutipan pendek, dan elemen kartu kecil.
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4">2. Transformasi Kapitalisasi & Elipsis</h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed">
                                        <p>
                                            Memanipulasi huruf melalui CSS sangat esensial (hindari edit data di backend/database jika urusannya hanya kosmetik): <code>uppercase</code> (Huruf Besar Semua), <code>lowercase</code>, dan <code>capitalize</code> (Kapital Di Awal Kata). 
                                        </p>
                                        <p>
                                            Sistem overflow juga dikendalikan penuh oleh tipografi modern: Gunakan utilitas <code>truncate</code> untuk teks panjang agar terpotong lurus secara horizontal dengan indikator '...' (elipsis), atau panggil fitur modern <code>line-clamp-[number]</code> untuk memangkas multiline pada deskripsi blog otomatis tanpa menggunakan potong string Javascript.
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4">3. Custom Text Decoration</h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed">
                                        <p>
                                            Salah satu loncatan besar Tailwind adalah kapabilitas memodifikasi detail hiasan garis bawah *(underline)* secara ekstrem:
                                        </p>
                                        <ul class="list-disc pl-5 space-y-2 mt-2">
                                            <li>Gaya garis: <code>decoration-solid</code>, <code>decoration-dashed</code>, <code>decoration-wavy</code>.</li>
                                            <li>Ketebalan & Warna: <code>decoration-2</code> dipadu <code>decoration-indigo-500</code>.</li>
                                            <li>Spasi Garis Bawah: <code>underline-offset-4</code> menjauhkan garis bawah dari teks agar tidak memotong karakter huruf dengan bagian menggantung (seperti j, y, p, g).</li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Simulator --}}
                                <div class="grid md:grid-cols-2 gap-8">
                                    <div class="bg-[#1e1e1e] p-6 rounded-2xl border border-white/5 relative overflow-hidden">
                                        <h4 class="text-indigo-400 font-bold mb-4 flex items-center gap-2">Alignment Tool</h4>
                                        <div class="flex gap-2 mb-4">
                                            <button onclick="updateAlignSim('text-left')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs">Left</button>
                                            <button onclick="updateAlignSim('text-center')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs">Center</button>
                                            <button onclick="updateAlignSim('text-right')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs">Right</button>
                                            <button onclick="updateAlignSim('text-justify')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs">Justify</button>
                                        </div>
                                        <p id="alignSimText" class="text-left text-white/70 text-sm bg-black/40 p-4 border border-white/5 rounded min-h-[150px] transition-all">
                                            Tipografi adalah keterampilan mengatur huruf. Desain tipografi antarmuka web sangat dipengaruhi tata letak rata kiri yang dominan karena mematuhi arah baca natural pengguna secara optikal.
                                        </p>
                                    </div>
                                    <div class="bg-[#1e1e1e] p-6 rounded-2xl border border-white/5 relative overflow-hidden">
                                        <h4 class="text-fuchsia-400 font-bold mb-4 flex items-center gap-2">Decoration Modifier</h4>
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            <button onclick="updateDecorSim('underline decoration-wavy decoration-fuchsia-500')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs">Wavy</button>
                                            <button onclick="updateDecorSim('underline underline-offset-8 decoration-4 decoration-indigo-400')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs">Offset-8 + Thick</button>
                                            <button onclick="updateDecorSim('line-through decoration-red-500')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs">Strikethrough</button>
                                        </div>
                                        <div class="flex items-center justify-center bg-black/40 border border-white/5 rounded min-h-[150px]">
                                            <p id="decorSimText" class="text-2xl font-bold text-white transition-all duration-300">Modern Stylings</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>

                    {{-- LESSON 51: EXPERT CHALLENGE --}}
                    <section id="activity-expert" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="51" data-manual="true">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl text-center group hover:border-indigo-500/30 transition-all duration-500">
                            
                            <div class="p-8 border-b border-white/10 bg-gradient-to-r from-indigo-900/20 to-transparent relative">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-600 text-white text-[10px] font-bold uppercase mb-4 tracking-widest shadow-lg shadow-indigo-600/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    Expert Challenge
                                </div>
                                <h2 class="text-3xl font-black text-white mb-4">Studi Kasus: Redesign Portal Berita</h2>
                                <p class="text-white/60 text-sm max-w-2xl mx-auto leading-relaxed">
                                    Anda ditugaskan memperbaiki tipografi sebuah artikel berita agar terlihat <strong>Profesional, Tegas, dan Mudah Dibaca</strong>. Editor meminta gaya jurnalisme klasik modern: Judul harus menggunakan Serif yang elegan, dan teks isi harus nyaman dibaca dengan spasi yang cukup.
                                </p>
                            </div>

                            <div class="grid lg:grid-cols-12 min-h-[600px] text-left border-x border-b border-white/5 rounded-b-[2rem] bg-[#0f141e]">
                                
                                {{-- Controls --}}
                                <div class="lg:col-span-4 bg-[#0f141e] border-r border-white/5 p-6 flex flex-col h-full">
                                    <h3 class="text-xs font-bold text-white/40 uppercase tracking-widest mb-6">Editor Config</h3>
                                    
                                    <div class="flex-1 space-y-6 overflow-y-auto custom-scrollbar pr-2" id="practice-controls">
                                        </div>
                                    
                                    <div class="pt-6 mt-6 border-t border-white/5">
                                        <button id="checkBtn" onclick="checkSolution()" class="w-full py-4 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold text-lg shadow-lg hover:shadow-indigo-500/25 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                                            Verifikasi Desain 🚀
                                        </button>
                                        <div id="feedback-area" class="mt-4 hidden p-4 rounded-lg text-center text-sm font-bold animate-pulse"></div>
                                    </div>
                                </div>

                                {{-- Preview --}}
                                <div class="lg:col-span-8 bg-slate-100 text-slate-900 p-8 md:p-12 relative overflow-y-auto flex flex-col items-center">
                                    <div class="absolute top-4 right-4 text-[10px] font-mono text-slate-400 bg-white px-2 py-1 rounded border border-slate-200 shadow-sm">BROWSER PREVIEW</div>

                                    <div class="w-full max-w-2xl bg-white min-h-[500px] shadow-xl p-10 border border-slate-200 mt-6 relative">
                                        <div class="relative group mb-6">
                                            <div class="absolute -left-8 top-2 bg-indigo-600 text-white text-[9px] font-bold px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition shadow-md">JUDUL</div>
                                            <div id="target-title" class="p-4 border-2 border-dashed border-indigo-300/50 rounded-xl hover:border-indigo-500 transition-colors cursor-help bg-indigo-50/20">
                                                BREAKING: Tailwind CSS Merevolusi Desain Web Modern
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-4 text-sm text-slate-400 mb-8 font-sans border-b border-slate-100 pb-4">
                                            <span class="font-bold text-red-600 uppercase tracking-widest text-xs">Teknologi</span>
                                            <span>•</span> <span>{{ date('d M Y') }}</span>
                                            <span>•</span> <span>5 Menit Baca</span>
                                        </div>

                                        <div class="relative group">
                                            <div class="absolute -left-8 top-2 bg-purple-600 text-white text-[9px] font-bold px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition shadow-md">KONTEN</div>
                                            <div id="target-body" class="p-4 border-2 border-dashed border-purple-300/50 rounded-xl hover:border-purple-500 transition-colors cursor-help bg-purple-50/20">
                                                Framework utility-first ini telah mengubah cara developer membangun antarmuka pengguna. Dengan menyediakan kelas-kelas tingkat rendah, Tailwind memungkinkan pembuatan desain kustom tanpa meninggalkan HTML Anda. Tidak ada lagi perpindahan konteks antara file markup dan style sheet yang memakan waktu.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                {{-- Navigation Footer --}}
                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center">
                    <a href="{{ route('courses.layout-mgmt') ?? '#' }}" class="group flex items-center gap-3 text-slate-400 hover:text-white transition">
                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-white/30 transition">←</div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest opacity-50">Sebelumnya</div>
                            <div class="font-bold text-sm">Layout Management</div>
                        </div>
                    </a>

                    <div id="nextChapterBtn" class="group flex items-center gap-3 text-right text-slate-500 cursor-not-allowed">
                        <div class="text-right">
                            <div class="text-[10px] uppercase tracking-widest opacity-30">Terkunci</div>
                            <div class="font-bold text-sm">Backgrounds</div>
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
    /* Styles specifically for this view */
    .nav-link.active { color: #818cf8; position: relative; } /* Indigo-400 */
    .nav-link.active::after { content: ''; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: linear-gradient(to right,#818cf8,#c084fc); box-shadow: 0 0 12px rgba(129,140,248,.8); border-radius: 2px; }
    
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    
    #animated-bg{ background: radial-gradient(600px circle at 20% 20%, rgba(99,102,241,.15), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(168,85,247,.15), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(56,189,248,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    /* --- CONFIGURATION --- */
    window.SUBBAB_LESSON_IDS = [46, 47, 48, 49, 50, 51]; 
    window.INIT_COMPLETED_LESSONS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedLessons = new Set(window.INIT_COMPLETED_LESSONS);
    
    // Activity ID = 10 (Expert Challenge)
    const ACTIVITY_ID = {{ $expertActivity->id ?? 10 }};
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};

    /* --- 1. CORE SYSTEM & ANIMATIONS --- */
    document.addEventListener('DOMContentLoaded', () => {
        updateProgressUI();
        initLessonObserver();
        initVisualEffects();
        renderControls();
        
        if (activityCompleted) disableExpertUI();
    });

    /* --- 2. SIMULATORS UI --- */
    window.setFont = function(cls) { 
        const el = document.getElementById('demo-font');
        el.className = `text-2xl md:text-3xl text-white transition-all duration-500 ease-out relative z-10 leading-relaxed ${cls}`;
    }

    window.updateSizeSim = function(size) {
        const el = document.getElementById('sizeSimText');
        const lab = document.getElementById('sizeSimLabel');
        el.className = `text-center text-white transition-all duration-300 font-bold text-${size}`;
        lab.innerText = `class="text-${size}"`;
    }

    window.updateWeightSim = function(cls) {
        const el = document.getElementById('weightSimText');
        el.className = `text-3xl text-white transition-all duration-300 ${cls}`;
    }

    window.updateLeadingSim = function(cls) {
        // Logic handled visually in the static block, dynamic update optional
    }
    
    window.updateTrackingSim = function(cls) {
        // Logic handled visually in the static block, dynamic update optional
    }

    window.updateAlignSim = function(cls) {
        const el = document.getElementById('alignSimText');
        el.className = el.className.replace(/text-(left|center|right|justify)/g, '');
        el.classList.add(cls);
    }

    window.updateDecorSim = function(cls) {
        const el = document.getElementById('decorSimText');
        el.className = `text-2xl font-bold text-white transition-all duration-300 ${cls}`;
    }

    /* --- 3. EXPERT CHALLENGE LOGIC --- */
    const studyCaseConfig = {
        title: {
            elementId: 'target-title',
            label: '1. Gaya Judul (Headline)',
            options: {
                font: [{cls:'font-sans',label:'Sans'},{cls:'font-serif',label:'Serif',correct:true},{cls:'font-mono',label:'Mono'}],
                size: [{cls:'text-xl',label:'Kecil'},{cls:'text-4xl',label:'Besar',correct:true},{cls:'text-6xl',label:'Jumbo'}],
                weight: [{cls:'font-normal',label:'Normal'},{cls:'font-bold',label:'Tebal'},{cls:'font-black',label:'Ekstra Tebal',correct:true}],
                color: [{cls:'text-slate-900',label:'Hitam',correct:true},{cls:'text-indigo-600',label:'Indigo'},{cls:'text-gray-400',label:'Pudar'}]
            }
        },
        body: {
            elementId: 'target-body',
            label: '2. Gaya Paragraf (Body)',
            options: {
                align: [{cls:'text-left',label:'Kiri'},{cls:'text-center',label:'Tengah'},{cls:'text-justify',label:'Rata Kiri Kanan',correct:true}],
                leading: [{cls:'leading-none',label:'Rapat'},{cls:'leading-normal',label:'Normal'},{cls:'leading-loose',label:'Longgar (Relaxed)',correct:true}],
                color: [{cls:'text-slate-600',label:'Abu Nyaman',correct:true},{cls:'text-red-500',label:'Merah'},{cls:'text-black',label:'Hitam Pekat'}]
            }
        }
    };

    let userChoices = { title: {font:'',size:'',weight:'',color:''}, body: {align:'',leading:'',color:''} };

    function renderControls() {
        const container = $('#practice-controls');
        if(!container.length) return;
        
        Object.entries(studyCaseConfig).forEach(([sectionKey, sectionData]) => {
            let html = `<div class="bg-black/40 p-5 rounded-2xl border border-white/5 mb-6 hover:border-indigo-500/20 transition-colors">
                <h4 class="text-indigo-400 font-bold mb-4 uppercase text-[10px] tracking-[0.2em] border-b border-white/5 pb-2 flex items-center gap-2">
                    <span class="w-1 h-4 bg-indigo-500 rounded-full"></span> ${sectionData.label}
                </h4>`;
            Object.entries(sectionData.options).forEach(([catKey, options]) => {
                html += `<div class="mb-4 last:mb-0"><label class="text-[10px] text-white/40 mb-2 block capitalize font-bold font-mono">${catKey}</label><div class="flex flex-wrap gap-2">`;
                options.forEach(opt => {
                    html += `<button onclick="selectOption('${sectionKey}','${catKey}','${opt.cls}',this)" class="btn-opt-${sectionKey}-${catKey} px-3 py-2 rounded-lg text-xs font-mono border border-white/10 hover:border-indigo-500/50 hover:bg-indigo-500/10 text-white/60 transition-all active:scale-95" data-cls="${opt.cls}">${opt.label}</button>`;
                });
                html += `</div></div>`;
            });
            html += `</div>`;
            container.append(html);
        });
    }

    window.selectOption = function(sKey, cKey, cls, btn) {
        if(activityCompleted) return;
        $(`.btn-opt-${sKey}-${cKey}`).removeClass('bg-indigo-600 text-white border-indigo-500 shadow-lg shadow-indigo-500/20').addClass('text-white/60 border-white/10');
        $(btn).removeClass('text-white/60 border-white/10').addClass('bg-indigo-600 text-white border-indigo-500 shadow-lg shadow-indigo-500/20');
        userChoices[sKey][cKey] = cls;
        const target = $(`#${studyCaseConfig[sKey].elementId}`);
        // Remove all possible classes for this category
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
                <div class="text-xs opacity-70 mt-1">Artikel kini terlihat profesional dan nyaman dibaca.</div>
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
            await saveLessonToDB(51); 
            completedLessons.add(51);
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
        
        const topBar = document.getElementById('topProgressBar');
        if(topBar) topBar.style.width = percent + '%';
        const topLabel = document.getElementById('progressLabelTop');
        if(topLabel) topLabel.innerText = percent + '%';
        
        if (percent === 100) unlockNext();
    }

    function initLessonObserver() {
        const obs = new IntersectionObserver(async entries => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    const id = Number(entry.target.dataset.lessonId);
                    const isManual = entry.target.getAttribute('data-manual') === 'true';
                    if (id && !isManual && !completedLessons.has(id)) {
                        try { 
                            await saveLessonToDB(id); 
                            completedLessons.add(id); 
                            updateProgressUI(); 
                        } catch (e) {}
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
            btn.innerHTML = `<div class="text-right"><div class="text-[10px] uppercase tracking-widest opacity-50">Selanjutnya</div><div class="font-bold text-sm">Backgrounds</div></div><div class="w-10 h-10 rounded-full border border-indigo-500/30 bg-indigo-500/10 flex items-center justify-center">→</div>`;
            btn.onclick = function() { window.location.href = "{{ route('courses.backgrounds') ?? '#' }}" };
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
</script>
@endsection