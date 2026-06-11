<style>
    :root {
        --sb-bg: rgba(255, 255, 255, 0.95);
        --sb-border: rgba(15, 23, 42, 0.08);
        --sb-text: #0f172a;
        --sb-muted: #64748b;
        --sb-hover: rgba(15, 23, 42, 0.04);
        --sb-active-bg: rgba(99, 102, 241, 0.10);
        --sb-active-border: rgba(99, 102, 241, 0.28);
        --sb-active-text: #4f46e5;
        --sb-card: #ffffff;
        --sb-card-border: rgba(15, 23, 42, 0.10);
    }

    .dark {
        --sb-bg: rgba(2, 6, 23, 0.95);
        --sb-border: rgba(255, 255, 255, 0.08);
        --sb-text: #ffffff;
        --sb-muted: rgba(226, 232, 240, 0.58);
        --sb-hover: rgba(255, 255, 255, 0.04);
        --sb-active-bg: rgba(99, 102, 241, 0.16);
        --sb-active-border: rgba(165, 180, 252, 0.32);
        --sb-active-text: #c7d2fe;
        --sb-card: #0f172a;
        --sb-card-border: rgba(255, 255, 255, 0.08);
    }

    #sidebar-scroll-container::-webkit-scrollbar { width: 5px; }
    #sidebar-scroll-container::-webkit-scrollbar-track { background: transparent; }
    #sidebar-scroll-container::-webkit-scrollbar-thumb { background: rgba(148,163,184,.35); border-radius: 999px; }
    #sidebar-scroll-container::-webkit-scrollbar-thumb:hover { background: #6366f1; }
    .dark #sidebar-scroll-container::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); }

    @media (max-width: 1023px) {
        #courseSidebar {
            position: fixed;
            top: 64px;
            left: -100%;
            height: calc(100vh - 64px);
            transition: left .3s ease-in-out;
        }
        #courseSidebar.mobile-open {
            left: 0;
            box-shadow: 10px 0 30px rgba(0,0,0,.45);
        }
        #mobileOverlay {
            display: none;
            position: fixed;
            inset: 0;
            top: 64px;
            background: rgba(2, 6, 23, .62);
            backdrop-filter: blur(2px);
            z-index: 30;
        }
        #mobileOverlay.show { display: block; }
    }

    .sidebar-anchor.active-anchor {
        background: rgba(99, 102, 241, .08);
        border-left-color: #6366f1;
    }
    .dark .sidebar-anchor.active-anchor {
        background: rgba(255, 255, 255, .05);
    }
</style>

<button id="mobileSidebarToggle" class="lg:hidden fixed bottom-6 right-6 z-50 p-4 rounded-full shadow-2xl bg-indigo-600 text-white hover:bg-indigo-500 focus:outline-none transition-transform hover:scale-110">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
    </svg>
</button>

<div id="mobileOverlay" onclick="toggleMobileSidebar()"></div>

