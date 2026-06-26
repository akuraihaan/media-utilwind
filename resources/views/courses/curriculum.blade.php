@extends('layouts.landing')
@section('title', 'Materi')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Curriculum Tailwind CSS
    |--------------------------------------------------------------------------
    | Struktur ini disesuaikan dengan data course_lessons yang berisi ID 1-65.
    | Setiap subbab memiliki 5 lesson. Lesson terakhir pada setiap subbab adalah
    | aktivitas. Status selesai dibaca dari completedLessonsMap yang dikirim
    | CourseController melalui getChapterStatus().
    |
    | Mapping benar:
    | 1.1 = 1-5,   1.2 = 6-10,  1.3 = 11-15, 1.4 = 16-20, 1.5 = 21-25
    | 2.1 = 26-30, 2.2 = 31-35, 2.3 = 36-40, 2.4 = 41-45
    | 3.1 = 46-50, 3.2 = 51-55, 3.3 = 56-60, 3.4 = 61-65
    */

    $completedLessonsMap = $completedLessonsMap ?? [];
    $passedLabsMap = $passedLabsMap ?? [];
    $progressPercent = $progressPercent ?? 0;
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
    $canAccessLearning = $canAccessLearning ?? $isAdmin;
    $accessRequirement = $accessRequirement ?? [
        'title' => 'Akses Materi Terkunci',
        'message' => 'Silabus dapat dibaca bebas, tetapi materi, lab, dan evaluasi membutuhkan akun belajar dan akses kelas aktif.',
        'action' => 'Masuk atau gabung kelas untuk membuka materi.',
    ];

    $chapters = [
        [
            'id' => 1,
            'number' => '01',
            'title' => 'PENDAHULUAN',
            'subtitle' => 'Dasar HTML, CSS, dan Tailwind CSS',
            'desc' => 'Bangun fondasi dari struktur HTML dan CSS, konsep utility-first, penggunaan CDN, instalasi, sampai konfigurasi dasar Tailwind CSS.',
            'color' => 'cyan',
            'quiz_req_prev' => null,
            'last_lesson_code' => '1.5',
            'lab_id' => 1,
            'lab_title' => 'Membangun Halaman Profil Dasar',
            'quiz_id' => 1,
            'quiz_key_db' => 'quiz_1',
            'topics' => [
                ['code' => '1.1', 'name' => 'Konsep Dasar HTML dan CSS', 'route' => 'courses.htmldancss', 'time' => '15 min', 'range' => '1-5'],
                ['code' => '1.2', 'name' => 'Konsep Dasar Tailwind CSS', 'route' => 'courses.tailwindcss', 'time' => '15 min', 'range' => '6-10'],
                ['code' => '1.3', 'name' => 'Tailwind CSS melalui CDN', 'route' => 'courses.latarbelakang', 'time' => '20 min', 'range' => '11-15'],
                ['code' => '1.4', 'name' => 'Instalasi Tailwind CSS', 'route' => ['courses.implementation', 'courses.implementasi'], 'time' => '25 min', 'range' => '16-20'],
                ['code' => '1.5', 'name' => 'Konfigurasi Dasar Tailwind CSS', 'route' => ['courses.advantages', 'courses.keunggulan'], 'time' => '25 min', 'range' => '21-25'],
            ],
        ],
        [
            'id' => 2,
            'number' => '02',
            'title' => 'LAYOUTING',
            'subtitle' => 'Ruang, Flexbox, Grid, dan Responsif',
            'desc' => 'Pelajari cara mengatur ruang, menyusun elemen dengan Flexbox, membuat Grid, dan menyesuaikan tampilan pada berbagai ukuran layar.',
            'color' => 'indigo',
            'quiz_req_prev' => 'quiz_1',
            'last_lesson_code' => '2.4',
            'lab_id' => 2,
            'lab_title' => 'Membuat Layout Kartu Responsif',
            'quiz_id' => 2,
            'quiz_key_db' => 'quiz_2',
            'topics' => [
                ['code' => '2.1', 'name' => 'Dasar Layout dan Ruang', 'route' => ['courses.layout-basics', 'courses.layout-spacing'], 'time' => '20 min', 'range' => '26-30'],
                ['code' => '2.2', 'name' => 'Flexbox', 'route' => 'courses.flexbox', 'time' => '25 min', 'range' => '31-35'],
                ['code' => '2.3', 'name' => 'Grid', 'route' => 'courses.grid', 'time' => '25 min', 'range' => '36-40'],
                ['code' => '2.4', 'name' => 'Responsif', 'route' => 'courses.responsive', 'time' => '20 min', 'range' => '41-45'],
            ],
        ],
        [
            'id' => 3,
            'number' => '03',
            'title' => 'STYLING',
            'subtitle' => 'Tipografi, Warna, Border, dan Efek Visual',
            'desc' => 'Perkuat tampilan antarmuka melalui tipografi, warna dan latar belakang, border, radius, bayangan, dan efek visual sederhana.',
            'color' => 'fuchsia',
            'quiz_req_prev' => 'quiz_2',
            'last_lesson_code' => '3.4',
            'lab_id' => 3,
            'lab_title' => 'Mendesain Komponen Produk',
            'quiz_id' => 3,
            'quiz_key_db' => 'quiz_3',
            'topics' => [
                ['code' => '3.1', 'name' => 'Tipografi', 'route' => 'courses.typography', 'time' => '20 min', 'range' => '46-50'],
                ['code' => '3.2', 'name' => 'Warna dan Latar Belakang', 'route' => ['courses.backgrounds', 'courses.background'], 'time' => '20 min', 'range' => '51-55'],
                ['code' => '3.3', 'name' => 'Border dan Radius', 'route' => 'courses.borders', 'time' => '20 min', 'range' => '56-60'],
                ['code' => '3.4', 'name' => 'Bayangan dan Efek Visual', 'route' => 'courses.effects', 'time' => '25 min', 'range' => '61-65'],
            ],
        ],
    ];

    $theme = [
        'cyan' => [
            'text' => 'text-cyan-600 dark:text-cyan-400',
            'badge' => 'bg-cyan-50 dark:bg-cyan-500/10 border-cyan-200 dark:border-cyan-500/20 text-cyan-600 dark:text-cyan-400',
            'dot' => 'bg-cyan-500 border-cyan-500',
            'bar' => 'from-cyan-400 to-blue-500',
            'hover' => 'group-hover:border-cyan-400 dark:group-hover:border-cyan-500/50',
            'button' => 'from-cyan-500 to-blue-500 dark:from-cyan-600 dark:to-blue-600 shadow-cyan-500/30 dark:shadow-cyan-500/20',
        ],
        'indigo' => [
            'text' => 'text-indigo-600 dark:text-indigo-400',
            'badge' => 'bg-indigo-50 dark:bg-indigo-500/10 border-indigo-200 dark:border-indigo-500/20 text-indigo-600 dark:text-indigo-400',
            'dot' => 'bg-indigo-500 border-indigo-500',
            'bar' => 'from-indigo-400 to-purple-500',
            'hover' => 'group-hover:border-indigo-400 dark:group-hover:border-indigo-500/50',
            'button' => 'from-indigo-500 to-purple-500 dark:from-indigo-600 dark:to-purple-600 shadow-indigo-500/30 dark:shadow-indigo-500/20',
        ],
        'fuchsia' => [
            'text' => 'text-fuchsia-600 dark:text-fuchsia-400',
            'badge' => 'bg-fuchsia-50 dark:bg-fuchsia-500/10 border-fuchsia-200 dark:border-fuchsia-500/20 text-fuchsia-600 dark:text-fuchsia-400',
            'dot' => 'bg-fuchsia-500 border-fuchsia-500',
            'bar' => 'from-fuchsia-400 to-pink-500',
            'hover' => 'group-hover:border-fuchsia-400 dark:group-hover:border-fuchsia-500/50',
            'button' => 'from-fuchsia-500 to-pink-500 dark:from-fuchsia-600 dark:to-pink-600 shadow-fuchsia-500/30 dark:shadow-fuchsia-500/20',
        ],
    ];

    $routeUrl = function ($names, array $params = []) {
        foreach ((array) $names as $name) {
            if (\Illuminate\Support\Facades\Route::has($name)) {
                return route($name, $params);
            }
        }
        return '#';
    };

    $isPassedLab = function ($labId) use ($passedLabsMap) {
        return isset($passedLabsMap[$labId]) || isset($passedLabsMap[(string) $labId]);
    };
