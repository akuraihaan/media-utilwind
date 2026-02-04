@php
    // 1. KONFIGURASI STRUKTUR MATERI
    $chapters = [
        [
            'title' => 'BAB 1: PENDAHULUAN',
            'quiz_id' => '1',
            'items' => [
                [
                    'id' => '1.1',
                    'title' => 'Konsep Dasar HTML & CSS',
                    'route' => 'courses.htmldancss',
                    'anchors' => [
                        ['id' => 'section-1', 'label' => 'Pengantar'],
                        ['id' => 'section-2', 'label' => 'Semantik & Atribut HTML'],
                        ['id' => 'section-3', 'label' => 'Pengenalan CSS Styling'],
                        ['id' => 'section-4', 'label' => 'Warna & Dasar Font'],
                        ['id' => 'section-5', 'label' => 'Box Model'],

                        ['id' => 'section-6', 'label' => 'Aktivitas 1.1', 'highlight' => true],
                    ]
                ],
                [
                    'id' => '1.2',
                    'title' => 'Konsep Dasar Tailwind',
                    'route' => 'courses.tailwindcss',
                    'anchors' => [
                        ['id' => 'section-1', 'label' => 'Filosofi Utility'],
                        ['id' => 'section-2', 'label' => 'Warna & Tipografi'],
                        ['id' => 'section-3', 'label' => 'Spacing & Sizing'],
                        ['id' => 'section-4', 'label' => 'Borders & Effects'],
                        ['id' => 'section-5', 'label' => 'Aktivitas 1.2', 'highlight' => true],
                    ]
                ],
                [
                    'id' => '1.3',
                    'title' => 'Latar Belakang',
                    'route' => 'courses.latarbelakang',
                    'anchors' => [
                        ['id' => 'section-1', 'label' => 'Krisis Skalabilitas CSS Tradisional'],
                        ['id' => 'section-2', 'label' => ' Arsitektur Tiga Lapisan'],
                        ['id' => 'section-3', 'label' => 'Mesin JIT '],

                        ['id' => 'section-4', 'label' => 'Aktivitas 1.3', 'highlight' => true],
                    ]
                ],
                [
                    'id' => '1.4',
                    'title' => 'Penerapan Utility', 
                    'route' => 'courses.implementation',
                    'anchors' => [
                        ['id' => 'section-1', 'label' => 'Konsep Utility & Pola Sintaks'],
                        ['id' => 'section-2', 'label' => 'Komposisi & Interaktivitas'], 
                        ['id' => 'section-3', 'label' => 'Manajemen Kode Berulang'], 
                        ['id' => 'section-4', 'label' => 'Aktivitas 1.4', 'highlight' => true],
                    ]
                ],
                [
                    'id' => '1.5',
                    'title' => 'Keunggulan',
                    'route' => 'courses.advantages',
                    'anchors' => [
                        ['id' => 'section-1', 'label' => 'Kecepatan'],
                        ['id' => 'section-2', 'label' => 'Konsistensi'],
                        ['id' => 'section-3', 'label' => 'Performa'],
                        ['id' => 'section-4', 'label' => 'Aktivitas 1.5', 'highlight' => true],
                    ]
                ],
                [
                    'id' => '1.6',
                    'title' => 'Instalasi & Konfigurasi',
                    'route' => 'courses.installation',
                    'anchors' => [
                        ['id' => 'prereq', 'label' => 'Prasyarat Node.js'],
                        ['id' => 'cli-steps', 'label' => 'Langkah Instalasi'],
                        ['id' => 'compilation', 'label' => 'Proses Kompilasi'],
                        ['id' => 'quiz-1-6', 'label' => 'Aktivitas 1.6', 'highlight' => true],
                    ]
                ],
            ]
        ],
        [
            'title' => 'BAB 2: LAYOUTING',
            'quiz_id' => '2',
            'items' => [
                [
                    'id' => '2.1',
                    'title' => 'Layout dengan Flexbox',
                    'route' => 'courses.flexbox',
                    'anchors' => [
                        ['id' => 'fondasi', 'label' => 'Ukuran Flexbox'],
                        ['id' => 'arahwrap', 'label' => 'Arah & Wrap'],
                        ['id' => 'sizing', 'label' => 'Flex Grow, Shrink & Order'],
                        ['id' => 'aktivitas-1-7', 'label' => 'Aktivitas 2.1', 'highlight' => true],
                    ]
                ],
                [
                    'id' => '2.2',
                    'title' => 'Layout dengan Grid',
                    'route' => 'courses.grid',
                    'anchors' => [
                        ['id' => 'section-34', 'label' => 'Konsep Grid'],
                        ['id' => 'section-35', 'label' => 'Penjajaran'],
                        ['id' => 'section-36', 'label' => 'Span'],
                        ['id' => 'section-37', 'label' => 'Template Rows'],
                        ['id' => 'section-38', 'label' => 'Template Cols'],
                        ['id' => 'section-39', 'label' => 'Auto Flow'],
                        ['id' => 'section-40', 'label' => 'Aktivitas 2.2', 'highlight' => true],
                    ]
                ],
                [
                    'id' => '2.3', 'title' => 'Mengelola Layout', 'route' => 'courses.layout-mgmt',
                    'anchors' => [
                        ['id'=>'section-41','label'=>'Container & Viewport'], 
                        ['id'=>'section-42','label'=>'Float & Clear'],        
                        ['id'=>'section-43','label'=>'Position & Z-Index'],   
                        ['id'=>'section-44','label'=>'Table Layout'],         
                        ['id'=>'section-45','label'=>'Aktivitas 2.3','highlight'=> true]
                    ]
                ],
            ]
        ],
        [
            'title' => 'BAB 3: STYLING',
            'quiz_id' => '3',
            'items' => [
                [
    'id' => '3.1',
    'title' => 'Tipografi',
    'route' => 'courses.typography',
    'anchors' => [
        [
            'id' => 'fonts', 'label' => 'Font Family & Size'
        ],
        [
            'id' => 'weight', 
            'label' => 'Weight & Style'
        ],
        [
            'id' => 'spacing', 
            'label' => 'Align & Spacing'
        ],
        [
            'id' => 'decoration', 
            'label' => 'Color & Decor'
        ],
        [
            'id' => 'transform', 
            'label' => 'Transform & Overflow'
        ],
        [
            'id' => 'activity-expert', 
            'label' => 'Aktivitas 3.1','highlight'=> true
        ],
    ]
],
                [
    'id' => '3.2',
    'title' => 'Backgrounds',
    'route' => 'courses.backgrounds', // Sesuai route web.php
    'anchors' => [
        [
            'id' => 'attachment', 
            'label' => 'Attachment, Clip & Origin'
        ],
        [
            'id' => 'position', 
            'label' => 'Color, Position & Repeat'
        ],
        [
            'id' => 'size', 
            'label' => 'Size, Image & Gradient'
        ],
        [
            'id' => 'activity-expert', 
                        'label' => 'Aktivitas 3.2','highlight'=> true

        ],
    ]
],
                [
    'id' => '3.3',
    'title' => 'Borders & Effects',
    'route' => 'courses.borders',
    'anchors' => [
        [
            'id' => 'radius', 
            'label' => 'Radius & Width'
        ],
        [
            'id' => 'style', 
            'label' => 'Gaya & Divide'
        ],
        [
            'id' => 'ring', 
            'label' => 'Outline & Ring'
        ],
        [
            'id' => 'activity-challenge', 
                        'label' => 'Aktivitas 3.3','highlight'=> true

        ],
    ]
],

[
    'id' => '3.4',
    'title' => 'Efek Visual',
    'route' => 'courses.effects', // Sesuaikan dengan route di web.php
    'anchors' => [
        [
            'id' => 'box-shadow', 
            'label' => 'Box Shadow & Color'
        ],
        [
            'id' => 'opacity', 
            'label' => 'Opacity'
        ],
        [
            'id' => 'filters', 
            'label' => 'Filters (Blur & Effects)'
        ],
        [
            'id' => 'transitions', 
            'label' => 'Transitions & Animation'
        ],
        [
            'id' => 'transforms', 
            'label' => 'Transforms'
        ],
        [
            'id' => 'visual-challenge', 
            'label' => 'Aktivitas 3.4','highlight'=> true
        ],
    ]
],
            ]
        ]
    ];

    // 2. DATA STATE DARI CONTROLLER
    $currentRoute = Route::currentRouteName();
    
    // Data progress (Materi & Quiz)
    // Pastikan di Controller Anda mengirim variable $quizProgress (Array ID Quiz yg selesai)
    // Contoh: $quizProgress = ['1', '2']; artinya kuis bab 1 & 2 selesai.
    $completedMap = $completedLessonsMap ?? []; 
    $completedQuizzes = $quizProgress ?? []; 

    // Variable pelacak urutan (Chain Lock)
    // Dimulai true agar Bab 1.1 selalu terbuka di awal
    $previousItemFinished = true;
