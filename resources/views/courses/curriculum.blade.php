@extends('layouts.landing')
@section('title', 'Materi')

@section('content')

{{-- 
    ============================================================================
    KONFIGURASI DATA BAB, MATERI, DAN LAB (100% UTUH)
    ============================================================================
--}}
@php
    $chapters = [
        [
            'id' => 1, 
            'number' => '01', 
            'title' => 'PENDAHULUAN', 
            'subtitle' => 'The Foundation',
            'desc' => 'Pelajari filosofi utility-first, sejarah CSS modern, dan instalasi Tailwind dari nol.', 
            'color' => 'cyan', 
            
            // Logic Prasyarat Bab
            'quiz_req_prev' => null, 
            
            // Logic Materi
            'last_lesson_code' => '1.6', 
            
            // Logic Lab
            'lab_id' => 1, 
            'lab_title' => 'Setup Environment & Config',
            
            // Logic Kuis
            'quiz_id' => 1,
            'quiz_key_db' => 'quiz_1', 
            
            'topics' => [
                ['code' => '1.1', 'name' => 'Konsep HTML & CSS', 'route' => 'courses.htmldancss', 'time' => '10 min'],
                ['code' => '1.2', 'name' => 'Konsep Dasar Tailwind', 'route' => 'courses.tailwindcss', 'time' => '15 min'],
                ['code' => '1.3', 'name' => 'Latar Belakang & Struktur', 'route' => 'courses.latarbelakang', 'time' => '12 min'],
                ['code' => '1.4', 'name' => 'Implementasi pada HTML', 'route' => 'courses.implementation', 'time' => '20 min'],
                ['code' => '1.5', 'name' => 'Keunggulan & Utilitas', 'route' => 'courses.advantages', 'time' => '10 min'],
                ['code' => '1.6', 'name' => 'Instalasi & Konfigurasi', 'route' => 'courses.installation', 'time' => '25 min'],
            ]
        ],
        [
            'id' => 2, 
            'number' => '02', 
            'title' => 'LAYOUTING', 
            'subtitle' => 'Modern Layouts',
            'desc' => 'Kuasai teknik tata letak modern menggunakan Flexbox dan Grid System yang responsif.', 
            'color' => 'indigo', 
            
            'quiz_req_prev' => 'quiz_1', 
            'last_lesson_code' => '2.3',
            
            'lab_id' => 2,
            'lab_title' => 'Building Responsive Grid',
            
            'quiz_id' => 2,
            'quiz_key_db' => 'quiz_2',
            
            'topics' => [
                ['code' => '2.1', 'name' => 'Flexbox Architecture', 'route' => 'courses.flexbox', 'time' => '30 min'],
                ['code' => '2.2', 'name' => 'Grid System Mastery', 'route' => 'courses.grid', 'time' => '35 min'],
                ['code' => '2.3', 'name' => 'Layout Management', 'route' => 'courses.layout-mgmt', 'time' => '20 min'],
            ]
        ],
        [
            'id' => 3, 
            'number' => '03', 
            'title' => 'STYLING', 
            'subtitle' => 'Visual Arts',
            'desc' => 'Teknik dekorasi tingkat lanjut, tipografi, filter efek, dan animasi interaktif.', 
            'color' => 'fuchsia', 
            
            'quiz_req_prev' => 'quiz_2', 
            'last_lesson_code' => '3.4',
            
            'lab_id' => 3,
            'lab_title' => 'Styling Components',
            
            'quiz_id' => 3,
            'quiz_key_db' => 'quiz_3',
            
            'topics' => [
                ['code' => '3.1', 'name' => 'Tipografi & Font', 'route' => 'courses.typography', 'time' => '15 min'],
                ['code' => '3.2', 'name' => 'Backgrounds', 'route' => 'courses.backgrounds', 'time' => '15 min'],
                ['code' => '3.3', 'name' => 'Borders & Rings', 'route' => 'courses.borders', 'time' => '20 min'],
                ['code' => '3.4', 'name' => 'Effects & Filters', 'route' => 'courses.effects', 'time' => '25 min'],
            ]
        ]
    ];

    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
    $previousItemComplete = true; 
