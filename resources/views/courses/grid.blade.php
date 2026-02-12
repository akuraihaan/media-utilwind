@extends('layouts.landing')
@section('title','Bab 2.2 · Layouting dengan Grid')

@section('content')
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-cyan-500/30 pt-20">

    {{-- BACKGROUND COSMIC LAYER --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-50"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[600px] h-[600px] bg-cyan-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-blue-900/10 rounded-full blur-[100px]"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="cursor-glow"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative z-20">
        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            {{-- STICKY HEADER --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-[#020617]/80 backdrop-blur-2xl border-b border-white/5 px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cyan-500/20 to-transparent border border-cyan-500/20 flex items-center justify-center font-bold text-xs text-cyan-400">2.2</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Layouting dengan Grid</h1>
                        <p class="text-[10px] text-white/50">Two-Dimensional Layout System</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-32 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-cyan-500 to-blue-500 w-0 transition-all duration-500 shadow-[0_0_10px_#0ea5e9]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-cyan-400 font-bold text-xs">0%</span>
                </div>
            </div>

            {{-- CONTENT WRAPPER --}}
            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                
                {{-- LEARNING OBJECTIVES --}}
                <div class="mb-24">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tujuan Pembelajaran
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-cyan-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-cyan-500/10 text-cyan-400 flex items-center justify-center shrink-0 font-bold text-xs">1</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Grid System</h4><p class="text-[11px] text-white/50 leading-relaxed">Memahami paradigma layout dua dimensi (baris & kolom).</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-blue-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-blue-500/10 text-blue-400 flex items-center justify-center shrink-0 font-bold text-xs">2</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Columns & Gap</h4><p class="text-[11px] text-white/50 leading-relaxed">Memahami penggunaan <code>grid-cols</code> dan manajemen spasi.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-sky-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-sky-500/10 text-sky-400 flex items-center justify-center shrink-0 font-bold text-xs">3</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Spanning</h4><p class="text-[11px] text-white/50 leading-relaxed">Memahami cara menggabungkan sel (merge cells) secara responsif.</p></div>
                        </div>
                        <div class="bg-[#1e1e1e] border border-white/5 p-5 rounded-xl flex items-start gap-4 hover:border-teal-500/30 transition group h-full">
                            <div class="w-8 h-8 rounded bg-teal-500/10 text-teal-400 flex items-center justify-center shrink-0 font-bold text-xs">4</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Grid Alignment</h4><p class="text-[11px] text-white/50 leading-relaxed">Memahami <code>justify-items</code> vs <code>items-center</code> dalam konteks grid.</p></div>
                        </div>
                        <div class="bg-gradient-to-br from-cyan-900/40 to-blue-900/40 border border-cyan-500/30 p-5 rounded-xl flex items-start gap-4 hover:shadow-[0_0_20px_rgba(34,211,238,0.2)] transition group h-full col-span-2 md:col-span-2">
                            <div class="w-8 h-8 rounded bg-white/10 text-white flex items-center justify-center shrink-0 font-bold text-xs">🏁</div>
                            <div><h4 class="text-sm font-bold text-white mb-1">Final Mission</h4><p class="text-[11px] text-white/70 leading-relaxed">Live Code: Membangun Layout Galeri Foto.</p></div>
                        </div>
                    </div>
                </div>

                <article class="space-y-40">
                    
                    {{-- LESSON 1: KONSEP GRID (ID 34) --}}
                    {{-- LESSON 1: KONSEP GRID (ID 34) --}}
    <section id="section-34" class="lesson-section scroll-mt-32" data-lesson-id="34">
        <div class="space-y-10">
            <div class="space-y-4 border-l-4 border-cyan-500 pl-6">
                <span class="text-cyan-400 font-mono text-xs uppercase tracking-widest">Lesson 2.2.1</span>
                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                    Filosofi Grid & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Struktur Kolom</span>
                </h2>
            </div>
            
            {{-- Materi Part 1 --}}
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-cyan-600 flex items-center justify-center text-[10px] text-white">A</span> Mengapa Kita Membutuhkan Grid?</h3>
                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                    <p>
                        Selama bertahun-tahun, Flexbox telah menjadi "raja" tata letak CSS. Namun, seperti yang dijelaskan dalam buku <em>"Tailwind CSS by Ivaylo Gerchev"</em>, Flexbox memiliki keterbatasan mendasar: ia adalah sistem <strong>satu dimensi</strong>. Flexbox sangat hebat dalam mengatur elemen dalam satu baris <em>atau</em> satu kolom, tetapi ia mulai kesulitan ketika harus menangani hubungan antara baris dan kolom secara bersamaan.
                    </p>
                    <p>
                        Di sinilah <strong>CSS Grid Layout</strong> masuk. Grid adalah sistem tata letak <strong>dua dimensi</strong> pertama yang sejati di web. Dengan Grid, Anda tidak lagi menyusun konten elemen per elemen. Sebaliknya, Anda mendefinisikan "papan permainan" (scaffolding) terlebih dahulu—garis-garis vertikal dan horizontal—lalu menempatkan konten ke dalam kotak-kotak yang tercipta.
                    </p>
                    <p>
                        Di Tailwind CSS, mengaktifkan mode ini semudah menambahkan kelas <code>grid</code> pada elemen pembungkus (container). Ini mengubah <em>formatting context</em> elemen tersebut, memungkinkan penggunaan properti-properti grid yang kuat pada elemen anak (children).
                    </p>
                </div>
            </div>

            {{-- Materi Part 2 --}}
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-blue-600 flex items-center justify-center text-[10px] text-white">B</span> Logika Template Columns</h3>
                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                    <p>
                        Pondasi utama dari Grid adalah kolom. Dalam CSS murni, kita menggunakan properti <code>grid-template-columns</code>. Tailwind menyederhanakan sintaks yang rumit ini menjadi utilitas yang mudah diingat: <code>grid-cols-{n}</code>.
                    </p>
                    <p>
                        Misalnya, kelas <code>grid-cols-3</code> akan secara otomatis membagi container menjadi 3 kolom dengan lebar yang sama persis. Di belakang layar, Tailwind menggunakan unit <code>fr</code> (fractional unit), yaitu <code>repeat(3, minmax(0, 1fr))</code>. Unit <code>1fr</code> ini sangat ajaib karena ia menghitung "ruang yang tersedia" setelah dikurangi gap dan padding, lalu membaginya secara proporsional. Ini membuat grid di Tailwind sangat fluid dan anti-pecah.
                    </p>
                    <p>
                        Dalam buku <em>"Modern CSS with Tailwind"</em>, Noel Rappin menekankan pentingnya responsivitas. Anda tidak perlu menulis media query manual. Cukup gunakan prefix: <code>grid-cols-1 md:grid-cols-3 lg:grid-cols-4</code>. Ini berarti: "Mulai dengan 1 kolom di HP, berubah jadi 3 kolom di Tablet, dan 4 kolom di Laptop".
                    </p>
                </div>
            </div>

            {{-- Materi Part 3 --}}
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-sky-600 flex items-center justify-center text-[10px] text-white">C</span> Manajemen Celah (Gap)</h3>
                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                    <p>
                        Di masa lalu, memberikan jarak antar kolom adalah mimpi buruk yang melibatkan margin negatif dan kalkulasi lebar yang rumit (seperti <code>width: 33.33%</code> dikurangi margin). Grid memecahkan masalah ini dengan properti <code>gap</code>.
                    </p>
                    <p>
                        Di Tailwind, utilitas <code>gap-{n}</code> (misalnya <code>gap-4</code> atau <code>gap-8</code>) menciptakan saluran kosong (gutter) di antara baris dan kolom. Celah ini tidak menambah lebar container, melainkan "mendorong" konten ke dalam. Anda juga bisa mengontrol celah secara terpisah menggunakan <code>gap-x-{n}</code> untuk horizontal dan <code>gap-y-{n}</code> untuk vertikal.
                    </p>
                </div>
            </div>

                            {{-- SIMULATOR 1 --}}
                            <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-8 shadow-2xl relative group hover:border-cyan-500/30 transition-all">
                                <div class="flex flex-col md:flex-row justify-between items-end mb-6 gap-6">
                                    <div>
                                        <h3 class="text-lg font-bold text-white mb-1">🏗️ Grid Column Simulator</h3>
                                        <p class="text-xs text-white/50">Klik tombol untuk mengubah struktur kolom secara real-time.</p>
                                    </div>
                                    <div class="flex gap-2 bg-white/5 p-1 rounded-lg">
                                        <button onclick="updateGridCols(1, this)" class="col-btn px-4 py-2 text-xs rounded bg-white/5 text-white/50 hover:bg-white/10 transition">grid-cols-1</button>
                                        <button onclick="updateGridCols(2, this)" class="col-btn px-4 py-2 text-xs rounded bg-cyan-600 text-white shadow-lg transition active-btn">grid-cols-2</button>
                                        <button onclick="updateGridCols(3, this)" class="col-btn px-4 py-2 text-xs rounded bg-white/5 text-white/50 hover:bg-white/10 transition">grid-cols-3</button>
                                        <button onclick="updateGridCols(4, this)" class="col-btn px-4 py-2 text-xs rounded bg-white/5 text-white/50 hover:bg-white/10 transition">grid-cols-4</button>
                                    </div>
                                </div>
                                <div id="demo-grid-cols" class="grid grid-cols-2 gap-4 p-6 bg-black/40 rounded-xl border-2 border-dashed border-white/10 transition-all duration-500 overflow-hidden relative">
                                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 pointer-events-none"></div>
                                    <div class="h-20 bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center font-bold rounded-lg text-xl relative z-10">1</div>
                                    <div class="h-20 bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center font-bold rounded-lg text-xl relative z-10">2</div>
                                    <div class="h-20 bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center font-bold rounded-lg text-xl relative z-10">3</div>
                                    <div class="h-20 bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center font-bold rounded-lg text-xl relative z-10">4</div>
                                </div>
                                <div class="mt-4 text-center"><code id="code-grid-cols" class="bg-black/50 px-4 py-2 rounded text-cyan-300 text-xs font-mono border border-white/10">class="grid grid-cols-2 gap-4"</code></div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 2: ALIGNMENT (ID 35) --}}
                   <section id="section-35" class="lesson-section scroll-mt-32" data-lesson-id="35">
        <div class="space-y-10">
            <div class="space-y-4 border-l-4 border-blue-500 pl-6">
                <span class="text-blue-400 font-mono text-xs uppercase tracking-widest">Lesson 2.2.2</span>
                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                    Penjajaran Grid <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">(Alignment)</span>
                </h2>
            </div>

            {{-- Materi Part 1 --}}
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-blue-600 flex items-center justify-center text-[10px] text-white">A</span> Justify Items (Sumbu X)</h3>
                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                    <p>
                        Banyak pemula Tailwind yang bingung antara <code>justify-content</code> dan <code>justify-items</code>. Dalam konteks Grid, perbedaannya sangat penting.
                    </p>
                    <p>
                        <code>justify-items-*</code> mengontrol posisi elemen <strong>di dalam sel grid-nya sendiri</strong> pada sumbu horizontal (baris).
                    </p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><strong><code>justify-items-start</code></strong>: Elemen merapat ke kiri sel. Lebarnya menyesuaikan konten (tidak stretch).</li>
                        <li><strong><code>justify-items-center</code></strong>: Elemen berada tepat di tengah horizontal sel.</li>
                        <li><strong><code>justify-items-end</code></strong>: Elemen merapat ke kanan sel.</li>
                        <li><strong><code>justify-items-stretch</code></strong> (Default): Elemen ditarik paksa agar lebarnya memenuhi lebar sel sepenuhnya. Inilah mengapa secara default div di dalam grid terlihat penuh.</li>
                    </ul>
                </div>
            </div>

            {{-- Materi Part 2 --}}
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-[10px] text-white">B</span> Align Items (Sumbu Y)</h3>
                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                    <p>
                        Untuk sumbu vertikal, kita menggunakan properti <code>align-items</code>, yang di Tailwind ditulis sebagai <code>items-*</code>. Ini mengontrol posisi elemen di dalam sel grid secara vertikal.
                    </p>
                    <p>
                        Kombinasi paling populer dalam desain UI modern adalah:
                        <br><code>justify-items-center items-center</code>.
                        <br>Ini adalah "mantra ajaib" untuk meletakkan konten tepat di tengah-tengah kotak grid, terlepas dari berapapun tinggi atau lebar kotak tersebut. Ini jauh lebih ringkas dibandingkan teknik margin auto atau absolute positioning zaman dulu.
                    </p>
                </div>
            </div>

                            {{-- SIMULATOR 2 --}}
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
                                            <div class="w-10 h-10 bg-cyan-600 rounded flex items-center justify-center text-xs font-bold text-white shadow-lg">A</div>
                                            <div class="w-10 h-10 bg-cyan-600 rounded flex items-center justify-center text-xs font-bold text-white shadow-lg">B</div>
                                            <div class="w-10 h-10 bg-cyan-600 rounded flex items-center justify-center text-xs font-bold text-white shadow-lg">C</div>
                                            <div class="w-10 h-10 bg-cyan-600 rounded flex items-center justify-center text-xs font-bold text-white shadow-lg">D</div>
                                        </div>
                                        <code class="text-[10px] text-cyan-400 block text-center bg-black/40 p-1 rounded" id="code-justify">justify-items-center</code>
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
                                            <div class="w-full h-8 bg-fuchsia-600 rounded flex items-center justify-center text-xs font-bold text-white shadow-lg">A</div>
                                            <div class="w-full h-8 bg-fuchsia-600 rounded flex items-center justify-center text-xs font-bold text-white shadow-lg">B</div>
                                        </div>
                                        <code class="text-[10px] text-fuchsia-400 block text-center bg-black/40 p-1 rounded" id="code-align">items-center</code>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 3: SPANNING (ID 36) --}}
                    <section id="section-36" class="lesson-section scroll-mt-32" data-lesson-id="36">
        <div class="space-y-10">
            <div class="space-y-4 border-l-4 border-sky-500 pl-6">
                <span class="text-sky-400 font-mono text-xs uppercase tracking-widest">Lesson 2.2.3</span>
                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                    Teknik Span <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-cyan-500">(Penggabungan Sel)</span>
                </h2>
            </div>

            {{-- Materi Part 1 --}}
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-sky-600 flex items-center justify-center text-[10px] text-white">A</span> Menguasai Col-Span</h3>
                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                    <p>
                        Fitur paling kuat dari Grid yang sulit ditiru oleh Flexbox adalah kemampuan menggabungkan sel (merge cells). Bayangkan Anda sedang mendesain sebuah layout dashboard. Anda mungkin ingin Header membentang penuh di atas, Sidebar di kiri, dan Konten Utama di kanan.
                    </p>
                    <p>
                        Tailwind menyediakan utilitas <code>col-span-{n}</code> untuk ini. Angka <em>n</em> menunjukkan berapa banyak kolom yang ingin "dimakan" oleh elemen tersebut.
                    </p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><strong><code>col-span-2</code></strong>: Membuat elemen melebar ke samping, mengambil jatah 2 kolom.</li>
                        <li><strong><code>col-span-full</code></strong>: Utilitas khusus yang memerintahkan elemen untuk mengambil <em>seluruh</em> lebar grid (start line 1 sampai end line -1), sangat berguna untuk membuat header atau footer tanpa perlu tahu jumlah kolom pasti gridnya.</li>
                    </ul>
                </div>
            </div>

            {{-- Materi Part 2 --}}
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-cyan-600 flex items-center justify-center text-[10px] text-white">B</span> Menguasai Row-Span</h3>
                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                    <p>
                        Sama seperti kolom, Anda bisa menggabungkan baris vertikal menggunakan <code>row-span-{n}</code>. Ini memungkinkan pembuatan layout "Bento Grid" atau "Masonry Style" yang populer, di mana satu kartu konten penting bisa memiliki tinggi dua kali lipat dibanding kartu lainnya.
                    </p>
                    <p>
                        Dalam buku <em>"Ultimate Tailwind CSS Handbook"</em>, teknik ini sering digunakan untuk membuat sidebar navigasi yang tinggi penuh, sementara di sebelahnya terdapat beberapa baris widget dashboard.
                    </p>
                </div>
            </div>

                            {{-- DEMO BENTO GRID --}}
                            <div class="bg-[#0b0f19] p-6 rounded-2xl border border-white/10 shadow-2xl relative group">
                                <div class="absolute -top-3 -right-3 bg-orange-600 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-lg">Bento Grid Demo</div>
                                <div class="grid grid-cols-3 grid-rows-3 gap-3 h-64">
                                    <div class="row-span-3 bg-indigo-600/20 border border-indigo-500/50 rounded-xl flex flex-col items-center justify-center text-indigo-400 font-bold p-2 text-center text-xs hover:bg-indigo-600/30 transition">Sidebar<span class="opacity-50 text-[9px] mt-1 font-mono">row-span-3</span></div>
                                    <div class="col-span-2 bg-pink-600/20 border border-pink-500/50 rounded-xl flex flex-col items-center justify-center text-pink-400 font-bold text-xs hover:bg-pink-600/30 transition">Header <span class="opacity-50 text-[9px] mt-1 font-mono">col-span-2</span></div>
                                    <div class="bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-white/30 text-xs hover:bg-white/10 transition">Content A</div>
                                    <div class="bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-white/30 text-xs hover:bg-white/10 transition">Content B</div>
                                    <div class="col-span-2 bg-cyan-600/20 border border-cyan-500/50 rounded-xl flex items-center justify-center text-cyan-400 font-bold text-xs hover:bg-cyan-600/30 transition">Footer <span class="opacity-50 text-[9px] mt-1 font-mono">col-span-2</span></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- LESSON 4: GRID ROWS (ID 37) --}}
                    <section id="section-37" class="lesson-section scroll-mt-32" data-lesson-id="37">
        <div class="space-y-10">
            <div class="flex items-center gap-4 mb-8">
                <h2 class="text-3xl font-bold text-white">4. Grid Template Rows</h2>
                <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
            </div>
            
            <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                <p>
                    Secara default, Grid bersifat <em>implicit rows</em>: ia akan membuat baris baru secara otomatis sebanyak yang dibutuhkan konten. Namun, terkadang kita perlu mendefinisikan tinggi baris secara eksplisit.
                </p>
                <p>
                    Gunakan utilitas <code class="text-blue-400">grid-rows-{n}</code> untuk membagi tinggi container menjadi <em>n</em> baris yang sama rata. Ini sering digunakan bersamaan dengan <code>grid-flow-col</code> untuk membuat layout yang mengalir secara vertikal terlebih dahulu (seperti daftar nama di buku telepon).
                </p>
            </div>

            <div class="bg-[#1e1e1e] p-6 rounded-xl border border-white/10">
                <div class="flex justify-between mb-3"><span class="text-sm font-bold text-white">Preview</span><code class="text-[10px] text-blue-400 bg-white/5 px-2 py-1 rounded">grid-rows-3 grid-flow-col</code></div>
                <div class="grid grid-rows-3 grid-flow-col gap-2 h-32">
                    <div class="bg-white/5 rounded border border-white/5 flex items-center justify-center text-xs text-white/50">1</div>
                    <div class="bg-white/5 rounded border border-white/5 flex items-center justify-center text-xs text-white/50">2</div>
                    <div class="bg-white/5 rounded border border-white/5 flex items-center justify-center text-xs text-white/50">3</div>
                    <div class="bg-white/5 rounded border border-white/5 flex items-center justify-center text-xs text-white/50">4</div>
                    <div class="bg-white/5 rounded border border-white/5 flex items-center justify-center text-xs text-white/50">5</div>
                    <div class="bg-white/5 rounded border border-white/5 flex items-center justify-center text-xs text-white/50">6</div>
                </div>
            </div>
        </div>
    </section>

                    <section id="section-38" class="lesson-section scroll-mt-32" data-lesson-id="38">
        <div class="space-y-10">
            <div class="flex items-center gap-4 mb-8">
                <h2 class="text-3xl font-bold text-white">5. Arbitrary Columns (JIT)</h2>
                <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
            </div>
            
            <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                <p>
                    Terkadang, pembagian kolom standar (1/3, 1/4, dll) tidak cukup. Anda mungkin membutuhkan sidebar yang lebarnya tepat <code>250px</code> dan konten utama mengisi sisanya.
                </p>
                <p>
                    Berkat engine JIT (Just-in-Time) Tailwind, Anda bisa menggunakan <strong>Nilai Arbitrer</strong> dengan tanda kurung siku <code class="text-red-400">[]</code>. Contoh: <code>grid-cols-[250px_1fr]</code> akan membuat kolom pertama fix 250px dan kolom kedua fleksibel memenuhi sisa ruang. Ini memberikan fleksibilitas CSS asli langsung di dalam kelas utility.
                </p>
            </div>

            <div class="bg-[#1e1e1e] p-6 rounded-xl border border-white/10">
                <div class="flex justify-between mb-3"><span class="text-sm font-bold text-white">Preview</span><code class="text-[10px] text-red-400 bg-white/5 px-2 py-1 rounded">grid-cols-[80px_1fr_80px]</code></div>
                <div class="grid grid-cols-[80px_1fr_80px] gap-2 h-32">
                    <div class="bg-red-500/10 border border-red-500/30 rounded flex flex-col items-center justify-center text-[10px] text-red-400 font-bold">Fixed<br>80px</div>
                    <div class="bg-green-500/10 border border-green-500/30 rounded flex flex-col items-center justify-center text-[10px] text-green-400 font-bold">Flexible<br>(1fr)</div>
                    <div class="bg-red-500/10 border border-red-500/30 rounded flex flex-col items-center justify-center text-[10px] text-red-400 font-bold">Fixed<br>80px</div>
                </div>
            </div>
        </div>
    </section>

    {{-- LESSON 6: AUTO FLOW (ID 39) --}}
    <section id="section-39" class="lesson-section scroll-mt-32" data-lesson-id="39">
        <div class="space-y-10">
            <div class="space-y-4 border-l-4 border-teal-500 pl-6">
                <span class="text-teal-400 font-mono text-xs uppercase tracking-widest">Lesson 2.2.4</span>
                <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                    Grid Auto <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-emerald-500">Flow & Dense</span>
                </h2>
            </div>

            <div class="space-y-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-teal-600 flex items-center justify-center text-[10px] text-white">A</span> Algoritma Penempatan Grid</h3>
                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                    <p>
                        Salah satu aspek Grid yang paling cerdas adalah algoritma penempatannya (Auto-placement). Secara default, Tailwind menggunakan <code>grid-flow-row</code>. Ini berarti elemen akan mengisi baris pertama dari kiri ke kanan sampai penuh, lalu membuat baris baru di bawahnya (Pola Z).
                    </p>
                    <p>
                        Namun, Anda bisa mengubahnya menjadi <code>grid-flow-col</code>. Dengan ini, elemen akan mengisi kolom pertama dari atas ke bawah sampai penuh, baru pindah ke kolom berikutnya di sebelah kanan (Pola N).
                    </p>
                </div>
            </div>
            
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-2"><span class="w-6 h-6 rounded bg-emerald-600 flex items-center justify-center text-[10px] text-white">B</span> Grid Flow Dense</h3>
                <div class="prose prose-invert max-w-none text-white/70 text-lg leading-relaxed space-y-4">
                    <p>
                        Fitur yang kurang diketahui tapi sangat powerful adalah <code>grid-flow-dense</code>. Jika Anda memiliki grid dengan item-item yang ukurannya berbeda-beda (ada yang span-2, ada yang span-1), seringkali akan muncul "lubang" kosong dalam layout karena item besar tidak muat di sisa ruang baris tersebut.
                    </p>
                    <p>
                        Dengan menambahkan <code>grid-flow-dense</code>, Anda memerintahkan browser untuk: "Jangan biarkan ada lubang. Jika item besar tidak muat, cari item kecil berikutnya yang muat dan taruh di celah tersebut, meskipun itu berarti mengacak urutan aslinya." Ini sangat bagus untuk layout galeri foto bergaya Pinterest.
                    </p>
                </div>
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
        </div>
    </section>

                    {{-- FINAL MISSION (ID 40) --}}
                    <section id="section-40" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="40" data-type="activity">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl group hover:border-cyan-500/30 transition-all duration-500">
                            
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-cyan-600/20 blur-[100px] rounded-full pointer-events-none"></div>

                            <div class="flex items-center gap-4 mb-6 relative z-10">
                                <div class="p-3 bg-blue-600 rounded-xl text-white shadow-lg shadow-blue-500/30">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-white">Aktivitas 2.2: Gallery Grid</h2>
                                    <p class="text-cyan-400 text-sm">Lengkapi kode untuk membuat layout galeri 2x2 yang berada di tengah.</p>
                                </div>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-6 max-w-6xl mx-auto">
                                {{-- EDITOR --}}
                                <div class="bg-[#1e1e1e] rounded-xl border border-white/10 flex flex-col overflow-hidden h-full">
                                    
                                    {{-- LOCK OVERLAY --}}
                                    <div id="lockOverlay" class="hidden absolute inset-0 bg-[#050912]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-6 border-4 border-emerald-500/20 m-1 rounded-lg">
                                        <div class="w-24 h-24 bg-emerald-500/10 rounded-full flex items-center justify-center mb-6 border border-emerald-500/50 shadow-[0_0_50px_rgba(16,185,129,0.3)] animate-bounce">
                                            <svg class="w-12 h-12 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h3 class="text-3xl font-black text-white mb-2 tracking-tight">SELESAI!</h3>
                                        <p class="text-base text-white/60 mb-8 max-w-xs">Anda telah menaklukkan Grid. Lanjut ke materi berikutnya.</p>
                                        <button disabled class="px-8 py-3 rounded-full bg-white/5 border border-white/10 text-white/30 text-xs font-bold cursor-not-allowed uppercase tracking-widest">Review Mode</button>
                                    </div>

                                    <div class="bg-[#2d2d2d] px-4 py-3 border-b border-white/5 flex justify-between items-center">
                                        <span class="text-xs text-gray-400 font-mono">CSS Controls</span>
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

                                {{-- PREVIEW --}}
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
    .nav-link.active::after { content: ''; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: linear-gradient(to right,#22d3ee,#3b82f6); box-shadow: 0 0 12px rgba(34,211,238,.8); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }

    #animated-bg{ background: radial-gradient(600px circle at 20% 20%, rgba(168,85,247,.15), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(217,70,239,.15), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(34,211,238,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    
    .nav-item.active { color: #c084fc; background: rgba(192,132,252,0.05); font-weight: 600; }
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
    const ACTIVITY_ID = 4; // ID untuk aktivitas Grid

    document.addEventListener('DOMContentLoaded', () => {
        initSidebarScroll();
        initLessonObserver();
        initVisualEffects();
        updateProgressUI();
        if (activityCompleted) {
            markActivityCompleteUI();
            unlockNext();
        }
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
        const code = document.getElementById('code-justify');
        demo.classList.remove('justify-items-start', 'justify-items-center', 'justify-items-end');
        demo.classList.add(val);
        code.innerText = val;
    }
    function updateAlign(val) {
        const demo = document.getElementById('demo-align');
        const code = document.getElementById('code-align');
        demo.classList.remove('items-start', 'items-center', 'items-end');
        demo.classList.add(val);
        code.innerText = val;
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
            await fetch('/activity/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify({ activity_id: ACTIVITY_ID, score: 100 }) });
            await saveLessonToDB(ACTIVITY_LESSON_ID); 
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
        document.getElementById('lockOverlay').classList.remove('hidden'); // Show Overlay
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
</script>
@endsection