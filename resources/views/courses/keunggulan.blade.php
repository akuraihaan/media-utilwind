@extends('layouts.landing')
@section('title','Bab 1.5 · Keunggulan Tailwind CSS')

@section('content')
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-fuchsia-500/30">

    <div class="fixed inset-0 -z-50 pointer-events-none">
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
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500/20 to-transparent border border-purple-500/20 flex items-center justify-center font-bold text-xs text-purple-400">1.5</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Keunggulan Tailwind CSS</h1>
                        <p class="text-[10px] text-white/50">Estimasi: 20 Menit</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-24 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-purple-400 to-cyan-500 w-0 transition-all duration-500 shadow-[0_0_10px_#d946ef]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-purple-400 font-bold text-xs">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                <article class="space-y-32">
                    
                    <section id="efficiency" class="lesson-section scroll-mt-32" data-lesson-id="{{ $subbab15LessonIds[0] ?? 20 }}">
                        <div class="space-y-8">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-300 text-[10px] font-bold uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-pulse"></span>
                                Efisiensi UI
                            </div>
                            
                            <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                Nama Kelas yang <br> 
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400">Intuitif & Cepat</span>
                            </h2>
                            
                            <p class="text-lg text-white/70 leading-relaxed max-w-3xl">
                                Tailwind menghasilkan nama kelas yang hampir sesuai dengan tujuan penggunaannya. Anda tidak perlu menghafal kode aneh, yang membuat <strong>kurva pembelajarannya menjadi sangat mudah</strong>.
                            </p>

                            <div class="grid md:grid-cols-2 gap-6 mt-6">
                                <div class="bg-[#0f141e] p-6 rounded-2xl border border-white/5 relative overflow-hidden group">
                                    <div class="absolute top-0 right-0 p-3 opacity-10 text-4xl grayscale group-hover:grayscale-0 transition">🐢</div>
                                    <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                                        <span class="text-red-400">❌</span> CSS Tradisional
                                    </h3>
                                    <p class="text-sm text-white/50 mb-4">Harus membuka file CSS terpisah untuk memahami apa arti kelas ini.</p>
                                    <code class="block bg-black/30 p-3 rounded text-xs text-red-300 font-mono">.card-wrapper-inner</code>
                                </div>
                                <div class="bg-[#0f141e] p-6 rounded-2xl border border-purple-500/30 shadow-lg shadow-purple-900/20 relative overflow-hidden group">
                                    <div class="absolute top-0 right-0 p-3 opacity-10 text-4xl grayscale group-hover:grayscale-0 transition">🚀</div>
                                    <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                                        <span class="text-purple-400">✔</span> Tailwind CSS
                                    </h3>
                                    <p class="text-sm text-white/50 mb-4">Langsung mengerti hanya dengan membacanya (Self-explanatory).</p>
                                    <code class="block bg-black/30 p-3 rounded text-xs text-purple-300 font-mono">p-4 bg-white rounded shadow</code>
                                </div>
                            </div>

                            <div class="bg-purple-500/5 border-l-4 border-purple-500 p-6 rounded-r-xl">
                                <p class="text-purple-100 italic">
                                    "Merancang User Interface (UI) menjadi lebih efisien karena tidak wajib menulis aturan CSS sendiri. Kebutuhan desain terpenuhi melalui kelas-kelas bawaan."
                                </p>
                            </div>
                        </div>
                    </section>

                    <section id="responsive" class="lesson-section scroll-mt-32" data-lesson-id="{{ $subbab15LessonIds[1] ?? 21 }}">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">1.5.2 Responsif & Performa</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-8 mb-12 relative overflow-hidden shadow-2xl">
                            <div class="flex justify-between items-end mb-6">
                                <div>
                                    <h3 class="text-lg font-bold text-white mb-1">📱 Simulator Breakpoint</h3>
                                    <p class="text-sm text-white/60">Klik tombol untuk simulasi ukuran layar.</p>
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="setDevice('w-[320px]')" class="px-3 py-1.5 rounded bg-white/10 hover:bg-white/20 text-[10px] uppercase font-bold text-white transition border border-white/5">Mobile</button>
                                    <button onclick="setDevice('w-[480px]')" class="px-3 py-1.5 rounded bg-white/10 hover:bg-white/20 text-[10px] uppercase font-bold text-white transition border border-white/5">Tablet</button>
                                    <button onclick="setDevice('w-full')" class="px-3 py-1.5 rounded bg-white/10 hover:bg-white/20 text-[10px] uppercase font-bold text-white transition border border-white/5">Desktop</button>
                                </div>
                            </div>

                            <div class="w-full bg-black/50 rounded-xl border border-white/5 h-[260px] flex justify-center items-center relative overflow-hidden" id="device-container">
                                <div id="resizable-box" class="w-[320px] h-[90%] bg-[#1e1e1e] border-x-4 border-purple-500/20 transition-all duration-500 ease-in-out flex flex-col p-4 relative shadow-2xl">
                                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-purple-500 text-white px-2 rounded-b text-[9px] font-bold" id="device-label">Mobile</div>
                                    
                                    <div class="grid grid-cols-1 gap-2 w-full mt-4 transition-all duration-500" id="demo-grid">
                                        <div class="bg-purple-500/10 border border-purple-500/30 p-3 rounded text-center">
                                            <div class="w-8 h-8 bg-purple-500/20 rounded-full mx-auto mb-2"></div>
                                            <div class="h-2 bg-purple-500/20 rounded w-16 mx-auto"></div>
                                        </div>
                                        <div class="bg-cyan-500/10 border border-cyan-500/30 p-3 rounded text-center">
                                            <div class="w-8 h-8 bg-cyan-500/20 rounded-full mx-auto mb-2"></div>
                                            <div class="h-2 bg-cyan-500/20 rounded w-16 mx-auto"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-auto pt-4 border-t border-white/5 text-center">
                                        <p class="text-[10px] text-purple-400 font-mono">class="grid-cols-1 md:grid-cols-2"</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-8 items-center">
                            <div>
                                <h3 class="text-xl font-bold text-white mb-4">⚡ Kecepatan Pemuatan (Browser Loading)</h3>
                                <p class="text-white/70 text-sm leading-relaxed mb-4">
                                    Versi produksi dari file CSS Tailwind hanya berisi kumpulan aturan yang <strong>benar-benar digunakan</strong> di HTML Anda.
                                </p>
                                <p class="text-white/70 text-sm leading-relaxed">
                                    Teknik ini disebut <em>Tree Shaking</em> atau <em>Purging</em>. Tailwind membuang kelas yang tidak terpakai, membuat ukuran file sangat kecil.
                                </p>
                            </div>
                            <div class="bg-[#1e1e1e] p-6 rounded-2xl border border-white/5">
                                <div class="space-y-6">
                                    <div>
                                        <div class="flex justify-between text-xs text-white/60 mb-2">
                                            <span>Framework Lain (Full)</span>
                                            <span>~300KB</span>
                                        </div>
                                        <div class="h-3 bg-white/5 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-red-500 to-red-600 w-full rounded-full"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-xs text-white/60 mb-2">
                                            <span>Tailwind (Dev Mode)</span>
                                            <span>~3MB (Besar)</span>
                                        </div>
                                        <div class="h-3 bg-white/5 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-yellow-500 to-yellow-600 w-full animate-pulse rounded-full"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-xs text-white/60 mb-2">
                                            <span class="text-purple-400 font-bold">Tailwind (Production)</span>
                                            <span class="text-purple-400 font-bold">~10KB (Sangat Kecil)</span>
                                        </div>
                                        <div class="h-3 bg-white/5 rounded-full overflow-hidden relative">
                                            <div class="h-full bg-gradient-to-r from-purple-400 to-cyan-500 w-[5%] rounded-full shadow-[0_0_10px_#a855f7]"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="dry" class="lesson-section scroll-mt-32" data-lesson-id="{{ $subbab15LessonIds[2] ?? 22 }}">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">1.5.3 Prinsip DRY (Don't Repeat Yourself)</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <p class="text-white/70 mb-6">
                            Tailwind memungkinkan Anda mempersiapkan <strong>komponen yang dapat digunakan kembali</strong> (reusable component). Ini mendukung strategi DRY untuk menjaga kebersihan kode dan memudahkan pemeliharaan.
                        </p>

                        <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-8 relative overflow-hidden text-center shadow-2xl">
                            <p class="text-xs text-white/40 mb-8 font-mono">Klik tombol "Update Master" untuk melihat efeknya pada seluruh komponen turunan.</p>
                            
                            <div class="flex flex-col items-center gap-8 relative z-10">
                                <div class="relative group">
                                    <div class="absolute -inset-4 bg-purple-500/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition duration-700"></div>
                                    <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-[9px] text-purple-400 uppercase tracking-widest font-bold">Master Component</span>
                                    <button onclick="triggerUpdate()" id="master-btn" class="relative bg-blue-600 text-white font-bold py-3 px-8 rounded-lg transition-all duration-500 border border-white/10 shadow-lg transform active:scale-95">
                                        Tombol Utama
                                    </button>
                                </div>

                                <div class="flex gap-20 text-white/10 text-2xl">
                                    <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                    <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                    <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                </div>

                                <div class="flex flex-wrap justify-center gap-6">
                                    <div class="child-component bg-blue-600 text-white font-bold py-2 px-6 rounded-lg opacity-70 scale-90 transition-all duration-500 border border-transparent">Halaman A</div>
                                    <div class="child-component bg-blue-600 text-white font-bold py-2 px-6 rounded-lg opacity-70 scale-90 transition-all duration-500 border border-transparent">Halaman B</div>
                                    <div class="child-component bg-blue-600 text-white font-bold py-2 px-6 rounded-lg opacity-70 scale-90 transition-all duration-500 border border-transparent">Halaman C</div>
                                </div>
                            </div>

                            <button onclick="triggerUpdate()" class="mt-10 px-6 py-2 rounded-full bg-white/5 border border-white/10 hover:bg-white/10 text-xs transition flex items-center gap-2 mx-auto text-purple-300">
                                <span>🔄</span> Ubah Desain Master
                            </button>
                        </div>

                        <div class="mt-8 p-6 bg-purple-500/5 border border-purple-500/10 rounded-xl">
                            <p class="text-sm text-purple-200 leading-relaxed">
                                <strong>Systematic Coherence:</strong> Framework ini membantu menjaga konsistensi visual di seluruh komponen situs web tanpa membatasi kreativitas.
                            </p>
                        </div>
                    </section>

                    <section id="quiz-1-5" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="{{ $subbab15LessonIds[3] ?? 23 }}" data-manual="true">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl text-center group hover:border-purple-500/30 transition-all duration-500">
                            
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-2/3 h-1 bg-gradient-to-r from-transparent via-purple-500 to-transparent opacity-50 blur-sm"></div>

                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-300 text-[10px] font-bold uppercase mb-4 tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-pulse"></span>
                                Evaluasi 1.5
                            </div>
                            <h2 class="text-3xl font-black text-white mb-6">Uji Pemahaman</h2>

                            <div id="quiz-container" class="max-w-xl mx-auto min-h-[300px] relative">
                                <div class="flex justify-between mb-6 px-2">
                                    <div class="h-1 flex-1 bg-white/10 rounded-full overflow-hidden mr-2">
                                        <div id="quiz-progress" class="h-full bg-purple-500 w-[33%] transition-all duration-500"></div>
                                    </div>
                                    <span id="question-counter" class="text-[10px] text-purple-400 font-bold">1/3</span>
                                </div>

                                <div id="q1" class="quiz-step transition-all duration-500 absolute inset-0">
                                    <p class="font-bold text-white mb-6 text-lg">1. Mengapa kurva pembelajaran Tailwind dianggap mudah?</p>
                                    <div class="space-y-3">
                                        <button onclick="nextQuestion(1, false, this)" class="quiz-opt w-full text-left p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition group flex justify-between items-center">
                                            <span>Karena wajib menghafal semua kode CSS manual</span>
                                            <span class="opacity-0 group-hover:opacity-100 text-xs">✖</span>
                                        </button>
                                        <button onclick="nextQuestion(1, true, this)" class="quiz-opt w-full text-left p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition group flex justify-between items-center">
                                            <span>Karena nama kelas hampir sesuai dengan tujuannya</span>
                                            <span class="opacity-0 group-hover:opacity-100 text-xs text-green-400">✔</span>
                                        </button>
                                        <button onclick="nextQuestion(1, false, this)" class="quiz-opt w-full text-left p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition group flex justify-between items-center">
                                            <span>Karena hanya ada sedikit fitur</span>
                                            <span class="opacity-0 group-hover:opacity-100 text-xs">✖</span>
                                        </button>
                                    </div>
                                </div>

                                <div id="q2" class="quiz-step transition-all duration-500 absolute inset-0 translate-x-[120%] opacity-0 pointer-events-none">
                                    <p class="font-bold text-white mb-6 text-lg">2. Bagaimana Tailwind menangani ukuran file di produksi?</p>
                                    <div class="space-y-3">
                                        <button onclick="nextQuestion(2, false, this)" class="quiz-opt w-full text-left p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition group flex justify-between items-center">
                                            <span>Memuat semua ribuan kelas bawaan</span>
                                            <span class="opacity-0 group-hover:opacity-100 text-xs">✖</span>
                                        </button>
                                        <button onclick="nextQuestion(2, true, this)" class="quiz-opt w-full text-left p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition group flex justify-between items-center">
                                            <span>Hanya memuat kelas yang benar-benar digunakan</span>
                                            <span class="opacity-0 group-hover:opacity-100 text-xs text-green-400">✔</span>
                                        </button>
                                    </div>
                                </div>

                                <div id="q3" class="quiz-step transition-all duration-500 absolute inset-0 translate-x-[120%] opacity-0 pointer-events-none">
                                    <p class="font-bold text-white mb-6 text-lg">3. Fitur "Reusable Component" mendukung prinsip apa?</p>
                                    <div class="space-y-3">
                                        <button onclick="nextQuestion(3, true, this)" class="quiz-opt w-full text-left p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition group flex justify-between items-center">
                                            <span>DRY (Don't Repeat Yourself)</span>
                                            <span class="opacity-0 group-hover:opacity-100 text-xs text-green-400">✔</span>
                                        </button>
                                        <button onclick="nextQuestion(3, false, this)" class="quiz-opt w-full text-left p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition group flex justify-between items-center">
                                            <span>WET (Write Everything Twice)</span>
                                            <span class="opacity-0 group-hover:opacity-100 text-xs">✖</span>
                                        </button>
                                    </div>
                                </div>

                                <div id="quiz-success" class="transition-all duration-500 absolute inset-0 translate-y-10 opacity-0 pointer-events-none text-center flex flex-col justify-center items-center">
                                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-fuchsia-500 to-purple-600 flex items-center justify-center mb-6 shadow-lg shadow-purple-500/30 scale-0 transition-transform duration-500 delay-200" id="success-icon">
                                        <span class="text-4xl text-white">🏆</span>
                                    </div>
                                    <h3 class="text-2xl font-bold text-white mb-2">Hebat!</h3>
                                    <p class="text-white/60 mb-8">Anda telah memahami keunggulan utama Tailwind.</p>
                                    
                                    <button id="finishBtn" onclick="finishChapter()" class="px-8 py-3 rounded-xl bg-gradient-to-r from-fuchsia-500 to-purple-600 text-white font-bold shadow-lg shadow-purple-900/40 hover:scale-105 transition-transform hover:shadow-purple-500/40">
                                        Selesaikan Bab 1.5 →
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center">
                    <a href="{{ route('courses.implementation') }}" class="group flex items-center gap-3 text-slate-400 hover:text-white transition">
                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-white/30 transition">←</div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest opacity-50">Sebelumnya</div>
                            <div class="font-bold text-sm">Implementasi</div>
                        </div>
                    </a>

                    <div id="nextChapterBtn" class="group flex items-center gap-3 text-right text-slate-500 cursor-not-allowed">
                        <div class="text-right">
                            <div class="text-[10px] uppercase tracking-widest opacity-30">Terkunci</div>
                            <div class="font-bold text-sm">Instalasi & Konfigurasi</div>
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
    /* UTILS & ANIMATION */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }

    #animated-bg{ background: radial-gradient(600px circle at 20% 20%, rgba(168,85,247,.15), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(217,70,239,.15), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(34,211,238,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: rgba(255,255,255,0.5); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: #fff; background: rgba(255,255,255,0.03); }
    .nav-item.active { color: #c084fc; background: rgba(192,132,252,0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #334155; transition: all 0.3s; }
    .nav-item.active .dot { background: #c084fc; box-shadow: 0 0 8px #c084fc; transform: scale(1.2); }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    /* --- CONFIGURATION --- */
    window.SUBBAB_LESSON_IDS = {!! json_encode($subbab15LessonIds ?? []) !!}; 
    window.INIT_COMPLETED_LESSONS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedLessons = new Set(window.INIT_COMPLETED_LESSONS);
    
    // Manual boolean parsing
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};

    const QUIZ_LESSON_ID = 23; // ID 23 sesuai database

    document.addEventListener('DOMContentLoaded', () => {
        updateProgressUI();
        initLessonObserver();
        initVisualEffects();
        initSidebarScroll();
        if (activityCompleted) disableQuizUI();
    });

    /* --- 1. RESPONSIVE SIMULATOR --- */
    function setDevice(widthClass) {
        const box = document.getElementById('resizable-box');
        const grid = document.getElementById('demo-grid');
        const label = document.getElementById('device-label');
        
        box.classList.remove('w-[320px]', 'w-[480px]', 'w-full');
        box.classList.add(widthClass);
        
        if(widthClass === 'w-[320px]') {
            grid.classList.remove('grid-cols-2');
            grid.classList.add('grid-cols-1');
            label.innerText = "Mobile";
        } else if (widthClass === 'w-[480px]') {
            grid.classList.remove('grid-cols-1');
            grid.classList.add('grid-cols-2');
            label.innerText = "Tablet";
        } else {
            grid.classList.remove('grid-cols-1');
            grid.classList.add('grid-cols-2');
            label.innerText = "Desktop";
        }
    }

    /* --- 2. DRY SIMULATOR --- */
    function triggerUpdate() {
        const master = document.getElementById('master-btn');
        const children = document.querySelectorAll('.child-component');
        
        // Toggle Master Style
        if(master.classList.contains('bg-blue-600')) {
            master.classList.remove('bg-blue-600');
            master.classList.add('bg-fuchsia-600', 'rounded-full', 'shadow-fuchsia-500/50'); 
            master.innerText = "Tombol Updated!";
        } else {
            // Revert
            master.classList.remove('bg-fuchsia-600', 'rounded-full', 'shadow-fuchsia-500/50');
            master.classList.add('bg-blue-600');
            master.innerText = "Tombol Utama";
        }
        
        // Propagate to Children
        setTimeout(() => {
            children.forEach(c => {
                if(c.classList.contains('bg-blue-600')) {
                    c.classList.remove('bg-blue-600', 'opacity-70', 'scale-90');
                    c.classList.add('bg-fuchsia-600', 'rounded-full', 'opacity-100', 'scale-100');
                } else {
                    c.classList.remove('bg-fuchsia-600', 'rounded-full', 'opacity-100', 'scale-100');
                    c.classList.add('bg-blue-600', 'opacity-70', 'scale-90');
                }
            });
        }, 300);
    }

    /* --- 3. QUIZ LOGIC --- */
    let currentStep = 1;
    function nextQuestion(step, isCorrect, btn) {
        if(activityCompleted) return;
        if (!isCorrect) {
            btn.classList.add('bg-red-500/20', 'border-red-500', 'shake');
            setTimeout(() => btn.classList.remove('bg-red-500/20', 'border-red-500', 'shake'), 500);
            return;
        }
        btn.classList.add('bg-purple-500/20', 'border-purple-500', 'text-purple-300');
        setTimeout(() => {
            const current = document.getElementById('q' + step);
            const next = document.getElementById(step === 3 ? 'quiz-success' : 'q' + (step + 1));
            
            current.classList.add('-translate-x-[120%]', 'opacity-0', 'pointer-events-none');
            next.classList.remove('translate-x-[120%]', 'translate-y-10', 'opacity-0', 'pointer-events-none');
            
            currentStep++;
            if(step < 3) {
                document.getElementById('quiz-progress').style.width = ((step + 1) / 3 * 100) + '%';
                document.getElementById('question-counter').innerText = (step + 1) + '/3';
            } else {
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
            await fetch('/activity/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify({ activity_id: 5, score: 100 }) });
            await saveLessonToDB(QUIZ_LESSON_ID);
            completedLessons.add(QUIZ_LESSON_ID);
            activityCompleted = true;
            updateProgressUI();
            btn.innerHTML = "Tersimpan ✔";
            btn.classList.add('bg-fuchsia-600', 'cursor-default');
            unlockNext();
        } catch(e) { console.error(e); btn.innerText = "Gagal. Coba lagi."; btn.disabled = false; }
    }

    function disableQuizUI() {
        document.getElementById('q1').classList.add('hidden');
        document.getElementById('quiz-success').classList.remove('translate-y-10', 'opacity-0', 'pointer-events-none');
        document.getElementById('success-icon').classList.remove('scale-0');
        const btn = document.getElementById('finishBtn');
        btn.innerHTML = "Bab Selesai ✔";
        btn.classList.add('bg-fuchsia-600', 'cursor-default');
        btn.onclick = null;
        document.getElementById('quiz-progress').parentElement.classList.add('hidden');
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
        const icon = document.querySelector('.sb-group.open .icon-status');
        if(icon) { icon.innerHTML = '✔'; icon.className = 'icon-status w-6 h-6 rounded-lg border flex items-center justify-center transition-colors bg-purple-500/20 text-purple-400 border-purple-500/20'; }
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.className = "group flex items-center gap-3 text-right text-purple-400 hover:text-purple-300 transition cursor-pointer";
            btn.innerHTML = `<div class="text-right"><div class="text-[10px] uppercase tracking-widest opacity-50">Selanjutnya</div><div class="font-bold text-sm">Instalasi & Konfigurasi</div></div><div class="w-10 h-10 rounded-full border border-purple-500/30 bg-purple-500/10 flex items-center justify-center">→</div>`;
            btn.onclick = () => window.location.href = "instalasi"; // Ganti dengan route bab berikutnya jika ada
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