@endphp

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.72);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(15, 23, 42, 0.08);
        box-shadow: 0 4px 30px rgba(15, 23, 42, 0.05);
    }
    .dark .glass-card {
        background: rgba(15, 23, 42, 0.66);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.12);
    }
    .glass-card-locked {
        background: rgba(241, 245, 249, 0.68);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(15, 23, 42, 0.05);
    }
    .dark .glass-card-locked {
        background: rgba(2, 6, 23, 0.68);
        border: 1px solid rgba(255, 255, 255, 0.04);
    }
    .reveal-up { opacity: 0; transform: translateY(30px); animation: reveal .8s cubic-bezier(.2, 1, .3, 1) forwards; }
    .delay-200 { animation-delay: 200ms; }
    .delay-500 { animation-delay: 500ms; }
    @keyframes reveal { to { opacity: 1; transform: translateY(0); } }
    .writing-vertical-rl { writing-mode: vertical-rl; }
    .chapter-card-accent::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: 2rem;
        padding: 1px;
        background: linear-gradient(135deg, rgba(34,211,238,.45), rgba(99,102,241,.28), rgba(217,70,239,.25));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
        opacity: .62;
    }
    @keyframes shimmer { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }
    .animate-shimmer { animation: shimmer 2s infinite; }
</style>

