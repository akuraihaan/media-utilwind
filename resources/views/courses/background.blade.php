@extends('layouts.landing')
@section('title', 'Bab 3.2 · Background Masterclass')

@section('content')
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-blue-500/30 pt-20">

    {{-- Background Effects --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50"></div>
        <div class="absolute top-[-20%] left-[-10%] w-[1000px] h-[1000px] bg-blue-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[800px] h-[800px] bg-cyan-900/10 rounded-full blur-[100px]"></div>
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
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500/20 to-transparent border border-blue-500/20 flex items-center justify-center font-bold text-xs text-blue-400">3.2</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Background Masterclass</h1>
                        <p class="text-[10px] text-white/50">Images, Gradients & Masking</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-32 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-blue-500 to-cyan-500 w-0 transition-all duration-500 shadow-[0_0_10px_#3b82f6]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-blue-400 font-bold text-xs">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40"> 
                
                {{-- Hero & Objectives --}}
                <div class="mb-24">
                   

                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-blue-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-blue-500/10 text-blue-400 flex items-center justify-center shrink-0 font-bold text-xs">1</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Memahami Attachment</h4><p class="text-[11px] text-white/50 leading-relaxed">Menguasai perilaku scroll (Fixed, Scroll, Local) untuk efek Parallax.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-cyan-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-cyan-500/10 text-cyan-400 flex items-center justify-center shrink-0 font-bold text-xs">2</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Memahami Posisi</h4><p class="text-[11px] text-white/50 leading-relaxed">Mengontrol Focal Point gambar agar responsif di berbagai layar.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-indigo-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-indigo-500/10 text-indigo-400 flex items-center justify-center shrink-0 font-bold text-xs">3</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Memahami Skala</h4><p class="text-[11px] text-white/50 leading-relaxed">Perbedaan Cover vs Contain untuk penanganan aspek rasio gambar.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-purple-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-purple-500/10 text-purple-400 flex items-center justify-center shrink-0 font-bold text-xs">4</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Memahami Layering</h4><p class="text-[11px] text-white/50 leading-relaxed">Menggabungkan gambar dan gradien untuk keterbacaan teks.</p></div>
                        </div>
                        <div class="bg-gradient-to-br from-blue-900/40 to-cyan-900/40 border border-blue-500/30 p-5 rounded-xl flex items-start gap-4 hover:shadow-[0_0_20px_rgba(59,130,246,0.2)] transition group h-full col-span-2 md:col-span-2">
                            <div class="w-8 h-8 rounded bg-white/10 text-white flex items-center justify-center shrink-0 font-bold text-xs">🏁</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Final Mission: Hero Parallax</h4><p class="text-[11px] text-white/70 leading-relaxed">Studi Kasus: Membangun Hero Section Travel yang Imersif.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 52: ATTACHMENT & CLIP --}}
                    <section id="attachment" class="lesson-section scroll-mt-32" data-lesson-id="52">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-blue-500 pl-6">
                                <span class="text-blue-400 font-mono text-xs uppercase tracking-widest">Lesson 3.2.1</span>
                                <h2 class="text-3xl lg:text-4xl font-black text-white leading-[1.1]">
                                    Attachment & Clipping
                                </h2>
                            </div>
                            
                            <div class="space-y-16">
                                {{-- Part 1: Attachment --}}
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded bg-blue-600 flex items-center justify-center text-[10px] text-white">1</span>
                                        Fisika Scrolling (Attachment)
                                    </h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed mb-6">
                                        <p>
                                            Secara default (<code>bg-scroll</code>), gambar latar menempel pada elemennya dan ikut bergerak saat halaman digulir. Ini perilaku standar yang kita lihat di sebagian besar web.
                                        </p>
                                        <p>
                                            Namun, untuk menciptakan efek premium atau "Parallax" sederhana, Tailwind menyediakan <code>bg-fixed</code>. Kelas ini memaku gambar latar ke viewport (jendela browser). Hasilnya? Konten di depan bergerak, tapi latar belakang terlihat diam di kejauhan, menciptakan ilusi kedalaman 3D tanpa JavaScript.
                                        </p>
                                    </div>
                                    {{-- Micro Sim 1 --}}
                                    <div class="bg-[#0f141e] p-6 rounded-xl border border-white/5">
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="text-xs font-bold text-white/50 uppercase">Simulator: Parallax Effect</span>
                                            <div class="flex gap-2">
                                                <button onclick="setMicroSim52('fixed')" class="px-3 py-1 bg-blue-600/20 text-blue-400 border border-blue-500/50 rounded text-xs">bg-fixed</button>
                                                <button onclick="setMicroSim52('scroll')" class="px-3 py-1 bg-white/5 text-white/50 border border-white/10 rounded text-xs">bg-scroll</button>
                                            </div>
                                        </div>
                                        <div class="h-40 overflow-y-scroll custom-scrollbar border border-white/10 rounded-lg relative" id="sim52-target">
                                            <div class="h-[200%] flex flex-col items-center justify-center space-y-12">
                                                <div class="bg-black/70 p-4 rounded text-white backdrop-blur-sm">Scroll Down ↓</div>
                                                <div class="bg-black/70 p-4 rounded text-white backdrop-blur-sm">Keep Scrolling...</div>
                                                <div class="bg-black/70 p-4 rounded text-white backdrop-blur-sm">End of Content</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Part 2: Clipping --}}
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded bg-blue-600 flex items-center justify-center text-[10px] text-white">2</span>
                                        Magisnya Background Clip
                                    </h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed mb-6">
                                        <p>
                                            Properti <code>bg-clip</code> mengontrol seberapa jauh latar belakang merender dirinya. Defaultnya adalah <code>bg-clip-border</code> (sampai batas border terluar).
                                        </p>
                                        <p>
                                            Fitur "killer" di sini adalah <code>bg-clip-text</code>. Dikombinasikan dengan <code>text-transparent</code>, ini memungkinkan gambar atau gradien untuk "mengisi" bentuk huruf teks Anda. Teknik ini sangat populer di desain web modern untuk membuat judul yang menonjol (Gradient Text).
                                        </p>
                                    </div>
                                    {{-- Micro Sim 2 --}}
                                    <div class="bg-[#0f141e] p-8 rounded-xl border border-white/5 text-center group">
                                        <h2 id="sim52-text" class="text-6xl font-black text-white transition-all duration-500">AWESOME</h2>
                                        <div class="mt-6 flex justify-center gap-4">
                                            <button onclick="toggleTextClip(false)" class="px-4 py-2 bg-white/5 rounded text-xs border border-white/10 hover:bg-white/10 transition">Normal Text</button>
                                            <button onclick="toggleTextClip(true)" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded text-xs font-bold shadow-lg hover:shadow-cyan-500/50 transition">Activate Clip</button>
                                        </div>
                                        <p class="text-xs text-white/30 mt-4 font-mono">class="bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent"</p>
                                    </div>
                                </div>

                                {{-- Part 3: Local Attachment --}}
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded bg-blue-600 flex items-center justify-center text-[10px] text-white">3</span>
                                        Local vs Scroll (Edge Case)
                                    </h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed mb-6">
                                        <p>
                                            Ada satu nilai lagi: <code>bg-local</code>. Ini sering membingungkan pemula. Bedanya dengan <code>bg-scroll</code> adalah:
                                            <br>• <strong>bg-scroll:</strong> Background diam relatif terhadap elemen itu sendiri (hanya bergerak jika body page discroll).
                                            <br>• <strong>bg-local:</strong> Background menempel pada <em>konten</em> di dalam elemen. Jika elemen tersebut punya scrollbar sendiri (seperti sidebar), background akan ikut bergerak saat isinya discroll.
                                        </p>
                                    </div>
                                    {{-- Micro Sim 3 --}}
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-black/30 p-4 rounded border border-white/10 h-40 overflow-y-scroll bg-scroll bg-[url('https://grainy-gradients.vercel.app/noise.svg')]">
                                            <p class="text-xs font-bold mb-2 sticky top-0 bg-blue-900/50">bg-scroll</p>
                                            <div class="h-[200%] w-full bg-gradient-to-b from-blue-500/20 to-transparent"></div>
                                        </div>
                                        <div class="bg-black/30 p-4 rounded border border-white/10 h-40 overflow-y-scroll bg-local bg-[url('https://grainy-gradients.vercel.app/noise.svg')]">
                                            <p class="text-xs font-bold mb-2 sticky top-0 bg-blue-900/50">bg-local</p>
                                            <div class="h-[200%] w-full bg-gradient-to-b from-blue-500/20 to-transparent"></div>
                                        </div>
                                    </div>
                                    <p class="text-center text-xs text-white/40 mt-2">Scroll kedua kotak di atas untuk melihat perbedaannya.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 53: POSITION & REPEAT --}}
                    <section id="position" class="lesson-section scroll-mt-32" data-lesson-id="53">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-blue-500 pl-6">
                                <span class="text-blue-400 font-mono text-xs uppercase tracking-widest">Lesson 3.2.2</span>
                                <h2 class="text-3xl lg:text-4xl font-black text-white leading-[1.1]">
                                    Posisi, Pola & Fallback
                                </h2>
                            </div>

                            <div class="space-y-16">
                                {{-- Part 1: Position --}}
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded bg-blue-600 flex items-center justify-center text-[10px] text-white">1</span>
                                        Mengontrol Focal Point (Position)
                                    </h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed mb-6">
                                        <p>
                                            Pada desain responsif, gambar latar seringkali terpotong (cropped) saat ukuran layar berubah. Properti posisi (<code>bg-center</code>, <code>bg-top</code>, <code>bg-right-bottom</code>) memberi tahu browser bagian mana dari gambar yang paling penting ("Focal Point").
                                        </p>
                                        <p>
                                            Misalnya, jika objek utama gambar ada di sisi kanan, gunakan <code>bg-right</code>. Ini menjamin objek tersebut tetap terlihat di layar ponsel (mobile view), sementara sisi kiri gambar yang "dikorbankan".
                                        </p>
                                    </div>
                                    {{-- Micro Sim 1 --}}
                                    <div class="bg-[#0f141e] border border-white/10 rounded-xl p-6">
                                        <div class="flex gap-2 mb-4 justify-center">
                                            <button onclick="setSimPos('bg-left-top')" class="px-3 py-1 rounded bg-white/5 hover:bg-white/20 text-xs transition border border-white/10">Top Left</button>
                                            <button onclick="setSimPos('bg-center')" class="px-3 py-1 rounded bg-white/5 hover:bg-white/20 text-xs transition border border-white/10">Center</button>
                                            <button onclick="setSimPos('bg-right-bottom')" class="px-3 py-1 rounded bg-white/5 hover:bg-white/20 text-xs transition border border-white/10">Bottom Right</button>
                                        </div>
                                        <div id="demo-pos" class="h-48 w-full bg-black/40 rounded-lg border border-white/10 bg-[url('https://img.icons8.com/fluency/96/tailwind_css.png')] bg-no-repeat bg-center transition-all duration-500 relative">
                                            <span class="absolute inset-0 flex items-center justify-center text-white/5 text-4xl font-black uppercase tracking-widest pointer-events-none">CANVAS</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Part 2: Repeat --}}
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded bg-blue-600 flex items-center justify-center text-[10px] text-white">2</span>
                                        Manajemen Tekstur (Repeat)
                                    </h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed mb-6">
                                        <p>
                                            Untuk foto, kita hampir selalu menggunakan <code>bg-no-repeat</code> untuk mencegah gambar muncul berulang kali seperti ubin. Namun, untuk aset pola (pattern) atau tekstur noise, <code>bg-repeat</code> adalah teman terbaik Anda.
                                        </p>
                                        <p>
                                            Tailwind juga menyediakan <code>bg-repeat-x</code> (ulang horizontal saja) dan <code>bg-repeat-y</code> (ulang vertikal saja). Ini berguna untuk membuat elemen dekoratif seperti garis putus-putus atau timeline vertikal menggunakan gambar kecil 1x10px.
                                        </p>
                                    </div>
                                    {{-- Micro Sim 2 --}}
                                    <div class="bg-[#0f141e] p-6 rounded-xl border border-white/10 flex gap-4 items-center">
                                        <div id="demo-repeat" class="w-full h-32 bg-white/5 rounded border border-white/10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-repeat"></div>
                                        <div class="flex flex-col gap-2 shrink-0">
                                            <button onclick="document.getElementById('demo-repeat').className='w-full h-32 bg-white/5 rounded border border-white/10 bg-[url(\'https://www.transparenttextures.com/patterns/cubes.png\')] bg-repeat'" class="px-3 py-2 bg-blue-600/20 text-blue-400 text-xs rounded border border-blue-500/30">Repeat</button>
                                            <button onclick="document.getElementById('demo-repeat').className='w-full h-32 bg-white/5 rounded border border-white/10 bg-[url(\'https://www.transparenttextures.com/patterns/cubes.png\')] bg-no-repeat bg-center'" class="px-3 py-2 bg-white/5 text-white/50 text-xs rounded border border-white/10">No Repeat</button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Part 3: Fallback --}}
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded bg-blue-600 flex items-center justify-center text-[10px] text-white">3</span>
                                        Safety Net (Fallback Color)
                                    </h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed mb-6">
                                        <p>
                                            Gambar latar belakang (terutama resolusi tinggi) membutuhkan waktu untuk diunduh. Jika koneksi lambat, pengguna mungkin melihat latar belakang putih kosong selama beberapa detik, membuat teks putih di atasnya tidak terbaca (Invisible Text).
                                        </p>
                                        <p>
                                            <strong>Best Practice:</strong> Selalu tetapkan warna latar belakang (<code>bg-slate-900</code>) bersamaan dengan gambar latar. Warna ini akan muncul instan saat gambar sedang loading, menjaga teks tetap terbaca.
                                        </p>
                                    </div>
                                    {{-- Micro Sim 3 --}}
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="h-32 bg-white flex items-center justify-center text-center p-4 border border-white/10 relative">
                                            <span class="text-white font-bold drop-shadow-md z-10">Teks Putih (Tanpa Fallback)</span>
                                            <div class="absolute inset-0 flex items-center justify-center text-black/20 text-xs">Image Loading...</div>
                                        </div>
                                        <div class="h-32 bg-slate-800 flex items-center justify-center text-center p-4 border border-white/10 relative">
                                            <span class="text-white font-bold z-10">Teks Putih (Dengan Fallback)</span>
                                            <div class="absolute bottom-2 right-2 text-white/20 text-[10px]">bg-slate-800</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 54: SIZE & GRADIENT --}}
                    <section id="size" class="lesson-section scroll-mt-32" data-lesson-id="54">
                        <div class="space-y-10">
                            <div class="space-y-4 border-l-4 border-blue-500 pl-6">
                                <span class="text-blue-400 font-mono text-xs uppercase tracking-widest">Lesson 3.2.3</span>
                                <h2 class="text-3xl lg:text-4xl font-black text-white leading-[1.1]">
                                    Ukuran & Gradasi
                                </h2>
                            </div>

                            <div class="space-y-16">
                                {{-- Part 1: Cover vs Contain --}}
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded bg-blue-600 flex items-center justify-center text-[10px] text-white">1</span>
                                        Dilema Cover vs Contain
                                    </h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed mb-6">
                                        <p>
                                            • <strong>bg-cover:</strong> Memaksa gambar menutupi seluruh area elemen. Sisi gambar mungkin terpotong, tapi tidak ada ruang kosong. Ini standar untuk Hero Section.
                                            <br>• <strong>bg-contain:</strong> Memaksa seluruh gambar terlihat utuh. Akan ada ruang kosong di sisi elemen jika rasio aspek tidak cocok. Cocok untuk menampilkan logo partner atau produk.
                                        </p>
                                    </div>
                                    {{-- Micro Sim 1 --}}
                                    <div class="bg-[#0f141e] p-6 rounded-xl border border-white/10 grid grid-cols-2 gap-4">
                                        <div>
                                            <div class="h-24 w-full bg-black/50 rounded overflow-hidden relative border border-white/10 mb-2">
                                                <div class="absolute inset-0 bg-cover bg-center opacity-80" style="background-image: url('https://images.unsplash.com/photo-1550684848-fac1c5b4e853');"></div>
                                            </div>
                                            <code class="text-xs text-blue-400 block text-center">bg-cover</code>
                                        </div>
                                        <div>
                                            <div class="h-24 w-full bg-black/50 rounded overflow-hidden relative border border-white/10 mb-2">
                                                <div class="absolute inset-0 bg-contain bg-center bg-no-repeat opacity-80" style="background-image: url('https://images.unsplash.com/photo-1550684848-fac1c5b4e853');"></div>
                                            </div>
                                            <code class="text-xs text-blue-400 block text-center">bg-contain</code>
                                        </div>
                                    </div>
                                </div>

                                {{-- Part 2: Gradient Engine --}}
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded bg-blue-600 flex items-center justify-center text-[10px] text-white">2</span>
                                        Mesin Gradasi Modern
                                    </h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed mb-6">
                                        <p>
                                            Tailwind menyederhanakan sintaks CSS gradient yang rumit. Anda cukup mendefinisikan arah (<code>bg-gradient-to-r</code>), warna mulai (<code>from-blue-500</code>), warna tengah opsional (<code>via-purple-500</code>), dan warna akhir (<code>to-pink-500</code>).
                                        </p>
                                    </div>
                                    {{-- Micro Sim 2 --}}
                                    <div class="bg-[#0f141e] p-6 rounded-xl border border-white/10">
                                        <div id="demo-grad" class="h-20 rounded-lg bg-gradient-to-r from-blue-500 to-cyan-500 shadow-lg transition-all duration-500 mb-4 border border-white/5"></div>
                                        <div class="flex gap-2 justify-center">
                                            <button onclick="setSimGrad('bg-gradient-to-r from-blue-500 to-cyan-500')" class="px-2 py-1 bg-white/5 rounded text-[10px] hover:bg-white/10 border border-white/10">Right</button>
                                            <button onclick="setSimGrad('bg-gradient-to-br from-indigo-500 to-purple-500')" class="px-2 py-1 bg-white/5 rounded text-[10px] hover:bg-white/10 border border-white/10">Bottom Right</button>
                                            <button onclick="setSimGrad('bg-gradient-to-t from-emerald-500 to-teal-500')" class="px-2 py-1 bg-white/5 rounded text-[10px] hover:bg-white/10 border border-white/10">Top</button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Part 3: Layering (Overlay) --}}
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded bg-blue-600 flex items-center justify-center text-[10px] text-white">3</span>
                                        Teknik Layering (Overlay)
                                    </h3>
                                    <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed mb-6">
                                        <p>
                                            Teks putih di atas gambar terang adalah resep bencana keterbacaan. Solusi pro: Gunakan div absolut dengan gradasi hitam transparan di atas gambar.
                                        </p>
                                        <p>
                                            Code pattern: <code>bg-gradient-to-t from-black/80 to-transparent</code>. Ini membuat bagian bawah gelap (tempat teks berada) dan bagian atas tetap jernih menampilkan gambar.
                                        </p>
                                    </div>
                                    {{-- Micro Sim 3 --}}
                                    <div class="relative h-40 w-full rounded-xl overflow-hidden group cursor-pointer border border-white/10">
                                        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e')] bg-cover bg-center"></div>
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                        <div class="absolute bottom-0 left-0 p-4 translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                            <h4 class="text-white font-bold">Hover Me</h4>
                                            <p class="text-xs text-white/70 opacity-0 group-hover:opacity-100 transition duration-500 delay-100">Overlay membuat teks ini terbaca.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 55: EXPERT CHALLENGE --}}
                    <section id="activity-expert" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="55" data-manual="true">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl text-center group hover:border-blue-500/30 transition-all duration-500">
                            
                            <div class="p-8 border-b border-white/10 bg-gradient-to-r from-blue-900/10 to-transparent relative">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-600 text-white text-[10px] font-bold uppercase mb-4 tracking-widest shadow-lg shadow-blue-600/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    Expert Challenge
                                </div>
                                <h2 class="text-3xl font-black text-white mb-4">Studi Kasus: The Bali Experience</h2>
                                <p class="text-white/60 text-sm max-w-2xl mx-auto leading-relaxed">
                                    Anda diminta membuat Hero Section untuk website travel premium. Klien menginginkan gambar <strong>Pura Bali</strong> yang memenuhi layar, efek <strong>Parallax</strong> saat discroll, dan teks yang terbaca jelas menggunakan teknik <strong>Overlay Gradient</strong>.
                                </p>
                            </div>

                            <div class="grid lg:grid-cols-12 min-h-[600px] text-left border-x border-b border-white/5 rounded-b-[2rem] bg-[#0f141e]">
                                
                                {{-- Controls --}}
                                <div class="lg:col-span-4 bg-[#0f141e] border-r border-white/5 p-6 flex flex-col h-full">
                                    <h3 class="text-xs font-bold text-white/40 uppercase tracking-widest mb-6">Configurator</h3>
                                    
                                    <div class="flex-1 space-y-6 overflow-y-auto custom-scrollbar pr-2" id="practice-controls">
                                        </div>
                                    
                                    <div class="pt-6 mt-6 border-t border-white/5">
                                        <button id="checkBtn" onclick="checkSolution()" class="w-full py-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-bold text-lg shadow-lg hover:shadow-indigo-500/25 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                                            Verifikasi Desain 🚀
                                        </button>
                                        <div id="feedback-area" class="mt-4 hidden p-4 rounded-lg text-center text-sm font-bold animate-pulse"></div>
                                    </div>
                                </div>

                                {{-- Preview --}}
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
                                                <div><div class="h-4 bg-slate-200 rounded w-32 mb-2"></div><div class="h-3 bg-slate-100 rounded w-24"></div></div>
                                            </div>
                                            <div class="space-y-6 opacity-50"><div class="h-4 bg-slate-200 rounded w-full"></div><div class="h-4 bg-slate-200 rounded w-full"></div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                {{-- Navigation Footer --}}
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
    .nav-link.active { color: #60a5fa; position: relative; } /* Blue-400 */
    .nav-link.active::after { content: ''; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: linear-gradient(to right,#60a5fa,#6366f1); box-shadow: 0 0 12px rgba(96,165,250,0.8); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    
    #animated-bg{ background: radial-gradient(600px circle at 20% 20%, rgba(59,130,246,.15), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(99,102,241,.15), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(139,92,246,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    /* --- CONFIGURATION --- */
    window.SUBBAB_LESSON_IDS = [52, 53, 54, 55]; 
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
        updatePreview();
        
        // Init Micro Sim 52
        setMicroSim52('scroll');
        
        if (activityCompleted) {
            disableExpertUI();
        }
    });

    /* --- 2. MICRO SIMULATORS UI (Lesson 52-54) --- */
    
    // Lesson 52 Sim 1: Fixed vs Scroll
    window.setMicroSim52 = function(mode) {
        const target = document.getElementById('sim52-target');
        target.classList.remove('bg-fixed', 'bg-scroll');
        target.classList.add('bg-' + mode);
        // Force redraw hint
        target.style.backgroundImage = "url('https://grainy-gradients.vercel.app/noise.svg')";
        target.classList.add('bg-cover');
    }

    // Lesson 52 Sim 2: Text Clip
    window.toggleTextClip = function(active) {
        const el = document.getElementById('sim52-text');
        if(active) {
            el.className = "text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-400 transition-all duration-500";
        } else {
            el.className = "text-6xl font-black text-white transition-all duration-500";
        }
    }

    // Lesson 53 Sim 1: Position
    window.setSimPos = function(cls) { 
        const el = document.getElementById('demo-pos');
        el.className = `h-48 w-full bg-black/40 rounded-lg border border-white/10 bg-[url('https://img.icons8.com/fluency/96/tailwind_css.png')] bg-no-repeat transition-all duration-500 relative shadow-inner ${cls}`;
        // Ensure not center if specific class
        if(cls !== 'bg-center') el.classList.remove('bg-center');
    }
    
    // Lesson 54 Sim 2: Gradient
    window.setSimGrad = function(cls) { 
        document.getElementById('demo-grad').className = `h-20 rounded-lg shadow-lg transition-all duration-500 mb-4 border border-white/5 ${cls}`; 
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
        $('#practice-controls').empty();
        renderControls();
        updatePreview();
    }

    function updatePreview() {
        const hero = document.getElementById('target-hero');
        const overlay = document.getElementById('target-overlay');
        
        hero.className = `relative w-full h-[500px] flex items-center justify-center transition-all duration-700 ease-in-out border-b-8 border-blue-500 bg-center bg-no-repeat overflow-hidden group`;
        
        hero.classList.add(userChoices.size);
        hero.classList.add(userChoices.attachment);
        
        if (userChoices.image !== 'none') {
            hero.style.backgroundImage = `url('${userChoices.image}')`;
            hero.classList.remove('bg-slate-800');
        } else {
            hero.style.backgroundImage = 'none';
            hero.classList.add('bg-slate-800');
        }
        
        overlay.className = `absolute inset-0 z-0 transition-all duration-500 ${userChoices.overlay}`;
    }

    window.checkSolution = async function() {
        if(activityCompleted) return;
        let isCorrect = true;
        let errorMsg = "";

        Object.entries(challengeData).forEach(([key, data]) => {
            const correctVal = data.options.find(o => o.correct).val;
            if(userChoices[key] !== correctVal) {
                isCorrect = false;
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
            await saveLessonToDB(55); // Mark last lesson done
            completedLessons.add(55);
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