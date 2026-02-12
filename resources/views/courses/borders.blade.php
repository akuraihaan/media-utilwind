@extends('layouts.landing')
@section('title', 'Bab 3.3 · Borders & Effects')

@section('content')
{{-- <div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30"> --}}
<div id="courseRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-indigo-500/30 pt-20">

    {{-- DYNAMIC BACKGROUND --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-60"></div>
        <div id="gradient-wave" class="absolute inset-0"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.035]"></div>
        <div id="cursor-glow"></div>
    </div>

    {{-- NAVBAR --}}
        @include('layouts.partials.navbar')


    <div class="flex flex-1 overflow-hidden relative z-20">

        @include('layouts.partials.course-sidebar')

        <main id="mainScroll" class="flex-1 h-full overflow-y-auto scroll-smooth relative bg-transparent custom-scrollbar scroll-padding-top-24">
            
            {{-- STICKY HEADER --}}
            <div id="stickyHeader" class="sticky top-0 z-30 w-full bg-[#020617]/80 backdrop-blur-2xl border-b border-white/5 px-8 py-4 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500/20 to-transparent border border-indigo-500/20 flex items-center justify-center font-bold text-xs text-indigo-400">3.3</div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Borders & Effects</h1>
                        <p class="text-[10px] text-white/50">Radius, Width, Ring & Divide</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block w-32 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div id="topProgressBar" class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 w-0 transition-all duration-500 shadow-[0_0_10px_#6366f1]"></div>
                    </div>
                    <span id="progressLabelTop" class="text-indigo-400 font-bold text-xs">0%</span>
                </div>
            </div>

            <div class="p-6 lg:p-16 max-w-5xl mx-auto pb-40">
                <article class="space-y-40">
                    
                    {{-- LESSON 1: RADIUS & WIDTH --}}
                    <section id="radius" class="lesson-section scroll-mt-32" data-lesson-id="54">
                        <div class="space-y-8">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase tracking-widest">
                                <!-- <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span> -->
                                Dasar Border
                            </div>
                            
                            <h2 class="text-4xl lg:text-5xl font-black text-white leading-[1.1]">
                                Radius & <br> 
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Ketebalan Garis</span>
                            </h2>
                            
                            <div class="prose prose-invert max-w-none text-white/80 space-y-6">
                                <p class="text-lg leading-relaxed">
                                    Border radius digunakan untuk memberikan efek sudut melengkung pada elemen, menciptakan tampilan yang lebih lembut dan modern. Sedangkan Border width mengatur ketebalan garis tepi elemen untuk memberikan struktur.
                                </p>
                                
                                <h3 class="text-xl font-bold text-white mt-8">1. Border Radius (Rounded)</h3>
                                <p>Tailwind menyediakan utilitas <code>rounded</code> mulai dari <code>none</code> (tajam) hingga <code>full</code> (lingkaran).</p>

                                <div class="bg-[#0f141e] p-6 rounded-2xl border border-white/5 relative overflow-hidden group mt-6">
                                    <h4 class="text-xs font-bold text-white/40 uppercase tracking-widest mb-4">Simulator Radius</h4>
                                    <div class="flex flex-wrap gap-3 mb-6">
                                        <button onclick="setRadius('rounded-none')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs hover:bg-white/10 transition">Square</button>
                                        <button onclick="setRadius('rounded-lg')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs hover:bg-white/10 transition">Rounded LG</button>
                                        <button onclick="setRadius('rounded-full')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs hover:bg-white/10 transition">Circle</button>
                                        <button onclick="setRadius('rounded-tr-[3rem]')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs hover:bg-white/10 transition">Custom Corner</button>
                                    </div>
                                    <div class="flex justify-center h-40 items-center bg-black/20 rounded-xl border border-white/5">
                                        <div id="demo-radius" class="w-32 h-32 bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg flex items-center justify-center text-white font-bold transition-all duration-500 border-4 border-white/20 rounded-none">
                                            Box
                                        </div>
                                    </div>
                                </div>

                                <h3 class="text-xl font-bold text-white mt-12">2. Border Width</h3>
                                <p>Mengatur ketebalan garis. Tersedia ukuran 0, 2, 4, dan 8 pixel. Bisa juga per sisi (<code>border-t-*</code>, <code>border-x-*</code>).</p>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                                    <div class="h-16 bg-white/5 border border-indigo-500 flex items-center justify-center text-xs rounded">border (1px)</div>
                                    <div class="h-16 bg-white/5 border-4 border-indigo-500 flex items-center justify-center text-xs rounded">border-4</div>
                                    <div class="h-16 bg-white/5 border-l-8 border-indigo-500 flex items-center justify-center text-xs rounded">border-l-8</div>
                                    <div class="h-16 bg-white/5 border-y-4 border-indigo-500 flex items-center justify-center text-xs rounded">border-y-4</div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- 2. COLOR, STYLE & DIVIDE --}}
                    <section id="style" class="lesson-section scroll-mt-32" data-lesson-id="55">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">3.3.2 Gaya & Divide</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <div class="prose prose-invert max-w-none text-white/80 space-y-6">
                            <p>
                                Kombinasikan ketebalan dengan <strong>Color</strong> (<code>border-indigo-500</code>) dan <strong>Style</strong> (<code>dashed</code>, <code>dotted</code>, <code>double</code>).
                                Gunakan <strong>Divide</strong> untuk memberi garis antar elemen anak secara otomatis.
                            </p>
                            
                            <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-8 mb-12 relative overflow-hidden shadow-2xl">
                                <h3 class="text-lg font-bold text-white mb-6 text-center">Divide Simulator</h3>
                                <div class="flex justify-center gap-2 mb-6">
                                    <button onclick="setDivide('divide-y divide-white/10')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs hover:bg-white/10 transition">Default (Y)</button>
                                    <button onclick="setDivide('divide-x divide-indigo-500')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs hover:bg-white/10 transition">Divide X Indigo</button>
                                    <button onclick="setDivide('divide-y divide-dashed divide-purple-500')" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs hover:bg-white/10 transition">Divide Dashed</button>
                                </div>
                                <ul id="demo-divide" class="divide-y divide-white/10 bg-white/5 rounded-xl text-sm transition-all duration-300 flex flex-col w-full max-w-md mx-auto">
                                    <li class="p-4 hover:bg-white/5 transition text-center flex-1">Item 1</li>
                                    <li class="p-4 hover:bg-white/5 transition text-center flex-1">Item 2</li>
                                    <li class="p-4 hover:bg-white/5 transition text-center flex-1">Item 3</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    {{-- 3. OUTLINE & RING --}}
                    <section id="ring" class="lesson-section scroll-mt-32" data-lesson-id="56">
                        <div class="flex items-center gap-4 mb-8">
                            <h2 class="text-3xl font-bold text-white">3.3.3 Outline & Ring</h2>
                            <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                        </div>

                        <div class="prose prose-invert max-w-none text-white/80 space-y-6">
                            <p>
                                <strong>Ring</strong> adalah utilitas unik Tailwind untuk membuat outline solid menggunakan <code>box-shadow</code>. Ini lebih fleksibel daripada border biasa karena tidak memengaruhi layout dan mendukung offset (jarak).
                            </p>

                            <div class="bg-[#0b0f19] border border-white/10 rounded-2xl p-8 mb-8 relative overflow-hidden shadow-2xl text-center">
                                <div id="demo-ring" class="inline-block px-8 py-3 bg-white text-slate-900 font-bold rounded-lg transition-all duration-300 ring-0 ring-indigo-500 mb-8">
                                    Focus Target
                                </div>
                                <div class="flex justify-center gap-2">
                                    <button onclick="setRing('ring-0')" class="px-3 py-1 bg-white/5 rounded text-xs border border-white/10 hover:bg-white/10">No Ring</button>
                                    <button onclick="setRing('ring-4')" class="px-3 py-1 bg-white/5 rounded text-xs border border-white/10 hover:bg-white/10">Ring-4</button>
                                    <button onclick="setRing('ring-2 ring-offset-4 ring-offset-[#0b0f19]')" class="px-3 py-1 bg-white/5 rounded text-xs border border-white/10 hover:bg-white/10">Offset Ring</button>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- 4. EXPERT CHALLENGE --}}
                    <section id="activity-challenge" class="lesson-section scroll-mt-32 pt-10 border-t border-white/10" data-lesson-id="56" data-manual="true">
                        <div class="relative rounded-[2rem] bg-[#0b0f19] border border-white/10 p-8 overflow-hidden shadow-2xl text-center group hover:border-purple-500/30 transition-all duration-500">
                            
                            <div class="p-8 border-b border-white/10 bg-gradient-to-r from-indigo-900/10 to-transparent relative">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-600 text-white text-[10px] font-bold uppercase mb-4 tracking-widest shadow-lg shadow-indigo-600/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    Expert Challenge
                                </div>
                                <h2 class="text-3xl font-black text-white mb-4">Studi Kasus: Profile Card</h2>
                                <p class="text-white/60 text-sm max-w-2xl mx-auto leading-relaxed">
                                    Bangun komponen kartu profil modern. Syarat: <strong>Avatar</strong> harus bulat sempurna dengan Ring. <strong>Tombol</strong> harus memiliki border bawah tebal (efek 3D). <strong>List Skill</strong> harus dipisah dengan Divide.
                                </p>
                            </div>

                            <div class="grid lg:grid-cols-12 min-h-[600px] text-left border-x border-b border-white/5 rounded-b-[2rem] bg-[#0f141e]">
                                
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
                                        {{-- JS will inject controls here --}}
                                    </div>
                                    
                                    <div class="pt-6 mt-6 border-t border-white/5">
                                        <button id="checkBtn" onclick="checkSolution()" class="w-full py-4 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold text-lg shadow-lg hover:shadow-indigo-500/25 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                                            Verifikasi Desain 🚀
                                        </button>
                                        <div id="feedback-area" class="mt-4 hidden p-4 rounded-lg text-center text-sm font-bold animate-pulse"></div>
                                    </div>
                                </div>

                                <div class="lg:col-span-8 bg-slate-900 p-8 md:p-12 relative overflow-y-auto flex flex-col items-center justify-center">
                                    <div class="absolute top-4 right-4 text-[10px] font-mono text-slate-400 bg-slate-800 px-2 py-1 rounded border border-slate-700 shadow-sm">COMPONENT PREVIEW</div>

                                    <div class="w-72 bg-slate-800 rounded-2xl overflow-hidden shadow-2xl border border-slate-700 transition-all duration-500 group/card transform hover:-translate-y-1">
                                        
                                        <div class="h-24 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
                                            <div class="absolute inset-0 bg-white/10"></div>
                                        </div>
                                        
                                        <div class="px-6 pb-6 relative">
                                            <div class="relative -mt-10 mb-4 flex justify-center">
                                                <img id="target-avatar" src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&h=150" 
                                                    class="w-20 h-20 bg-slate-700 transition-all duration-500 object-cover rounded-none">
                                            </div>

                                            <div class="text-center mb-6">
                                                <h3 class="text-white font-bold text-lg">Alex Dev</h3>
                                                <p class="text-slate-400 text-xs">Frontend Engineer</p>
                                            </div>

                                            <button id="target-btn" class="w-full py-2.5 bg-indigo-600 text-white text-sm font-semibold mb-6 transition-all duration-200 active:scale-95 rounded-none">
                                                Follow Me
                                            </button>

                                            <div id="target-list" class="text-sm text-slate-300 transition-all duration-500 border border-slate-700 rounded-lg">
                                                <div class="p-3 text-center">Laravel</div>
                                                <div class="p-3 text-center">Tailwind CSS</div>
                                                <div class="p-3 text-center">React JS</div>
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
                        <div class="text-left">
                            <div class="text-[10px] uppercase tracking-widest opacity-50">Sebelumnya</div>
                            <div class="font-bold text-sm">3.2 Backgrounds</div>
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
    /* UTILS & ANIMATION (Indigo Theme) */
    .nav-link.active { color: #818cf8; position: relative; }
    .nav-link.active::after { content: ''; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px; background: linear-gradient(to right,#818cf8,#a78bfa); box-shadow: 0 0 12px rgba(129,140,248,.8); border-radius: 2px; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .shake { animation: shake 0.4s ease-in-out; }

    #animated-bg{ background: radial-gradient(600px circle at 20% 20%, rgba(99,102,241,.15), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(124,58,237,.15), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(167,139,250,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    
    .nav-item { display: flex; width: 100%; text-align: left; align-items: center; gap: 12px; padding: 10px 14px; font-size: 0.85rem; color: rgba(255,255,255,0.5); border-radius: 8px; transition: all 0.2s; position: relative; }
    .nav-item:hover { color: #fff; background: rgba(255,255,255,0.03); }
    .nav-item.active { color: #818cf8; background: rgba(129, 140, 248, 0.05); font-weight: 600; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: #334155; transition: all 0.3s; }
    .nav-item.active .dot { background: #818cf8; box-shadow: 0 0 8px #818cf8; transform: scale(1.2); }

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
    window.SUBBAB_LESSON_IDS = [56,57,58,59]; 
    window.INIT_COMPLETED_LESSONS = {!! json_encode($completedLessonIds ?? []) !!};
    let completedLessons = new Set(window.INIT_COMPLETED_LESSONS);
    
    // Activity ID = 13 (Borders Challenge)
    const ACTIVITY_ID = {{ $activityId ?? 12 }};
    let activityCompleted = {!! ($activityCompleted ?? false) ? 'true' : 'false' !!};

    /* --- 1. CORE SYSTEM --- */
    document.addEventListener('DOMContentLoaded', () => {
        updateProgressUI();
        initLessonObserver();
        initVisualEffects();
        initSidebarScroll();
        renderControls();
        updatePreview();
        
        if (activityCompleted) {
            disableExpertUI();
        }
    });

    /* --- 2. SIMULATORS UI --- */
    function setRadius(cls) { document.getElementById('demo-radius').className = `w-32 h-32 bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg flex items-center justify-center text-white font-bold transition-all duration-500 border-4 border-white/20 ${cls}`; }
    
    function setDivide(cls) {
        const list = document.getElementById('demo-divide');
        // Handle divide-x (row) vs divide-y (col)
        if(cls.includes('divide-x')) {
            list.className = `${cls} bg-white/5 rounded-xl text-sm transition-all duration-300 flex flex-row w-full max-w-md mx-auto`;
        } else {
            list.className = `${cls} bg-white/5 rounded-xl text-sm transition-all duration-300 flex flex-col w-full max-w-md mx-auto`;
        }
    }
    
    function setRing(cls) { document.getElementById('demo-ring').className = `inline-block px-8 py-3 bg-white text-slate-900 font-bold rounded-lg transition-all duration-300 ${cls} mb-8`; }

    /* --- 3. EXPERT CHALLENGE LOGIC --- */
    const studyCaseConfig = {
        avatar: {
            elementId: 'target-avatar',
            label: '1. Gaya Avatar',
            options: {
                style: [
                    {cls:'rounded-none', label:'Kotak'}, 
                    {cls:'rounded-full ring-4 ring-slate-700', label:'Ring', correct:true}, 
                    {cls:'rounded-xl border-4 border-white', label:'Border'}
                ]
            }
        },
        button: {
            elementId: 'target-btn',
            label: '2. Gaya Tombol',
            options: {
                style: [
                    {cls:'rounded-none', label:'Flat'}, 
                    {cls:'rounded-lg border-b-4 border-indigo-900', label:'3D Border', correct:true}, 
                    {cls:'rounded-full outline outline-2', label:'Outline'}
                ]
            }
        },
        list: {
            elementId: 'target-list',
            label: '3. Pemisah List',
            options: {
                style: [
                    {cls:'', label:'Kosong'}, 
                    {cls:'divide-y divide-slate-700', label:'Divide Y', correct:true}, 
                    {cls:'border-y border-slate-700', label:'Border Y'}
                ]
            }
        }
    };

    let userChoices = { avatar: {style:''}, button: {style:''}, list: {style:''} };

    function renderControls() {
        const container = $('#practice-controls');
        if(!container.length) return;
        
        Object.entries(studyCaseConfig).forEach(([sectionKey, sectionData]) => {
            let html = `<div class="bg-black/40 p-5 rounded-2xl border border-white/5 mb-6 hover:border-indigo-500/20 transition-colors">
                <h4 class="text-indigo-400 font-bold mb-4 uppercase text-[10px] tracking-[0.2em] border-b border-white/5 pb-2 flex items-center gap-2">
                    <span class="w-1 h-4 bg-indigo-500 rounded-full"></span>
                    ${sectionData.label}
                </h4>`;
            
            html += `<div class="flex flex-wrap gap-2">`;
            sectionData.options.style.forEach(opt => {
                html += `<button onclick="selectOption('${sectionKey}','style','${opt.cls}',this)" class="btn-opt-${sectionKey}-style px-3 py-2 rounded-lg text-xs font-mono border border-white/10 hover:border-indigo-500/50 hover:bg-indigo-500/10 text-white/60 transition-all active:scale-95" data-cls="${opt.cls}">${opt.label}</button>`;
            });
            html += `</div></div>`;
            container.append(html);
        });
    }

    window.selectOption = function(sKey, cKey, cls, btn) {
        if(activityCompleted) return;
        
        $(`.btn-opt-${sKey}-${cKey}`).removeClass('bg-indigo-600 text-white border-indigo-500 shadow-lg shadow-indigo-500/20').addClass('text-white/60 border-white/10');
        $(btn).removeClass('text-white/60 border-white/10').addClass('bg-indigo-600 text-white border-indigo-500 shadow-lg shadow-indigo-500/20');
        
        userChoices[sKey][cKey] = cls;
        const target = $(`#${studyCaseConfig[sKey].elementId}`);
        
        studyCaseConfig[sKey].options[cKey].forEach(o => {
            if(o.cls) target.removeClass(o.cls);
        });
        target.addClass(cls);
    }

    window.checkSolution = async function() {
        if(activityCompleted) return;
        let isCorrect = true;
        Object.entries(studyCaseConfig).forEach(([sKey, sData]) => {
            const pick = userChoices[sKey].style;
            const correct = sData.options.style.find(o => o.correct).cls;
            if(pick !== correct) isCorrect = false;
        });

        const fb = $('#feedback-area');
        fb.removeClass('hidden bg-red-500/10 text-red-400 bg-emerald-500/10 text-emerald-400 border-red-500/20 border-emerald-500/20');
        
        if(isCorrect) {
            fb.addClass('bg-emerald-500/10 text-emerald-400 border border-emerald-500/20').html(`
                <div class="text-3xl mb-2 animate-bounce">🎉</div>
                <div class="text-lg font-bold">Sempurna!</div>
                <div class="text-xs opacity-70 mt-1">Struktur visual mantap.</div>
            `);
            await finishChapter();
        } else {
            fb.addClass('bg-red-500/10 text-red-400 border border-red-500/20').html(`
                <div class="text-3xl mb-2 animate-pulse">🧐</div>
                <div class="text-lg font-bold">Belum Tepat</div>
                <div class="text-xs opacity-70 mt-1">Cek: Avatar=Ring, Tombol=3D, List=Divide.</div>
            `);
        }
        fb.removeClass('hidden');
    }

    async function finishChapter() {
        const btn = document.getElementById('checkBtn');
        btn.innerHTML = "Menyimpan...";
        try {
            await fetch('/activity/complete', { method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'}, body: JSON.stringify({ activity_id: ACTIVITY_ID, score: 100 }) });
            await saveLessonToDB(56);
            completedLessons.add(56);
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
            btn.className = "group flex items-center gap-3 text-right text-indigo-400 hover:text-indigo-300 transition cursor-pointer";
            btn.innerHTML = `<div class="text-right"><div class="text-[10px] uppercase tracking-widest opacity-50">Selanjutnya</div><div class="font-bold text-sm">Bab 4: Komponen</div></div><div class="w-10 h-10 rounded-full border border-indigo-500/30 bg-indigo-500/10 flex items-center justify-center">→</div>`;
        }
    }

    function initVisualEffects() { 
        const c=document.getElementById('stars'),x=c.getContext('2d');
        let s=[]; function r(){c.width=innerWidth;c.height=innerHeight}
        r();window.onresize=r;
        for(let i=0;i<100;i++)s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.2,v:Math.random()*0.2+.1});
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