<div class="relative min-h-screen bg-slate-50 dark:bg-[#020617] text-slate-800 dark:text-white font-sans overflow-x-hidden pt-28 pb-32 transition-colors duration-500">
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[1000px] h-[1000px] bg-indigo-300/30 dark:bg-indigo-900/20 rounded-full blur-[120px] animate-pulse transition-colors duration-500"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[800px] h-[800px] bg-cyan-300/30 dark:bg-cyan-900/10 rounded-full blur-[100px] transition-colors duration-500"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02] dark:opacity-[0.03] transition-opacity duration-500"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-24 gap-12 reveal-up">
            <div class="space-y-4 max-w-3xl w-full">
                <nav class="flex items-center gap-2 mb-2 text-[10px] md:text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-white/40 transition-colors">
                    <a href="/" class="hover:text-slate-900 dark:hover:text-white transition-colors flex items-center gap-1.5">
                        <svg class="w-3 h-3 md:w-3.5 md:h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Home
                    </a>
                    <span class="text-slate-300 dark:text-white/20 transition-colors">/</span>
                    <a href="{{ $routeUrl('dashboard') }}" class="hover:text-slate-900 dark:hover:text-white transition-colors">Dashboard</a>
                    <span class="text-slate-300 dark:text-white/20 transition-colors">/</span>
                    <span class="text-cyan-600 dark:text-cyan-400 drop-shadow-none dark:drop-shadow-[0_0_8px_rgba(34,211,238,0.5)] transition-colors">Materi</span>
                </nav>

                <div class="flex items-start gap-4">
                    <h1 class="text-5xl md:text-7xl font-black text-slate-900 dark:text-white leading-tight tracking-tight transition-colors">
                        Silabus <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 via-indigo-600 to-fuchsia-600 dark:from-cyan-400 dark:via-indigo-400 dark:to-fuchsia-400">
                            Tailwind CSS
                        </span>
                    </h1>
                    <button onclick="openInfoModal()" class="mt-4 md:mt-6 w-8 h-8 md:w-10 md:h-10 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-sm md:text-lg font-black text-slate-400 dark:text-slate-500 hover:text-cyan-600 dark:hover:text-cyan-400 bg-white/50 dark:bg-white/5 backdrop-blur-sm hover:bg-white dark:hover:bg-white/10 hover:border-cyan-200 dark:hover:border-cyan-500/30 transition-all duration-300 shadow-sm hover:shadow-md focus:outline-none shrink-0" title="Informasi Silabus">
                        ?
                    </button>
                </div>

                <p class="text-slate-500 dark:text-slate-400 text-lg md:text-xl leading-relaxed transition-colors mt-2">
                    Materi disusun dari dasar menuju praktik. Mulai dari HTML dan CSS, lanjut ke Tailwind CSS, kemudian masuk ke layouting dan styling komponen agar pembelajaran berjalan bertahap dan mudah diikuti.
                </p>

                @if($isAdmin)
                    <div class="inline-block mt-2 bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 px-3 py-1 rounded text-xs font-bold uppercase tracking-widest">
                        Mode Admin : Semua akses terbuka
                    </div>
                @elseif(!$canAccessLearning)
                    <div class="mt-4 max-w-2xl rounded-2xl border border-cyan-200/80 dark:border-cyan-500/20 bg-white/70 dark:bg-cyan-500/10 px-5 py-4 shadow-sm backdrop-blur-md">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 w-9 h-9 rounded-xl bg-cyan-100 dark:bg-cyan-500/15 border border-cyan-200 dark:border-cyan-400/20 flex items-center justify-center text-cyan-700 dark:text-cyan-300 font-black shrink-0">
                                i
                            </div>
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-cyan-700 dark:text-cyan-300">Silabus Publik</p>
                                <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed mt-1">
                                    {{ $accessRequirement['message'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="w-full lg:w-96 glass-card rounded-3xl p-8 relative overflow-hidden group hover:border-cyan-400 dark:hover:border-cyan-500/30 transition duration-500 shrink-0 shadow-lg dark:shadow-none">
                <div class="absolute -right-12 -top-12 w-40 h-40 bg-cyan-400/20 dark:bg-cyan-500/10 rounded-full blur-3xl group-hover:bg-cyan-500/30 dark:group-hover:bg-cyan-500/20 transition duration-500"></div>
                <div class="flex justify-between items-end mb-6">
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1 transition-colors">Total Progress</p>
                        <h3 class="text-5xl font-black text-slate-900 dark:text-white tracking-tight transition-colors">{{ $progressPercent }}<span class="text-2xl text-slate-400 dark:text-slate-500 transition-colors">%</span></h3>
                    </div>
                    <svg class="w-10 h-10 text-cyan-500 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div class="w-full h-3 bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden border border-slate-300 dark:border-white/5 transition-colors">
                    <div class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 dark:from-cyan-500 dark:to-blue-600 relative overflow-hidden transition-all duration-1000 ease-out" style="width: {{ $progressPercent }}%">
                        <div class="absolute inset-0 bg-white/30 dark:bg-white/20 animate-shimmer"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 reveal-up delay-200">
            @foreach($chapters as $chapter)
                @php
                    $chapterTheme = $theme[$chapter['color']];
                    $isChapterUnlocked = true;

                    if ($chapter['quiz_req_prev']) {
                        $isChapterUnlocked = $isAdmin || !empty($completedLessonsMap[$chapter['quiz_req_prev']]);
                    }

                    $cardStyle = $isChapterUnlocked
                        ? 'glass-card hover:-translate-y-2 shadow-md dark:shadow-none ' . $chapterTheme['hover']
                        : 'glass-card-locked opacity-70 grayscale shadow-sm dark:shadow-none border-slate-200 dark:border-white/5';

                    $completedTopicCount = 0;
                    foreach ($chapter['topics'] as $topicItem) {
                        if (!empty($completedLessonsMap[$topicItem['code']])) {
                            $completedTopicCount++;
                        }
                    }
                    $totalTopicCount = count($chapter['topics']);
                    $chapterPercent = $totalTopicCount > 0 ? round(($completedTopicCount / $totalTopicCount) * 100) : 0;
                    $previousItemComplete = $isChapterUnlocked;
                @endphp

                <div class="chapter-card-accent group relative rounded-[2rem] flex flex-col transition-all duration-500 {{ $cardStyle }}">
                    @if(!$isChapterUnlocked)
                        <div class="absolute inset-0 z-30 flex flex-col items-center justify-center bg-slate-100/60 dark:bg-[#020617]/60 backdrop-blur-[2px] rounded-[2rem] transition-colors">
                            <div class="w-14 h-14 rounded-full bg-slate-200/80 dark:bg-black/60 border border-slate-300 dark:border-white/10 flex items-center justify-center mb-4 shadow-md dark:shadow-xl transition-colors">
                                <svg class="w-6 h-6 text-slate-500 dark:text-white/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <div class="bg-slate-200/90 dark:bg-black/80 px-4 py-2 rounded-full border border-slate-300 dark:border-white/10 flex items-center gap-2 transition-colors">
                                <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                                <span class="text-xs font-bold text-slate-600 dark:text-white/60">Selesaikan Bab Sebelumnya</span>
                            </div>
                        </div>
                    @endif

                    <div class="p-8 pb-0">
                        <div class="flex justify-between items-start mb-6">
                            <span class="text-[10px] font-black tracking-widest px-3 py-1.5 rounded-lg border transition-colors {{ $isChapterUnlocked ? $chapterTheme['badge'] : 'bg-slate-100 dark:bg-white/5 border-slate-200 dark:border-white/5 text-slate-400 dark:text-white/30' }}">
                                BAB {{ $chapter['number'] }}
                            </span>
                            <span class="text-5xl font-black text-slate-200/50 dark:text-white/5 absolute top-6 right-8 group-hover:text-slate-200 dark:group-hover:text-white/10 transition-colors">{{ $chapter['number'] }}</span>
                        </div>

                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-2 leading-tight transition-colors">{{ $chapter['title'] }}</h2>
                        <p class="text-sm font-medium {{ $chapterTheme['text'] }} mb-3 opacity-90 dark:opacity-80 transition-colors">{{ $chapter['subtitle'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2 transition-colors">{{ $chapter['desc'] }}</p>

                        <div class="mt-5">
                            <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-white/40 mb-2">
                                <span>Progress Bab</span>
                                <span>{{ $completedTopicCount }}/{{ $totalTopicCount }} Materi</span>
                            </div>
                            <div class="h-1.5 rounded-full bg-slate-200 dark:bg-white/10 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r {{ $chapterTheme['bar'] }} transition-all duration-700" style="width: {{ $chapterPercent }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 flex-1">
                        <div class="space-y-0 relative">
                            <div class="absolute left-[7px] top-2 bottom-2 w-px bg-gradient-to-b from-slate-300 dark:from-white/10 via-slate-200 dark:via-white/5 to-transparent transition-colors"></div>

                            @foreach($chapter['topics'] as $topic)
                                @php
                                    $isCompleted = !empty($completedLessonsMap[$topic['code']]);
                                    $isAccessible = $isAdmin || ($isChapterUnlocked && $previousItemComplete);
                                    $previousItemComplete = $isCompleted;
                                    $topicUrl = $routeUrl($topic['route']);
                                @endphp

                                <div class="relative pl-6 py-2.5 group/lesson">
                                    <div class="absolute left-[3px] top-[18px] w-2 h-2 rounded-full border-2 border-white dark:border-[#0f172a] z-10 transition-all duration-300 {{ $isCompleted ? 'bg-emerald-500 border-emerald-500 shadow-sm dark:shadow-[0_0_10px_#10b981]' : ($isAccessible ? $chapterTheme['dot'] . ' animate-pulse' : 'bg-slate-300 dark:bg-slate-700 border-slate-300 dark:border-slate-700') }}"></div>

                                    @if($isAccessible && $canAccessLearning)
                                        <a href="{{ $topicUrl }}" class="flex items-center justify-between group-hover/lesson:translate-x-1 transition-transform duration-300">
                                            <div>
                                                <div class="text-sm font-bold transition-colors {{ $isCompleted ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-700 dark:text-slate-200 group-hover/lesson:text-slate-900 dark:group-hover/lesson:text-white' }}">
                                                    <span class="font-mono text-[10px] opacity-60 dark:opacity-40 mr-2">{{ $topic['code'] }}</span>
                                                    {{ $topic['name'] }}
                                                </div>
                                                <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 ml-8 transition-colors">{{ $topic['time'] }} · Lesson {{ $topic['range'] }}</div>
                                            </div>

                                            @if($isCompleted)
                                                <div class="bg-emerald-50 dark:bg-emerald-500/10 p-1 rounded-full border border-emerald-200 dark:border-emerald-500/20 transition-colors">
                                                    <svg class="w-3 h-3 text-emerald-600 dark:text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                </div>
                                            @else
                                                <svg class="w-4 h-4 text-slate-300 dark:text-white/20 group-hover/lesson:text-slate-500 dark:group-hover/lesson:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            @endif
                                        </a>
                                    @elseif($isAccessible)
                                        <button type="button" onclick="openAccessModal()" class="w-full text-left flex items-center justify-between group-hover/lesson:translate-x-1 transition-transform duration-300">
                                            <div>
                                                <div class="text-sm font-bold transition-colors text-slate-700 dark:text-slate-200 group-hover/lesson:text-slate-900 dark:group-hover/lesson:text-white">
                                                    <span class="font-mono text-[10px] opacity-60 dark:opacity-40 mr-2">{{ $topic['code'] }}</span>
                                                    {{ $topic['name'] }}
                                                </div>
                                                <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 ml-8 transition-colors">{{ $topic['time'] }} · Lesson {{ $topic['range'] }}</div>
                                            </div>

                                            <div class="bg-cyan-50 dark:bg-cyan-500/10 px-2 py-1 rounded-full border border-cyan-200 dark:border-cyan-500/20 text-[9px] font-black text-cyan-700 dark:text-cyan-300 uppercase tracking-widest">
                                                Login
                                            </div>
                                        </button>
                                    @else
                                        <div class="flex items-center justify-between opacity-50 dark:opacity-40 cursor-not-allowed">
                                            <div>
                                                <div class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">
                                                    <span class="font-mono text-[10px] opacity-50 dark:opacity-30 mr-2">{{ $topic['code'] }}</span>
                                                    {{ $topic['name'] }}
                                                </div>
                                                <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 ml-8 transition-colors">{{ $topic['time'] }} · Lesson {{ $topic['range'] }}</div>
                                            </div>
                                            <svg class="w-3 h-3 text-slate-400 dark:text-slate-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @php
                        $areAllLessonsDone = !empty($completedLessonsMap[$chapter['last_lesson_code']]);
                        $isLabPassed = $isPassedLab($chapter['lab_id']);
                        $canAccessLab = $isAdmin || $areAllLessonsDone;
                        $labLink = $canAccessLab ? $routeUrl('lab.start', ['id' => $chapter['lab_id']]) : '#';

                        if ($isLabPassed) {
                            $labBorder = 'border-emerald-300 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/5';
                            $labIconBg = 'bg-emerald-500 text-white shadow-emerald-500/20';
                            $labIcon = '✔';
                            $labStatusText = 'LAB SELESAI';
                        } elseif ($canAccessLab) {
                            $labBorder = 'border-amber-300 dark:border-amber-500/40 bg-amber-50 dark:bg-amber-500/5 hover:bg-amber-100 dark:hover:bg-amber-500/10 cursor-pointer';
                            $labIconBg = 'bg-amber-500 text-white shadow-amber-500/20 animate-pulse';
                            $labIcon = '⚡';
                            $labStatusText = 'MULAI LAB';
                        } else {
                            $labBorder = 'border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02] opacity-60 dark:opacity-50 cursor-not-allowed';
                            $labIconBg = 'bg-slate-200 dark:bg-white/10 text-slate-400 dark:text-white/20';
                            $labIcon = '🔒';
                            $labStatusText = 'SELESAIKAN MATERI';
                        }
                    @endphp

                    <div class="px-6 pb-2">
                        <button type="button" onclick="{{ ($canAccessLab && $canAccessLearning) ? "location.href='" . e($labLink) . "'" : ($canAccessLab ? 'openAccessModal()' : 'return false;') }}" class="w-full flex items-center gap-4 p-3 rounded-xl border transition-all duration-300 group/lab {{ $labBorder }}">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-sm shadow-md transition-transform group-hover/lab:scale-105 {{ $labIconBg }}">
                                {{ $labIcon }}
                            </div>
                            <div class="flex-1 text-left min-w-0">
                                <div class="text-[10px] font-bold tracking-wider opacity-80 dark:opacity-60 mb-0.5 transition-colors {{ $canAccessLab ? 'text-amber-600 dark:text-amber-400' : 'text-slate-500' }}">{{ $labStatusText }}</div>
                                <div class="text-xs font-bold text-slate-800 dark:text-white truncate transition-colors">{{ $chapter['lab_title'] }}</div>
                            </div>
                            @if($canAccessLab)
                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 group-hover/lab:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            @endif
                        </button>
                    </div>

                    @php
                        $canTakeQuiz = $isAdmin || $isLabPassed;
                        $isQuizPassed = !empty($completedLessonsMap[$chapter['quiz_key_db']]);
                    @endphp

                    <div class="p-6 pt-2 border-t border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02] mt-2 rounded-b-[2rem] transition-colors">
                        @if($isQuizPassed)
                            <div class="w-full py-3.5 rounded-xl bg-emerald-100 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-xs font-bold uppercase tracking-widest flex justify-center items-center gap-2 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Bab Terselesaikan</span>
                            </div>
                        @elseif($canTakeQuiz && $canAccessLearning)
                            <a href="{{ $routeUrl('quiz.intro', ['chapterId' => $chapter['quiz_id']]) }}" class="group/btn w-full py-3.5 rounded-xl bg-gradient-to-r {{ $chapterTheme['button'] }} hover:scale-[1.02] active:scale-[0.98] text-white text-xs font-bold uppercase tracking-widest shadow-md dark:shadow-lg transition-all flex justify-center items-center gap-2">
                                <span>Mulai Kuis</span>
                                <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        @elseif($canTakeQuiz)
                            <button type="button" onclick="openAccessModal()" class="group/btn w-full py-3.5 rounded-xl bg-gradient-to-r {{ $chapterTheme['button'] }} hover:scale-[1.02] active:scale-[0.98] text-white text-xs font-bold uppercase tracking-widest shadow-md dark:shadow-lg transition-all flex justify-center items-center gap-2">
                                <span>Buka Akses Kuis</span>
                                <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </button>
                        @else
                            <button disabled class="w-full py-3.5 rounded-xl border border-slate-200 dark:border-white/5 bg-slate-100 dark:bg-white/5 text-slate-400 dark:text-slate-500 text-xs font-bold uppercase tracking-widest flex justify-center items-center gap-2 cursor-not-allowed transition-colors">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span>{{ $canAccessLab ? 'Selesaikan Praktik Dulu' : 'Materi Belum Selesai' }}</span>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @php
            /*
            |--------------------------------------------------------------------------
            | Referensi PDF Nyata
            |--------------------------------------------------------------------------
            | Simpan tiga file PDF yang diunggah pada folder:
            | public/references/
            | dengan nama file persis seperti nilai `file` di bawah.
            */
            $referenceBooks = [
                [
                    'title' => 'Modern CSS with Tailwind',
                    'subtitle' => 'Flexible Styling Without the Fuss',
                    'author' => 'Noel Rappin',
                    'year' => '2021',
                    'publisher' => 'The Pragmatic Bookshelf',
                    'pages' => '90 halaman',
                    'description' => 'Membahas utility-first, tipografi, layout, animasi, desain responsif, dan konfigurasi Tailwind CSS.',
                    'file' => 'references/Modern CSS with Tailwind_ Flexible Styling Without the Fuss(4).pdf',
                    'tone' => 'cyan',
                    'cover' => 'from-cyan-500 to-blue-600 dark:from-cyan-500 dark:to-blue-500',
                    'ring' => 'border-cyan-200 dark:border-cyan-400/20',
                    'text' => 'text-cyan-700 dark:text-cyan-300',
                    'soft' => 'bg-cyan-50 dark:bg-cyan-500/10',
                ],
                [
                    'title' => 'Tailwind CSS',
                    'subtitle' => 'Craft Beautiful, Flexible, and Responsive Designs',
                    'author' => 'Ivaylo Gerchev',
                    'year' => '2022',
                    'publisher' => 'SitePoint',
                    'pages' => '108 halaman',
                    'description' => 'Rujukan untuk memahami utility class, desain responsif, design system, dan penerapan Tailwind CSS dalam antarmuka web.',
                    'file' => 'references/Tailwind CSS(4).pdf',
                    'tone' => 'indigo',
                    'cover' => 'from-indigo-500 to-violet-600 dark:from-indigo-500 dark:to-violet-500',
                    'ring' => 'border-indigo-200 dark:border-indigo-400/20',
                    'text' => 'text-indigo-700 dark:text-indigo-300',
                    'soft' => 'bg-indigo-50 dark:bg-indigo-500/10',
                ],
                [
                    'title' => 'Ultimate Tailwind CSS Handbook',
                    'subtitle' => 'Build Sleek and Modern Websites with Immersive UIs',
                    'author' => 'Kartik Bhat',
                    'year' => '2023',
                    'publisher' => 'Orange Education / AVA',
                    'pages' => '298 halaman',
                    'description' => 'Buku pendamping untuk HTML, CSS, konfigurasi Tailwind, layout, styling komponen, praktik pengembangan web, dan best practice.',
                    'file' => 'references/Ultimate Tailwind CSS Handbook_ Build sleek and modern websites with immersive UIs using Tailwind CSS(4).pdf',
                    'tone' => 'fuchsia',
                    'cover' => 'from-fuchsia-500 to-rose-600 dark:from-fuchsia-500 dark:to-rose-500',
                    'ring' => 'border-fuchsia-200 dark:border-fuchsia-400/20',
                    'text' => 'text-fuchsia-700 dark:text-fuchsia-300',
                    'soft' => 'bg-fuchsia-50 dark:bg-fuchsia-500/10',
                ],
            ];
        @endphp

        <section class="mt-32 border-t border-slate-200 pt-20 transition-colors dark:border-white/5 reveal-up delay-500" aria-labelledby="reference-title">
            <div class="mb-10 flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-100 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-slate-500 transition-colors dark:border-white/10 dark:bg-slate-800 dark:text-slate-400">
                        Sumber Belajar
                    </div>
                    <h2 id="reference-title" class="mt-4 text-3xl font-black text-slate-900 transition-colors dark:text-white">Referensi Pembelajaran</h2>
                    <p class="mt-2 max-w-2xl text-sm text-slate-500 transition-colors dark:text-slate-400">
                        Tiga buku PDF berikut tersedia sebagai bahan pendukung pembelajaran. Pilih <strong class="font-bold text-slate-700 dark:text-slate-200">Buka PDF</strong> untuk membaca dokumen pada tab baru.
                    </p>
                </div>

                <div class="inline-flex items-center gap-2 self-start rounded-xl border border-slate-200 bg-white/70 px-3.5 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-500 shadow-sm backdrop-blur-sm transition-colors dark:border-white/10 dark:bg-white/5 dark:text-slate-400 md:self-auto">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    3 Dokumen PDF
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach($referenceBooks as $book)
                    <article class="glass-card group flex h-full flex-col rounded-2xl p-5 transition-all duration-300 hover:-translate-y-1 hover:bg-slate-50 hover:shadow-lg dark:hover:bg-white/5">
                        <div class="flex items-start gap-4">
                            <div class="relative grid h-24 w-[72px] shrink-0 place-items-center overflow-hidden rounded-xl border {{ $book['ring'] }} bg-gradient-to-br {{ $book['cover'] }} shadow-md transition-transform duration-300 group-hover:scale-[1.03]">
                                <svg class="h-8 w-8 text-white/95" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 2.5H6.75A1.75 1.75 0 005 4.25v15.5c0 .966.784 1.75 1.75 1.75h10.5A1.75 1.75 0 0019 19.75V7.5L14 2.5z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 2.5V7.5h5M8.5 12h7M8.5 15.5h7"/>
                                </svg>
                                <span class="absolute bottom-2 rounded-full bg-slate-950/20 px-1.5 py-0.5 text-[8px] font-black tracking-[.16em] text-white">PDF</span>
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] font-bold uppercase tracking-[.14em] {{ $book['text'] }}">Buku Referensi</p>
                                <h3 class="mt-1 text-[15px] font-black leading-5 text-slate-900 transition-colors dark:text-white">{{ $book['title'] }}</h3>
                                <p class="mt-1 text-[11px] font-medium leading-5 text-slate-500 transition-colors dark:text-slate-400">{{ $book['subtitle'] }}</p>
                                <p class="mt-2 text-[11px] font-mono text-slate-500 transition-colors dark:text-slate-400">{{ $book['author'] }} · {{ $book['year'] }}</p>
                            </div>
                        </div>

                        <p class="mt-5 text-[11px] leading-5 text-slate-600 transition-colors dark:text-slate-400">{{ $book['description'] }}</p>

                        <div class="mt-5 flex items-center justify-between gap-3 border-t border-slate-200 pt-4 text-[10px] font-bold text-slate-500 transition-colors dark:border-white/10 dark:text-slate-400">
                            <span>{{ $book['pages'] }}</span>
                            <span class="truncate text-right">{{ $book['publisher'] }}</span>
                        </div>

                        <div class="mt-4 grid grid-cols-[minmax(0,1fr)_42px] gap-2">
                            <a href="{{ asset($book['file']) }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl px-3 text-[11px] font-bold text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 {{ $book['cover'] }}">
                                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m0 0l-4-4m4 4l4-4M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                                </svg>
                                Buka PDF
                            </a>

                            <a href="{{ asset($book['file']) }}" download
                               class="inline-flex min-h-10 items-center justify-center rounded-xl border {{ $book['ring'] }} {{ $book['soft'] }} {{ $book['text'] }} transition-all duration-200 hover:-translate-y-0.5"
                               title="Unduh {{ $book['title'] }}" aria-label="Unduh {{ $book['title'] }}">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16.5v2A1.5 1.5 0 005.5 20h13a1.5 1.5 0 001.5-1.5v-2M12 4v11m0 0l-4-4m4 4l4-4"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    </div>
</div>

<div id="accessModal" class="fixed inset-0 z-[120] hidden items-center justify-center p-4 sm:p-6 opacity-0 transition-opacity duration-300">
    <div class="absolute inset-0 bg-slate-950/60 dark:bg-[#020617]/80 backdrop-blur-md cursor-pointer transition-opacity" onclick="closeAccessModal()"></div>

    <div id="accessContent" class="relative w-full max-w-lg transform scale-95 translate-y-4 transition-all duration-300 ease-out">
        <div class="relative glass-card rounded-[2rem] p-8 md:p-9 border border-cyan-200 dark:border-cyan-500/20 shadow-2xl overflow-hidden">
            <div class="absolute -top-20 -right-16 w-56 h-56 bg-cyan-300/25 dark:bg-cyan-500/10 rounded-full blur-[70px] pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-16 w-56 h-56 bg-indigo-300/20 dark:bg-indigo-500/10 rounded-full blur-[80px] pointer-events-none"></div>

            <button onclick="closeAccessModal()" class="absolute top-5 right-5 p-2 rounded-full hover:bg-slate-100 dark:hover:bg-white/5 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-all focus:outline-none z-10">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="relative z-10">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-500 to-indigo-600 text-white shadow-lg shadow-cyan-500/20 flex items-center justify-center mb-6">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M12 11c1.657 0 3-1.343 3-3V7a3 3 0 10-6 0v1c0 1.657 1.343 3 3 3zm-7 2.5A2.5 2.5 0 017.5 11h9a2.5 2.5 0 012.5 2.5v4A2.5 2.5 0 0116.5 20h-9A2.5 2.5 0 015 17.5v-4z"/></svg>
                </div>

                <p class="text-[10px] font-black uppercase tracking-[0.24em] text-cyan-700 dark:text-cyan-300 mb-2">Akses Pembelajaran</p>
                <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white leading-tight">
                    {{ $accessRequirement['title'] }}
                </h3>
                <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed mt-4">
                    {{ $accessRequirement['message'] }}
                </p>

                <div class="mt-5 rounded-2xl border border-slate-200 dark:border-white/10 bg-slate-50/80 dark:bg-white/[0.04] p-4">
                    <p class="text-[10px] uppercase tracking-widest font-black text-slate-400 dark:text-slate-500 mb-1">Yang perlu dilakukan</p>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $accessRequirement['action'] }}</p>
                </div>

                <div class="mt-7 flex flex-col sm:flex-row gap-3">
                    @guest
                        <a href="{{ route('login') }}" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-200 px-5 py-3 text-sm font-black text-white dark:text-slate-950 transition-colors">
                            Login
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="{{ route('register') }}" class="flex-1 inline-flex items-center justify-center rounded-xl border border-slate-200 dark:border-white/10 bg-white/70 dark:bg-white/5 px-5 py-3 text-sm font-black text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-white/10 transition-colors">
                            Buat Akun
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-200 px-5 py-3 text-sm font-black text-white dark:text-slate-950 transition-colors">
                            Buka Dasbor
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    @endguest
                    <button type="button" onclick="closeAccessModal()" class="flex-1 rounded-xl border border-slate-200 dark:border-white/10 bg-white/70 dark:bg-white/5 px-5 py-3 text-sm font-black text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-white/10 transition-colors">
                        Tetap Lihat Silabus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="infoModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6 opacity-0 transition-opacity duration-300">
    <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#020617]/70 backdrop-blur-sm cursor-pointer transition-opacity" onclick="closeInfoModal()"></div>

    <div id="infoContent" class="relative w-full max-w-xl transform scale-95 translate-y-4 transition-all duration-300 ease-out">
        <div class="relative glass-card rounded-2xl p-8 md:p-10 border border-slate-200 dark:border-white/10 shadow-xl dark:shadow-2xl">
            <button onclick="closeInfoModal()" class="absolute top-5 right-5 p-2 rounded-full hover:bg-slate-100 dark:hover:bg-white/5 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-all focus:outline-none">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 bg-slate-100 dark:bg-white/5 rounded-xl border border-slate-200 dark:border-white/10 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-slate-700 dark:text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white leading-tight">Informasi Alur Pembelajaran</h3>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">Urutan Materi Tailwind CSS</p>
                </div>
            </div>

            <div class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-medium mb-8">
                Halaman ini memuat daftar materi utama yang disusun berurutan. Setiap bab memiliki materi, praktik, dan evaluasi agar progres belajar dapat dipantau dengan jelas.
            </div>

            <div class="space-y-3">
                <div class="flex items-start gap-3 p-4 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-white/5">
                    <span class="text-slate-400 dark:text-slate-500 mt-0.5 font-mono">01</span>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Materi Bacaan</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Materi utama berisi konsep, contoh, dan simulasi singkat sesuai urutan subbab.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-white/5">
                    <span class="text-slate-400 dark:text-slate-500 mt-0.5 font-mono">02</span>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Kegiatan Praktik</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Aktivitas praktik digunakan untuk menguji pemahaman sebelum membuka tahapan berikutnya.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-white/5">
                    <span class="text-slate-400 dark:text-slate-500 mt-0.5 font-mono">03</span>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Evaluasi</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Evaluasi bab digunakan sebagai syarat progres menuju bab berikutnya.</p>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <button onclick="closeInfoModal()" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-200 text-white dark:text-slate-900 font-bold text-sm rounded-lg transition-colors">
                    Tutup Panduan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openAccessModal() {
        const modal = document.getElementById('accessModal');
        const content = document.getElementById('accessContent');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95', 'translate-y-4');
        }, 10);
    }

    function closeAccessModal() {
        const modal = document.getElementById('accessModal');
        const content = document.getElementById('accessContent');

        modal.classList.add('opacity-0');
        content.classList.add('scale-95', 'translate-y-4');

        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 300);
    }

    function openInfoModal() {
        const modal = document.getElementById('infoModal');
        const content = document.getElementById('infoContent');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95', 'translate-y-4');
        }, 10);
    }

    function closeInfoModal() {
        const modal = document.getElementById('infoModal');
        const content = document.getElementById('infoContent');

        modal.classList.add('opacity-0');
        content.classList.add('scale-95', 'translate-y-4');

        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection
