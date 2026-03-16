<style>
    /* --- CSS THEME CONFIGURATIONS (SINKRON DENGAN MAIN COURSE) --- */
    :root {
        --sb-bg: rgba(255, 255, 255, 0.95);
        --sb-border: rgba(0, 0, 0, 0.05);
        --sb-text: #0f172a;
        --sb-muted: #64748b;
        --sb-hover: rgba(0, 0, 0, 0.03);
        --sb-active-bg: rgba(217, 70, 239, 0.1);
        --sb-active-border: rgba(217, 70, 239, 0.3);
        --sb-active-text: #c026d3;
        --sb-card: #ffffff;
        --sb-card-border: rgba(0, 0, 0, 0.1);
    }
    
    .dark {
        --sb-bg: rgba(2, 6, 23, 0.95);
        --sb-border: rgba(255, 255, 255, 0.1);
        --sb-text: #ffffff;
        --sb-muted: rgba(255, 255, 255, 0.5);
        --sb-hover: rgba(255, 255, 255, 0.02);
        --sb-active-bg: rgba(217, 70, 239, 0.1);
        --sb-active-border: rgba(217, 70, 239, 0.3);
        --sb-active-text: #ffffff;
        --sb-card: #0f141e;
        --sb-card-border: rgba(255, 255, 255, 0.05);
    }

    /* SCROLLBAR UNTUK SIDEBAR KIRI */
    #sidebar-scroll-container::-webkit-scrollbar { width: 4px; }
    #sidebar-scroll-container::-webkit-scrollbar-track { background: transparent; }
    #sidebar-scroll-container::-webkit-scrollbar-thumb { background: rgba(150, 150, 150, 0.2); border-radius: 10px; }
    #sidebar-scroll-container::-webkit-scrollbar-thumb:hover { background: rgba(150, 150, 150, 0.4); }
    .dark #sidebar-scroll-container::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); }
    .dark #sidebar-scroll-container::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }
</style>