<aside id="courseSidebar" class="w-[85%] sm:w-[340px] lg:w-[340px] h-full flex flex-col shrink-0 z-40 font-sans transition-colors duration-300 backdrop-blur-xl lg:left-0" style="background-color: var(--sb-bg); border-right: 1px solid var(--sb-border);">
    @php
        $routeFacade = \Illuminate\Support\Facades\Route::class;
        $currentRoute = \Illuminate\Support\Facades\Route::currentRouteName();
        $authUser = auth()->user();
        $userId = auth()->id();
        $isAdmin = $authUser && $authUser->role === 'admin';
        $kkmQuiz = 70;

        $map = $completedLessonsMap ?? [];
        $scores = $quizScores ?? [];
        $activeQuizSessions = $activeQuizSessions ?? [];
        $completedLabs = $completedLabs ?? [];
        $activeSessions = $activeSessions ?? [];

        $routeUrl = function ($names, array $params = []) use ($routeFacade) {
            foreach ((array) $names as $name) {
                if ($routeFacade::has($name)) {
                    return route($name, $params);
                }
            }
            return '#';
        };

        $routeIsActive = function ($names) use ($currentRoute) {
            return in_array($currentRoute, (array) $names, true);
        };

        $isDone = function ($key) use ($map) {
            return !empty($map[$key]) || !empty($map[(string) $key]);
        };

        $chapters = [
            [
                'id' => 1,
                'title' => 'BAB 1: PENDAHULUAN',
                'quiz_id' => 1,
                'quiz_key' => 'quiz_1',
                'lab_id' => 1,
                'color' => 'cyan',
                'items' => [
                    [
                        'id' => '1.1', 'title' => 'Konsep Dasar HTML dan CSS', 'route' => 'courses.htmldancss',
                        'anchors' => [
                            ['id' => 'section-1', 'label' => 'HTML sebagai Struktur Halaman'],
                            ['id' => 'section-2', 'label' => 'CSS sebagai Pengatur Tampilan'],
                            ['id' => 'section-3', 'label' => 'Menghubungkan HTML dan CSS'],
                            ['id' => 'section-4', 'label' => 'Box Model dan Display'],
                            ['id' => 'section-5', 'label' => 'Aktivitas Latihan'],
                        ],
                    ],
                    [
                        'id' => '1.2', 'title' => 'Konsep Dasar Tailwind CSS', 'route' => 'courses.tailwindcss',
                        'anchors' => [
                            ['id' => 'section-6', 'label' => 'Mengenal Tailwind CSS'],
                            ['id' => 'section-7', 'label' => 'Cara Membaca Utility Class'],
                            ['id' => 'section-8', 'label' => 'Kelompok Utility Class'],
                            ['id' => 'section-9', 'label' => 'Urutan Class pada Komponen'],
                            ['id' => 'section-10', 'label' => 'Aktivitas Latihan'],
                        ],
                    ],
                    [
                        'id' => '1.3', 'title' => 'Tailwind CSS melalui CDN', 'route' => 'courses.latarbelakang',
                        'anchors' => [
                            ['id' => 'section-11', 'label' => 'Fungsi CDN Tailwind CSS'],
                            ['id' => 'section-12', 'label' => 'Menempatkan CDN di Head'],
                            ['id' => 'section-13', 'label' => 'Menerapkan Class pada HTML'],
                            ['id' => 'section-14', 'label' => 'Kelebihan dan Batasan CDN'],
                            ['id' => 'section-15', 'label' => 'Aktivitas Latihan'],
                        ],
                    ],
                    [
                        'id' => '1.4', 'title' => 'Instalasi Tailwind CSS', 'route' => ['courses.implementation', 'courses.implementasi'],
                        'anchors' => [
                            ['id' => 'section-16', 'label' => 'Persiapan Node.js dan NPM'],
                            ['id' => 'section-17', 'label' => 'Struktur Folder Proyek'],
                            ['id' => 'section-18', 'label' => 'Instalasi Tailwind CLI'],
                            ['id' => 'section-19', 'label' => 'Proses Build CSS'],
                            ['id' => 'section-20', 'label' => 'Aktivitas Latihan'],
                        ],
                    ],
                    [
                        'id' => '1.5', 'title' => 'Konfigurasi Dasar Tailwind CSS', 'route' => ['courses.advantages', 'courses.keunggulan'],
                        'anchors' => [
                            ['id' => 'section-21', 'label' => 'File Input CSS'],
                            ['id' => 'section-22', 'label' => 'Penggunaan @import'],
                            ['id' => 'section-23', 'label' => 'Konfigurasi @theme'],
                            ['id' => 'section-24', 'label' => 'Hasil Class Custom'],
                            ['id' => 'section-25', 'label' => 'Aktivitas Latihan'],
                        ],
                    ],
                ],
            ],
            [
                'id' => 2,
                'title' => 'BAB 2: LAYOUTING',
                'quiz_id' => 2,
                'quiz_key' => 'quiz_2',
                'lab_id' => 2,
                'color' => 'indigo',
                'items' => [
                    [
                        'id' => '2.1', 'title' => 'Dasar Layout dan Ruang', 'route' => ['courses.layout-spacing', 'courses.layout-basics'],
                        'anchors' => [
                            ['id' => 'section-26', 'label' => 'Pengertian Layout dan Ruang'],
                            ['id' => 'section-27', 'label' => 'Padding, Margin, dan Gap'],
                            ['id' => 'section-28', 'label' => 'Layout Rapat dan Rapi'],
                            ['id' => 'section-29', 'label' => 'Pengaturan Ruang Fleksibel'],
                            ['id' => 'section-30', 'label' => 'Aktivitas Latihan'],
                        ],
                    ],
                    [
                        'id' => '2.2', 'title' => 'Flexbox', 'route' => 'courses.flexbox',
                        'anchors' => [
                            ['id' => 'section-31', 'label' => 'Konsep Dasar Flexbox'],
                            ['id' => 'section-32', 'label' => 'Arah Susunan Elemen'],
                            ['id' => 'section-33', 'label' => 'Gap dan Penjajaran'],
                            ['id' => 'section-34', 'label' => 'Pembagian Ruang'],
                            ['id' => 'section-35', 'label' => 'Aktivitas Latihan'],
                        ],
                    ],
                    [
                        'id' => '2.3', 'title' => 'Grid', 'route' => 'courses.grid',
                        'anchors' => [
                            ['id' => 'section-36', 'label' => 'Konsep Dasar Grid'],
                            ['id' => 'section-37', 'label' => 'Kolom dan Baris Grid'],
                            ['id' => 'section-38', 'label' => 'Gap dan Kartu Responsif'],
                            ['id' => 'section-39', 'label' => 'Pola Grid Sederhana'],
                            ['id' => 'section-40', 'label' => 'Aktivitas Latihan'],
                        ],
                    ],
                    [
                        'id' => '2.4', 'title' => 'Responsif', 'route' => 'courses.responsive',
                        'anchors' => [
                            ['id' => 'section-41', 'label' => 'Konsep Responsif'],
                            ['id' => 'section-42', 'label' => 'Breakpoint sm, md, dan lg'],
                            ['id' => 'section-43', 'label' => 'Layout Berubah Sesuai Layar'],
                            ['id' => 'section-44', 'label' => 'Ruang Responsif'],
                            ['id' => 'section-45', 'label' => 'Aktivitas Latihan'],
                        ],
                    ],
                ],
            ],
            [
                'id' => 3,
                'title' => 'BAB 3: STYLING',
                'quiz_id' => 3,
                'quiz_key' => 'quiz_3',
                'lab_id' => 3,
                'color' => 'fuchsia',
                'items' => [
                    [
                        'id' => '3.1', 'title' => 'Tipografi', 'route' => 'courses.typography',
                        'anchors' => [
                            ['id' => 'section-46', 'label' => 'Hierarki Teks'],
                            ['id' => 'section-47', 'label' => 'Ukuran dan Ketebalan Teks'],
                            ['id' => 'section-48', 'label' => 'Jarak Baris dan Perataan'],
                            ['id' => 'section-49', 'label' => 'Warna Teks dan Jenis Huruf'],
                            ['id' => 'section-50', 'label' => 'Aktivitas Latihan'],
                        ],
                    ],
                    [
                        'id' => '3.2', 'title' => 'Warna dan Latar Belakang', 'route' => ['courses.backgrounds', 'courses.background'],
                        'anchors' => [
                            ['id' => 'section-51', 'label' => 'Pola Class Warna'],
                            ['id' => 'section-52', 'label' => 'Latar Halaman'],
                            ['id' => 'section-53', 'label' => 'Kartu dan Kontras'],
                            ['id' => 'section-54', 'label' => 'Warna Tombol Berdasarkan Fungsi'],
                            ['id' => 'section-55', 'label' => 'Aktivitas Latihan'],
                        ],
                    ],
                    [
                        'id' => '3.3', 'title' => 'Border dan Radius', 'route' => 'courses.borders',
                        'anchors' => [
                            ['id' => 'section-56', 'label' => 'Radius dan Ketebalan Border'],
                            ['id' => 'section-57', 'label' => 'Gaya Border dan Divide'],
                            ['id' => 'section-58', 'label' => 'Outline dan Ring'],
                            ['id' => 'section-59', 'label' => 'Bayangan'],
                            ['id' => 'section-60', 'label' => 'Aktivitas Latihan'],
                        ],
                    ],
                    [
                        'id' => '3.4', 'title' => 'Bayangan dan Efek Visual', 'route' => 'courses.effects',
                        'anchors' => [
                            ['id' => 'section-61', 'label' => 'Shadow pada Komponen'],
                            ['id' => 'section-62', 'label' => 'Opacity'],
                            ['id' => 'section-63', 'label' => 'Filter dan Blur'],
                            ['id' => 'section-64', 'label' => 'Transisi dan Animasi'],
                            ['id' => 'section-65', 'label' => 'Aktivitas Latihan'],
                        ],
                    ],
                ],
            ],
        ];
    @endphp

    <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-8 transition-colors duration-300" id="sidebar-scroll-container">
        @php $previousChapterPassed = true; @endphp

        @foreach($chapters as $chapter)
            @php
                $chapterId = (int) $chapter['id'];
                $quizKey = $chapter['quiz_key'];
                $quizId = (string) $chapter['quiz_id'];
                $isChapterLocked = $isAdmin ? false : !$previousChapterPassed;

                $quizScore = $scores[$quizId] ?? $scores[$chapter['quiz_id']] ?? null;
                $isQuizPassed = $isAdmin || $isDone($quizKey) || ($quizScore !== null && $quizScore >= $kkmQuiz);
                $currentChapterPassed = $isAdmin || $isQuizPassed;

                $chapterCompletedCount = 0;
                foreach ($chapter['items'] as $itemForCount) {
                    if ($isDone($itemForCount['id'])) {
                        $chapterCompletedCount++;
                    }
                }

                $chapterProgressText = $chapterCompletedCount . '/' . count($chapter['items']);

                $labId = $chapter['lab_id'];
                $labModel = null;
                try {
                    $labModel = \App\Models\Lab::where('id', $labId)->first() ?: \App\Models\Lab::where('chapter_id', $chapterId)->first();
                } catch (\Throwable $e) {
                    $labModel = null;
                }

                $labSlug = $labModel->slug ?? null;
                $labTitle = $labModel->title ?? ('Lab Bab ' . $chapterId);
                $labScore = null;
                $isLabPassed = false;
                $isLabActive = false;
                $lastScore = null;

                if (isset($passedLabsMap) && (isset($passedLabsMap[$labId]) || isset($passedLabsMap[(string) $labId]))) {
                    $isLabPassed = true;
                }

                if ($labSlug && isset($completedLabs[$labSlug]) && $completedLabs[$labSlug] >= 50) {
                    $isLabPassed = true;
                    $labScore = $completedLabs[$labSlug];
                }

                if ($labSlug && isset($activeSessions[$labSlug])) {
                    $isLabActive = true;
                }

                if ($labModel && $userId && !$isLabPassed) {
                    try {
                        $lastHistory = \App\Models\LabHistory::where('user_id', $userId)->where('lab_id', $labModel->id)->latest('id')->first();
                        if ($lastHistory) {
                            $lastScore = $lastHistory->final_score;
                            if ($lastHistory->status === 'passed') {
                                $isLabPassed = true;
                                $labScore = $lastHistory->final_score;
                            }
                        }
                    } catch (\Throwable $e) {
                        $lastScore = null;
                    }
                }

                $canAccessLab = $isAdmin || (!$isChapterLocked && collect($chapter['items'])->every(fn($it) => $isDone($it['id'])));
                if ($isLabPassed || $isLabActive || $lastScore !== null) {
                    $canAccessLab = true;
                }

                $canAccessQuiz = $isAdmin || $isQuizPassed || ($canAccessLab && $isLabPassed);
            @endphp

            <div class="relative transition-all duration-500 {{ $isChapterLocked ? 'opacity-50 grayscale' : 'opacity-100' }}">
                <div class="px-2 mb-3 sticky top-0 z-20 py-2 border-b transition-colors shadow-sm dark:shadow-lg backdrop-blur-sm" style="background-color: var(--sb-bg); border-color: var(--sb-border);">
                    <div class="flex justify-between items-center gap-2">
                        <div>
                            <h4 class="text-[11px] font-extrabold uppercase tracking-widest transition-colors" style="color: var(--sb-muted);">{{ $chapter['title'] }}</h4>
                            <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 dark:text-white/30 mt-1">Materi {{ $chapterProgressText }}</p>
                        </div>

                        @if($isChapterLocked)
                            <div class="flex items-center gap-1 px-2 py-0.5 rounded text-[9px] font-bold tracking-wider text-slate-500 bg-slate-200 border-slate-300 dark:text-white/30 dark:bg-white/5 dark:border-white/5 border transition-colors">🔒 TERKUNCI</div>
                        @elseif($isQuizPassed && !$isAdmin)
                            <div class="flex items-center gap-1 px-2 py-0.5 rounded text-[9px] font-bold tracking-wider text-emerald-600 bg-emerald-100 border-emerald-200 dark:text-emerald-400 dark:bg-emerald-500/10 dark:border-emerald-500/20 border transition-colors">✔ SELESAI</div>
                        @elseif($isAdmin)
                            <div class="flex items-center gap-1 px-2 py-0.5 rounded text-[9px] font-bold tracking-wider text-indigo-600 bg-indigo-100 border-indigo-200 dark:text-indigo-300 dark:bg-indigo-500/10 dark:border-indigo-500/20 border transition-colors">ADMIN</div>
                        @endif
                    </div>
                </div>

                <div class="space-y-1 relative">
                    <div class="absolute left-[1.15rem] top-2 bottom-2 w-px bg-gradient-to-b from-slate-300 via-slate-200 dark:from-white/10 dark:via-white/5 to-transparent -z-10 transition-colors"></div>

                    @php $previousItemFinished = true; @endphp
                    @foreach($chapter['items'] as $item)
                        @php
                            $isCompleted = $isDone($item['id']);
                            $isActive = $routeIsActive($item['route']);
                            $isItemLocked = $isAdmin ? false : ($isChapterLocked || !$previousItemFinished);
                            if ($isActive) {
                                $isItemLocked = false;
                            }

                            $showAccordion = $isActive && !empty($item['anchors']) && !$isChapterLocked;
                            $collapseId = 'collapse-' . str_replace('.', '-', $item['id']);
                            $itemUrl = $routeUrl($item['route']);

                            $activeBg = $isActive ? 'var(--sb-active-bg)' : 'transparent';
                            $activeBorder = $isActive ? 'var(--sb-active-border)' : 'transparent';
                            $hoverClass = $isActive ? '' : 'hover:bg-slate-100 dark:hover:bg-white/[0.04]';
                            $textColor = $isActive ? 'var(--sb-active-text)' : ($isItemLocked ? 'var(--sb-muted)' : 'var(--sb-text)');
                            $iconBg = $isCompleted ? 'bg-emerald-500 text-white border-emerald-500' : ($isActive ? 'bg-indigo-500 text-white border-indigo-500' : 'bg-slate-200 dark:bg-[#1a1f2e] border-slate-300 dark:border-white/10 text-slate-500 dark:text-white/45');
                        @endphp

                        <div class="accordion-item group relative">
                            <div id="nav-item-{{ str_replace('.', '-', $item['id']) }}"
                                 data-course-active="{{ $isActive ? 'true' : 'false' }}"
                                 data-course-id="{{ $item['id'] }}"
                                 class="flex items-center gap-3 p-2.5 rounded-xl transition-all duration-300 {{ $hoverClass }} {{ $isItemLocked ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer' }}"
                                 style="background-color: {{ $activeBg }}; border: 1px solid {{ $activeBorder }};"
                                 onclick="{{ $isItemLocked ? 'return false;' : ($isActive ? "toggleAccordion('$collapseId')" : "location.href='$itemUrl#courseRoot'") }}">

                                <div class="relative w-7 h-7 rounded-lg flex items-center justify-center text-[10px] font-bold z-10 shrink-0 border transition-colors {{ $iconBg }}">
                                    @if($isCompleted)
                                        ✔
                                    @elseif($isItemLocked)
                                        🔒
                                    @else
                                        {{ $item['id'] }}
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <span class="block text-[13px] truncate w-full transition-colors" style="color: {{ $textColor }}; font-weight: {{ $isActive ? '800' : '600' }};">
                                        {{ $item['title'] }}
                                    </span>
                                </div>

                                @if(!empty($item['anchors']) && !$isItemLocked)
                                    <div id="icon-{{ $collapseId }}" class="w-6 h-6 flex items-center justify-center rounded-full transition-transform duration-300 shrink-0 {{ $showAccordion ? 'rotate-180 bg-slate-200 dark:bg-white/10' : '' }}">
                                        <svg class="w-3 h-3 text-slate-500 dark:text-white/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                @endif
                            </div>

                            @if(!empty($item['anchors']) && !$isItemLocked)
                                <div id="{{ $collapseId }}" class="overflow-hidden transition-all duration-300" style="{{ $showAccordion ? 'max-height: 1000px; opacity: 1;' : 'max-height: 0px; opacity: 0;' }}">
                                    <div class="pb-2 pl-[3.25rem] pr-2 space-y-1 relative pt-1">
                                        <div class="absolute left-[1.9rem] top-0 bottom-4 w-px border-l border-dashed border-slate-300 dark:border-white/10 transition-colors"></div>
                                        @foreach($item['anchors'] as $anchor)
                                            @php
                                                $isActivity = str_contains(strtolower($anchor['label']), 'aktivitas');
                                                $dataType = $isActivity ? 'activity' : 'normal';
                                            @endphp
                                            <button type="button" data-target="{{ $anchor['id'] }}" data-type="{{ $dataType }}" onclick="scrollToSection('{{ $anchor['id'] }}'); closeMobileSidebar();"
                                                class="sidebar-anchor flex items-center w-full gap-3 px-3 py-1.5 rounded-md text-left group/sub transition-all relative border-l-2 border-transparent">
                                                @if($isActivity)
                                                    <span class="anchor-dot w-2 h-2 rotate-45 rounded-sm bg-slate-400 dark:bg-slate-600 transition-all duration-300 group-hover/sub:bg-amber-500 dark:group-hover/sub:bg-amber-400"></span>
                                                @else
                                                    <span class="anchor-dot w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-600 transition-all duration-300 group-hover/sub:bg-indigo-500 dark:group-hover/sub:bg-indigo-400"></span>
                                                @endif
                                                <span class="anchor-text text-[11px] text-slate-500 dark:text-slate-500 transition-all duration-300 group-hover/sub:text-slate-800 dark:group-hover/sub:text-slate-300 truncate w-40 {{ $isActivity ? 'font-semibold' : '' }}">
                                                    {{ $anchor['label'] }}
                                                </span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        @php $previousItemFinished = (!$isChapterLocked && $isCompleted); @endphp
                    @endforeach

                    @php
                        if ($isLabPassed) {
                            $labStatusText = $labScore !== null ? "NILAI: {$labScore}" : 'LAB SELESAI';
                            $labStatusColor = 'text-emerald-600 dark:text-emerald-400';
                            $labBorder = 'border-emerald-300 dark:border-emerald-500/40 bg-emerald-50 dark:bg-emerald-500/5 hover:bg-emerald-100 dark:hover:bg-emerald-500/10 cursor-pointer';
                            $labIconClass = 'bg-emerald-500 text-white shadow-md dark:shadow-emerald-500/30';
                            $labIcon = '✔';
                            $labLink = $routeUrl('lab.workspace', ['id' => $labId]);
                        } elseif ($isLabActive) {
                            $labStatusText = 'SEDANG DIKERJAKAN';
                            $labStatusColor = 'text-indigo-600 dark:text-indigo-400';
                            $labBorder = 'border-indigo-300 dark:border-indigo-500/40 bg-indigo-50 dark:bg-indigo-600/10 cursor-pointer';
                            $labIconClass = 'bg-indigo-500 text-white shadow-md dark:shadow-indigo-600/30 animate-pulse';
                            $labIcon = '⚡';
                            $labLink = $routeUrl('lab.workspace', ['id' => $labId]);
                        } elseif ($lastScore !== null) {
                            $labStatusText = "TERAKHIR: {$lastScore} - COBA LAGI";
                            $labStatusColor = 'text-rose-600 dark:text-rose-400';
                            $labBorder = 'border-rose-300 dark:border-rose-500/40 bg-rose-50 dark:bg-rose-500/5 hover:bg-rose-100 dark:hover:bg-rose-500/10 cursor-pointer';
                            $labIconClass = 'bg-rose-500 text-white shadow-md dark:shadow-rose-500/30';
                            $labIcon = '↻';
                            $labLink = $routeUrl('lab.start', ['id' => $labId]);
                        } elseif ($canAccessLab) {
                            $labStatusText = 'MULAI LAB';
                            $labStatusColor = 'text-cyan-600 dark:text-cyan-300';
                            $labBorder = 'border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-[#1a1f2e] hover:border-cyan-500 hover:bg-slate-100 dark:hover:border-cyan-500/40 dark:hover:bg-white/5 cursor-pointer';
                            $labIconClass = 'bg-cyan-500 dark:bg-cyan-600 text-white shadow-md dark:shadow-cyan-600/30 group-hover:scale-110';
                            $labIcon = '&gt;_';
                            $labLink = $routeUrl('lab.start', ['id' => $labId]);
                        } else {
                            $labStatusText = 'TERKUNCI';
                            $labStatusColor = 'text-slate-400 dark:text-white/30';
                            $labBorder = 'border-slate-200 dark:border-white/5 bg-slate-100 dark:bg-[#151921] opacity-60 cursor-not-allowed';
                            $labIconClass = 'bg-slate-200 dark:bg-white/5 text-slate-400 dark:text-white/20';
                            $labIcon = '🔒';
                            $labLink = '#';
                        }
                    @endphp

                    <div class="pt-4 pb-2 pl-1 pr-1">
                        <button type="button" onclick="{{ $canAccessLab ? "location.href='$labLink'" : 'return false;' }}"
                            class="w-full flex items-center justify-between p-3.5 rounded-xl border transition-all duration-300 group relative overflow-hidden {{ $labBorder }}">
                            <div class="flex items-center gap-3 relative z-10 w-full">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center font-bold text-xs transition-all shrink-0 {{ $labIconClass }}">
                                    {!! $labIcon !!}
                                </div>
                                <div class="flex flex-col text-left overflow-hidden w-full">
                                    <span class="text-xs font-bold {{ $canAccessLab ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-white/30' }} truncate pr-2 transition-colors">{{ $labTitle }}</span>
                                    <span class="text-[9px] font-bold uppercase tracking-wider transition-colors {{ $labStatusColor }}">{{ $labStatusText }}</span>
                                </div>
                            </div>
                        </button>
                    </div>

                    @php
                        $quizLink = $canAccessQuiz ? $routeUrl('quiz.intro', ['chapterId' => $chapter['quiz_id']]) : '#';
                        $quizColor = $canAccessQuiz ? 'bg-indigo-50 dark:bg-indigo-600/20 border-indigo-200 dark:border-indigo-500/50 cursor-pointer hover:bg-indigo-100 dark:hover:bg-indigo-900/20' : 'bg-slate-100 dark:bg-white/5 border-transparent opacity-50 grayscale cursor-not-allowed';
                        $quizLabel = $isChapterLocked ? 'SELESAIKAN BAB SEBELUMNYA' : (!$canAccessQuiz ? 'SELESAIKAN LAB' : ($quizScore !== null ? 'NILAI: ' . $quizScore : 'KERJAKAN KUIS'));
                    @endphp

                    <div class="pt-1 pl-1 pr-1 pb-4">
                        <button type="button" onclick="{{ $canAccessQuiz ? "location.href='$quizLink'" : 'return false;' }}"
                            class="w-full flex items-center justify-between p-3 rounded-xl border transition-all duration-300 group {{ $quizColor }}">
                            <div class="flex items-center gap-3 relative z-10">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs transition-colors {{ $canAccessQuiz ? 'bg-indigo-500 text-white shadow-md dark:shadow-indigo-500/30' : 'bg-slate-200 dark:bg-white/10 text-slate-400 dark:text-white/20' }}">
                                    @if(!$canAccessQuiz) 🔒 @elseif($quizScore !== null || $isQuizPassed) ✔ @else ★ @endif
                                </div>
                                <div class="flex flex-col text-left">
                                    <span class="text-xs font-bold text-slate-900 dark:text-white transition-colors">Evaluasi Bab {{ $chapter['quiz_id'] }}</span>
                                    <span class="text-[9px] font-bold uppercase tracking-wider transition-colors {{ $canAccessQuiz ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-500 dark:text-white/30' }}">{{ $quizLabel }}</span>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            @php $previousChapterPassed = $currentChapterPassed; @endphp
        @endforeach

        @php
            $finalQuizId = 99;
            $finalQuizScore = $scores[(string) $finalQuizId] ?? $scores[$finalQuizId] ?? null;
            $isFinalLocked = $isAdmin ? false : !$previousChapterPassed;
            $capstoneLabId = 4;
            $capstoneActive = false;
            $capstonePassed = false;
            $capstoneScore = null;

            try {
                if ($userId) {
                    $capstoneActive = \App\Models\LabSession::where('user_id', $userId)->where('lab_id', $capstoneLabId)->where('status', 'active')->exists();
                    $latestCapstoneHistory = \App\Models\LabHistory::where('user_id', $userId)->where('lab_id', $capstoneLabId)->latest('id')->first();
                    if ($latestCapstoneHistory) {
                        $capstoneScore = $latestCapstoneHistory->final_score;
                        $capstonePassed = $latestCapstoneHistory->status === 'passed';
                    }
                }
            } catch (\Throwable $e) {
                $capstoneActive = false;
                $capstonePassed = false;
            }

            if ($capstoneActive || $capstonePassed || $capstoneScore !== null) {
                $isFinalLocked = false;
            }

            $capstoneCanOpen = $isAdmin || !$isFinalLocked || $capstoneActive || $capstonePassed || $capstoneScore !== null;
            $capstoneLink = $capstonePassed || $capstoneActive ? $routeUrl('lab.workspace', ['id' => $capstoneLabId]) : $routeUrl('lab.start', ['id' => $capstoneLabId]);
            $finalQuizCanOpen = $isAdmin || (($capstonePassed || $finalQuizScore !== null) && !$isFinalLocked);
            $finalQuizLink = $finalQuizCanOpen ? $routeUrl('quiz.intro', ['chapterId' => $finalQuizId]) : '#';
        @endphp

        <div class="mt-8 border-t-2 border-slate-200 dark:border-white/10 pt-6 relative transition-all duration-500 {{ $isFinalLocked ? 'opacity-50 grayscale' : 'opacity-100' }}">
            <div class="px-2 mb-4 flex justify-between items-center">
                <h4 class="text-[11px] font-extrabold uppercase tracking-widest text-amber-500 flex items-center gap-2 transition-colors">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L1.573 10.1c-.783-.57-.38-1.81.588-1.81h4.915a1 1 0 00.95-.69l1.519-4.674z"/></svg>
                    FINAL EVALUATION
                </h4>
            </div>

            <div class="mb-3 px-1">
                <button type="button" onclick="{{ $capstoneCanOpen ? "location.href='$capstoneLink'" : 'return false;' }}"
                    class="w-full flex items-center justify-between p-3.5 rounded-xl border transition-all duration-300 group relative overflow-hidden shadow-sm dark:shadow-lg {{ $capstoneCanOpen ? 'border-amber-300 dark:border-amber-500/40 bg-amber-50 dark:bg-amber-600/10 cursor-pointer hover:bg-amber-100 dark:hover:bg-amber-600/20' : 'border-slate-200 dark:border-white/5 bg-slate-100 dark:bg-[#151921] opacity-60 cursor-not-allowed' }}">
                    <div class="flex items-center gap-3 relative z-10 w-full">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center font-bold text-xs transition-all shrink-0 {{ $capstoneCanOpen ? 'bg-amber-500 text-white shadow-md' : 'bg-slate-200 dark:bg-white/10 text-slate-400 dark:text-white/20' }}">
                            @if(!$capstoneCanOpen) 🔒 @elseif($capstonePassed) 🏆 @elseif($capstoneActive) ⚡ @else 🚀 @endif
                        </div>
                        <div class="flex flex-col text-left overflow-hidden w-full">
                            <span class="text-xs font-bold text-slate-900 dark:text-white truncate pr-2 transition-colors">DevStudio Landing Page</span>
                            <span class="text-[9px] font-bold uppercase tracking-wider transition-colors {{ $capstoneCanOpen ? 'text-amber-600 dark:text-amber-400' : 'text-slate-400 dark:text-white/30' }}">
                                @if(!$capstoneCanOpen) TERKUNCI @elseif($capstonePassed) LULUS{{ $capstoneScore !== null ? ' - NILAI: '.$capstoneScore : '' }} @elseif($capstoneActive) LANJUTKAN PROYEK @elseif($capstoneScore !== null) COBA LAGI - NILAI: {{ $capstoneScore }} @else MULAI CAPSTONE @endif
                            </span>
                        </div>
                    </div>
                </button>
            </div>

            <div class="px-1">
                <button type="button" onclick="{{ $finalQuizCanOpen ? "location.href='$finalQuizLink'" : 'return false;' }}"
                    class="w-full flex items-center justify-between p-3 rounded-xl border transition-all duration-300 group {{ $finalQuizCanOpen ? 'bg-amber-50 dark:bg-amber-900/20 border-amber-300 dark:border-amber-600/50 hover:bg-amber-100 dark:hover:bg-amber-900/40 cursor-pointer shadow-sm' : 'bg-slate-100 dark:bg-white/5 border-transparent opacity-50 cursor-not-allowed' }}">
                    <div class="flex items-center gap-3 relative z-10">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs transition-colors {{ $finalQuizCanOpen ? 'bg-amber-500 dark:bg-amber-600 text-white shadow-md dark:shadow-amber-500/30' : 'bg-slate-200 dark:bg-white/10 text-slate-400 dark:text-white/20' }}">
                            @if(!$finalQuizCanOpen) 🔒 @elseif($finalQuizScore !== null) ✔ @else 🎓 @endif
                        </div>
                        <div class="flex flex-col text-left">
                            <span class="text-xs font-bold text-slate-900 dark:text-white transition-colors">Ujian Teori Akhir</span>
                            <span class="text-[9px] font-bold uppercase tracking-wider transition-colors {{ $finalQuizCanOpen ? 'text-amber-600 dark:text-amber-400' : 'text-slate-500 dark:text-white/30' }}">
                                @if(!$finalQuizCanOpen) SELESAIKAN CAPSTONE @elseif($finalQuizScore !== null) NILAI: {{ $finalQuizScore }} @else MULAI UJIAN @endif
                            </span>
                        </div>
                    </div>
                </button>
            </div>
        </div>

        <div class="h-10 mt-6 border-t border-slate-200 dark:border-white/10 pt-4 px-2">
            <button id="theme-toggle-course-sidebar" type="button" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 text-slate-600 dark:text-slate-300 transition-colors border border-slate-200 dark:border-transparent text-xs font-bold shadow-sm dark:shadow-none">
                <svg id="theme-toggle-dark-icon-csb" class="hidden w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                <svg id="theme-toggle-light-icon-csb" class="hidden w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path></svg>
                <span id="theme-toggle-text-csb">Ganti Tema</span>
            </button>
        </div>
        <div class="h-20"></div>
    </div>
