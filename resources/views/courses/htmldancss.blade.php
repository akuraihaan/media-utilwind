@extends('layouts.landing')
@section('title','Bab 1.1 · Dasar HTML & CSS')

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
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-white/10 to-transparent border border-white/10 flex items-center justify-center font-bold text-xs">1.1</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Pendahuluan Web</h1>
                        <p class="text-[10px] text-white/50">Estimasi: 15 Menit</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-24 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-fuchsia-500 to-cyan-400 w-0 transition-all duration-500 shadow-[0_0_10px_#d946ef]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-cyan-400 font-bold text-xs">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                <article class="space-y-32">
                    
                    <section id="intro" class="lesson-section scroll-mt-32" data-lesson-id="1">
                        <div class="space-y-6">
                            <span class="inline-block px-3 py-1 rounded-full bg-gradient-to-r from-fuchsia-500/10 to-transparent border-l-2 border-fuchsia-500 text-fuchsia-300 text-[10px] font-bold uppercase tracking-widest">Konsep Dasar</span>
                            <h2 class="text-4xl lg:text-6xl font-black text-white leading-[1.1] tracking-tight">
                                Fondasi <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 via-purple-400 to-cyan-400 animate-gradient-x">Web Modern</span>
                            </h2>
                            <p class="text-xl text-white/70 leading-relaxed max-w-3xl">
                                Sebelum terjun ke framework canggih seperti Tailwind, kita harus menguasai "batu bata" penyusun web: <strong class="text-cyan-300 border-b border-cyan-500/30">HTML</strong> dan <strong class="text-fuchsia-300 border-b border-fuchsia-500/30">CSS</strong>.
                            </p>
                            <div class="grid md:grid-cols-2 gap-6 mt-8">
                                <div class="p-8 rounded-3xl bg-[#0f141e] border border-white/5 hover:border-orange-500/30 transition-all duration-500 group">
                                    <div class="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition">💀</div>
                                    <h3 class="text-lg font-bold text-white mb-2">HTML = Kerangka</h3>
                                    <p class="text-sm text-white/50">Memberikan struktur, posisi, dan keberadaan elemen.</p>
                                </div>
                                <div class="p-8 rounded-3xl bg-[#0f141e] border border-white/5 hover:border-blue-500/30 transition-all duration-500 group">
                                    <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition">🎨</div>
                                    <h3 class="text-lg font-bold text-white mb-2">CSS = Tampilan</h3>
                                    <p class="text-sm text-white/50">Memberikan warna, gaya, dan estetika.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="html-css" class="lesson-section scroll-mt-32" data-lesson-id="2">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">Definisi Teknis</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>
                        <div class="space-y-4">
                            <div class="p-6 rounded-2xl bg-white/[0.02] border border-white/5 hover:bg-white/[0.04] transition flex gap-6 items-start">
                                <div class="w-12 h-12 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-500 font-black shrink-0">H</div>
                                <div>
                                    <h3 class="text-lg font-bold text-white mb-1">HTML</h3>
                                    <p class="text-white/60 text-sm">HyperText Markup Language. Tidak memiliki logika (if/else), hanya deklarasi elemen seperti <code>h1</code>, <code>div</code>, <code>span</code>.</p>
                                </div>
                            </div>
                            <div class="p-6 rounded-2xl bg-white/[0.02] border border-white/5 hover:bg-white/[0.04] transition flex gap-6 items-start">
                                <div class="w-12 h-12 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-500 font-black shrink-0">C</div>
                                <div>
                                    <h3 class="text-lg font-bold text-white mb-1">CSS</h3>
                                    <p class="text-white/60 text-sm">Cascading Style Sheets. Mekanisme untuk mengatur properti visual seperti <code>color</code>, <code>font-size</code>, dan <code>display</code>.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="relation" class="lesson-section scroll-mt-32" data-lesson-id="3">
                        <div class="grid lg:grid-cols-2 gap-10 items-center">
                            <div>
                                <h2 class="text-3xl font-bold text-white mb-4">Struktur Dokumen</h2>
                                <p class="text-white/70 leading-relaxed mb-6">
                                    Setiap halaman web yang valid memiliki struktur pohon (DOM Tree). Bayangkan seperti pohon terbalik: Akarnya adalah <code>html</code>, cabangnya adalah <code>head</code> dan <code>body</code>.
                                </p>
                                <ul class="space-y-3 text-sm text-white/60">
                                    <li class="flex gap-3"><span class="text-emerald-400 font-bold">✓</span> Wajib ada DOCTYPE.</li>
                                    <li class="flex gap-3"><span class="text-emerald-400 font-bold">✓</span> Head tidak terlihat user.</li>
                                    <li class="flex gap-3"><span class="text-emerald-400 font-bold">✓</span> Body adalah kanvas utama.</li>
                                </ul>
                            </div>
                            <div class="rounded-xl overflow-hidden border border-white/10 bg-[#0d1117] shadow-2xl group ring-1 ring-white/5">
                                <div class="flex items-center justify-between px-4 py-2 bg-white/5 border-b border-white/5">
                                    <span class="text-[10px] font-mono text-white/30">index.html</span>
                                </div>
                                <div class="p-5 overflow-x-auto custom-scrollbar">
                                    <pre class="font-mono text-sm leading-relaxed"><span class="text-gray-500">&lt;!-- Struktur Dasar --&gt;</span>
<span class="text-purple-400">&lt;!DOCTYPE html&gt;</span>
<span class="text-blue-300">&lt;html&gt;</span>
  <span class="text-blue-300">&lt;head&gt;</span>
    <span class="text-green-300">&lt;title&gt;</span>Web Saya<span class="text-green-300">&lt;/title&gt;</span>
  <span class="text-blue-300">&lt;/head&gt;</span>
  <span class="text-blue-300">&lt;body&gt;</span>
    <span class="text-green-300">&lt;h1&gt;</span>Halo!<span class="text-green-300">&lt;/h1&gt;</span>
  <span class="text-blue-300">&lt;/body&gt;</span>
<span class="text-blue-300">&lt;/html&gt;</span></pre>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="practice" class="lesson-section scroll-mt-32" data-lesson-id="4">
                        <div class="p-8 rounded-3xl bg-gradient-to-r from-fuchsia-900/10 to-transparent border-l-4 border-fuchsia-500">
                            <h2 class="text-2xl font-bold text-white mb-2">Contoh Praktik</h2>
                            <p class="text-white/70 mb-4 text-sm">Kode HTML untuk membuat sebuah kartu:</p>
                            <div class="bg-black/40 rounded-xl p-4 border border-white/5 font-mono text-sm overflow-x-auto text-cyan-300 shadow-inner">&lt;div class="card"&gt;
  &lt;h2&gt;Judul&lt;/h2&gt;
  &lt;p&gt;Deskripsi...&lt;/p&gt;
&lt;/div&gt;</div>
                        </div>
                    </section>

                    <section id="exercise" class="lesson-section scroll-mt-32" data-lesson-id="5">
                        <div class="flex items-start gap-5 p-6 rounded-2xl bg-cyan-900/5 border border-cyan-500/10">
                            <div class="w-10 h-10 rounded-full bg-cyan-500/20 flex items-center justify-center text-cyan-400 text-xl">💡</div>
                            <div class="space-y-2">
                                <h2 class="text-xl font-bold text-white">Refleksi Materi</h2>
                                <p class="text-white/70 text-sm leading-relaxed">
                                    Ingatlah hierarki <code>html > head/body</code>. Ini adalah pola universal. Pastikan Anda memahami perbedaan antara tag pembuka <code>&lt;tag&gt;</code> dan penutup <code>&lt;/tag&gt;</code>.
                                </p>
                            </div>
                        </div>
                    </section>

                    <section id="activity-1-1" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="6">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 lg:p-12 overflow-hidden shadow-2xl">
                            <div class="relative z-10 grid lg:grid-cols-2 gap-12 items-center">
                                <div>
                                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-[10px] font-bold uppercase mb-4 tracking-widest">
                                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                                        Interactive
                                    </div>
                                    <h2 class="text-3xl font-black text-white mb-3">Susun Puzzle HTML</h2>
                                    <p class="text-white/60 mb-6 text-sm leading-relaxed">
                                        Kode di samping berantakan! <strong>Seret (Drag)</strong> item untuk menyusun urutan struktur HTML yang valid.
                                    </p>
                                    
                                    <div id="activitySubmitFeedback" class="min-h-[24px] mb-2 text-sm font-bold transition-all"></div>
                                    <p id="htmlOrderFeedback" class="min-h-[20px] text-xs text-white/40 mb-6"></p>

                                    <button id="submitActivity11Btn" onclick="handleSubmitActivity11()" 
                                        class="group w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-white transition-all duration-200 bg-gradient-to-r from-fuchsia-600 to-purple-600 rounded-xl hover:from-fuchsia-500 hover:to-purple-500 shadow-lg shadow-fuchsia-900/30 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span>Cek & Selesaikan</span>
                                    </button>
                                </div>

                                <div class="bg-black/20 backdrop-blur-sm rounded-2xl p-5 border border-white/5 shadow-inner">
                                    <ul id="html-order" class="space-y-2 select-none">
                                        <li draggable="true" data-order="1" class="draggable-item group relative flex items-center gap-3 p-3 rounded-lg bg-[#1a1f2e] border border-white/5 cursor-grab">
                                            <code class="text-orange-300 font-mono text-xs font-bold">&lt;!DOCTYPE html&gt;</code>
                                            <div class="ml-auto check-icon hidden"><span class="text-emerald-400 text-xs font-bold">✔</span></div>
                                        </li>
                                        <li draggable="true" data-order="2" class="draggable-item group relative flex items-center gap-3 p-3 rounded-lg bg-[#1a1f2e] border border-white/5 cursor-grab">
                                            <code class="text-blue-300 font-mono text-xs font-bold">&lt;html&gt; ... &lt;/html&gt;</code>
                                            <div class="ml-auto check-icon hidden"><span class="text-emerald-400 text-xs font-bold">✔</span></div>
                                        </li>
                                        <li draggable="true" data-order="3" class="draggable-item group relative flex items-center gap-3 p-3 rounded-lg bg-[#1a1f2e] border border-white/5 cursor-grab">
                                            <code class="text-purple-300 font-mono text-xs font-bold">&lt;head&gt; ... &lt;/head&gt;</code>
                                            <div class="ml-auto check-icon hidden"><span class="text-emerald-400 text-xs font-bold">✔</span></div>
                                        </li>
                                        <li draggable="true" data-order="4" class="draggable-item group relative flex items-center gap-3 p-3 rounded-lg bg-[#1a1f2e] border border-white/5 cursor-grab">
                                            <code class="text-emerald-300 font-mono text-xs font-bold">&lt;body&gt; ... &lt;/body&gt;</code>
                                            <div class="ml-auto check-icon hidden"><span class="text-emerald-400 text-xs font-bold">✔</span></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>

                </article>

                <div class="mt-32 pt-8 border-t border-white/5 text-center text-white/30 text-xs">
                    &copy; {{ date('Y') }} Flowwind Learn. Materi dilindungi hak cipta.
                </div>
            </div>
        </main>
    </div>
</div>

<style>
    /* NAVBAR ACTIVE LINK */
    .nav-link.active { color: #e879f9; position: relative; }
    .nav-link.active::after { content: ''; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: linear-gradient(to right,#d946ef,#22d3ee); box-shadow: 0 0 12px rgba(217,70,239,.8); border-radius: 2px; }

    /* BACKGROUND ANIMATIONS */
    #animated-bg{ background: radial-gradient(600px circle at 20% 20%, rgba(217,70,239,.25), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(34,211,238,.25), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(168,85,247,.25), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    #gradient-wave{ background:linear-gradient(120deg,rgba(217,70,239,.12),rgba(34,211,238,.12),rgba(168,85,247,.12)); background-size:400% 400%; animation:waveMove 26s ease infinite; }
    @keyframes waveMove{ 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }

    /* SCROLLBARS */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

    /* SIDEBAR ACCORDION */
    .sb-group.open .accordion-content { max-height: 1000px; opacity: 1; }
    .sb-group:not(.open) .accordion-content { max-height: 0; opacity: 0; overflow: hidden; }
    .sb-group.open svg { transform: rotate(180deg); }

    /* NAVIGATION ITEM */
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: rgba(255,255,255,0.5); border-radius: 8px; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); position: relative; }
    .nav-item:hover { color: #fff; background: rgba(255,255,255,0.03); }
    .nav-item.active { color: #22d3ee; background: rgba(34,211,238,0.05); font-weight: 600; }
    .nav-item.activity-link { color: #e879f9; }
    .nav-item.activity-link.active { color: #e879f9; background: rgba(232,121,249,0.05); }

    /* DOT INDICATOR */
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #334155; transition: all 0.3s; }
    .nav-item:hover .dot { background: #94a3b8; }
    .nav-item.active .dot { background: #22d3ee; box-shadow: 0 0 8px #22d3ee; transform: scale(1.2); }
    .dot-activity { width: 6px; height: 6px; border-radius: 50%; background: #e879f9; opacity: 0.8; }

    /* DRAG & DROP */
    .draggable-item { transition: transform 0.2s, box-shadow 0.2s; }
    .draggable-item.dragging { opacity: 0.5; border: 1px dashed #22d3ee; background: rgba(34,211,238,0.05); }
    .draggable-item.correct { border-color: #10b981; background: rgba(16,185,129,0.05); }
    .draggable-item.wrong { border-color: #ef4444; animation: shake 0.4s; }

    /* ANIMATIONS */
    @keyframes shake { 0%, 100% {transform: translateX(0);} 25% {transform: translateX(-4px);} 75% {transform: translateX(4px);} }
    .animate-unlock { animation: unlockPop 0.6s ease-out; }
    @keyframes unlockPop { 0% { transform: scale(1); } 50% { transform: scale(1.05); box-shadow: 0 0 15px #22d3ee; } 100% { transform: scale(1); } }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    /* --- CONFIG & STATE --- */
    window.SUBBAB_1_LESSON_IDS = @json($subbab1LessonIds);
    window.INIT_COMPLETED_LESSONS = @json($completedLessonIds);
    let completedLessons = new Set(window.INIT_COMPLETED_LESSONS);
    let activityCompleted = @json($activityCompleted ?? false);

    /* --- DOM READY --- */
    document.addEventListener('DOMContentLoaded', () => {
        updateProgressUI();
        initLessonObserver();
        initScrollSpy();
        initDragDrop();
        initVisualEffects(); 
        if (activityCompleted) {
            markActivityAsDoneUI();
            unlockSubbab12();
        }
    });

    /* --- 0. VISUAL EFFECTS --- */
    function initVisualEffects() {
        const c=document.getElementById('stars');
        if(c) {
            const x=c.getContext('2d');
            let s=[]; function r(){c.width=innerWidth;c.height=innerHeight}
            r();window.onresize=r;
            for(let i=0;i<140;i++)s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.2,v:Math.random()*0.4+.2});
            (function a(){x.clearRect(0,0,c.width,c.height);x.fillStyle='rgba(255,255,255,.6)';s.forEach(t=>{x.beginPath();x.arc(t.x,t.y,t.r,0,6.28);x.fill();t.y+=t.v;if(t.y>c.height)t.y=0});requestAnimationFrame(a);})();
        }
        $(window).on('mousemove',e=>{ $('#cursor-glow').css({left:e.clientX,top:e.clientY}); });
    }

    /* --- 1. ACCORDION LOGIC --- */
    function toggleAccordion(contentId) {
        const content = document.getElementById(contentId);
        const group = content.closest('.accordion-group');
        const arrow = document.getElementById(contentId.replace('content', 'arrow'));
        if (content.style.maxHeight) { content.style.maxHeight = null; group.classList.remove('open'); if(arrow) arrow.style.transform = 'rotate(0deg)'; } 
        else { content.style.maxHeight = content.scrollHeight + "px"; group.classList.add('open'); if(arrow) arrow.style.transform = 'rotate(180deg)'; }
    }

    /* --- 2. PROGRESS ENGINE --- */
    function updateProgressUI() {
        const total = window.SUBBAB_1_LESSON_IDS.length;
        const done = window.SUBBAB_1_LESSON_IDS.filter(id => completedLessons.has(id)).length;
        let percent = Math.round((done / total) * 100);
        if (percent >= 95 && !activityCompleted) percent = 95;
        else if (done === total && activityCompleted) percent = 100;

        ['courseProgress', 'sideProgressBar', 'topProgressBar'].forEach(id => { const el = document.getElementById(id); if(el) el.style.width = percent + '%'; });
        ['progressLabelTop', 'progressLabelSide'].forEach(id => { const el = document.getElementById(id); if(el) el.innerText = percent + '%'; });

        if (percent === 100) unlockSubbab12();
    }

    function unlockSubbab12() {
        const btn = document.getElementById('btnSubbab12');
        if (!btn || !btn.querySelector('.icon-status').innerText.includes('🔒')) return;
        btn.classList.remove('text-white/40', 'cursor-not-allowed', 'opacity-50');
        btn.classList.add('text-white', 'animate-unlock', 'cursor-pointer', 'opacity-100');
        btn.disabled = false; // Enable button
        
        const iconSpan = btn.querySelector('.icon-status');
        if(iconSpan) {
            iconSpan.innerHTML = '→';
            iconSpan.className = 'icon-status w-6 h-6 rounded-lg flex items-center justify-center transition-all bg-cyan-500/20 text-cyan-400 border-cyan-500/30 border';
        }
        btn.onclick = () => window.location.href = btn.dataset.href;
    }

    /* --- 3. SCROLL SPY ENGINE --- */
    function initScrollSpy() {
        const mainScroll = document.getElementById('mainScroll');
        const sections = document.querySelectorAll('.lesson-section');
        const navLinks = document.querySelectorAll('.accordion-content .nav-item');
        mainScroll.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => { if (mainScroll.scrollTop >= section.offsetTop - 200) current = '#' + section.id; });
            navLinks.forEach(link => { link.classList.remove('active'); if (link.getAttribute('data-target') === current) link.classList.add('active'); });
        });
        navLinks.forEach(link => { link.addEventListener('click', () => { const target = document.querySelector(link.getAttribute('data-target')); if(target) mainScroll.scrollTo({ top: target.offsetTop - 120, behavior: 'smooth' }); }); });
    }

    /* --- 4. AUTO READ OBSERVER --- */
    function initLessonObserver() {
        const mainScroll = document.getElementById('mainScroll');
        const sections = document.querySelectorAll('.lesson-section');
        const observer = new IntersectionObserver(async entries => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    const id = Number(entry.target.dataset.lessonId);
                    if (id && !completedLessons.has(id)) {
                        try { await saveLessonToDB(id); completedLessons.add(id); updateProgressUI(); } catch (e) { console.error(e); }
                    }
                }
            }
        }, { threshold: 0.5, root: mainScroll });
        sections.forEach(s => observer.observe(s));
    }

    async function saveLessonToDB(id) {
        const form = new FormData(); form.append('lesson_id', id);
        await fetch('/lesson/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: form });
    }

    /* --- 5. DRAG & DROP LOGIC --- */
    function initDragDrop() {
        let dragged = null;
        const list = document.getElementById('html-order');
        list.querySelectorAll('li').forEach(item => {
            item.addEventListener('dragstart', () => { dragged = item; item.classList.add('dragging'); });
            item.addEventListener('dragend', () => { item.classList.remove('dragging'); dragged = null; });
            item.addEventListener('dragover', e => { e.preventDefault(); const after = getDragAfterElement(list, e.clientY); if (!after) list.appendChild(dragged); else list.insertBefore(dragged, after); });
        });
    }

    function getDragAfterElement(container, y) {
        const els = [...container.querySelectorAll('.draggable-item:not(.dragging)')];
        return els.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) return { offset: offset, element: child }; else return closest;
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    /* --- 6. SUBMIT ACTIVITY --- */
    async function handleSubmitActivity11() {
        const items = [...document.querySelectorAll('#html-order li')];
        let allCorrect = true;
        items.forEach((li, i) => {
            const ok = Number(li.dataset.order) === (i + 1);
            li.classList.toggle('correct', ok); li.classList.toggle('wrong', !ok);
            li.querySelector('.check-icon')?.classList.toggle('hidden', !ok);
            if (!ok) allCorrect = false;
        });

        const fb = document.getElementById('activitySubmitFeedback');
        const orderFb = document.getElementById('htmlOrderFeedback');

        if (!allCorrect) { orderFb.innerHTML = `<span class="text-red-400 font-bold">❌ Susunan masih salah.</span>`; return; }
        
        orderFb.innerHTML = `<span class="text-emerald-400 font-bold">✅ Susunan benar!</span>`;
        const btn = document.getElementById('submitActivity11Btn');
        btn.disabled = true; btn.classList.add('opacity-70', 'cursor-wait');

        try {
            const res = await fetch('/activity/complete', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }, body: JSON.stringify({ activity_id: 1, score: 100 }) });
            if(res.ok) { activityCompleted = true; updateProgressUI(); markActivityAsDoneUI(); }
        } catch(e) { btn.disabled = false; fb.innerHTML = `<span class="text-red-400 text-xs">Gagal koneksi.</span>`; }
    }

    function markActivityAsDoneUI() {
        const btn = document.getElementById('submitActivity11Btn');
        const feedback = document.getElementById('activitySubmitFeedback');
        if(btn) { btn.disabled = true; btn.innerHTML = "Selesai ✔"; btn.classList.add('opacity-50', 'cursor-not-allowed', 'bg-emerald-500/50'); }
        if(feedback) feedback.innerHTML = `<span class="text-emerald-400 font-bold">✔ Aktivitas Selesai</span>`;
        document.querySelectorAll('#html-order li').forEach(li => { li.classList.add('correct'); li.querySelector('.check-icon')?.classList.remove('hidden'); });
    }
</script>
@endsection