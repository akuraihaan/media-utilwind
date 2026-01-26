@extends('layouts.landing')
@section('title','Bab 1.3 · Latar Belakang & Struktur')

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
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500/20 to-transparent border border-purple-500/20 flex items-center justify-center font-bold text-xs text-purple-400">1.3</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Latar Belakang & Struktur</h1>
                        <p class="text-[10px] text-white/50">Estimasi: 30 Menit</p>
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
                    
                    <section id="intro" class="lesson-section scroll-mt-32" data-lesson-id="12">
                        <div class="space-y-6">
                            <span class="inline-block px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-300 text-[10px] font-bold uppercase tracking-widest">
                                Konsep Dasar
                            </span>
                            <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1] tracking-tight">
                                Mengapa Tailwind <br> 
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-fuchsia-500 animate-gradient-x">Begitu Cepat?</span>
                            </h2>
                            
                            <p class="text-lg text-white/70 leading-relaxed max-w-3xl">
                                Mengembangkan web biasanya membutuhkan dua hal terpisah: HTML untuk struktur dan CSS untuk gaya. Tailwind mengubah paradigma ini dengan menyediakan <strong>API Desain</strong> langsung di dalam HTML Anda.
                            </p>

                            <div class="grid md:grid-cols-2 gap-6 mt-8">
                                <div class="p-6 rounded-2xl bg-[#0f141e] border border-white/5">
                                    <h3 class="text-lg font-bold text-white mb-2">🚀 Tanpa Context Switching</h3>
                                    <p class="text-sm text-white/50 leading-relaxed">
                                        Anda tidak perlu lagi melompat-lompat antara file `.html` dan `.css`. Ubah desain langsung di tempat struktur berada.
                                    </p>
                                </div>
                                <div class="p-6 rounded-2xl bg-[#0f141e] border border-white/5">
                                    <h3 class="text-lg font-bold text-white mb-2">📱 Responsif Instan</h3>
                                    <p class="text-sm text-white/50 leading-relaxed">
                                        Menciptakan antarmuka yang bersih (<em>cleaner look</em>) di berbagai perangkat menjadi sangat mudah tanpa menulis Media Query manual.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="structure" class="lesson-section scroll-mt-32" data-lesson-id="13">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">1.3.1 Struktur Dasar</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <p class="text-white/70 mb-8">
                            Tailwind terdiri dari tiga lapisan (layer) utama yang diimpor melalui perintah khusus. Klik tab di bawah untuk mempelajari fungsinya:
                        </p>

                        <div class="bg-[#0b0f19] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
                            <div class="flex border-b border-white/10">
                                <button onclick="switchLayer('base')" id="tab-base" class="flex-1 py-4 text-sm font-bold text-center bg-white/5 text-purple-400 border-b-2 border-purple-500 transition-all hover:bg-white/10">
                                    @tailwind base
                                </button>
                                <button onclick="switchLayer('components')" id="tab-components" class="flex-1 py-4 text-sm font-bold text-center text-white/50 hover:text-white transition-all hover:bg-white/5">
                                    @tailwind components
                                </button>
                                <button onclick="switchLayer('utilities')" id="tab-utilities" class="flex-1 py-4 text-sm font-bold text-center text-white/50 hover:text-white transition-all hover:bg-white/5">
                                    @tailwind utilities
                                </button>
                            </div>

                            <div class="p-8 min-h-[250px] relative">
                                <div id="content-base" class="layer-content animate-fade-in">
                                    <div class="flex gap-6 items-start">
                                        <div class="w-12 h-12 rounded-lg bg-red-500/10 flex items-center justify-center text-red-400 text-2xl shrink-0">🏗️</div>
                                        <div>
                                            <h3 class="text-xl font-bold text-white mb-2">Preflight (Reset Style)</h3>
                                            <p class="text-white/60 text-sm leading-relaxed mb-4">
                                                Lapisan ini memuat gaya dasar "Preflight" untuk menyamakan tampilan di semua browser.
                                            </p>
                                            <ul class="space-y-2 text-sm text-white/50">
                                                <li class="flex gap-2"><span>•</span> Menghapus style h1-h6.</li>
                                                <li class="flex gap-2"><span>•</span> Margin & Padding di-set ke 0.</li>
                                                <li class="flex gap-2"><span>•</span> Gambar (img) menjadi <code>display: block</code>.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div id="content-components" class="layer-content hidden animate-fade-in">
                                    <div class="flex gap-6 items-start">
                                        <div class="w-12 h-12 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400 text-2xl shrink-0">🧩</div>
                                        <div>
                                            <h3 class="text-xl font-bold text-white mb-2">Komponen Reusable</h3>
                                            <p class="text-white/60 text-sm leading-relaxed mb-4">
                                                Tempat untuk kelas abstrak seperti <code>.btn</code> atau <code>.card</code>. Ideal untuk elemen yang digunakan berulang kali.
                                            </p>
                                            <code class="text-xs text-blue-300 bg-blue-900/20 px-2 py-1 rounded">.btn { @apply py-2 px-4 rounded; }</code>
                                        </div>
                                    </div>
                                </div>

                                <div id="content-utilities" class="layer-content hidden animate-fade-in">
                                    <div class="flex gap-6 items-start">
                                        <div class="w-12 h-12 rounded-lg bg-green-500/10 flex items-center justify-center text-green-400 text-2xl shrink-0">⚡</div>
                                        <div>
                                            <h3 class="text-xl font-bold text-white mb-2">Jantung Framework</h3>
                                            <p class="text-white/60 text-sm leading-relaxed mb-4">
                                                Berisi ribuan kelas siap pakai. Ini adalah lapisan yang paling sering Anda gunakan.
                                            </p>
                                            <code class="text-xs text-green-300 bg-green-900/20 px-2 py-1 rounded">p-4, flex, mt-2, text-center</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="duplication" class="lesson-section scroll-mt-32" data-lesson-id="14">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">1.3.2 Pengelolaan Duplikasi</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <p class="text-white/70 mb-6">
                            Masalah umum: "Class-nya kepanjangan dan berulang!". Solusinya adalah abstraksi.
                        </p>

                        <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-8 relative overflow-hidden">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold text-white">Simulasi Refactoring</h3>
                                <div class="bg-black/40 p-1 rounded-lg flex border border-white/10">
                                    <button onclick="setRefactorMode('messy')" id="btnMessy" class="px-4 py-1.5 text-xs font-bold rounded bg-white/10 text-white transition">Duplikat</button>
                                    <button onclick="setRefactorMode('clean')" id="btnClean" class="px-4 py-1.5 text-xs font-bold rounded text-white/50 hover:text-white transition">Clean (@apply)</button>
                                </div>
                            </div>

                            <div class="relative min-h-[120px]">
                                <div id="view-messy" class="transition-all duration-300 absolute inset-0">
                                    <div class="space-y-2 font-mono text-[10px] text-white/60">
                                        <div class="bg-red-500/10 p-2 rounded border border-red-500/20">
                                            &lt;button class="<span class="text-white">bg-blue-500 text-white font-bold py-2 px-4 rounded</span>"&gt;Btn 1&lt;/button&gt;
                                        </div>
                                        <div class="bg-red-500/10 p-2 rounded border border-red-500/20">
                                            &lt;button class="<span class="text-white">bg-blue-500 text-white font-bold py-2 px-4 rounded</span>"&gt;Btn 2&lt;/button&gt;
                                        </div>
                                    </div>
                                    <p class="mt-4 text-xs text-red-400">⚠️ Masalah: Jika desain berubah, Anda harus edit satu per satu.</p>
                                </div>

                                <div id="view-clean" class="transition-all duration-300 absolute inset-0 opacity-0 translate-x-10 pointer-events-none">
                                    <div class="space-y-2 font-mono text-[10px] text-white/60">
                                        <div class="bg-emerald-500/10 p-2 rounded border border-emerald-500/20 mb-4">
                                            <span class="text-purple-400">.btn-primary</span> { @apply bg-blue-500 text-white font-bold py-2 px-4 rounded; }
                                        </div>
                                        <div class="bg-blue-500/10 p-2 rounded border border-blue-500/20">
                                            &lt;button class="<span class="text-emerald-400">btn-primary</span>"&gt;Btn 1&lt;/button&gt;
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="quiz-1-3" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="15" data-manual="true">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl text-center">
                            
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-300 text-[10px] font-bold uppercase mb-4 tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-pulse"></span>
                                Evaluasi Pemahaman
                            </div>
                            <h2 class="text-3xl font-black text-white mb-6">Uji Pengetahuan</h2>
                            <p class="text-white/60 text-sm mb-8 max-w-xl mx-auto">
                                Direktif mana yang digunakan untuk menggabungkan beberapa kelas utilitas menjadi satu kelas CSS kustom?
                            </p>
                            
                            <div class="grid sm:grid-cols-3 gap-4 max-w-2xl mx-auto">
                                <button onclick="checkQuiz('a', this)" class="quiz-option p-4 rounded-xl bg-white/5 border border-white/10 hover:border-purple-500/50 transition text-sm font-mono text-cyan-300">
                                    @tailwind
                                </button>
                                <button onclick="checkQuiz('b', this)" class="quiz-option p-4 rounded-xl bg-white/5 border border-white/10 hover:border-purple-500/50 transition text-sm font-mono text-cyan-300">
                                    @apply
                                </button>
                                <button onclick="checkQuiz('c', this)" class="quiz-option p-4 rounded-xl bg-white/5 border border-white/10 hover:border-purple-500/50 transition text-sm font-mono text-cyan-300">
                                    @layer
                                </button>
                            </div>

                            <div class="mt-8 min-h-[60px]">
                                <div id="quizFeedback" class="text-sm font-bold text-white/50 opacity-0 transition-opacity">Pilih jawaban...</div>
                                <button id="finishBtn" disabled onclick="finishChapter()" class="mt-4 px-8 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-fuchsia-600 text-white font-bold shadow-lg opacity-0 pointer-events-none transition-all hover:scale-105 transform scale-90">
                                    Selesaikan Bab 1.3
                                </button>
                            </div>

                        </div>
                    </section>

                </article>

                <div class="mt-32 pt-10 border-t border-white/10 flex justify-between items-center">
                    <a href="{{ route('courses.tailwindcss') }}" class="group flex items-center gap-3 text-slate-400 hover:text-white transition">
                        <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-white/30 transition">←</div>
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest opacity-50">Sebelumnya</div>
                            <div class="font-bold text-sm">Konsep Dasar</div>
                        </div>
                    </a>

                    <div id="nextChapterBtn" class="group flex items-center gap-3 text-right text-slate-500 cursor-not-allowed">
                        <div class="text-right">
                            <div class="text-[10px] uppercase tracking-widest opacity-30">Terkunci</div>
                            <div class="font-bold text-sm">Penerapan</div>
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
    .quiz-option.correct { background: rgba(16,185,129,0.2); border-color: #10b981; color: #a7f3d0; }
    .quiz-option.wrong { background: rgba(239,68,68,0.2); border-color: #ef4444; color: #fecaca; }

    /* ANIMATIONS */
    #animated-bg{ background: radial-gradient(600px circle at 20% 20%, rgba(168,85,247,.15), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(217,70,239,.15), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(34,211,238,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    @keyframes shake { 0%, 100% {transform: translateX(0);} 25% {transform: translateX(-5px);} 75% {transform: translateX(5px);} }
    
    /* SIDEBAR */
    .sb-group.open .accordion-content { max-height: 1000px; opacity: 1; }
    .sb-group:not(.open) .accordion-content { max-height: 0; opacity: 0; overflow: hidden; }
    .sb-group.open svg { transform: rotate(180deg); }
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: rgba(255,255,255,0.5); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: #fff; background: rgba(255,255,255,0.03); }
    .nav-item.active { color: #c084fc; background: rgba(192,132,252,0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #334155; transition: all 0.3s; }
    .nav-item.active .dot { background: #c084fc; box-shadow: 0 0 8px #c084fc; transform: scale(1.2); }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    /* --- CONFIGURATION --- */
    window.SUBBAB_LESSON_IDS = @json($subbab13LessonIds); // ID: 12, 13, 14, 15
    window.INIT_COMPLETED_LESSONS = @json($completedLessonIds);
    let completedLessons = new Set(window.INIT_COMPLETED_LESSONS);
    let activityCompleted = @json($activityCompleted ?? false);

    const QUIZ_LESSON_ID = 15; // Lesson ID untuk Quiz Manual

    /* --- DOM READY --- */
    document.addEventListener('DOMContentLoaded', () => {
        updateProgressUI();
        initLessonObserver();
        initVisualEffects();
        initSidebarScroll();
        
        if (activityCompleted) {
            disableQuizUI();
        }
    });

    /* --- 1. INTERACTIVE: LAYER TABS --- */
    function switchLayer(layer) {
        document.querySelectorAll('[id^="tab-"]').forEach(t => {
            t.className = "flex-1 py-4 text-sm font-bold text-center text-white/50 hover:text-white transition-all hover:bg-white/5";
        });
        document.getElementById('tab-' + layer).className = "flex-1 py-4 text-sm font-bold text-center bg-white/5 text-purple-400 border-b-2 border-purple-500 transition-all";
        document.querySelectorAll('.layer-content').forEach(c => c.classList.add('hidden'));
        document.getElementById('content-' + layer).classList.remove('hidden');
    }

    /* --- 2. INTERACTIVE: REFACTOR TOGGLE --- */
    function setRefactorMode(mode) {
        const messy = document.getElementById('view-messy');
        const clean = document.getElementById('view-clean');
        const btnMessy = document.getElementById('btnMessy');
        const btnClean = document.getElementById('btnClean');

        if(mode === 'clean') {
            messy.classList.add('opacity-0', '-translate-x-10', 'pointer-events-none');
            clean.classList.remove('opacity-0', 'translate-x-10', 'pointer-events-none');
            btnClean.className = "px-4 py-1.5 text-xs font-bold rounded bg-emerald-500 text-white transition";
            btnMessy.className = "px-4 py-1.5 text-xs font-bold rounded text-white/50 hover:text-white transition";
        } else {
            messy.classList.remove('opacity-0', '-translate-x-10', 'pointer-events-none');
            clean.classList.add('opacity-0', 'translate-x-10', 'pointer-events-none');
            btnMessy.className = "px-4 py-1.5 text-xs font-bold rounded bg-white/10 text-white transition";
            btnClean.className = "px-4 py-1.5 text-xs font-bold rounded text-white/50 hover:text-white transition";
        }
    }

    /* --- 3. QUIZ LOGIC (MANUAL SAVE) --- */
    function checkQuiz(answer, btn) {
        if(activityCompleted) return;

        document.querySelectorAll('.quiz-option').forEach(b => b.className = 'quiz-option p-4 rounded-xl bg-white/5 border border-white/10 hover:border-purple-500/50 transition text-sm font-mono text-cyan-300');
        const fb = document.getElementById('quizFeedback');
        const finishBtn = document.getElementById('finishBtn');

        if (answer === 'b') { // Jawaban Benar (@apply)
            btn.classList.add('correct');
            fb.innerHTML = "<span class='text-emerald-400'>Benar! @apply digunakan untuk ekstraksi komponen.</span>";
            fb.classList.remove('opacity-0');
            finishBtn.classList.remove('opacity-0', 'pointer-events-none', 'scale-90');
            finishBtn.disabled = false;
        } else {
            btn.classList.add('wrong');
            fb.innerHTML = "<span class='text-red-400'>Kurang tepat. Coba lagi.</span>";
            fb.classList.remove('opacity-0');
            finishBtn.classList.add('opacity-0', 'pointer-events-none');
        }
    }

    async function finishChapter() {
        const btn = document.getElementById('finishBtn');
        btn.innerText = "Menyimpan...";
        btn.disabled = true;
        
        try {
            // 1. Simpan Activity (ID 3) - Bobot Nilai
            await fetch('/activity/complete', { 
                method: 'POST', 
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }, 
                body: JSON.stringify({ activity_id: 3, score: 100 }) 
            });

            // 2. Simpan Lesson Manual (ID 15) - Agar Progress 100%
            await saveLessonToDB(QUIZ_LESSON_ID);
            completedLessons.add(QUIZ_LESSON_ID);

            // 3. Update UI
            activityCompleted = true;
            updateProgressUI();
            disableQuizUI();
            
        } catch(e) {
            console.error(e);
            btn.innerText = "Gagal. Coba lagi.";
            btn.disabled = false;
        }
    }

    function disableQuizUI() {
        const btn = document.getElementById('finishBtn');
        const fb = document.getElementById('quizFeedback');
        btn.innerText = "Selesai ✔";
        btn.classList.add('bg-emerald-600', 'cursor-not-allowed');
        btn.classList.remove('opacity-0', 'pointer-events-none', 'scale-90');
        btn.disabled = true;
        fb.innerHTML = "<span class='text-emerald-400 font-bold'>Anda telah menyelesaikan bab ini.</span>";
        
        document.querySelectorAll('.quiz-option').forEach(op => {
            if(op.innerText.includes('@apply')) op.classList.add('correct');
            else op.classList.add('opacity-50');
            op.disabled = true;
        });
    }

    /* --- 4. CORE SYSTEM (Progress & Observer) --- */
    function updateProgressUI() {
        const total = window.SUBBAB_LESSON_IDS.length;
        const done = window.SUBBAB_LESSON_IDS.filter(id => completedLessons.has(id)).length;
        
        // Progress Logic
        let percent = Math.round((done / total) * 100);
        
        // Jika belum selesai activity/quiz manual, tahan di 90%
        if (done === total && !activityCompleted) percent = 90; 
        else if (done === total && activityCompleted) percent = 100;

        // Update UI
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
        if(icon) { icon.innerHTML = '✔'; icon.className = 'icon-status w-6 h-6 rounded-lg border flex items-center justify-center transition-colors bg-emerald-500/20 text-emerald-400 border-emerald-500/20'; }
        
        const btn = document.getElementById('nextChapterBtn');
        if(btn) {
            btn.className = "group flex items-center gap-3 text-right text-purple-400 hover:text-purple-300 transition cursor-pointer";
            btn.innerHTML = `<div class="text-right"><div class="text-[10px] uppercase tracking-widest opacity-50">Selanjutnya</div><div class="font-bold text-sm">Penerapan</div></div><div class="w-10 h-10 rounded-full border border-purple-500/30 bg-purple-500/10 flex items-center justify-center">→</div>`;
            btn.onclick = () => window.location.href = "{{ route('courses.implementation') }}";
        }
    }

    /* --- 5. VISUALS --- */
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