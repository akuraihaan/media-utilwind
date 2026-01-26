@extends('layouts.landing')
@section('title','Bab 1.2 · Konsep Dasar Tailwind CSS')

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
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cyan-500/20 to-transparent border border-cyan-500/20 flex items-center justify-center font-bold text-xs text-cyan-400">1.2</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Konsep Dasar Tailwind</h1>
                        <p class="text-[10px] text-white/50">Estimasi: 25 Menit</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-24 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 w-0 transition-all duration-500 shadow-[0_0_10px_#22d3ee]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-cyan-400 font-bold text-xs">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                <article class="space-y-32">
                    
                    <section id="intro" class="lesson-section scroll-mt-32" data-lesson-id="{{ $subbab1LessonIds[0] ?? 7 }}">
                        <div class="space-y-6">
                            <span class="inline-block px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-[10px] font-bold uppercase tracking-widest">
                                Pengenalan
                            </span>
                            <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1] tracking-tight">
                                Apa itu <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500 animate-gradient-x">Tailwind CSS?</span>
                            </h2>
                            <p class="text-lg text-white/70 leading-relaxed max-w-3xl">
                                Tailwind CSS adalah framework CSS bersifat <em>open-source</em> yang diciptakan oleh <strong>Adam Wathan</strong>. Berbeda dengan framework lain yang memberimu komponen jadi (seperti tombol siap pakai), Tailwind memberimu <strong>alat</strong> untuk membangun komponenmu sendiri.
                            </p>
                            
                            <div class="p-6 rounded-2xl bg-[#0f141e] border border-white/5 flex gap-4 items-start mt-4">
                                <div class="w-10 h-10 rounded-lg bg-yellow-500/10 flex items-center justify-center text-yellow-400 text-xl">⭐</div>
                                <div>
                                    <h3 class="font-bold text-white mb-1">Populer & Modern</h3>
                                    <p class="text-sm text-white/60">
                                        Dengan lebih dari <strong>61.000 bintang di GitHub</strong>, Tailwind telah menjadi standar industri baru karena fleksibilitasnya yang luar biasa.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="utility-concept" class="lesson-section scroll-mt-32" data-lesson-id="{{ $subbab1LessonIds[1] ?? 8 }}">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">Filosofi Utility-First</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>
                        
                        <div class="space-y-6 text-white/70 leading-relaxed">
                            <p>
                                <strong>Utility-First</strong> berarti kita membangun desain menggunakan kelas-kelas kecil (utility) yang memiliki satu fungsi spesifik.
                            </p>
                            <p>
                                Bayangkan ini seperti bermain LEGO. Anda tidak mencetak bentuk rumah sekaligus, tapi menyusunnya dari bata-bata kecil.
                            </p>
                            
                            <ul class="grid sm:grid-cols-2 gap-4 mt-4">
                                <li class="p-4 bg-white/5 rounded-xl border border-white/5 flex items-center gap-3">
                                    <code class="text-cyan-300 font-bold bg-black/30 px-2 py-1 rounded">bg-red-500</code>
                                    <span class="text-sm">Ubah background jadi merah</span>
                                </li>
                                <li class="p-4 bg-white/5 rounded-xl border border-white/5 flex items-center gap-3">
                                    <code class="text-cyan-300 font-bold bg-black/30 px-2 py-1 rounded">text-center</code>
                                    <span class="text-sm">Ratakan teks ke tengah</span>
                                </li>
                                <li class="p-4 bg-white/5 rounded-xl border border-white/5 flex items-center gap-3">
                                    <code class="text-cyan-300 font-bold bg-black/30 px-2 py-1 rounded">p-4</code>
                                    <span class="text-sm">Beri padding (jarak dalam)</span>
                                </li>
                                <li class="p-4 bg-white/5 rounded-xl border border-white/5 flex items-center gap-3">
                                    <code class="text-cyan-300 font-bold bg-black/30 px-2 py-1 rounded">rounded-full</code>
                                    <span class="text-sm">Buat sudut melengkung penuh</span>
                                </li>
                            </ul>
                        </div>
                    </section>

                    <section id="interactive-demo" class="lesson-section scroll-mt-32" data-lesson-id="{{ $subbab1LessonIds[2] ?? 9 }}">
                        <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-8 shadow-2xl relative overflow-hidden">
                            <div class="absolute -top-20 -right-20 w-64 h-64 bg-cyan-500/10 rounded-full blur-[80px]"></div>

                            <div class="grid lg:grid-cols-2 gap-10 items-center relative z-10">
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-3">Eksperimen Langsung</h3>
                                    <p class="text-sm text-white/60 mb-6">
                                        Klik tombol di bawah untuk menambahkan kelas utilitas ke kotak. Lihat betapa cepatnya Anda bisa mengubah desain tanpa menyentuh CSS.
                                    </p>

                                    <div class="space-y-4">
                                        <div>
                                            <p class="text-[10px] uppercase tracking-widest text-white/30 font-bold mb-2">Bentuk & Layout</p>
                                            <div class="flex flex-wrap gap-2">
                                                <button onclick="toggleUtil('p-8')" class="util-btn px-3 py-1.5 rounded-lg border border-white/10 bg-white/5 hover:bg-cyan-500/20 hover:text-cyan-300 text-xs font-mono transition">p-8</button>
                                                <button onclick="toggleUtil('rounded-full')" class="util-btn px-3 py-1.5 rounded-lg border border-white/10 bg-white/5 hover:bg-cyan-500/20 hover:text-cyan-300 text-xs font-mono transition">rounded-full</button>
                                                <button onclick="toggleUtil('shadow-2xl')" class="util-btn px-3 py-1.5 rounded-lg border border-white/10 bg-white/5 hover:bg-cyan-500/20 hover:text-cyan-300 text-xs font-mono transition">shadow-2xl</button>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-[10px] uppercase tracking-widest text-white/30 font-bold mb-2">Warna & Efek</p>
                                            <div class="flex flex-wrap gap-2">
                                                <button onclick="toggleUtil('bg-gradient-to-r from-fuchsia-600 to-purple-600')" class="util-btn px-3 py-1.5 rounded-lg border border-white/10 bg-white/5 hover:bg-fuchsia-500/20 hover:text-fuchsia-300 text-xs font-mono transition">bg-gradient</button>
                                                <button onclick="toggleUtil('border-4 border-white/20')" class="util-btn px-3 py-1.5 rounded-lg border border-white/10 bg-white/5 hover:bg-fuchsia-500/20 hover:text-fuchsia-300 text-xs font-mono transition">border-white</button>
                                                <button onclick="toggleUtil('transform hover:rotate-12 transition duration-300')" class="util-btn px-3 py-1.5 rounded-lg border border-white/10 bg-white/5 hover:bg-fuchsia-500/20 hover:text-fuchsia-300 text-xs font-mono transition">hover:rotate</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="h-64 bg-[#050912] rounded-xl border border-white/5 flex items-center justify-center relative overflow-hidden">
                                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
                                    <div id="demoBox" class="bg-gray-700 text-white w-32 h-32 flex items-center justify-center font-bold transition-all duration-500 cursor-pointer text-sm">
                                        Elemen
                                    </div>
                                    <div class="absolute bottom-4 left-4 right-4">
                                        <code id="classListDisplay" class="block w-full text-[10px] text-cyan-300 font-mono bg-black/50 backdrop-blur p-2 rounded border border-white/10 truncate">
                                            class="bg-gray-700..."
                                        </code>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="comparison" class="lesson-section scroll-mt-32" data-lesson-id="{{ $subbab1LessonIds[3] ?? 10 }}">
                        <div class="p-8 rounded-3xl bg-gradient-to-r from-purple-900/10 to-transparent border-l-4 border-purple-500">
                            <h2 class="text-2xl font-bold text-white mb-4">Semantik vs Utilitas</h2>
                            <p class="text-white/70 mb-6 text-sm">
                                Geser tombol di bawah untuk melihat perbedaan mendasar antara cara menulis CSS Konvensional (Semantik) dengan Tailwind (Utilitas).
                            </p>
                            
                            <div class="flex justify-center mb-6">
                                <div class="bg-black/30 p-1 rounded-lg border border-white/10 flex">
                                    <button onclick="setCompareMode('semantic')" id="btnSemantic" class="px-6 py-2 rounded-md text-sm font-bold bg-white/10 text-white transition-all">Konvensional</button>
                                    <button onclick="setCompareMode('utility')" id="btnUtility" class="px-6 py-2 rounded-md text-sm font-bold text-white/50 hover:text-white transition-all">Tailwind CSS</button>
                                </div>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-8 items-start">
                                <div class="bg-[#0d1117] rounded-xl border border-white/10 overflow-hidden shadow-lg h-64 relative">
                                    <div class="absolute top-0 left-0 px-4 py-2 bg-white/5 border-b border-white/5 w-full flex justify-between">
                                        <span class="text-[10px] font-mono text-gray-400" id="filenameDisplay">style.css</span>
                                    </div>
                                    <div class="p-4 pt-12 overflow-auto h-full custom-scrollbar">
                                        <pre id="codeDisplay" class="font-mono text-xs leading-relaxed text-blue-300"></pre>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div id="explanationTitle" class="text-lg font-bold text-white">Pendekatan CSS Tradisional</div>
                                    <p id="explanationText" class="text-sm text-white/60 leading-relaxed">
                                        Anda harus membuat nama kelas semantik, lalu membuka file CSS terpisah. Ini menyebabkan "Context Switching" dan file CSS yang terus membesar.
                                    </p>
                                    <div id="prosCons" class="grid grid-cols-2 gap-2 mt-4">
                                        </div>
                                </div>
                            </div>
                        </div>
                    </section>