@endphp

<aside class="w-[340px] bg-[#050912]/80 backdrop-blur-md border-r border-white/5 flex flex-col shrink-0 z-40 hidden lg:flex h-full">
    
    <div class="p-6 pb-2 border-b border-white/5 bg-transparent">
        <div class="mb-6">
            <p class="text-xs uppercase tracking-widest text-white/40 mb-1">TailwindLearn</p>
            <h3 class="text-lg font-semibold text-white">Daftar Materi</h3>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-8" id="sidebar-scroll-container">
        
        @foreach($chapters as $chapterIndex => $chapter)
            @php
                // --- LOGIC 1: CEK KELENGKAPAN SUBBAB ---
                $allSubbabsCompleted = true;
                foreach($chapter['items'] as $subItem) {
                    if (!isset($completedMap[$subItem['id']]) || !$completedMap[$subItem['id']]) {
                        $allSubbabsCompleted = false;
                        break;
                    }
                }
                
                // --- LOGIC 2: STATUS KUIS ---
                // Cek apakah ID kuis ini ada di array completedQuizzes (Harus string match)
                $isQuizCompleted = in_array((string)$chapter['quiz_id'], $completedQuizzes);
                
                // Cek apakah sedang di halaman kuis ini
                $isQuizActive = (request()->route('chapterId') == $chapter['quiz_id'] && Route::is('quiz.*'));
                
                // Kuis Terkunci JIKA: Subbab belum beres ATAU item sebelumnya belum selesai
                $isQuizLocked = !$allSubbabsCompleted; 
            @endphp

            <div>
                <h4 class="px-3 mb-3 text-[10px] font-bold uppercase tracking-widest text-white/30 sticky top-0 bg-[#050912]/90 backdrop-blur z-10 py-1">
                    {{ $chapter['title'] }}
                </h4>

                <div class="space-y-1">
                    {{-- 1. LOOPING MATERI PEMBELAJARAN --}}
                    @foreach($chapter['items'] as $item)
                        @php
                            $isCompleted = isset($completedMap[$item['id']]) && $completedMap[$item['id']];
                            $isActive = ($currentRoute == $item['route']);
                            $isLocked = !$previousItemFinished; // Kunci jika item sebelumnya belum beres

                            // Jangan kunci halaman yang sedang aktif (safety)
                            if ($isActive) $isLocked = false;
                        @endphp

                        <div id="group-{{ str_replace('.', '-', $item['id']) }}" 
                             class="accordion-group rounded-xl overflow-hidden border transition-all duration-300 sb-group 
                             {{ $isActive ? 'open bg-white/[0.02] border-white/10' : 'border-transparent' }} 
                             {{ $isLocked ? 'opacity-50 grayscale cursor-not-allowed' : 'opacity-80 hover:opacity-100' }}">
                            
                            <button 
                                onclick="{{ $isLocked ? 'return false;' : ($isActive ? "toggleAccordion('content-{$item['id']}')" : "location.href='".route($item['route'])."'") }}"
                                class="sb-header w-full flex items-center justify-between p-3 {{ $isActive ? 'bg-white/5' : ($isLocked ? '' : 'hover:bg-white/5') }} transition text-left group">
                                
                                <div class="flex items-center gap-3">
                                    <div class="w-6 h-6 rounded-lg border flex items-center justify-center text-[10px] font-bold transition-colors shadow-sm
                                        {{ $isCompleted ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/20' : 
                                           ($isActive ? 'bg-fuchsia-500/20 text-fuchsia-400 border-fuchsia-500/20' : 
                                           ($isLocked ? 'bg-white/5 border-white/5 text-white/20' : 'bg-black border-white/10 text-white/40')) }}">
                                        {{ $isCompleted ? '✔' : ($isLocked ? '🔒' : $item['id']) }}
                                    </div>
                                    <span class="text-sm font-bold truncate w-48 transition-colors
                                        {{ $isActive ? 'text-white' : ($isLocked ? 'text-white/30' : 'text-white/60 group-hover:text-white') }}">
                                        {{ $item['title'] }}
                                    </span>
                                </div>

                                @if($isActive)
                                    <svg class="w-4 h-4 text-white/40 transform rotate-180 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                @endif
                            </button>

                            @if($isActive && count($item['anchors']) > 0)
                                <div id="content-{{ $item['id'] }}" class="accordion-content transition-all duration-300 ease-in-out" style="max-height: 1000px;">
                                    <div class="pl-4 pr-2 py-2 space-y-1 bg-black/20 border-t border-white/5">
                                        @foreach($item['anchors'] as $anchor)
                                            <button data-target="#{{ $anchor['id'] }}" class="nav-item flex items-center w-full gap-3 px-3 py-2 rounded-lg hover:bg-white/5 text-left transition-all group/item">
                                                <span class="w-1.5 h-1.5 rounded-full transition-all duration-300 {{ isset($anchor['highlight']) ? 'bg-fuchsia-500 shadow-[0_0_5px_#d946ef]' : 'bg-slate-600 group-hover/item:bg-white' }} dot"></span> 
                                                <span class="text-xs transition-colors label {{ isset($anchor['highlight']) ? 'text-fuchsia-300 font-bold' : 'text-slate-400 group-hover/item:text-white' }}">{{ $anchor['label'] }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        @php
                            // UPDATE RANTAI: Item berikutnya hanya terbuka jika item ini selesai
                            $previousItemFinished = $isCompleted;
                        @endphp
                    @endforeach

                    {{-- 2. TOMBOL KUIS / EVALUASI BAB --}}
                    <div class="pt-2">
                        <button 
                            {{-- LOGIKA KLIK: Jika Terkunci ATAU Sudah Selesai -> Return False (Gak bisa diklik) --}}
                            onclick="{{ ($isQuizLocked || $isQuizCompleted) ? 'return false;' : "location.href='".route('quiz.intro', ['chapterId' => $chapter['quiz_id']])."'" }}"
                            
                            class="w-full flex items-center justify-between p-3 rounded-xl border transition-all duration-300 group relative overflow-hidden
                            {{-- STYLING TOMBOL --}}
                            {{ $isQuizCompleted ? 'bg-green-500/10 border-green-500/50 cursor-default opacity-80' : 
                               ($isQuizActive ? 'bg-gradient-to-r from-fuchsia-600/20 to-purple-600/20 border-fuchsia-500/50' : 
                               ($isQuizLocked ? 'bg-white/5 border-transparent opacity-50 cursor-not-allowed grayscale' : 'bg-gradient-to-r from-fuchsia-900/20 to-purple-900/20 border-fuchsia-500/20 hover:border-fuchsia-500/50 hover:shadow-lg hover:shadow-fuchsia-900/20 cursor-pointer')) }}">
                            
                            {{-- Background Glow jika aktif dan belum selesai --}}
                            @if(!$isQuizLocked && !$isQuizCompleted)
                                <div class="absolute inset-0 bg-fuchsia-500/5 opacity-0 group-hover:opacity-100 transition duration-500"></div>
                            @endif

                            <div class="flex items-center gap-3 relative z-10">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs transition-all shadow-inner
                                    {{ $isQuizCompleted ? 'bg-green-500 text-white shadow-green-500/30' : 
                                       ($isQuizLocked ? 'bg-white/10 text-white/20' : 'bg-gradient-to-br from-fuchsia-500 to-purple-600 text-white shadow-fuchsia-500/30 animate-pulse') }}">
                                    @if($isQuizCompleted) ✔ @elseif($isQuizLocked) 🔒 @else ★ @endif
                                </div>
                                <div class="flex flex-col text-left">
                                    <span class="text-xs font-bold {{ $isQuizLocked ? 'text-white/30' : 'text-white group-hover:text-fuchsia-200' }}">
                                        Evaluasi Bab {{ $chapter['quiz_id'] }}
                                    </span>
                                    <span class="text-[9px] {{ $isQuizCompleted ? 'text-green-400 font-bold uppercase tracking-wider' : ($isQuizLocked ? 'text-white/20' : 'text-fuchsia-400/70') }}">
                                        {{ $isQuizCompleted ? 'SUDAH SELESAI' : ($isQuizLocked ? 'Selesaikan materi dulu' : 'Siap dikerjakan') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Panah Animasi (Hanya muncul jika belum dikerjakan & terbuka) --}}
                            @if(!$isQuizLocked && !$isQuizCompleted)
                                <div class="relative z-10 bg-fuchsia-500/20 p-1 rounded-full animate-bounce">
                                    <svg class="w-3 h-3 text-fuchsia-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            @endif
                        </button>
                    </div>

                    @php
                        // UPDATE RANTAI: Bab berikutnya hanya terbuka jika KUIS bab ini SUDAH SELESAI
                        $previousItemFinished = $isQuizCompleted;
                    @endphp

                </div>
            </div>
        @endforeach

    </div>
</aside>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Cari elemen yang aktif (class .open) atau kuis aktif
        const activeGroup = document.querySelector('.sb-group.open');
        if (activeGroup) {
            activeGroup.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>