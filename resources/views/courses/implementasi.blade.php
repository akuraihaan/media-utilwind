@extends('layouts.landing')
@section('title','Bab 1.4 · Penerapan Utility Classes')

@section('content')
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-cyan-500/30">

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
                    <!-- <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cyan-500/20 to-transparent border border-cyan-500/20 flex items-center justify-center font-bold text-xs text-cyan-400">1.4</div> -->
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500/20 to-transparent border border-purple-500/20 flex items-center justify-center font-bold text-xs text-purple-400">1.4</div>

                    <div>
                        <h1 class="text-sm font-bold text-white">Penerapan Utility Classes</h1>
                        <p class="text-[10px] text-white/50">Estimasi: 20 Menit</p>
                    </div>
                </div>
                 <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-24 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-purple-400 to-fuchsia-500 w-0 transition-all duration-500 shadow-[0_0_10px_#d946ef]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-purple-400 font-bold text-xs">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                <article class="space-y-32">
                    
                    <section id="concept" class="lesson-section scroll-mt-32" data-lesson-id="{{ $subbab14LessonIds[0] ?? 16 }}">
                        <div class="space-y-8">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-[10px] font-bold uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                                Konsep Dasar
                            </div>
                            
                            <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                Kekuatan Atribut <br> 
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Class HTML</span>
                            </h2>
                            
                            <p class="text-lg text-white/70 leading-relaxed max-w-3xl">
                                HTML memiliki seperangkat elemen untuk mendefinisikan struktur. Salah satu atribut terpenting adalah <code>class</code>. 
                                Dalam pengembangan tradisional, class digunakan untuk menghubungkan elemen dengan file CSS eksternal.
                            </p>

                            <div class="p-6 bg-[#0f141e] border-l-4 border-cyan-500 rounded-r-xl group hover:bg-[#151b26] transition">
                                <p class="text-white/90 italic text-lg">
                                    "Ketika menggunakan Tailwind CSS, penerapan gaya menjadi sangat sederhana: Anda cukup menyebutkan nama-nama kelas yang telah disediakan di dalam atribut class."
                                </p>
                            </div>
                        </div>
                    </section>

                    <section id="chaining" class="lesson-section scroll-mt-32" data-lesson-id="{{ $subbab14LessonIds[1] ?? 17 }}">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">1.4.2 Chaining Kelas (Penggabungan)</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <p class="text-white/70 mb-6">
                            Tailwind memungkinkan Anda <strong>menggabungkan (chaining)</strong> beberapa kelas sekaligus untuk mencapai desain yang kompleks. Cobalah simulator di bawah ini:
                        </p>

                        <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-8 relative overflow-hidden shadow-2xl">
                            
                            <div class="flex justify-center items-center min-h-[180px] bg-black/30 rounded-xl border border-white/5 mb-6 relative">
                                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
                                <div id="demo-box" class="transition-all duration-500 p-4 border border-white/10 text-white">
                                    Kalimat ini memiliki desain yang baik.
                                </div>
                            </div>

                            <p class="text-xs text-white/40 mb-3 uppercase tracking-widest font-bold">Klik untuk tambah/hapus class:</p>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                                <button onclick="toggleClass('bg-gray-700', '')" class="toggle-btn px-3 py-3 rounded-lg bg-white/5 border border-white/10 text-xs font-mono hover:bg-white/10 transition text-left group">
                                    <span class="block text-gray-500 text-[9px] mb-1">Background</span>
                                    <span class="text-cyan-300 font-bold">bg-gray-700</span>
                                </button>
                                <button onclick="toggleClass('text-center', '')" class="toggle-btn px-3 py-3 rounded-lg bg-white/5 border border-white/10 text-xs font-mono hover:bg-white/10 transition text-left">
                                    <span class="block text-gray-500 text-[9px] mb-1">Alignment</span>
                                    <span class="text-cyan-300 font-bold">text-center</span>
                                </button>
                                <button onclick="toggleClass('font-bold', '')" class="toggle-btn px-3 py-3 rounded-lg bg-white/5 border border-white/10 text-xs font-mono hover:bg-white/10 transition text-left">
                                    <span class="block text-gray-500 text-[9px] mb-1">Weight</span>
                                    <span class="text-cyan-300 font-bold">font-bold</span>
                                </button>
                                <button onclick="toggleClass('italic', '')" class="toggle-btn px-3 py-3 rounded-lg bg-white/5 border border-white/10 text-xs font-mono hover:bg-white/10 transition text-left">
                                    <span class="block text-gray-500 text-[9px] mb-1">Style</span>
                                    <span class="text-cyan-300 font-bold">italic</span>
                                </button>
                                <button onclick="toggleClass('underline', '')" class="toggle-btn px-3 py-3 rounded-lg bg-white/5 border border-white/10 text-xs font-mono hover:bg-white/10 transition text-left">
                                    <span class="block text-gray-500 text-[9px] mb-1">Decoration</span>
                                    <span class="text-cyan-300 font-bold">underline</span>
                                </button>
                                <button onclick="toggleClass('text-cyan-400', '')" class="toggle-btn px-3 py-3 rounded-lg bg-white/5 border border-white/10 text-xs font-mono hover:bg-white/10 transition text-left">
                                    <span class="block text-gray-500 text-[9px] mb-1">Color</span>
                                    <span class="text-cyan-300 font-bold">text-cyan-400</span>
                                </button>
                            </div>

                            <div class="mt-6 pt-6 border-t border-white/10">
                                <p class="text-xs text-gray-500 mb-2 font-mono">Generated HTML:</p>
                                <div class="bg-black/50 p-4 rounded-xl border border-white/5 font-mono text-sm overflow-x-auto whitespace-nowrap">
                                    <span class="text-blue-400">&lt;div</span> <span class="text-purple-400">class</span>=<span class="text-green-400">"</span><span id="code-output" class="text-green-400"></span><span class="text-green-400">"</span><span class="text-blue-400">&gt;</span>...<span class="text-blue-400">&lt;/div&gt;</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="comparison" class="lesson-section scroll-mt-32" data-lesson-id="{{ $subbab14LessonIds[2] ?? 18 }}">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">1.4.3 Perbandingan Efisiensi</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <p class="text-white/70 mb-6">
                            Mari kita bandingkan kode Tailwind dengan CSS Tradisional untuk hasil yang sama. Perhatikan bagaimana Tailwind menghilangkan kebutuhan untuk menulis selector CSS dan properti terpisah.
                        </p>

                        <div class="grid lg:grid-cols-2 gap-0 rounded-2xl overflow-hidden border border-white/10">
                            
                            <div class="bg-[#0f141e] p-6 border-b lg:border-b-0 lg:border-r border-white/10 relative group">
                                <div class="absolute top-2 right-2 text-[10px] bg-cyan-500/10 text-cyan-400 px-2 py-1 rounded border border-cyan-500/20">Modern Way</div>
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-2 h-2 rounded-full bg-cyan-400"></div>
                                    <h3 class="text-sm font-bold text-white">Tailwind CSS (HTML)</h3>
                                </div>
                                <div class="font-mono text-xs leading-relaxed text-gray-300">
                                    <span class="text-blue-400">&lt;div</span> <span class="text-purple-400">class</span>=<span class="text-green-400">"bg-gray-300 text-center font-bold italic underline"</span><span class="text-blue-400">&gt;</span><br>
                                    &nbsp;&nbsp;Kalimat ini memiliki desain...<br>
                                    <span class="text-blue-400">&lt;/div&gt;</span>
                                </div>
                                <div class="mt-6 pt-4 border-t border-white/5 opacity-50 group-hover:opacity-100 transition">
                                    <span class="text-[10px] uppercase font-bold text-cyan-500 tracking-widest">Keunggulan</span>
                                    <p class="text-xs text-white/70 mt-1">Hanya 1 baris di HTML. Tidak perlu membuka file CSS terpisah.</p>
                                </div>
                            </div>

                            <div class="bg-[#1e1e1e] p-6 relative group">
                                <div class="absolute top-2 right-2 text-[10px] bg-white/5 text-gray-400 px-2 py-1 rounded border border-white/10">Old Way</div>
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-2 h-2 rounded-full bg-orange-400"></div>
                                    <h3 class="text-sm font-bold text-white">CSS Tradisional</h3>
                                </div>
                                <div class="font-mono text-xs leading-relaxed text-gray-300">
                                    <span class="text-orange-300">div</span> {<br>
                                    &nbsp;&nbsp;<span class="text-cyan-300">background-color</span>: #d1d5db;<br>
                                    &nbsp;&nbsp;<span class="text-cyan-300">text-align</span>: center;<br>
                                    &nbsp;&nbsp;<span class="text-cyan-300">font-weight</span>: bold;<br>
                                    &nbsp;&nbsp;<span class="text-cyan-300">font-style</span>: italic;<br>
                                    &nbsp;&nbsp;<span class="text-cyan-300">text-decoration</span>: underline;<br>
                                    }
                                </div>
                                <div class="mt-6 pt-4 border-t border-white/5 opacity-50 group-hover:opacity-100 transition">
                                    <span class="text-[10px] uppercase font-bold text-orange-500 tracking-widest">Kelemahan</span>
                                    <p class="text-xs text-white/70 mt-1">Memerlukan 7 baris kode & file terpisah (Context Switching).</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 p-6 bg-cyan-500/5 border border-cyan-500/10 rounded-xl flex gap-4 items-start">
                            <span class="text-2xl">💡</span>
                            <p class="text-sm text-cyan-200 leading-relaxed">
                                <strong>Kesimpulan:</strong> Pekerjaan seperti menentukan warna, posisi teks, ketebalan huruf, atau gaya tipografi sudah disiapkan di balik layar oleh sistem Tailwind CSS. Hal ini membuat proses pengembangan jauh lebih cepat.
                            </p>
                        </div>
                    </section>

                    <section id="quiz-1-4" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="{{ $subbab14LessonIds[3] ?? 19 }}" data-manual="true">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl text-center group hover:border-cyan-500/30 transition-all duration-500">
                            
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-2/3 h-1 bg-gradient-to-r from-transparent via-cyan-500 to-transparent opacity-50 blur-sm"></div>

                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-[10px] font-bold uppercase mb-4 tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                                Evaluasi 1.4
                            </div>
                            <h2 class="text-3xl font-black text-white mb-6">Uji Pemahaman</h2>

                            <div id="quiz-container" class="max-w-xl mx-auto min-h-[300px] relative">
                                <div class="flex justify-between mb-6 px-2">
                                    <div class="h-1 flex-1 bg-white/10 rounded-full overflow-hidden mr-2">
                                        <div id="quiz-progress" class="h-full bg-cyan-500 w-[33%] transition-all duration-500"></div>
                                    </div>
                                    <span id="question-counter" class="text-[10px] text-cyan-400 font-bold">1/3</span>
                                </div>

                                <div id="q1" class="quiz-step transition-all duration-500 absolute inset-0">
                                    <p class="font-bold text-white mb-6 text-lg">1. Di mana kita menulis kelas utilitas Tailwind?</p>
                                    <div class="space-y-3">
                                        <button onclick="nextQuestion(1, false, this)" class="quiz-opt w-full text-left p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition group flex justify-between items-center">
                                            <span>Di file style.css eksternal</span>
                                            <span class="opacity-0 group-hover:opacity-100 text-xs"></span>
                                        </button>
                                        <button onclick="nextQuestion(1, true, this)" class="quiz-opt w-full text-left p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition group flex justify-between items-center">
                                            <span>Di dalam atribut class elemen HTML</span>
                                            <span class="opacity-0 group-hover:opacity-100 text-xs"></span>
                                        </button>
                                        <button onclick="nextQuestion(1, false, this)" class="quiz-opt w-full text-left p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition group flex justify-between items-center">
                                            <span>Di dalam tag &lt;head&gt;</span>
                                            <span class="opacity-0 group-hover:opacity-100 text-xs"></span>
                                        </button>
                                    </div>
                                </div>

                                <div id="q2" class="quiz-step transition-all duration-500 absolute inset-0 translate-x-[120%] opacity-0 pointer-events-none">
                                    <p class="font-bold text-white mb-6 text-lg">2. Apa nama teknik menggabungkan beberapa kelas dalam satu atribut?</p>
                                    <div class="space-y-3">
                                        <button onclick="nextQuestion(2, false, this)" class="quiz-opt w-full text-left p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition group flex justify-between items-center">
                                            <span>Nesting</span>
                                            <span class="opacity-0 group-hover:opacity-100 text-xs"></span>
                                        </button>
                                        <button onclick="nextQuestion(2, true, this)" class="quiz-opt w-full text-left p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition group flex justify-between items-center">
                                            <span>Chaining</span>
                                            <span class="opacity-0 group-hover:opacity-100 text-xs"></span>
                                        </button>
                                        <button onclick="nextQuestion(2, false, this)" class="quiz-opt w-full text-left p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition group flex justify-between items-center">
                                            <span>Importing</span>
                                            <span class="opacity-0 group-hover:opacity-100 text-xs"></span>
                                        </button>
                                    </div>
                                </div>

                                <div id="q3" class="quiz-step transition-all duration-500 absolute inset-0 translate-x-[120%] opacity-0 pointer-events-none">
                                    <p class="font-bold text-white mb-6 text-lg">3. Bagaimana cara membuat teks tebal dan miring?</p>
                                    <div class="space-y-3">
                                        <button onclick="nextQuestion(3, true, this)" class="quiz-opt w-full text-left p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition group flex justify-between items-center">
                                            <span class="font-mono text-xs">class="font-bold italic"</span>
                                            <span class="opacity-0 group-hover:opacity-100 text-xs"></span>
                                        </button>
                                        <button onclick="nextQuestion(3, false, this)" class="quiz-opt w-full text-left p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition group flex justify-between items-center">
                                            <span class="font-mono text-xs">style="bold; italic;"</span>
                                            <span class="opacity-0 group-hover:opacity-100 text-xs"></span>
                                        </button>
                                    </div>
                                </div>

                                <div id="quiz-success" class="transition-all duration-500 absolute inset-0 translate-y-10 opacity-0 pointer-events-none text-center flex flex-col justify-center items-center">
                                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center mb-6 shadow-lg shadow-cyan-500/30 scale-0 transition-transform duration-500 delay-200" id="success-icon">
                                        <span class="text-4xl text-white">✨</span>
                                    </div>
                                    <h3 class="text-2xl font-bold text-white mb-2">Sempurna!</h3>
                                    <p class="text-white/60 mb-8">Anda telah memahami inti penggunaan Tailwind.</p>
                                    
                                    <button id="finishBtn" onclick="finishChapter()" class="px-8 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-bold shadow-lg shadow-cyan-900/40 hover:scale-105 transition-transform hover:shadow-cyan-500/40">
                                        Selesaikan Bab 1.4 →
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center">
                    <a href="{{ route('courses.background') }}" class="group flex items-center gap-3 text-slate-400 hover:text-white transition">
                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-white/30 transition">←</div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest opacity-50">Sebelumnya</div>
                            <div class="font-bold text-sm">Latar Belakang</div>
                        </div>
                    </a>

                    <div id="nextChapterBtn" class="group flex items-center gap-3 text-right text-slate-500 cursor-not-allowed">
                        <div class="text-right">
                            <div class="text-[10px] uppercase tracking-widest opacity-30">Terkunci</div>
                            <div class="font-bold text-sm">Keunggulan (Segera)</div>
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
    .animate-fade-in { animation: fadeIn 0.5s ease forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Quiz Feedback */
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }

    /* ANIMATIONS */
    #animated-bg{ background: radial-gradient(600px circle at 20% 20%, rgba(34,211,238,.15), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(168,85,247,.15), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(34,211,238,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    
    /* SIDEBAR COMPATIBILITY */
    .sb-group.open .accordion-content { max-height: 1000px; opacity: 1; }
    .sb-group:not(.open) .accordion-content { max-height: 0; opacity: 0; overflow: hidden; }
    .sb-group.open svg { transform: rotate(180deg); }
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: rgba(255,255,255,0.5); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: #fff; background: rgba(255,255,255,0.03); }
    .nav-item.active { color: #22d3ee; background: rgba(34,211,238,0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #334155; transition: all 0.3s; }
    .nav-item.active .dot { background: #22d3ee; box-shadow: 0 0 8px #22d3ee; transform: scale(1.2); }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    /* --- CONFIGURATION --- */
    window.SUBBAB_LESSON_IDS = {!! json_encode($subbab14LessonIds ?? []) !!}; 
    window.INIT_COMPLETED_LESSONS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedLessons = new Set(window.INIT_COMPLETED_LESSONS);
    

    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};

    const QUIZ_LESSON_ID = 19; // ID 19 sesuai database untuk quiz 1.4

    document.addEventListener('DOMContentLoaded', () => {
        updateProgressUI();
        initLessonObserver();
        initVisualEffects();
        initSidebarScroll();
        if (activityCompleted) {
            disableQuizUI();
        }
    });

    /* --- 1. LIVE CLASS TOGGLER (CHAINING) --- */
    let activeClasses = new Set();

    function toggleClass(className, removeClass) {
        const box = document.getElementById('demo-box');
        const btns = document.querySelectorAll('.toggle-btn');
        const code = document.getElementById('code-output');

        // Toggle logic
        if (activeClasses.has(className)) {
            activeClasses.delete(className);
            box.classList.remove(className);
            if(removeClass) box.classList.add(removeClass); // Revert default (e.g. text-white)
        } else {
            activeClasses.add(className);
            box.classList.add(className);
            if(removeClass) box.classList.remove(removeClass);
        }

        // Highlight button logic
        const clickedBtn = [...btns].find(b => b.innerHTML.includes(className));
        if(clickedBtn) {
            if(activeClasses.has(className)) {
                clickedBtn.classList.add('bg-cyan-500/20', 'border-cyan-500');
            } else {
                clickedBtn.classList.remove('bg-cyan-500/20', 'border-cyan-500');
            }
        }

        // Update code string
        const classString = Array.from(activeClasses).join(' ');
        code.innerText = classString;
    }

    /* --- 2. QUIZ LOGIC (LOGIKA SAMA SEPERTI BAB 1.3) --- */
    let currentStep = 1;
    function nextQuestion(step, isCorrect, btn) {
        if(activityCompleted) return;

        if (!isCorrect) {
            btn.classList.add('bg-red-500/20', 'border-red-500', 'shake');
            setTimeout(() => btn.classList.remove('bg-red-500/20', 'border-red-500', 'shake'), 500);
            return;
        }

        // Jika benar
        btn.classList.add('bg-cyan-500/20', 'border-cyan-500', 'text-cyan-300');
        
        setTimeout(() => {
            const currentEl = document.getElementById('q' + step);
            const nextEl = document.getElementById(step === 3 ? 'quiz-success' : 'q' + (step + 1));
            
            // Animasi Slide
            currentEl.classList.add('-translate-x-[120%]', 'opacity-0', 'pointer-events-none');
            nextEl.classList.remove('translate-x-[120%]', 'translate-y-10', 'opacity-0', 'pointer-events-none');
            
            currentStep++;
            if(step < 3) {
                // Update Progress Bar Kuis
                document.getElementById('quiz-progress').style.width = ((step + 1) / 3 * 100) + '%';
                document.getElementById('question-counter').innerText = (step + 1) + '/3';
            } else {
                // Selesai
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
            // Save Activity (ID 4 untuk Bab 1.4)
            await fetch('/activity/complete', { 
                method: 'POST', 
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }, 
                body: JSON.stringify({ activity_id: 4, score: 100 }) 
            });

            // Save Lesson Manual
            await saveLessonToDB(QUIZ_LESSON_ID);
            completedLessons.add(QUIZ_LESSON_ID);

            activityCompleted = true;
            updateProgressUI();
            
            btn.innerHTML = "Tersimpan ";
            btn.classList.add('bg-cyan-500', 'text-white', 'cursor-default');
            btn.onclick = null;
            
            unlockNext();

        } catch(e) {
            console.error(e);
            btn.innerText = "Gagal. Coba lagi.";
            btn.disabled = false;
        }
    }

    function disableQuizUI() {
        document.getElementById('q1').classList.add('hidden');
        document.getElementById('quiz-success').classList.remove('translate-y-10', 'opacity-0', 'pointer-events-none');
        document.getElementById('success-icon').classList.remove('scale-0');
        
        const btn = document.getElementById('finishBtn');
        btn.innerHTML = "Bab Selesai ";
        btn.classList.add('bg-cyan-600', 'text-white', 'cursor-default');
        btn.onclick = null;
        
        document.getElementById('quiz-progress').parentElement.classList.add('hidden');
        document.getElementById('question-counter').classList.add('hidden');
    }

    /* --- 3. CORE SYSTEM (PROGRESS & OBSERVER) --- */
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
        if(icon) { icon.innerHTML = ''; icon.className = 'icon-status w-6 h-6 rounded-lg border flex items-center justify-center transition-colors bg-cyan-500/20 text-cyan-400 border-cyan-500/20'; }
        
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.className = "group flex items-center gap-3 text-right text-cyan-400 hover:text-cyan-300 transition cursor-pointer";
            btn.innerHTML = `<div class="text-right"><div class="text-[10px] uppercase tracking-widest opacity-50">Selanjutnya</div><div class="font-bold text-sm">Keunggulan</div></div><div class="w-10 h-10 rounded-full border border-cyan-500/30 bg-cyan-500/10 flex items-center justify-center">→</div>`;
            btn.onclick = () => window.location.href = "{{ route('courses.advantages') }}";
        }
    }

    /* --- 4. VISUALS --- */
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
        const sections = document.querySelectorAll('.lesson-section');
        const links = document.querySelectorAll('.accordion-content .nav-item');
        main.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(sec => { if (main.scrollTop >= sec.offsetTop - 250) current = '#' + sec.id; });
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