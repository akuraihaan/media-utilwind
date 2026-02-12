@php
    // ==========================================
    // 1. DATA & KONFIGURASI GLOBAL
    // ==========================================
    $currentRoute = Route::currentRouteName();
    $userId = auth()->id();
    $kkmLab = 50; 
    $kkmQuiz = 70;

    // Helper Data (Safe Fallback)
    $map = $completedLessonsMap ?? []; 
    $scores = $quizScores ?? []; 

    // State Global: Bab 1 Selalu Terbuka
    $previousChapterPassed = true; 

    // ==========================================
    // 2. STRUKTUR MATERI
    // ==========================================
    $chapters = [
        [
            'id' => 1, 'title' => 'BAB 1: PENDAHULUAN', 'quiz_id' => '1',
            'items' => [
                ['id' => '1.1', 'title' => 'Konsep Dasar HTML & CSS', 'route' => 'courses.htmldancss', 'anchors' => [['id'=>'section-1','label'=>'Pengantar Web'], ['id'=>'section-2','label'=>'Semantik HTML'], ['id'=>'section-3','label'=>'Pengenalan CSS']]],
                ['id' => '1.2', 'title' => 'Konsep Dasar Tailwind', 'route' => 'courses.tailwindcss', 'anchors' => [['id'=>'section-1','label'=>'Filosofi Utility']]],
                ['id' => '1.3', 'title' => 'Latar Belakang', 'route' => 'courses.latarbelakang', 'anchors' => []],
                ['id' => '1.4', 'title' => 'Penerapan Utility', 'route' => 'courses.implementation', 'anchors' => []],
                ['id' => '1.5', 'title' => 'Keunggulan', 'route' => 'courses.advantages', 'anchors' => []],
                ['id' => '1.6', 'title' => 'Instalasi & Konfigurasi', 'route' => 'courses.installation', 'anchors' => [['id'=>'cli-steps','label'=>'Prasyarat Sistem']]],
            ]
        ],
        [
            'id' => 2, 'title' => 'BAB 2: LAYOUTING', 'quiz_id' => '2',
            'items' => [
                ['id' => '2.1', 'title' => 'Layout dengan Flexbox', 'route' => 'courses.flexbox', 'anchors' => [['id'=>'fondasi','label'=>'Konsep Flex'], ['id'=>'arahwrap','label'=>'Direction']]],
                ['id' => '2.2', 'title' => 'Layout dengan Grid', 'route' => 'courses.grid', 'anchors' => [['id'=>'section-34','label'=>'Grid Columns']]],
                ['id' => '2.3', 'title' => 'Mengelola Layout', 'route' => 'courses.layout-mgmt', 'anchors' => []],
            ]
        ],
        [
            'id' => 3, 'title' => 'BAB 3: STYLING', 'quiz_id' => '3',
            'items' => [
                ['id' => '3.1', 'title' => 'Tipografi', 'route' => 'courses.typography', 'anchors' => [['id'=>'fonts','label'=>'Font Family']]],
                ['id' => '3.2', 'title' => 'Backgrounds', 'route' => 'courses.backgrounds', 'anchors' => []],
                ['id' => '3.3', 'title' => 'Borders & Effects', 'route' => 'courses.borders', 'anchors' => []],
                ['id' => '3.4', 'title' => 'Efek Visual', 'route' => 'courses.effects', 'anchors' => []],
            ]
        ]
    ];
@endphp