<!-- reviisi -->
                    <section id="quiz-1-2" 
    class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" 
    data-lesson-id="{{ $subbab1LessonIds[4] ?? 11 }}"
    data-manual="true">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-6 lg:p-10 overflow-hidden shadow-2xl">
                            
                            <div class="mb-8">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-[10px] font-bold uppercase mb-4 tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    Final Challenge
                                </div>
                                <h2 class="text-2xl font-black text-white mb-2">Tantangan: Buat Kartu Profil</h2>
                                <p class="text-white/60 text-sm">
                                    Gunakan utility class Tailwind untuk membuat komponen sederhana.
                                    <br>Syarat: <span class="text-cyan-300">bg-gray-800</span>, <span class="text-cyan-300">p-6</span>, <span class="text-cyan-300">rounded-xl</span>, <span class="text-cyan-300">text-white</span>.
                                </p>
                            </div>

                            <div class="grid lg:grid-cols-2 gap-4 h-[400px] border border-white/10 rounded-xl overflow-hidden bg-black/40">
                                <div class="flex flex-col border-r border-white/10">
                                    <div class="flex justify-between items-center px-4 py-2 bg-[#0f141e] border-b border-white/5">
                                        <span class="text-xs text-white/50 font-mono">HTML Editor</span>
                                        <button onclick="resetEditor()" class="text-[10px] text-red-400 hover:text-red-300">Reset</button>
                                    </div>
                                    <div id="codeEditor" class="flex-1"></div>
                                </div>
                                <div class="flex flex-col bg-white relative">
                                    <div class="flex justify-between items-center px-4 py-2 bg-gray-100 border-b border-gray-200">
                                        <span class="text-xs text-gray-500 font-bold">Live Preview</span>
                                    </div>
                                    <iframe id="previewFrame" class="flex-1 w-full h-full border-0 bg-white"></iframe>
                                </div>
                            </div>

                            <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white/5 p-4 rounded-xl border border-white/5">
                                <div id="validationFeedback" class="text-sm font-bold text-white/50">
                                    ⏳ Silakan mulai mengetik...
                                </div>
                                <button id="submitExerciseBtn" onclick="submitExercise()" disabled
                                    class="px-6 py-2 rounded-lg bg-emerald-600 text-white text-sm font-bold shadow-lg shadow-emerald-900/20 opacity-50 cursor-not-allowed transition-all hover:scale-105">
                                    Selesai & Lanjut
                                </button>
                            </div>

                        </div>
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
                            <div class="font-bold text-sm">Latar Belakang</div>
                        </div>
                        <div class="w-10 h-10 rounded-full border border-white/5 flex items-center justify-center">🔒</div>
                    </div>
                </div>
                    </section>

                </article>

               
                <div class="mt-16 text-center text-white/20 text-xs">
                    &copy; {{ date('Y') }} Flowwind Learn. Materi dilindungi hak cipta.
                </div>
            </div>
        </main>
    </div>