</aside>

<script>
    window.markActiveCourseItemCompleted = function() {
        const activeItem = document.querySelector('[data-course-active="true"]');
        if (!activeItem) return;

        const icon = activeItem.querySelector('.relative.w-7.h-7');
        if (!icon) return;

        icon.textContent = '✔';
        icon.className = 'relative w-7 h-7 rounded-lg flex items-center justify-center text-[10px] font-bold z-10 shrink-0 border transition-colors bg-emerald-500 text-white border-emerald-500';
        activeItem.classList.remove('opacity-60', 'cursor-not-allowed');
        activeItem.classList.add('cursor-pointer');
    };

    function toggleAccordion(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        if (!content) return;

        const isClosed = content.style.maxHeight === '0px' || content.style.maxHeight === '';
        content.style.maxHeight = isClosed ? content.scrollHeight + 'px' : '0px';
        content.style.opacity = isClosed ? '1' : '0';

        if (icon) {
            if (isClosed) icon.classList.add('rotate-180', 'bg-slate-200', 'dark:bg-white/10');
            else icon.classList.remove('rotate-180', 'bg-slate-200', 'dark:bg-white/10');
        }
    }

    function highlightAnchor(id) {
        const anchors = document.querySelectorAll('.sidebar-anchor');
        anchors.forEach(anchor => {
            anchor.classList.remove('active-anchor', 'border-indigo-500', 'border-amber-500');
            anchor.classList.add('border-transparent');

            const dot = anchor.querySelector('.anchor-dot');
            const text = anchor.querySelector('.anchor-text');
            if (dot) {
                dot.classList.remove('scale-125', 'bg-indigo-500', 'dark:bg-indigo-400', 'bg-amber-500', 'dark:bg-amber-400', 'shadow-sm');
                dot.classList.add('bg-slate-400', 'dark:bg-slate-600');
            }
            if (text) {
                text.classList.remove('text-slate-900', 'dark:text-white', 'font-bold');
                text.classList.add('text-slate-500', 'dark:text-slate-500');
            }
        });

        const active = document.querySelector(`.sidebar-anchor[data-target="${id}"]`);
        if (!active) return;

        const isActivity = active.dataset.type === 'activity';
        active.classList.add('active-anchor', isActivity ? 'border-amber-500' : 'border-indigo-500');
        active.classList.remove('border-transparent');

        const dot = active.querySelector('.anchor-dot');
        const text = active.querySelector('.anchor-text');
        if (dot) {
            dot.classList.remove('bg-slate-400', 'dark:bg-slate-600');
            dot.classList.add(isActivity ? 'bg-amber-500' : 'bg-indigo-500', isActivity ? 'dark:bg-amber-400' : 'dark:bg-indigo-400', 'scale-125', 'shadow-sm');
        }
        if (text) {
            text.classList.remove('text-slate-500', 'dark:text-slate-500');
            text.classList.add('text-slate-900', 'dark:text-white', 'font-bold');
        }
    }

    function scrollToSection(id) {
        const el = document.getElementById(id);
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        highlightAnchor(id);
    }

    function toggleMobileSidebar() {
        const sidebar = document.getElementById('courseSidebar');
        const overlay = document.getElementById('mobileOverlay');
        if (!sidebar || !overlay) return;

        sidebar.classList.toggle('mobile-open');
        overlay.classList.toggle('show');
    }

    function closeMobileSidebar() {
        const sidebar = document.getElementById('courseSidebar');
        const overlay = document.getElementById('mobileOverlay');
        if (!sidebar || !overlay) return;

        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('show');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const mobileToggleBtn = document.getElementById('mobileSidebarToggle');
        if (mobileToggleBtn) mobileToggleBtn.addEventListener('click', toggleMobileSidebar);

        setTimeout(() => {
            const activeSidebarItem = document.querySelector('[data-course-active="true"]');
            if (activeSidebarItem) activeSidebarItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 250);

        const mainScroll = document.getElementById('mainScroll');
        const sections = document.querySelectorAll('.lesson-section');

        if (mainScroll && sections.length > 0) {
            const observer = new IntersectionObserver((entries) => {
                const visible = entries.filter(entry => entry.isIntersecting);
                if (visible.length > 0) highlightAnchor(visible[0].target.id);
            }, { root: mainScroll, threshold: 0.45 });

            sections.forEach(section => observer.observe(section));
        }

        const themeBtnCsb = document.getElementById('theme-toggle-course-sidebar');
        const darkIconCsb = document.getElementById('theme-toggle-dark-icon-csb');
        const lightIconCsb = document.getElementById('theme-toggle-light-icon-csb');
        const textCsb = document.getElementById('theme-toggle-text-csb');

        const syncCsbIcons = (isDark) => {
            if (isDark) {
                lightIconCsb?.classList.remove('hidden');
                darkIconCsb?.classList.add('hidden');
                if (textCsb) textCsb.textContent = 'Tema Terang';
            } else {
                lightIconCsb?.classList.add('hidden');
                darkIconCsb?.classList.remove('hidden');
                if (textCsb) textCsb.textContent = 'Tema Gelap';
            }
        };

        syncCsbIcons(document.documentElement.classList.contains('dark'));

        themeBtnCsb?.addEventListener('click', function() {
            const willBeDark = !document.documentElement.classList.contains('dark');
            document.documentElement.classList.toggle('dark', willBeDark);
            localStorage.setItem('color-theme', willBeDark ? 'dark' : 'light');
            syncCsbIcons(willBeDark);
            window.dispatchEvent(new Event('theme-toggled'));

            const activeAnchor = document.querySelector('.sidebar-anchor.active-anchor');
            if (activeAnchor) highlightAnchor(activeAnchor.dataset.target);
        });
    });
</script>