<aside id="courseSidebar" class="w-full lg:w-[340px] h-full flex flex-col shrink-0 z-40 hidden lg:flex font-sans transition-colors duration-300 backdrop-blur-xl" style="background-color: var(--sb-bg); border-right: 1px solid var(--sb-border);">
    
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
                [
                    'id' => '1.1', 'title' => 'Konsep Dasar HTML & CSS', 'route' => 'courses.htmldancss', 
                    'anchors' => [['id' => 'section-1', 'label' => 'Pengantar Web'], ['id' => 'section-2', 'label' => 'Semantik dan Atribut HTML'], ['id' => 'section-3', 'label' => 'Pengenalan CSS'], ['id' => 'section-4', 'label' => 'Warna dan Dasar Font'], ['id' => 'section-5', 'label' => 'Box Model']]
                ],
                [
                    'id' => '1.2', 'title' => 'Konsep Dasar Tailwind', 'route' => 'courses.tailwindcss', 
                    'anchors' => [['id' => 'section-7', 'label' => 'Filosofi Utility First'], ['id' => 'section-8', 'label' => 'Warna & Tipografi Dasar'], ['id' => 'section-9', 'label' => 'Spacing & Sizing'], ['id' => 'section-10', 'label' => 'Borders & Effects']]
                ],
                [
                    'id' => '1.3', 'title' => 'Latar Belakang', 'route' => 'courses.latarbelakang', 
                    'anchors' => [['id' => 'section-12', 'label' => 'Latar Belakang & Efisiensi'], ['id' => 'section-13', 'label' => 'Struktur Dasar (3 Layers)'], ['id' => 'section-14', 'label' => 'Mesin JIT']]
                ],
                [
                    'id' => '1.4', 'title' => 'Penerapan Utility', 'route' => 'courses.implementation', 
                    'anchors' => [['id' => 'section-16', 'label' => 'Filosofi Utilitas & Sistem Desain'], ['id' => 'section-17', 'label' => 'Komposisi & Interaktivitas'], ['id' => 'section-18', 'label' => 'Arsitektur Kode & Duplikasi']]
                ],
                [
                    'id' => '1.5', 'title' => 'Keunggulan', 'route' => 'courses.advantages', 
                    'anchors' => [['id' => 'section-20', 'label' => 'Efisiensi & Kurva Belajar'], ['id' => 'section-21', 'label' => 'Responsif & Performa'], ['id' => 'section-22', 'label' => 'Prinsip DRY & Konsistensi']]
                ],
                [
                    'id' => '1.6', 'title' => 'Instalasi & Konfigurasi', 'route' => 'courses.installation', 
                    'anchors' => [['id' => 'section-24', 'label' => 'Prasyarat Sistem (Node.js)'], ['id' => 'section-25', 'label' => 'Simulasi Instalasi CLI'], ['id' => 'section-26', 'label' => 'Integrasi & Kompilasi'], ['id' => 'section-27', 'label' => 'Konfigurasi Tema']]
                ],
            ]
        ],
        [
            'id' => 2, 'title' => 'BAB 2: LAYOUTING', 'quiz_id' => '2',
            'items' => [
                [
                    'id' => '2.1', 'title' => 'Layout dengan Flexbox', 'route' => 'courses.flexbox', 
                    'anchors' => [['id' => 'section-29', 'label' => 'Ukuran & Arah Flexbox'], ['id' => 'section-30', 'label' => 'Flex Wrap & Perilaku'], ['id' => 'section-31', 'label' => 'Justify & Align'], ['id' => 'section-32', 'label' => 'Sizing & Flexibility']]
                ],
                [
                    'id' => '2.2', 'title' => 'Layout dengan Grid', 'route' => 'courses.grid', 
                    'anchors' => [['id' => 'section-34', 'label' => 'Konsep Grid Layout'], ['id' => 'section-35', 'label' => 'Penjajaran Grid (Alignment)'], ['id' => 'section-36', 'label' => 'Span & Start/End'], ['id' => 'section-37', 'label' => 'Grid Template Rows'], ['id' => 'section-38', 'label' => 'Arbitrary Columns'], ['id' => 'section-39', 'label' => 'Grid Auto Flow']]
                ],
                [
                    'id' => '2.3', 'title' => 'Mengelola Layout', 'route' => 'courses.layout-mgmt', 
                    'anchors' => [['id' => 'section-41', 'label' => 'Container & Viewport'], ['id' => 'section-42', 'label' => 'Float & Clear'], ['id' => 'section-43', 'label' => 'Position & Z-Index'], ['id' => 'section-44', 'label' => 'Table Layout']]
                ],
            ]
        ],
        [
            'id' => 3, 'title' => 'BAB 3: STYLING', 'quiz_id' => '3',
            'items' => [
                [
                    'id' => '3.1', 'title' => 'Tipografi', 'route' => 'courses.typography', 
                    'anchors' => [['id' => 'section-46', 'label' => 'Font Family, Size & Smoothing'], ['id' => 'section-47', 'label' => 'Font Style & Weight'], ['id' => 'section-48', 'label' => 'Letter Spacing & Alignment'], ['id' => 'section-49', 'label' => 'Text Color & Decoration'], ['id' => 'section-50', 'label' => 'Transform & Overflow']]
                ],
                [
                    'id' => '3.2', 'title' => 'Backgrounds', 'route' => 'courses.backgrounds', 
                    'anchors' => [['id' => 'section-52', 'label' => 'Attachment, Clip & Origin'], ['id' => 'section-53', 'label' => 'Color, Position & Repeat'], ['id' => 'section-54', 'label' => 'Size, Image & Gradient']]
                ],
                [
                    'id' => '3.3', 'title' => 'Borders & Effects', 'route' => 'courses.borders', 
                    'anchors' => [['id' => 'section-56', 'label' => 'Radius & Width'], ['id' => 'section-57', 'label' => 'Style, Color & Divide'], ['id' => 'section-58', 'label' => 'Outline & Ring']]
                ],
                [
                    'id' => '3.4', 'title' => 'Efek Visual', 'route' => 'courses.effects', 
                    'anchors' => [['id' => 'section-60', 'label' => 'Box Shadow & Color'], ['id' => 'section-61', 'label' => 'Opacity'], ['id' => 'section-62', 'label' => 'Filter'], ['id' => 'section-63', 'label' => 'Transisi dan Animasi'], ['id' => 'section-64', 'label' => 'Transform']]
                ],
            ]
        ]
    ];
    @endphp

    <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-8 transition-colors duration-300" id="sidebar-scroll-container">
        
        {{-- LOOP BAB REGULER (1-3) --}}
        @foreach($chapters as $chapter)
            @php
                $chapterId = $chapter['id'];
                $quizIdStr = (string)$chapter['quiz_id'];
                $isChapterLocked = !$previousChapterPassed;

                // Logic Lab DB
                $chapterLab = \App\Models\Lab::where('chapter_id', $chapterId)->first();
                $hasLab = $chapterLab ? true : false;
                
                $labId = null; $labScore = 0; $isLabPassed = false; $isLabActive = false;
                
                if ($hasLab) {
                    $labId = $chapterLab->id;
                    $labHistory = \App\Models\LabHistory::where('user_id', $userId)->where('lab_id', $labId)->where('status', 'passed')->orderBy('final_score', 'desc')->first();
                    if ($labHistory) {
                        $isLabPassed = true;
                        $labScore = $labHistory->final_score;
                    }
                    $activeSession = \App\Models\LabSession::where('user_id', $userId)->where('lab_id', $labId)->first();
                    if ($activeSession) $isLabActive = true;
                }

                // Logic Kuis
                $quizScore = $scores[$quizIdStr] ?? null;
                $isQuizPassed = ($quizScore !== null && $quizScore >= $kkmQuiz);
                $currentChapterPassed = $isQuizPassed; 

                // Logic Akses Kuis
                $canAccessQuiz = false;
                if (!$isChapterLocked) {
                     if ($hasLab) {
                         if ($isLabPassed) $canAccessQuiz = true;
                     } else {
                         $canAccessQuiz = true; 
                     }
                     if ($quizScore !== null) $canAccessQuiz = true;
                }
            @endphp

            <div class="relative transition-all duration-500 {{ $isChapterLocked ? 'opacity-50 grayscale' : 'opacity-100' }}">
                
                {{-- HEADER BAB --}}
                <div class="px-2 mb-3 sticky top-0 z-20 py-2 border-b transition-colors shadow-sm dark:shadow-lg backdrop-blur-sm" style="background-color: var(--sb-bg); border-color: var(--sb-border);">
                    <div class="flex justify-between items-center">
                        <h4 class="text-[11px] font-extrabold uppercase tracking-widest transition-colors" style="color: var(--sb-muted);">{{ $chapter['title'] }}</h4>
                        @if($isChapterLocked)
                             <div class="flex items-center gap-1 px-2 py-0.5 rounded text-[9px] font-bold tracking-wider text-slate-500 bg-slate-200 border-slate-300 dark:text-white/30 dark:bg-white/5 dark:border-white/5 border transition-colors">🔒 TERKUNCI</div>
                        @elseif($isQuizPassed)
                             <div class="flex items-center gap-1 px-2 py-0.5 rounded text-[9px] font-bold tracking-wider text-emerald-600 bg-emerald-100 border-emerald-200 dark:text-emerald-400 dark:bg-emerald-500/10 dark:border-emerald-500/20 border transition-colors">✔ SELESAI</div>
                        @endif
                    </div>
                </div>

                <div class="space-y-1 relative">
                    <div class="absolute left-[1.15rem] top-2 bottom-2 w-px bg-gradient-to-b from-slate-300 via-slate-200 dark:from-white/10 dark:via-white/5 to-transparent -z-10 transition-colors"></div>

                    {{-- 1. LOOP ITEM MATERI --}}
                    @php $previousItemFinished = true; @endphp
                    @foreach($chapter['items'] as $item)
                        @php
                            $isCompleted = !empty($map[$item['id']]);
                            $isActive = ($currentRoute == $item['route']);
                            $isItemLocked = $isChapterLocked || !$previousItemFinished;
                            if ($isActive) $isItemLocked = false;
                            
                            // FORCE OPEN ACCORDION IF ACTIVE
                            $showAccordion = $isActive && !empty($item['anchors']) && !$isChapterLocked;
                            $collapseId = 'collapse-' . str_replace('.', '-', $item['id']);

                            // Dynamic Style Variables based on state
                            $activeBg = $isActive ? 'var(--sb-active-bg)' : 'transparent';
                            $activeBorder = $isActive ? 'var(--sb-active-border)' : 'transparent';
                            $hoverClass = $isActive ? '' : 'hover:bg-slate-100 dark:hover:bg-white/[0.02]';
                            $textClass = $isActive ? 'var(--sb-active-text)' : ($isItemLocked ? 'var(--sb-muted)' : 'var(--sb-muted)');
                            $textHoverClass = $isActive ? '' : 'group-hover:text-indigo-500 dark:group-hover:text-slate-200';
                            
                            $iconBg = $isCompleted ? 'bg-emerald-500 text-white' : ($isActive ? 'bg-fuchsia-500 dark:bg-fuchsia-600 text-white' : 'bg-slate-200 dark:bg-[#1a1f2e] border-slate-300 dark:border-white/10 text-slate-400 dark:text-white/40');
                        @endphp

                        <div class="accordion-item group relative">
                            <div id="nav-item-{{ str_replace('.', '-', $item['id']) }}" 
                                 class="flex items-center gap-3 p-2.5 rounded-xl transition-all duration-300 {{ $hoverClass }} {{ $isItemLocked ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer' }}"
                                 style="background-color: {{ $activeBg }}; border: 1px solid {{ $activeBorder }};"
                                 onclick="{{ $isItemLocked ? 'return false;' : ($isActive ? "toggleAccordion('$collapseId')" : "location.href='".route($item['route'])."#courseRoot'") }}">
                                
                                <div class="relative w-7 h-7 rounded-lg flex items-center justify-center text-[10px] font-bold z-10 shrink-0 border transition-colors {{ $iconBg }}">
                                    @if($isCompleted) ✔ @elseif($isItemLocked) 🔒 @else {{ $item['id'] }} @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <span class="block text-[13px] font-medium truncate w-full transition-colors {{ $textHoverClass }}" style="color: {{ $textClass }}; font-weight: {{ $isActive ? '700' : '500' }};">
                                        {{ $item['title'] }}
                                    </span>
                                </div>
                                
                                @if(!empty($item['anchors']) && !$isItemLocked)
                                    <div id="icon-{{ $collapseId }}" class="w-6 h-6 flex items-center justify-center rounded-full transition-transform duration-300 shrink-0 {{ $showAccordion ? 'rotate-180 bg-slate-200 dark:bg-white/10' : '' }}">
                                        <svg class="w-3 h-3 text-slate-500 dark:text-white/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                @endif
                            </div>
                            
                            {{-- SUB-MENU ANCHORS --}}
                            @if(!empty($item['anchors']) && !$isItemLocked)
                                <div id="{{ $collapseId }}" class="overflow-hidden transition-all duration-300" 
                                     style="{{ $showAccordion ? 'max-height: 1000px; opacity: 1;' : 'max-height: 0px; opacity: 0;' }}">
                                    <div class="pb-2 pl-[3.25rem] pr-2 space-y-1 relative pt-1">
                                        <div class="absolute left-[1.9rem] top-0 bottom-4 w-px border-l border-dashed border-slate-300 dark:border-white/10 transition-colors"></div>
                                        @foreach($item['anchors'] as $anchor)
                                            {{-- ANCHOR ITEM WITH SCROLL SPY --}}
                                            <button 
                                                data-target="{{ $anchor['id'] }}"
                                                onclick="scrollToSection('{{ $anchor['id'] }}')" 
                                                class="sidebar-anchor flex items-center w-full gap-3 px-3 py-1.5 rounded-md text-left group/sub transition-all relative border-l-2 border-transparent">
                                                <span class="anchor-dot w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-600 transition-all duration-300 group-hover/sub:bg-fuchsia-500 dark:group-hover/sub:bg-fuchsia-400"></span>
                                                <span class="anchor-text text-[11px] text-slate-500 dark:text-slate-500 transition-all duration-300 group-hover/sub:text-slate-800 dark:group-hover/sub:text-slate-300 truncate w-40">
                                                    {{ $anchor['label'] }}
                                                </span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        
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
                            $labLink = '#'; $statusText = 'MULAI LAB'; 
                            $statusColor = 'text-cyan-600 dark:text-cyan-300';
                            $borderColor = 'border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-[#1a1f2e] hover:border-cyan-500 hover:bg-slate-100 dark:hover:border-cyan-500/40 dark:hover:bg-white/5 cursor-pointer';
                            $iconColor = 'bg-cyan-500 dark:bg-cyan-600 text-white shadow-md dark:shadow-cyan-600/30 group-hover:scale-110';
                            $iconContent = '&gt;_';
                            
                            $isLabLockedUI = $isChapterLocked || !$previousItemFinished;
                            if ($isLabPassed || $isLabActive) $isLabLockedUI = false;

                            if ($isLabPassed) {
                                $statusText = "LULUS ({$labScore})"; 
                                $statusColor = 'text-emerald-600 dark:text-emerald-400';
                                $borderColor = 'border-emerald-300 dark:border-emerald-500/40 bg-emerald-50 dark:bg-emerald-500/5 hover:bg-emerald-100 dark:hover:bg-emerald-500/10 cursor-pointer';
                                $iconColor = 'bg-emerald-500 text-white shadow-md dark:shadow-emerald-500/30'; $iconContent = '✔';
                                $labLink = route('lab.workspace', ['id' => $labId]); 
                            } elseif ($isLabActive) {
                                $statusText = "LANJUTKAN"; 
                                $statusColor = 'text-indigo-600 dark:text-indigo-400';
                                $borderColor = 'border-indigo-300 dark:border-indigo-500/40 bg-indigo-50 dark:bg-indigo-600/10 shadow-sm dark:shadow-indigo-500/10 cursor-pointer';
                                $iconColor = 'bg-indigo-500 dark:bg-indigo-600 text-white shadow-md dark:shadow-indigo-600/30 animate-pulse'; $iconContent = '⚡';
                                $labLink = route('lab.workspace', ['id' => $labId]); 
                            } elseif ($isLabLockedUI) {
                                $statusText = 'TERKUNCI'; 
                                $statusColor = 'text-slate-400 dark:text-white/30';
                                $borderColor = 'border-slate-200 dark:border-white/5 bg-slate-100 dark:bg-[#151921] opacity-60 cursor-not-allowed';
                                $iconColor = 'bg-slate-200 dark:bg-white/5 text-slate-400 dark:text-white/20'; $iconContent = '🔒';
                            } else {
                                $labLink = route('lab.start', ['id' => $labId]);
                            }
                        @endphp
                        <div class="pt-4 pb-2 pl-1 pr-1">
                            <button onclick="{{ ($isLabLockedUI) ? 'return false;' : "location.href='$labLink'" }}"
                                class="w-full flex items-center justify-between p-3.5 rounded-xl border transition-all duration-300 group relative overflow-hidden {{ $borderColor }}">
                                <div class="flex items-center gap-3 relative z-10 w-full">
                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center font-bold text-xs transition-all shrink-0 {{ $iconColor }}">
                                        {!! $iconContent !!}
                                    </div>
                                    <div class="flex flex-col text-left overflow-hidden w-full">
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs font-bold {{ $isLabLockedUI ? 'text-slate-500 dark:text-white/30' : 'text-slate-900 dark:text-white' }} truncate pr-2 transition-colors">{{ $chapterLab->title }}</span>
                                        </div>
                                        <span class="text-[9px] font-bold uppercase tracking-wider transition-colors {{ $statusColor }}">{{ $statusText }}</span>
                                    </div>
                                </div>
                            </button>
                        </div>
                    @endif

                    {{-- 3. ITEM KUIS --}}
                    @php
                        $quizLink = $canAccessQuiz ? route('quiz.intro', ['chapterId' => $chapter['quiz_id']]) : '#';
                        $quizColor = $canAccessQuiz ? 'bg-indigo-50 dark:bg-indigo-600/20 border-indigo-200 dark:border-indigo-500/50 cursor-pointer hover:bg-indigo-100 dark:hover:bg-indigo-900/20' : 'bg-slate-100 dark:bg-white/5 border-transparent opacity-50 grayscale cursor-not-allowed';
                    @endphp
                    <div class="pt-1 pl-1 pr-1 pb-4">
                        <button onclick="{{ $canAccessQuiz ? "location.href='$quizLink'" : "return false;" }}"
                            class="w-full flex items-center justify-between p-3 rounded-xl border transition-all duration-300 group {{ $quizColor }}">
                            <div class="flex items-center gap-3 relative z-10">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs transition-colors {{ $canAccessQuiz ? 'bg-indigo-500 text-white shadow-md dark:shadow-indigo-500/30' : 'bg-slate-200 dark:bg-white/10 text-slate-400 dark:text-white/20' }}">
                                    @if(!$canAccessQuiz) 🔒 @elseif($quizScore !== null) ✔ @else ★ @endif
                                </div>
                                <div class="flex flex-col text-left">
                                    <span class="text-xs font-bold text-slate-900 dark:text-white transition-colors">Evaluasi Bab {{ $chapter['quiz_id'] }}</span>
                                    <span class="text-[9px] font-bold uppercase tracking-wider transition-colors {{ $canAccessQuiz ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-500 dark:text-white/30' }}">
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
            @php $previousChapterPassed = $currentChapterPassed; @endphp
        @endforeach

        {{-- BAGIAN SPESIAL: FINAL PROJECT & EVALUASI AKHIR --}}
        @php
            $isFinalLocked = !$previousChapterPassed;
            // Capstone Lab Data
            $capstoneLabId = 4;
            $capstoneLink = '#'; $capstoneStatusText = 'TERKUNCI'; 
            $capstoneColor = 'text-slate-400 dark:text-white/30';
            $capstoneBorder = 'border-slate-200 dark:border-white/5 bg-slate-100 dark:bg-[#151921] opacity-60 cursor-not-allowed'; 
            $capstoneIcon = '🔒';
            
            $capstoneHistory = \App\Models\LabHistory::where('user_id', $userId)->where('lab_id', $capstoneLabId)->where('status', 'passed')->orderBy('final_score', 'desc')->first();
            $capstoneActive = \App\Models\LabSession::where('user_id', $userId)->where('lab_id', $capstoneLabId)->first();
            $isCapstonePassed = $capstoneHistory ? true : false;

            if (!$isFinalLocked) {
                if ($isCapstonePassed) {
                    $capstoneLink = route('lab.workspace', ['id' => $capstoneLabId]);
                    $capstoneStatusText = "SELESAI ({$capstoneHistory->final_score})";
                    $capstoneColor = 'text-amber-600 dark:text-amber-400';
                    $capstoneBorder = 'border-amber-300 dark:border-amber-500/40 bg-amber-50 dark:bg-amber-500/10 cursor-pointer hover:bg-amber-100 dark:hover:bg-amber-500/20';
                    $capstoneIcon = '🏆';
                } elseif ($capstoneActive) {
                    $capstoneLink = route('lab.workspace', ['id' => $capstoneLabId]);
                    $capstoneStatusText = "LANJUTKAN";
                    $capstoneColor = 'text-amber-600 dark:text-amber-400';
                    $capstoneBorder = 'border-amber-300 dark:border-amber-500/40 bg-amber-100 dark:bg-amber-600/10 cursor-pointer hover:bg-amber-200 dark:hover:bg-amber-600/20';
                    $capstoneIcon = '⚡';
                } else {
                    $capstoneLink = route('lab.start', ['id' => $capstoneLabId]);
                    $capstoneStatusText = "MULAI PROJECT";
                    $capstoneColor = 'text-amber-600 dark:text-amber-400';
                    $capstoneBorder = 'border-slate-300 dark:border-amber-500/40 bg-white dark:bg-[#1a1f2e] cursor-pointer hover:border-amber-400';
                    $capstoneIcon = '🚀';
                }
            }

            // Final Exam
            $finalQuizId = 99;
            $finalQuizScore = $scores['99'] ?? null;
            $finalQuizLink = ($isFinalLocked || !$isCapstonePassed) ? '#' : route('quiz.intro', ['chapterId' => 99]);
            $isFinalQuizAccessible = (!$isFinalLocked && $isCapstonePassed) || ($finalQuizScore !== null);
        @endphp

        <div class="mt-8 border-t-2 border-slate-200 dark:border-white/10 pt-6 relative transition-all duration-500 {{ $isFinalLocked ? 'opacity-50 grayscale' : 'opacity-100' }}">
            <div class="px-2 mb-4 flex justify-between items-center">
                <h4 class="text-[11px] font-extrabold uppercase tracking-widest text-amber-500 flex items-center gap-2 transition-colors">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 001.902 0l1.519-4.674z" /></svg>
                    FINAL EVALUATION
                </h4>
            </div>

            <div class="mb-3 px-1">
                <button onclick="{{ ($isFinalLocked) ? 'return false;' : "location.href='$capstoneLink'" }}"
                    class="w-full flex items-center justify-between p-3.5 rounded-xl border transition-all duration-300 group relative overflow-hidden shadow-sm dark:shadow-lg {{ $capstoneBorder }}">
                    <div class="flex items-center gap-3 relative z-10 w-full">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center font-bold text-xs transition-all shrink-0 {{ $isFinalLocked ? 'bg-slate-200 dark:bg-white/10 text-slate-400 dark:text-white/20 shadow-inner' : 'bg-amber-500 dark:bg-amber-600 text-white shadow-md dark:shadow-amber-600/30 group-hover:scale-110' }}">
                            {{ $capstoneIcon }}
                        </div>
                        <div class="flex flex-col text-left overflow-hidden w-full">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-slate-900 dark:text-white truncate pr-2 transition-colors">DevStudio Landing Page</span>
                            </div>
                            <span class="text-[9px] font-bold uppercase tracking-wider transition-colors {{ $capstoneColor }}">{{ $capstoneStatusText }}</span>
                        </div>
                    </div>
                </button>
            </div>

            <div class="px-1">
                <button onclick="{{ $isFinalQuizAccessible ? "location.href='$finalQuizLink'" : "return false;" }}"
                    class="w-full flex items-center justify-between p-3 rounded-xl border transition-all duration-300 group 
                    {{ $isFinalQuizAccessible ? 'bg-amber-50 dark:bg-amber-900/20 border-amber-300 dark:border-amber-600/50 hover:bg-amber-100 dark:hover:bg-amber-900/40 cursor-pointer shadow-sm' : 'bg-slate-100 dark:bg-white/5 border-transparent opacity-50 cursor-not-allowed' }}">
                    <div class="flex items-center gap-3 relative z-10">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs transition-colors
                            {{ $isFinalQuizAccessible ? 'bg-amber-500 dark:bg-amber-600 text-white shadow-md dark:shadow-amber-500/30' : 'bg-slate-200 dark:bg-white/10 text-slate-400 dark:text-white/20' }}">
                            @if(!$isFinalQuizAccessible) 🔒 @elseif($finalQuizScore !== null) ✔ @else 🎓 @endif
                        </div>
                        <div class="flex flex-col text-left">
                            <span class="text-xs font-bold text-slate-900 dark:text-white transition-colors">Ujian Teori Akhir</span>
                            <span class="text-[9px] font-bold uppercase tracking-wider transition-colors {{ $isFinalQuizAccessible ? 'text-amber-600 dark:text-amber-400' : 'text-slate-500 dark:text-white/30' }}">
                                @if($isFinalLocked) LOCKED @elseif(!$isCapstonePassed) SELESAIKAN CAPSTONE @elseif($finalQuizScore !== null) NILAI: {{ $finalQuizScore }} @else MULAI UJIAN @endif
                            </span>
                        </div>
                    </div>
                </button>
            </div>
        </div>
        
        <div class="h-10 mt-6 border-t border-slate-200 dark:border-white/10 pt-4 px-2">
            {{-- THEME SWITCHER BUTTON --}}
            <button id="theme-toggle-course-sidebar" type="button" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 text-slate-600 dark:text-slate-300 transition-colors border border-slate-200 dark:border-transparent text-xs font-bold shadow-sm dark:shadow-none">
                <svg id="theme-toggle-dark-icon-csb" class="hidden w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                <svg id="theme-toggle-light-icon-csb" class="hidden w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path></svg>
                <span id="theme-toggle-text-csb">Ganti Tema</span>
            </button>
        </div>
        <div class="h-20"></div>
    </div>