</div>

<style>
    /* UTILS */
    .nav-link.active { color: #22d3ee; position: relative; }
    .nav-link.active::after { content: ''; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: linear-gradient(to right,#22d3ee,#3b82f6); box-shadow: 0 0 12px rgba(34,211,238,.8); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    
    /* ANIMATIONS */
    #animated-bg{ background: radial-gradient(600px circle at 20% 20%, rgba(217,70,239,.15), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(34,211,238,.15), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(168,85,247,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    #gradient-wave{ background:linear-gradient(120deg,rgba(217,70,239,.08),rgba(34,211,238,.08),rgba(168,85,247,.08)); background-size:400% 400%; animation:waveMove 26s ease infinite; }
    @keyframes waveMove{ 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
    
    /* SIDEBAR */
    .sb-group.open .accordion-content { max-height: 1000px; opacity: 1; }
    .sb-group:not(.open) .accordion-content { max-height: 0; opacity: 0; overflow: hidden; }
    .sb-group.open svg { transform: rotate(180deg); }
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: rgba(255,255,255,0.5); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: #fff; background: rgba(255,255,255,0.03); }
    .nav-item.active { color: #22d3ee; background: rgba(34,211,238,0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #334155; transition: all 0.3s; }
    .nav-item.active .dot { background: #22d3ee; box-shadow: 0 0 8px #22d3ee; transform: scale(1.2); }
    .dot-activity { width: 6px; height: 6px; border-radius: 50%; background: #e879f9; opacity: 0.8; }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>

<script>
    /* --- 1. CONFIGURATION --- */
    window.SUBBAB_LESSON_IDS = @json($subbab12LessonIds); 
    window.INIT_COMPLETED_LESSONS = @json($completedLessonIds);
    
    // State
    let completedLessons = new Set(window.INIT_COMPLETED_LESSONS);
    let activityCompleted = @json($activityCompleted ?? false);

    // ID Lesson Tantangan (Lesson terakhir di array / Lesson 11)
    const CHALLENGE_LESSON_ID = window.SUBBAB_LESSON_IDS[window.SUBBAB_LESSON_IDS.length - 1];

    /* --- 2. DOM READY --- */
    document.addEventListener('DOMContentLoaded', () => {
        updateProgressUI();
        initLessonObserver();
        initScrollSpy();
        initVisualEffects();
        initMonaco();
        setCompareMode('semantic');

        if (activityCompleted) {
            markExerciseAsDone();
        }
    });

    /* --- 3. LOGIC PROGRESS BAR (HUMAN LOGIC) --- */
    function updateProgressUI() {
        const totalLessons = window.SUBBAB_LESSON_IDS.length;
        // Hitung lesson yang sudah ada di database
        const lessonsDone = window.SUBBAB_LESSON_IDS.filter(id => completedLessons.has(id)).length;
        
        // Kalkulasi Persentase
        let percent = Math.round((lessonsDone / totalLessons) * 100);

        // --- UPDATE UI ---
        ['topProgressBar', 'sideProgressBar'].forEach(id => {
            const el = document.getElementById(id); if(el) el.style.width = percent + '%';
        });
        ['progressLabelTop', 'progressLabelSide'].forEach(id => {
            const el = document.getElementById(id); if(el) el.innerText = percent + '%';
        });

        // Jika 100%, buka bab selanjutnya
        if (percent === 100) {
            unlockNextChapterUI();
        }
    }

    /* --- 4. SCROLL OBSERVER (DENGAN FILTER MANUAL) --- */
    function initLessonObserver() {
        const obs = new IntersectionObserver(async entries => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    const id = Number(entry.target.dataset.lessonId);
                    
                    // [LOGIC BARU] 
                    // Cek apakah section ini mode manual? (Tantangan Coding)
                    // Jika ya, JANGAN simpan otomatis saat discroll.
                    const isManual = entry.target.getAttribute('data-manual') === 'true';

                    if (!isManual && id && !completedLessons.has(id)) {
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

    /* --- 5. SUBMIT EXERCISE (SAVE LESSON 11 MANUAL) --- */
    async function submitExercise() {
        const btn = document.getElementById('submitExerciseBtn');
        btn.innerHTML = "Menyimpan...";
        btn.disabled = true;

        try {
            // 1. Simpan Activity (Bobot Nilai)
            const resActivity = await fetch('/activity/complete', { 
                method: 'POST', 
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }, 
                body: JSON.stringify({ activity_id: 2, score: 100 }) 
            });

            if(!resActivity.ok) throw new Error('Gagal Activity');

            // 2. [LOGIC BARU] Simpan Lesson Terakhir (Challenge) SEKARANG
            // Karena user sudah sukses mengerjakan, baru kita anggap Lesson 11 selesai.
            if (!completedLessons.has(CHALLENGE_LESSON_ID)) {
                await saveLessonToDB(CHALLENGE_LESSON_ID);
                completedLessons.add(CHALLENGE_LESSON_ID);
            }

            // 3. Update UI
            activityCompleted = true;
            updateProgressUI(); // Bar akan naik ke 100% di sini
            markExerciseAsDone();
            
         
        } catch(e) {
            btn.innerHTML = "Gagal, Coba Lagi";
            btn.disabled = false;
            console.error(e);
        }
    }

    // Fungsi simpan ke DB (Reusable)
    async function saveLessonToDB(id) {
        const form = new FormData(); form.append('lesson_id', id);
        await fetch('/lesson/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: form });
    }

    /* --- 6. MONACO & VALIDATION (SAMA SEPERTI SEBELUMNYA) --- */
    let editor = null;
    const starterCode = `<div class="">
  <h1 class="">Nama Anda</h1>
  <p class="">Web Developer</p>
  <p class="">Deskripsi singkat...</p>
</div>`;

    function initMonaco() {
        require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' } });
        require(['vs/editor/editor.main'], function () {
            editor = monaco.editor.create(document.getElementById('codeEditor'), {
                value: starterCode,
                language: 'html',
                theme: 'vs-dark',
                fontSize: 14,
                minimap: { enabled: false },
                automaticLayout: true,
                padding: { top: 16 }
            });
            renderPreview(starterCode);
            editor.onDidChangeModelContent(() => {
                if(activityCompleted) return;
                const code = editor.getValue();
                renderPreview(code);
                checkCompletion(code);
            });
        });
    }

    function renderPreview(code) {
        const frame = document.getElementById('previewFrame');
        if(!frame) return;
        frame.srcdoc = `<!doctype html><html><head><script src="https://cdn.tailwindcss.com"><\/script></head><body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">${code}</body></html>`;
    }

    function checkCompletion(code) {
        const checks = [
            { regex: /bg-gray-800/, label: "Background Gelap" },
            { regex: /p-6/, label: "Padding 6" },
            { regex: /rounded-xl/, label: "Rounded XL" },
            { regex: /text-white/, label: "Teks Putih" }
        ];
        let passed = 0;
        checks.forEach(c => { if (c.regex.test(code)) passed++; });

        const btn = document.getElementById('submitExerciseBtn');
        const msg = document.getElementById('validationFeedback');

        if (passed === checks.length) {
            msg.innerHTML = `<span class="text-emerald-400 font-bold">✅ Sempurna! Silakan submit.</span>`;
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            msg.innerHTML = `<span class="text-white/50">Lengkapi instruksi di atas...</span>`;
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    function resetEditor() { if(editor) editor.setValue(starterCode); }

    function markExerciseAsDone() {
        const btn = document.getElementById('submitExerciseBtn');
        const msg = document.getElementById('validationFeedback');
        btn.innerHTML = "Tercatat ✔";
        btn.className = "px-6 py-2 rounded-lg bg-white/10 text-white/50 text-sm font-bold cursor-not-allowed border border-white/10";
        btn.disabled = true;
        msg.innerHTML = `<span class="text-emerald-400 font-bold">🎉 Latihan Selesai! Progress Diupdate.</span>`;
        if(editor) editor.updateOptions({ readOnly: true });
    }

    /* --- 7. UTILS & INTERACTIVE DEMOS --- */
    function unlockNextChapterUI() {
        const sidebarIcon = document.querySelector('.sb-group.open .icon-status');
        if (sidebarIcon) {
            sidebarIcon.innerHTML = '✔';
            sidebarIcon.className = 'w-6 h-6 rounded-lg border flex items-center justify-center text-[10px] font-bold transition-colors bg-emerald-500/20 text-emerald-400 border-emerald-500/20';
        }
        const nextContainer = document.querySelector('.mt-32 .group.cursor-not-allowed');
        if (nextContainer) {
            nextContainer.classList.remove('cursor-not-allowed', 'text-slate-500');
            nextContainer.classList.add('text-cyan-400', 'cursor-pointer', 'hover:text-cyan-300');
            nextContainer.innerHTML = `<div class="text-right"><div class="text-[10px] uppercase tracking-widest opacity-50">Selanjutnya</div><div class="font-bold text-sm">Latar Belakang</div></div><div class="w-10 h-10 rounded-full border border-cyan-500/30 bg-cyan-500/10 flex items-center justify-center">→</div>`;
            nextContainer.onclick = () => window.location.href = "{{ route('courses.background') }}";
        }
    }

    // Toggle Demo Util
    function toggleUtil(className) {
        const box = document.getElementById('demoBox');
        const codeDisplay = document.getElementById('classListDisplay');
        const classes = className.split(' ');
        classes.forEach(c => { if(box.classList.contains(c)) box.classList.remove(c); else box.classList.add(c); });
        codeDisplay.innerText = `class="${box.className}"`;
    }

    // Comparison Logic
    const comparisonData = {
        semantic: { filename: 'style.css', code: `.chat-notification {\n  display: flex;\n  ... \n}`, title: 'Pendekatan CSS Tradisional', desc: 'Memisahkan style ke file lain.', prosCons: [{text:'⛔ File CSS Bengkak', type:'bad'}] },
        utility: { filename: 'index.html', code: `<div class="p-6 bg-white ...">\n</div>`, title: 'Pendekatan Utility-First', desc: 'Langsung di HTML.', prosCons: [{text:'✅ Development Cepat', type:'good'}] }
    };
    function setCompareMode(mode) {
        const data = comparisonData[mode];
        document.getElementById('btnSemantic').className = mode === 'semantic' ? "px-6 py-2 rounded-md text-sm font-bold bg-white/10 text-white transition-all" : "px-6 py-2 rounded-md text-sm font-bold text-white/50 hover:text-white transition-all";
        document.getElementById('btnUtility').className = mode === 'utility' ? "px-6 py-2 rounded-md text-sm font-bold bg-white/10 text-white transition-all" : "px-6 py-2 rounded-md text-sm font-bold text-white/50 hover:text-white transition-all";
        document.getElementById('filenameDisplay').innerText = data.filename;
        document.getElementById('codeDisplay').innerText = data.code;
        document.getElementById('explanationTitle').innerText = data.title;
        document.getElementById('explanationText').innerText = data.desc;
        document.getElementById('prosCons').innerHTML = data.prosCons.map(item => `<div class="p-3 rounded-lg border text-xs ${item.type === 'good' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-300' : 'bg-red-500/10 border-red-500/20 text-red-300'}">${item.text}</div>`).join('');
    }

    function initScrollSpy() {
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

    function initVisualEffects() {
        const c=document.getElementById('stars'),x=c.getContext('2d');
        let s=[]; function r(){c.width=innerWidth;c.height=innerHeight}
        r();window.onresize=r;
        for(let i=0;i<100;i++)s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.2,v:Math.random()*0.2+.1});
        (function a(){x.clearRect(0,0,c.width,c.height);x.fillStyle='rgba(255,255,255,.4)';s.forEach(t=>{x.beginPath();x.arc(t.x,t.y,t.r,0,6.28);x.fill();t.y+=t.v;if(t.y>c.height)t.y=0});requestAnimationFrame(a);})();
        $(window).on('mousemove',e=>{ $('#cursor-glow').css({left:e.clientX,top:e.clientY}); });
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