<aside id="courseSidebar" class="w-full lg:w-[340px] h-full bg-[#020617]/95 backdrop-blur-xl border-r border-white/10 flex-col shrink-0 z-40 hidden lg:flex font-sans transition-all duration-300">
    
    <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-8" id="sidebar-scroll-container">
        
        @foreach($chapters as $chapter)
            @php
                $chapterId = $chapter['id'];
                $quizIdStr = (string)$chapter['quiz_id'];
                
                // -----------------------------------------------------------
                // 1. LOGIC GATEKEEPER BAB (KUNCI UTAMA)
                // -----------------------------------------------------------
                // Bab ini terkunci jika bab sebelumnya belum lulus
                $isChapterLocked = !$previousChapterPassed;

                // -----------------------------------------------------------
                // 2. LOGIC LAB (DIRECT DB QUERY)
                // -----------------------------------------------------------
                $chapterLab = \App\Models\Lab::where('chapter_id', $chapterId)->first();
                $hasLab = $chapterLab ? true : false;
                
                $labId = null;
                $labScore = 0;
                $isLabPassed = false;
                $isLabActive = false;
                
                if ($hasLab) {
                    $labId = $chapterLab->id;

                    // Cek History Lulus
                    $labHistory = \App\Models\LabHistory::where('user_id', $userId)
                        ->where('lab_id', $labId)
                        ->where('status', 'passed')
                        ->orderBy('final_score', 'desc')
                        ->first();

                    if ($labHistory) {
                        $isLabPassed = true;
                        $labScore = $labHistory->final_score;
                    }

                    // Cek Session Aktif
                    $activeSession = \App\Models\LabSession::where('user_id', $userId)
                        ->where('lab_id', $labId)
                        ->first();
                    
                    if ($activeSession) $isLabActive = true;
                }

                // -----------------------------------------------------------
                // 3. LOGIC KUIS & KELULUSAN BAB
                // -----------------------------------------------------------
                $quizScore = $scores[$quizIdStr] ?? null;
                $isQuizPassed = ($quizScore !== null && $quizScore >= $kkmQuiz);
                
                // Syarat Bab Selesai = Kuis Lulus
                $currentChapterPassed = $isQuizPassed; 

                // Tentukan Logic Kunci Kuis
                $canAccessQuiz = false;
                if (!$isChapterLocked) {
                     // Jika ada lab, harus lulus lab dulu. Jika tidak, bebas.
                     if ($hasLab) {
                         if ($isLabPassed) $canAccessQuiz = true;
                     } else {
                         // Logic jika ingin cek materi dulu (opsional), disini kita set true jika bab terbuka
                         $canAccessQuiz = true; 
                     }
                     // Jika sudah pernah ngerjain, pasti boleh akses
                     if ($quizScore !== null) $canAccessQuiz = true;
                }
            @endphp

            {{-- WRAPPER BAB --}}
            <div class="relative transition-all duration-500 {{ $isChapterLocked ? 'opacity-50 grayscale' : 'opacity-100' }}">
                
                {{-- HEADER BAB --}}
                <div class="px-2 mb-3 sticky top-0 bg-[#020617] z-20 py-2 border-b border-white/5 flex justify-between items-center shadow-lg shadow-[#020617]">
                    <h4 class="text-[11px] font-extrabold uppercase tracking-widest text-slate-500">{{ $chapter['title'] }}</h4>
                    @if($isChapterLocked)
                         <div class="flex items-center gap-1 bg-white/5 px-2 py-0.5 rounded text-[9px] text-white/30 border border-white/5">🔒 TERKUNCI</div>
                    @elseif($isQuizPassed)
                         <div class="flex items-center gap-1 bg-emerald-500/10 px-2 py-0.5 rounded text-[9px] text-emerald-400 border border-emerald-500/20">✔ SELESAI</div>
                    @endif
                </div>

                <div class="space-y-1 relative">
                    <div class="absolute left-[1.15rem] top-2 bottom-2 w-px bg-gradient-to-b from-white/10 via-white/5 to-transparent -z-10"></div>

                    {{-- 1. LOOP ITEM MATERI --}}
                    @php $previousItemFinished = true; @endphp
                    @foreach($chapter['items'] as $item)
                        @php
                            $isCompleted = !empty($map[$item['id']]);
                            $isActive = ($currentRoute == $item['route']);
                            
                            // ITEM LOCKING LOGIC:
                            // 1. Jika Bab terkunci -> Semua item terkunci
                            // 2. Jika Item sebelumnya belum selesai -> Item ini terkunci
                            $isItemLocked = $isChapterLocked || !$previousItemFinished;
                            
                            // Pengecualian: Jika item ini sedang dibuka, berarti tidak terkunci
                            if ($isActive) $isItemLocked = false;

                            // Accordion hanya bisa dibuka jika aktif & bab tidak terkunci
                            $showAccordion = $isActive && !empty($item['anchors']) && !$isChapterLocked;
                            $collapseId = 'collapse-' . str_replace('.', '-', $item['id']);
                        @endphp

                        <div class="accordion-item group relative">
                            {{-- Klik logic: Jika locked return false --}}
                            <div class="flex items-center gap-3 p-2.5 rounded-xl transition-all duration-300 
                                {{ $isActive ? 'bg-fuchsia-500/10 border border-fuchsia-500/30' : 'border border-transparent hover:bg-white/[0.02]' }} 
                                {{ $isItemLocked ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer' }}"
                                onclick="{{ $isItemLocked ? 'return false;' : ($isActive ? "toggleAccordion('$collapseId')" : "location.href='".route($item['route'])."'") }}">
                                
                                <div class="relative w-7 h-7 rounded-lg flex items-center justify-center text-[10px] font-bold z-10 transition-colors duration-300 shrink-0
                                    {{ $isCompleted ? 'bg-emerald-500 text-white' : ($isActive ? 'bg-fuchsia-600 text-white' : 'bg-[#1a1f2e] border border-white/10 text-white/40') }}">
                                    @if($isCompleted) ✔ @elseif($isItemLocked) 🔒 @else {{ $item['id'] }} @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <span class="block text-[13px] font-medium truncate w-full {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-slate-200' }}">
                                        {{ $item['title'] }}
                                    </span>
                                </div>
                                
                                @if($showAccordion)
                                    <div id="icon-{{ $collapseId }}" class="w-6 h-6 flex items-center justify-center rounded-full transition-transform duration-300 rotate-180 bg-white/10 shrink-0">
                                        <svg class="w-3 h-3 text-white/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                @endif
                            </div>
                            
                            @if($showAccordion)
                                <div id="{{ $collapseId }}" class="overflow-hidden transition-all duration-300" style="max-height: 1000px; opacity: 1;">
                                    <div class="pb-2 pl-[3.25rem] pr-2 space-y-1 relative pt-1">
                                        <div class="absolute left-[1.9rem] top-0 bottom-4 w-px bg-white/5 border-l border-dashed border-white/10"></div>
                                        @foreach($item['anchors'] as $anchor)
                                            <button onclick="scrollToSection('{{ $anchor['id'] }}')" class="flex items-center w-full gap-3 px-3 py-1.5 rounded-md hover:bg-white/5 text-left group/sub transition-all relative">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-600 group-hover/sub:bg-fuchsia-400"></span>
                                                <span class="text-[11px] text-slate-500 group-hover/sub:text-slate-300 truncate w-40">{{ $anchor['label'] }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Logika untuk item berikutnya: Item ini harus selesai (di map) agar item berikutnya terbuka --}}
                        @php 
                            if ($isChapterLocked) {
                                $previousItemFinished = false; 
                            } else {
                                $previousItemFinished = $isCompleted; 
                            }
                        @endphp
                    @endforeach

                    {{-- 2. ITEM LAB --}}
                    @if($hasLab)
                        @php
                            $labLink = '#';
                            $statusText = 'MULAI LAB';
                            $statusColor = 'text-cyan-300';
                            $borderColor = 'border-white/10 bg-[#1a1f2e] hover:border-cyan-500/40 hover:bg-white/5 cursor-pointer';
                            $iconColor = 'bg-cyan-600 text-white shadow-cyan-600/30 group-hover:scale-110';
                            $iconContent = '&gt;_';
                            
                            // Lab terkunci jika Bab Terkunci ATAU Materi sebelumnya belum selesai
                            $isLabLockedUI = $isChapterLocked || !$previousItemFinished;

                            // Jika sudah lulus atau aktif, abaikan lock UI
                            if ($isLabPassed || $isLabActive) $isLabLockedUI = false;

                            if ($isLabPassed) {
                                $statusText = "LULUS ({$labScore})";
                                $statusColor = 'text-emerald-400';
                                $borderColor = 'border-emerald-500/40 bg-emerald-500/5 hover:bg-emerald-500/10 cursor-pointer';
                                $iconColor = 'bg-emerald-500 text-white shadow-emerald-500/30';
                                $iconContent = '✔';
                                $labLink = route('lab.workspace', ['id' => $labId]); 
                            } elseif ($isLabActive) {
                                $statusText = "LANJUTKAN";
                                $statusColor = 'text-indigo-400';
                                $borderColor = 'border-indigo-500/40 bg-indigo-600/10 shadow-indigo-500/10 cursor-pointer';
                                $iconColor = 'bg-indigo-600 text-white shadow-indigo-600/30 animate-pulse';
                                $iconContent = '⚡';
                                $labLink = route('lab.workspace', ['id' => $labId]); 
                            } elseif ($isLabLockedUI) {
                                $statusText = 'TERKUNCI';
                                $statusColor = 'text-white/30';
                                $borderColor = 'border-white/5 bg-[#151921] opacity-60 cursor-not-allowed';
                                $iconColor = 'bg-white/5 text-white/20';
                                $iconContent = '🔒';
                            } else {
                                $labLink = route('lab.start', ['id' => $labId]);
                            }
                        @endphp
                        
                        <div class="pt-4 pb-2 pl-1 pr-1">
                            <button onclick="{{ ($isLabLockedUI) ? 'return false;' : "location.href='$labLink'" }}"
                                class="w-full flex items-center justify-between p-3.5 rounded-xl border transition-all duration-300 group relative overflow-hidden shadow-lg {{ $borderColor }}">
                                <div class="flex items-center gap-3 relative z-10 w-full">
                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center font-bold text-xs transition-all shadow-inner shrink-0 {{ $iconColor }}">
                                        {!! $iconContent !!}
                                    </div>
                                    <div class="flex flex-col text-left overflow-hidden w-full">
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs font-bold {{ $isLabLockedUI ? 'text-white/30' : 'text-white' }} truncate pr-2">{{ $chapterLab->title }}</span>
                                        </div>
                                        <span class="text-[9px] font-bold uppercase tracking-wider {{ $statusColor }}">{{ $statusText }}</span>
                                    </div>
                                </div>
                            </button>
                        </div>
                    @endif

                    {{-- 3. ITEM KUIS --}}
                    @php
                        $quizLink = $canAccessQuiz ? route('quiz.intro', ['chapterId' => $chapter['quiz_id']]) : '#';
                        $quizColor = $canAccessQuiz 
                            ? 'bg-indigo-600/20 border-indigo-500/50 cursor-pointer hover:bg-indigo-900/20' 
                            : 'bg-white/5 border-transparent opacity-50 grayscale cursor-not-allowed';
                    @endphp

                    <div class="pt-1 pl-1 pr-1 pb-4">
                        <button onclick="{{ $canAccessQuiz ? "location.href='$quizLink'" : "return false;" }}"
                            class="w-full flex items-center justify-between p-3 rounded-xl border transition-all duration-300 group {{ $quizColor }}">
                            <div class="flex items-center gap-3 relative z-10">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs 
                                    {{ $canAccessQuiz ? 'bg-indigo-500 text-white shadow-indigo-500/30' : 'bg-white/10 text-white/20' }}">
                                    @if(!$canAccessQuiz) 🔒 @elseif($quizScore !== null) ✔ @else ★ @endif
                                </div>
                                <div class="flex flex-col text-left">
                                    <span class="text-xs font-bold text-white">Evaluasi Bab {{ $chapter['quiz_id'] }}</span>
                                    <span class="text-[9px] font-bold uppercase tracking-wider {{ $canAccessQuiz ? 'text-indigo-400' : 'text-white/30' }}">
                                        @if($isChapterLocked) SELESAIKAN BAB SEBELUMNYA
                                        @elseif(!$canAccessQuiz) SELESAIKAN LAB
                                        @elseif($quizScore !== null) NILAI: {{ $quizScore }}
                                        @else KERJAKAN KUIS
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </button>
                    </div>

                </div>
            </div>
            
            {{-- UPDATE STATE UNTUK BAB BERIKUTNYA --}}
            @php 
                $previousChapterPassed = $currentChapterPassed; 
            @endphp

        @endforeach
        
        <div class="h-32"></div>
    </div>
</aside>

<script>
    function toggleAccordion(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById('icon-' + id.replace('collapse-', ''));
        if (content) {
            const isClosed = content.style.maxHeight === '0px' || content.style.maxHeight === '';
            content.style.maxHeight = isClosed ? content.scrollHeight + "px" : "0px";
            content.style.opacity = isClosed ? "1" : "0";
            if(icon) {
                 if(isClosed) icon.classList.add('rotate-180', 'bg-white/10');
                 else icon.classList.remove('rotate-180', 'bg-white/10');
            }
        }
    }
    
    function scrollToSection(id) {
        const el = document.getElementById(id);
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(() => {
            const activeEl = document.querySelector('.border-fuchsia-500\\/30');
            if (activeEl) activeEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 500);
    });
</script>