</aside>

{{-- JAVASCRIPT UNTUK ACCORDION, SCROLL & THEME SWITCHER --}}
<script>
    // 1. Accordion Toggle
    function toggleAccordion(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById('icon-' + id.replace('collapse-', ''));
        if (content) {
            const isClosed = content.style.maxHeight === '0px' || content.style.maxHeight === '';
            content.style.maxHeight = isClosed ? content.scrollHeight + "px" : "0px";
            content.style.opacity = isClosed ? "1" : "0";
            if(icon) {
                 if(isClosed) icon.classList.add('rotate-180', 'bg-slate-200', 'dark:bg-white/10');
                 else icon.classList.remove('rotate-180', 'bg-slate-200', 'dark:bg-white/10');
            }
        }
    }
    
    // 2. Smooth Scroll to Section
    function scrollToSection(id) {
        const el = document.getElementById(id);
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    document.addEventListener("DOMContentLoaded", function() {
        
        // 3. AUTO SCROLL SIDEBAR TO ACTIVE ITEM
        setTimeout(() => {
            // Deteksi warna border yang aktif menyesuaikan variabel CSS baru
            const activeSidebarItem = document.querySelector('.accordion-item .border-fuchsia-500\\/30') || 
                                      document.querySelector('.accordion-item [style*="rgba(217, 70, 239, 0.3)"]');
            if (activeSidebarItem) {
                activeSidebarItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 300);

        // 4. SCROLL SPY (NAV HIGHLIGHT)
        const mainScroll = document.getElementById('mainScroll');
        const anchors = document.querySelectorAll('.sidebar-anchor');
        const sections = document.querySelectorAll('.lesson-section');

        if (mainScroll && sections.length > 0) {
            const observerOptions = {
                root: mainScroll,
                threshold: 0.5 
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const isDark = document.documentElement.classList.contains('dark');
                        
                        // Reset semua anchor
                        anchors.forEach(a => {
                            a.classList.remove('bg-slate-100', 'dark:bg-white/5', 'border-fuchsia-500');
                            a.classList.add('border-transparent');
                            const dot = a.querySelector('.anchor-dot');
                            dot.classList.remove('bg-fuchsia-500', 'dark:bg-fuchsia-400', 'scale-125', 'shadow-sm', 'dark:shadow-[0_0_10px_#e879f9]');
                            dot.classList.add('bg-slate-400', 'dark:bg-slate-600');
                            const text = a.querySelector('.anchor-text');
                            text.classList.remove('text-slate-800', 'dark:text-white', 'font-bold');
                            text.classList.add('text-slate-500');
                        });

                        // Highlight anchor yang sesuai
                        const activeAnchor = document.querySelector(`.sidebar-anchor[data-target="${entry.target.id}"]`);
                        if (activeAnchor) {
                            activeAnchor.classList.add(isDark ? 'dark:bg-white/5' : 'bg-slate-100', 'border-fuchsia-500');
                            activeAnchor.classList.remove('border-transparent');
                            
                            const dot = activeAnchor.querySelector('.anchor-dot');
                            dot.classList.remove('bg-slate-400', 'dark:bg-slate-600');
                            dot.classList.add(isDark ? 'dark:bg-fuchsia-400' : 'bg-fuchsia-500', 'scale-125', isDark ? 'dark:shadow-[0_0_10px_#e879f9]' : 'shadow-sm');
                            
                            const text = activeAnchor.querySelector('.anchor-text');
                            text.classList.remove('text-slate-500');
                            text.classList.add(isDark ? 'dark:text-white' : 'text-slate-800', 'font-bold');
                        }
                    }
                });
            }, observerOptions);

            sections.forEach(section => observer.observe(section));
        }

        // ==========================================
        // 5. THEME SWITCHER LOGIC
        // ==========================================
        const themeBtnCsb = document.getElementById('theme-toggle-course-sidebar');
        const darkIconCsb = document.getElementById('theme-toggle-dark-icon-csb');
        const lightIconCsb = document.getElementById('theme-toggle-light-icon-csb');
        const textCsb = document.getElementById('theme-toggle-text-csb');

        const syncCsbIcons = (isDark) => {
            if (isDark) {
                lightIconCsb?.classList.remove('hidden');
                darkIconCsb?.classList.add('hidden');
                if(textCsb) textCsb.textContent = "Tema Terang";
            } else {
                lightIconCsb?.classList.add('hidden');
                darkIconCsb?.classList.remove('hidden');
                if(textCsb) textCsb.textContent = "Tema Gelap";
            }
        };

        const isDarkThemeInitial = document.documentElement.classList.contains('dark');
        syncCsbIcons(isDarkThemeInitial);

        themeBtnCsb?.addEventListener('click', function() {
            const willBeDark = !document.documentElement.classList.contains('dark');
            if (willBeDark) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            }
            syncCsbIcons(willBeDark);
            
            // Trigger event untuk merefresh komponen lain (seperti scrollspy colors/charts) jika ada
            window.dispatchEvent(new Event('theme-toggled'));
            
            // Trigger scroll event sedikit untuk memaksa scrollspy me-render ulang warna
            const mainScroll = document.getElementById('mainScroll');
            if(mainScroll) mainScroll.dispatchEvent(new Event('scroll'));
        });
    });
</script>