@endphp

{{-- CSS Custom Dinamis --}}
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
    }
    .dark .glass-card {
        background: rgba(15, 23, 42, 0.65);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }

    .glass-card-locked {
        background: rgba(240, 240, 240, 0.6);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(0, 0, 0, 0.03);
    }
    .dark .glass-card-locked {
        background: rgba(10, 10, 15, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.03);
    }

    .reveal-up { opacity: 0; transform: translateY(30px); animation: reveal 0.8s cubic-bezier(0.2, 1, 0.3, 1) forwards; }
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    @keyframes reveal { to { opacity: 1; transform: translateY(0); } }
    .writing-vertical-rl { writing-mode: vertical-rl; }
</style>

<div class="relative min-h-screen bg-slate-50 dark:bg-[#020617] text-slate-800 dark:text-white font-sans overflow-x-hidden pt-28 pb-32 transition-colors duration-500">

    {{-- DYNAMIC BACKGROUND --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[1000px] h-[1000px] bg-indigo-300/30 dark:bg-indigo-900/20 rounded-full blur-[120px] animate-pulse transition-colors duration-500"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[800px] h-[800px] bg-cyan-300/30 dark:bg-cyan-900/10 rounded-full blur-[100px] transition-colors duration-500"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02] dark:opacity-[0.03] transition-opacity duration-500"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">

        {{-- HERO SECTION --}}
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-24 gap-12 reveal-up">
            <div class="space-y-4 max-w-3xl w-full">
                
                {{-- BREADCRUMB --}}
                <nav class="flex items-center gap-2 mb-2 text-[10px] md:text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-white/40 transition-colors">
                    <a href="/" class="hover:text-slate-900 dark:hover:text-white transition-colors flex items-center gap-1.5">
                        <svg class="w-3 h-3 md:w-3.5 md:h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Home
                    </a>
                    <span class="text-slate-300 dark:text-white/20 transition-colors">/</span>
                    <a href="{{ route('dashboard') }}" class="hover:text-slate-900 dark:hover:text-white transition-colors">Dashboard</a>
                    <span class="text-slate-300 dark:text-white/20 transition-colors">/</span>
                    <span class="text-cyan-600 dark:text-cyan-400 drop-shadow-none dark:drop-shadow-[0_0_8px_rgba(34,211,238,0.5)] transition-colors">Materi</span>
                </nav>

                {{-- HEADLINE & TOOLTIP TRIGGER --}}
                <div class="flex items-start gap-4">
                    <h1 class="text-5xl md:text-7xl font-black text-slate-900 dark:text-white leading-tight tracking-tight transition-colors">
                        Silabus <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 via-indigo-600 to-fuchsia-600 dark:from-cyan-400 dark:via-indigo-400 dark:to-fuchsia-400">
                            Utilwind
                        </span>
                    </h1>
                    <button onclick="openInfoModal()" class="mt-4 md:mt-6 w-8 h-8 md:w-10 md:h-10 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-sm md:text-lg font-black text-slate-400 dark:text-slate-500 hover:text-cyan-600 dark:hover:text-cyan-400 bg-white/50 dark:bg-white/5 backdrop-blur-sm hover:bg-white dark:hover:bg-white/10 hover:border-cyan-200 dark:hover:border-cyan-500/30 transition-all duration-300 shadow-sm hover:shadow-md focus:outline-none shrink-0" title="Informasi Silabus">
                        ?
                    </button>
                </div>
                
                <p class="text-slate-500 dark:text-slate-400 text-lg md:text-xl leading-relaxed transition-colors mt-2">
                    Panduan belajar disusun secara berurutan. Pelajari setiap materi bacaan, ikuti kegiatan praktik, dan selesaikan evaluasi untuk membuka akses ke bab selanjutnya.
                </p>

                @if($isAdmin)
                <div class="inline-block mt-2 bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 px-3 py-1 rounded text-xs font-bold uppercase tracking-widest">
                     Admin Override Active: All Unlocked
                </div>
                @endif
            </div>

            {{-- Progress Stats --}}
            <div class="w-full lg:w-96 glass-card rounded-3xl p-8 relative overflow-hidden group hover:border-cyan-400 dark:hover:border-cyan-500/30 transition duration-500 shrink-0 shadow-lg dark:shadow-none">
                <div class="absolute -right-12 -top-12 w-40 h-40 bg-cyan-400/20 dark:bg-cyan-500/10 rounded-full blur-3xl group-hover:bg-cyan-500/30 dark:group-hover:bg-cyan-500/20 transition duration-500"></div>
                <div class="flex justify-between items-end mb-6">
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1 transition-colors">Total Progress</p>
                        <h3 class="text-5xl font-black text-slate-900 dark:text-white tracking-tight transition-colors">{{ $progressPercent ?? 0 }}<span class="text-2xl text-slate-400 dark:text-slate-500 transition-colors">%</span></h3>
                    </div>
                    <div class="text-right">
                        <svg class="w-10 h-10 text-cyan-500 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                </div>
                <div class="w-full h-3 bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden border border-slate-300 dark:border-white/5 transition-colors">
                    <div class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 dark:from-cyan-500 dark:to-blue-600 relative overflow-hidden transition-all duration-1000 ease-out" style="width: {{ $progressPercent ?? 0 }}%">
                        <div class="absolute inset-0 bg-white/30 dark:bg-white/20 animate-[shimmer_2s_infinite]"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CURRICULUM GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 reveal-up delay-200">
            
            @foreach($chapters as $chapter)
                @php
                    $isChapterUnlocked = true;
                    if ($chapter['quiz_req_prev']) {
                        $isChapterUnlocked = $isAdmin || !empty($completedLessonsMap[$chapter['quiz_req_prev']]);
                    }

                    if ($isChapterUnlocked) {
                        $previousItemComplete = true; 
                    } else {
                        $previousItemComplete = false;
                    }

                    $cardStyle = $isChapterUnlocked ? 'glass-card hover:-translate-y-2 shadow-md dark:shadow-none' : 'glass-card-locked opacity-70 grayscale shadow-sm dark:shadow-none';
                    $borderColor = $isChapterUnlocked ? "group-hover:border-{$chapter['color']}-400 dark:group-hover:border-{$chapter['color']}-500/50" : 'border-slate-200 dark:border-white/5';
                    $textColor = "text-{$chapter['color']}-600 dark:text-{$chapter['color']}-400";
                    $bgBadge = "bg-{$chapter['color']}-50 dark:bg-{$chapter['color']}-500/10 border-{$chapter['color']}-200 dark:border-{$chapter['color']}-500/20";
                @endphp

                <div class="group relative rounded-[2rem] flex flex-col transition-all duration-500 {{ $cardStyle }} {{ $borderColor }}">
                    
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
                            <span class="text-[10px] font-black tracking-widest px-3 py-1.5 rounded-lg border transition-colors {{ $isChapterUnlocked ? $bgBadge : 'bg-slate-100 dark:bg-white/5 border-slate-200 dark:border-white/5 text-slate-400 dark:text-white/30' }} {{ $isChapterUnlocked ? $textColor : '' }}">
                                BAB {{ $chapter['number'] }}
                            </span>
                            <span class="text-5xl font-black text-slate-200/50 dark:text-white/5 absolute top-6 right-8 group-hover:text-slate-200 dark:group-hover:text-white/10 transition-colors">{{ $chapter['number'] }}</span>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-2 leading-tight transition-colors">{{ $chapter['title'] }}</h2>
                        <p class="text-sm font-medium {{ $textColor }} mb-3 opacity-90 dark:opacity-80 transition-colors">{{ $chapter['subtitle'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2 transition-colors">{{ $chapter['desc'] }}</p>
                    </div>

                    <div class="p-8 flex-1">
                        <div class="space-y-0 relative">
                            <div class="absolute left-[7px] top-2 bottom-2 w-px bg-gradient-to-b from-slate-300 dark:from-white/10 via-slate-200 dark:via-white/5 to-transparent transition-colors"></div>

                            @foreach($chapter['topics'] as $topic)
                                @php
                                    $isCompleted = !empty($completedLessonsMap[$topic['code']]);
                                    $isAccessible = $isAdmin || ($isChapterUnlocked && $previousItemComplete);
                                    $previousItemComplete = $isCompleted; 
                                @endphp

                                <div class="relative pl-6 py-2.5 group/lesson">
                                    <div class="absolute left-[3px] top-[18px] w-2 h-2 rounded-full border-2 border-white dark:border-[#0f172a] z-10 transition-all duration-300
                                        {{ $isCompleted ? 'bg-emerald-500 border-emerald-500 shadow-sm dark:shadow-[0_0_10px_#10b981]' : ($isAccessible ? "bg-{$chapter['color']}-500 border-{$chapter['color']}-500 animate-pulse" : 'bg-slate-300 dark:bg-slate-700 border-slate-300 dark:border-slate-700') }}">
                                    </div>

                                    @if($isAccessible)
                                        <a href="{{ route($topic['route']) }}" class="flex items-center justify-between group-hover/lesson:translate-x-1 transition-transform duration-300">
                                            <div>
                                                <div class="text-sm font-bold transition-colors {{ $isCompleted ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-700 dark:text-slate-200 group-hover/lesson:text-slate-900 dark:group-hover/lesson:text-white' }}">
                                                    <span class="font-mono text-[10px] opacity-60 dark:opacity-40 mr-2">{{ $topic['code'] }}</span>
                                                    {{ $topic['name'] }}
                                                </div>
                                                <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 ml-8 transition-colors">{{ $topic['time'] }}</div>
                                            </div>
                                            @if($isCompleted)
                                                <div class="bg-emerald-50 dark:bg-emerald-500/10 p-1 rounded-full border border-emerald-200 dark:border-emerald-500/20 transition-colors">
                                                    <svg class="w-3 h-3 text-emerald-600 dark:text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                </div>
                                            @else
                                                <svg class="w-4 h-4 text-slate-300 dark:text-white/20 group-hover/lesson:text-slate-500 dark:group-hover/lesson:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            @endif
                                        </a>
                                    @else
                                        <div class="flex items-center justify-between opacity-50 dark:opacity-40 cursor-not-allowed">
                                            <div>
                                                <div class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">
                                                    <span class="font-mono text-[10px] opacity-50 dark:opacity-30 mr-2">{{ $topic['code'] }}</span>
                                                    {{ $topic['name'] }}
                                                </div>
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
                        $isLabPassed = isset($passedLabsMap[$chapter['lab_id']]);
                        $canAccessLab = $isAdmin || $areAllLessonsDone;
                        
                        $labLink = $canAccessLab ? route('lab.start', ['id' => $chapter['lab_id']]) : '#';
                        $labStatusText = $isLabPassed ? 'LAB SELESAI' : ($canAccessLab ? 'MULAI LAB' : 'SELESAIKAN MATERI');
                        
                        if ($isLabPassed) {
                            $labBorder = "border-emerald-300 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/5";
                            $labIconBg = "bg-emerald-500 text-white shadow-emerald-500/20";
                            $labIcon = '✔';
                        } elseif ($canAccessLab) {
                            $labBorder = "border-amber-300 dark:border-amber-500/40 bg-amber-50 dark:bg-amber-500/5 hover:bg-amber-100 dark:hover:bg-amber-500/10 cursor-pointer";
                            $labIconBg = "bg-amber-500 text-white shadow-amber-500/20 animate-pulse";
                            $labIcon = '⚡';
                        } else {
                            $labBorder = "border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02] opacity-60 dark:opacity-50 cursor-not-allowed";
                            $labIconBg = "bg-slate-200 dark:bg-white/10 text-slate-400 dark:text-white/20";
                            $labIcon = '🔒';
                        }
                    @endphp

                    <div class="px-6 pb-2">
                        <button onclick="{{ $canAccessLab ? "location.href='$labLink'" : "return false;" }}"
                            class="w-full flex items-center gap-4 p-3 rounded-xl border transition-all duration-300 group/lab {{ $labBorder }}">
                            
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-sm shadow-md transition-transform group-hover/lab:scale-105 {{ $labIconBg }}">
                                {{ $labIcon }}
                            </div>
                            
                            <div class="flex-1 text-left">
                                <div class="text-[10px] font-bold tracking-wider opacity-80 dark:opacity-60 mb-0.5 transition-colors {{ $canAccessLab ? 'text-amber-600 dark:text-amber-400' : 'text-slate-500' }}">KEGIATAN PRAKTIK</div>
                                <div class="text-xs font-bold text-slate-800 dark:text-white truncate w-40 transition-colors">{{ $chapter['lab_title'] ?? 'Practical Exercise' }}</div>
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
                        @elseif($canTakeQuiz)
                            <a href="{{ route('quiz.intro', ['chapterId' => $chapter['quiz_id']]) }}" class="group/btn w-full py-3.5 rounded-xl bg-gradient-to-r from-{{ $chapter['color'] }}-500 to-indigo-500 dark:from-{{ $chapter['color'] }}-600 dark:to-indigo-600 hover:scale-[1.02] active:scale-[0.98] text-white text-xs font-bold uppercase tracking-widest shadow-md dark:shadow-lg shadow-{{ $chapter['color'] }}-500/30 dark:shadow-{{ $chapter['color'] }}-500/20 transition-all flex justify-center items-center gap-2">
                                <span>Mulai Evaluasi Akhir</span> 
                                <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
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

        {{-- REFERENCES SECTION --}}
        <div class="mt-32 pt-20 border-t border-slate-200 dark:border-white/5 reveal-up delay-500 transition-colors">
            <div class="mb-10 flex flex-col md:flex-row justify-between items-end gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 mb-4 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 text-[10px] font-bold tracking-widest uppercase transition-colors">
                        Sumber Belajar
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white transition-colors">Referensi Modul</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-2 max-w-2xl transition-colors">
                        Materi pada platform ini disusun berdasarkan literatur dan dokumentasi resmi terkini.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="#" class="group glass-card p-6 rounded-2xl flex items-start gap-5 hover:bg-slate-50 dark:hover:bg-white/5 transition-all duration-300">
                    <div class="w-16 h-20 bg-gradient-to-br from-slate-200 to-slate-400 dark:from-slate-700 dark:to-slate-900 rounded-lg shadow-md dark:shadow-xl shrink-0 flex items-center justify-center border border-slate-300 dark:border-white/10 group-hover:scale-105 transition-transform">
                        <span class="text-[10px] font-bold text-slate-600 dark:text-white/20 writing-vertical-rl rotate-180">BOOK</span>
                    </div>
                    <div>
                        <h4 class="text-slate-900 dark:text-white font-bold text-sm leading-tight group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">Modern CSS with Tailwind</h4>
                        <p class="text-[11px] text-slate-500 mt-1 font-mono">Noel Rappin</p>
                        <p class="text-[11px] text-slate-600 dark:text-slate-400 mt-3 leading-relaxed line-clamp-2 transition-colors">Flexible Styling Without the Fuss.</p>
                    </div>
                </a>
                <a href="#" class="group glass-card p-6 rounded-2xl flex items-start gap-5 hover:bg-slate-50 dark:hover:bg-white/5 transition-all duration-300">
                    <div class="w-16 h-20 bg-gradient-to-br from-indigo-200 to-slate-300 dark:from-indigo-900 dark:to-slate-900 rounded-lg shadow-md dark:shadow-xl shrink-0 flex items-center justify-center border border-slate-300 dark:border-white/10 group-hover:scale-105 transition-transform">
                        <span class="text-[10px] font-bold text-indigo-700 dark:text-white/20 writing-vertical-rl rotate-180">BOOK</span>
                    </div>
                    <div>
                        <h4 class="text-slate-900 dark:text-white font-bold text-sm leading-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Tailwind CSS (SitePoint)</h4>
                        <p class="text-[11px] text-slate-500 mt-1 font-mono">Ivaylo Gerchev</p>
                        <p class="text-[11px] text-slate-600 dark:text-slate-400 mt-3 leading-relaxed line-clamp-2 transition-colors">Craft Beautiful, Flexible, and Responsive Designs.</p>
                    </div>
                </a>
                <a href="#" class="group glass-card p-6 rounded-2xl flex items-start gap-5 hover:bg-slate-50 dark:hover:bg-white/5 transition-all duration-300">
                    <div class="w-16 h-20 bg-gradient-to-br from-fuchsia-200 to-slate-300 dark:from-fuchsia-900 dark:to-slate-900 rounded-lg shadow-md dark:shadow-xl shrink-0 flex items-center justify-center border border-slate-300 dark:border-white/10 group-hover:scale-105 transition-transform">
                        <span class="text-[10px] font-bold text-fuchsia-700 dark:text-white/20 writing-vertical-rl rotate-180">BOOK</span>
                    </div>
                    <div>
                        <h4 class="text-slate-900 dark:text-white font-bold text-sm leading-tight group-hover:text-fuchsia-600 dark:group-hover:text-fuchsia-400 transition-colors">Ultimate Handbook</h4>
                        <p class="text-[11px] text-slate-500 mt-1 font-mono">Kartik Bhat</p>
                        <p class="text-[11px] text-slate-600 dark:text-slate-400 mt-3 leading-relaxed line-clamp-2 transition-colors">Build sleek and modern websites with immersive UIs.</p>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>

{{-- ========================================================= --}}
{{-- MODAL POPUP: INFORMASI SILABUS --}}
{{-- ========================================================= --}}
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
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white leading-tight">Informasi Modul Pembelajaran</h3>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">Panduan Struktur Kurikulum Dasar</p>
                </div>
            </div>
            
            <div class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-medium mb-8">
                Halaman ini memuat hierarki pembelajaran yang disusun secara sekuensial. Setiap peserta wajib menyelesaikan tahapan berikut di masing-masing bab:
            </div>
            
            <div class="space-y-3">
                <div class="flex items-start gap-3 p-4 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-white/5">
                    <span class="text-slate-400 dark:text-slate-500 mt-0.5 font-mono">01</span>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Materi Bacaan</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Pemahaman teori dasar yang disusun secara sistematis pada setiap topik.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-white/5">
                    <span class="text-slate-400 dark:text-slate-500 mt-0.5 font-mono">02</span>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Kegiatan Praktik</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Latihan implementasi untuk memvalidasi pemahaman materi yang telah dipelajari.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-white/5">
                    <span class="text-slate-400 dark:text-slate-500 mt-0.5 font-mono">03</span>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Evaluasi </h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Uji kompetensi di penghujung bab. Akses ke bab selanjutnya mensyaratkan pencapaian nilai batas kelulusan.</p>
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

{{-- Skrip Pengendali Modal Popup --}}
<script>
    function openInfoModal() {
        const modal = document.getElementById('infoModal');
        const content = document.getElementById('infoContent');
        
        // Tampilkan elemen
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Jeda transisi
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95', 'translate-y-4');
        }, 10);
    }

    function closeInfoModal() {
        const modal = document.getElementById('infoModal');
        const content = document.getElementById('infoContent');
        
        // Putar balik transisi
        modal.classList.add('opacity-0');
        content.classList.add('scale-95', 'translate-y-4');
        
        // Sembunyikan elemen
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection