@extends('layouts.landing')
@section('title', 'Kurikulum & Peta Pembelajaran')

@section('content')

{{-- 
    ============================================================================
    KONFIGURASI DATA BAB, MATERI, DAN LAB
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
            'quiz_req_prev' => null, // Tidak ada syarat bab sebelumnya
            
            // Logic Materi
            'last_lesson_code' => '1.6', 
            
            // Logic Lab
            'lab_id' => 1, // ID Lab di Database
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
            
            'quiz_req_prev' => 'quiz_1', // Syarat: Lulus Kuis Bab 1
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
            
            'quiz_req_prev' => 'quiz_2', // Syarat: Lulus Kuis Bab 2
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

    // Logika Waterfall Awal
    $previousItemComplete = true; 
@endphp

{{-- CSS Custom --}}
<style>
    .glass-card {
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }
    .glass-card-locked {
        background: rgba(10, 10, 15, 0.6);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.03);
    }
    .reveal-up { opacity: 0; transform: translateY(30px); animation: reveal 0.8s cubic-bezier(0.2, 1, 0.3, 1) forwards; }
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    @keyframes reveal { to { opacity: 1; transform: translateY(0); } }
</style>

<div class="relative min-h-screen bg-[#020617] text-white font-sans overflow-x-hidden pt-28 pb-32">

    {{-- DYNAMIC BACKGROUND --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[1000px] h-[1000px] bg-indigo-900/20 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[800px] h-[800px] bg-cyan-900/10 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03]"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">

        {{-- HERO SECTION --}}
        <div class="flex flex-col lg:flex-row justify-between items-end mb-24 gap-12 reveal-up">
            <div class="space-y-6 max-w-3xl">
                <div class="inline-flex items-center gap-3 px-4 py-1.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-md">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                    </span>
                    <span class="text-[11px] font-bold tracking-[0.2em] text-cyan-200 uppercase">Learning Path</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-white leading-tight tracking-tight">
                    Kurikulum <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-indigo-400 to-fuchsia-400">
                        Tailwind Mastery
                    </span>
                </h1>
                <p class="text-slate-400 text-lg md:text-xl max-w-xl leading-relaxed">
                    Selesaikan Materi, kerjakan Hands-on Lab, lalu taklukkan Kuis untuk membuka level berikutnya.
                </p>
            </div>

            {{-- Progress Stats --}}
            <div class="w-full lg:w-96 glass-card rounded-3xl p-8 relative overflow-hidden group hover:border-cyan-500/30 transition duration-500">
                <div class="absolute -right-12 -top-12 w-40 h-40 bg-cyan-500/10 rounded-full blur-3xl group-hover:bg-cyan-500/20 transition duration-500"></div>
                <div class="flex justify-between items-end mb-6">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Progress</p>
                        <h3 class="text-5xl font-black text-white tracking-tight">{{ $progressPercent ?? 0 }}<span class="text-2xl text-slate-500">%</span></h3>
                    </div>
                    <div class="text-right">
                        <svg class="w-10 h-10 text-cyan-500 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                </div>
                <div class="w-full h-3 bg-slate-800 rounded-full overflow-hidden border border-white/5">
                    <div class="h-full bg-gradient-to-r from-cyan-500 to-blue-600 relative overflow-hidden transition-all duration-1000 ease-out" style="width: {{ $progressPercent ?? 0 }}%">
                        <div class="absolute inset-0 bg-white/20 animate-[shimmer_2s_infinite]"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CURRICULUM GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 reveal-up delay-200">
            
            @foreach($chapters as $chapter)
                @php
                    // --- 1. LOGIC KUNCI BAB (LEVEL) ---
                    $isChapterUnlocked = true;
                    if ($chapter['quiz_req_prev']) {
                        // Cek apakah kuis bab SEBELUMNYA sudah lulus
                        $isChapterUnlocked = !empty($completedLessonsMap[$chapter['quiz_req_prev']]);
                    }

                    // Reset logic waterfall untuk materi
                    if ($isChapterUnlocked) {
                        $previousItemComplete = true; 
                    } else {
                        $previousItemComplete = false;
                    }

                    // Styles
                    $cardStyle = $isChapterUnlocked ? 'glass-card hover:-translate-y-2' : 'glass-card-locked opacity-70 grayscale';
                    $borderColor = $isChapterUnlocked ? "group-hover:border-{$chapter['color']}-500/50" : 'border-white/5';
                    $textColor = "text-{$chapter['color']}-400";
                    $bgBadge = "bg-{$chapter['color']}-500/10 border-{$chapter['color']}-500/20";
                @endphp

                <div class="group relative rounded-[2rem] flex flex-col transition-all duration-500 {{ $cardStyle }} {{ $borderColor }}">
                    
                    {{-- Lock Overlay (Jika Bab Terkunci) --}}
                    @if(!$isChapterUnlocked)
                        <div class="absolute inset-0 z-30 flex flex-col items-center justify-center bg-[#020617]/60 backdrop-blur-[2px] rounded-[2rem]">
                            <div class="w-14 h-14 rounded-full bg-black/60 border border-white/10 flex items-center justify-center mb-4 shadow-xl">
                                <svg class="w-6 h-6 text-white/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <div class="bg-black/80 px-4 py-2 rounded-full border border-white/10 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                                <span class="text-xs font-bold text-white/60">Selesaikan Bab Sebelumnya</span>
                            </div>
                        </div>
                    @endif

                    {{-- Card Header --}}
                    <div class="p-8 pb-0">
                        <div class="flex justify-between items-start mb-6">
                            <span class="text-[10px] font-black tracking-widest px-3 py-1.5 rounded-lg border {{ $isChapterUnlocked ? $bgBadge : 'bg-white/5 border-white/5 text-white/30' }} {{ $isChapterUnlocked ? $textColor : '' }}">
                                BAB {{ $chapter['number'] }}
                            </span>
                            <span class="text-5xl font-black text-white/5 absolute top-6 right-8 group-hover:text-white/10 transition">{{ $chapter['number'] }}</span>
                        </div>
                        <h2 class="text-2xl font-black text-white mb-2 leading-tight">{{ $chapter['title'] }}</h2>
                        <p class="text-sm font-medium {{ $textColor }} mb-3 opacity-80">{{ $chapter['subtitle'] }}</p>
                        <p class="text-xs text-slate-400 leading-relaxed line-clamp-2">{{ $chapter['desc'] }}</p>
                    </div>

                    {{-- Lesson Timeline --}}
                    <div class="p-8 flex-1">
                        <div class="space-y-0 relative">
                            <div class="absolute left-[7px] top-2 bottom-2 w-px bg-gradient-to-b from-white/10 via-white/5 to-transparent"></div>

                            @foreach($chapter['topics'] as $topic)
                                @php
                                    $isCompleted = !empty($completedLessonsMap[$topic['code']]);
                                    $isAccessible = $isChapterUnlocked && $previousItemComplete;
                                    $previousItemComplete = $isCompleted; 
                                @endphp

                                <div class="relative pl-6 py-2.5 group/lesson">
                                    {{-- Dot --}}
                                    <div class="absolute left-[3px] top-[18px] w-2 h-2 rounded-full border-2 border-[#0f172a] z-10 transition-all duration-300
                                        {{ $isCompleted ? 'bg-emerald-500 border-emerald-500 shadow-[0_0_10px_#10b981]' : ($isAccessible ? "bg-{$chapter['color']}-500 border-{$chapter['color']}-500 animate-pulse" : 'bg-slate-700 border-slate-700') }}">
                                    </div>

                                    @if($isAccessible)
                                        <a href="{{ route($topic['route']) }}" class="flex items-center justify-between group-hover/lesson:translate-x-1 transition-transform duration-300">
                                            <div>
                                                <div class="text-sm font-bold {{ $isCompleted ? 'text-emerald-400' : 'text-slate-200 group-hover/lesson:text-white' }}">
                                                    <span class="font-mono text-[10px] opacity-40 mr-2">{{ $topic['code'] }}</span>
                                                    {{ $topic['name'] }}
                                                </div>
                                                <div class="text-[10px] text-slate-500 mt-0.5 ml-8">{{ $topic['time'] }}</div>
                                            </div>
                                            @if($isCompleted)
                                                <div class="bg-emerald-500/10 p-1 rounded-full border border-emerald-500/20">
                                                    <svg class="w-3 h-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                </div>
                                            @else
                                                <svg class="w-4 h-4 text-white/20 group-hover/lesson:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            @endif
                                        </a>
                                    @else
                                        <div class="flex items-center justify-between opacity-40 cursor-not-allowed">
                                            <div>
                                                <div class="text-sm font-medium text-slate-400">
                                                    <span class="font-mono text-[10px] opacity-30 mr-2">{{ $topic['code'] }}</span>
                                                    {{ $topic['name'] }}
                                                </div>
                                            </div>
                                            <svg class="w-3 h-3 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- SECTION BARU: HANDS-ON LAB --}}
                    @php
                        // --- 2. LOGIC KUNCI LAB ---
                        // Lab terbuka jika semua materi (lesson) di bab ini sudah selesai
                        $areAllLessonsDone = !empty($completedLessonsMap[$chapter['last_lesson_code']]);
                        
                        // Cek apakah Lab sudah LULUS di database
                        $isLabPassed = isset($passedLabsMap[$chapter['lab_id']]);
                        
                        $canAccessLab = $areAllLessonsDone;
                        
                        // Link Lab
                        $labLink = $canAccessLab ? route('lab.start', ['id' => $chapter['lab_id']]) : '#';
                        $labStatusText = $isLabPassed ? 'LAB SELESAI' : ($canAccessLab ? 'MULAI LAB' : 'SELESAIKAN MATERI');
                        
                        // Styling Lab
                        if ($isLabPassed) {
                            $labBorder = "border-emerald-500/30 bg-emerald-500/5";
                            $labIconBg = "bg-emerald-500 text-white shadow-emerald-500/20";
                            $labIcon = '✔';
                        } elseif ($canAccessLab) {
                            $labBorder = "border-amber-500/40 bg-amber-500/5 hover:bg-amber-500/10 cursor-pointer";
                            $labIconBg = "bg-amber-500 text-white shadow-amber-500/20 animate-pulse";
                            $labIcon = '⚡';
                        } else {
                            $labBorder = "border-white/5 bg-white/[0.02] opacity-50 cursor-not-allowed";
                            $labIconBg = "bg-white/10 text-white/20";
                            $labIcon = '🔒';
                        }
                    @endphp

                    <div class="px-6 pb-2">
                        <button onclick="{{ $canAccessLab ? "location.href='$labLink'" : "return false;" }}"
                            class="w-full flex items-center gap-4 p-3 rounded-xl border transition-all duration-300 group/lab {{ $labBorder }}">
                            
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-sm shadow-lg transition-transform group-hover/lab:scale-105 {{ $labIconBg }}">
                                {{ $labIcon }}
                            </div>
                            
                            <div class="flex-1 text-left">
                                <div class="text-[10px] font-bold tracking-wider opacity-60 mb-0.5 {{ $canAccessLab ? 'text-amber-400' : 'text-slate-500' }}">HANDS-ON LAB</div>
                                <div class="text-xs font-bold text-white truncate w-40">{{ $chapter['lab_title'] ?? 'Practical Exercise' }}</div>
                            </div>

                            @if($canAccessLab)
                                <svg class="w-4 h-4 text-amber-400 group-hover/lab:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            @endif
                        </button>
                    </div>

                    {{-- SECTION: KUIS --}}
                    @php
                        // --- 3. LOGIC KUNCI KUIS ---
                        // Kuis HANYA terbuka jika LAB sudah LULUS
                        $canTakeQuiz = $isLabPassed; 
                        
                        // Cek apakah Kuis sudah LULUS
                        $isQuizPassed = !empty($completedLessonsMap[$chapter['quiz_key_db']]);
                    @endphp

                    <div class="p-6 pt-2 border-t border-white/5 bg-white/[0.02] mt-2">
                        @if($isQuizPassed)
                            <div class="w-full py-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-widest flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Bab Terselesaikan</span>
                            </div>
                        @elseif($canTakeQuiz)
                            <a href="{{ route('quiz.intro', ['chapterId' => $chapter['quiz_id']]) }}" class="group/btn w-full py-3.5 rounded-xl bg-gradient-to-r from-{{ $chapter['color'] }}-600 to-indigo-600 hover:scale-[1.02] active:scale-[0.98] text-white text-xs font-bold uppercase tracking-widest shadow-lg shadow-{{ $chapter['color'] }}-500/20 transition-all flex justify-center items-center gap-2">
                                <span>Mulai Kuis Akhir</span> 
                                <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        @else
                            <button disabled class="w-full py-3.5 rounded-xl border border-white/5 bg-white/5 text-slate-500 text-xs font-bold uppercase tracking-widest flex justify-center items-center gap-2 cursor-not-allowed">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span>{{ $canAccessLab ? 'Selesaikan Lab Dulu' : 'Materi Belum Selesai' }}</span>
                            </button>
                        @endif
                    </div>

                </div>
            @endforeach

        </div>

        {{-- 3. REFERENCES SECTION --}}
        <div class="mt-32 pt-20 border-t border-white/5 reveal-up delay-500">
            <div class="mb-10 flex flex-col md:flex-row justify-between items-end gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 mb-4 rounded-full bg-slate-800 border border-white/10 text-slate-400 text-[10px] font-bold tracking-widest uppercase">
                        Sumber Belajar
                    </div>
                    <h2 class="text-3xl font-black text-white">Referensi Kurikulum</h2>
                    <p class="text-slate-400 text-sm mt-2 max-w-2xl">
                        Materi pada platform ini disusun berdasarkan best-practice dari literatur Tailwind CSS terkemuka di industri.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="#" class="group glass-card p-6 rounded-2xl flex items-start gap-5 hover:bg-white/5 transition-all duration-300">
                    <div class="w-16 h-20 bg-gradient-to-br from-slate-700 to-slate-900 rounded-lg shadow-xl shrink-0 flex items-center justify-center border border-white/10 group-hover:scale-105 transition-transform">
                        <span class="text-[10px] font-bold text-white/20 writing-vertical-rl rotate-180">BOOK</span>
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-sm leading-tight group-hover:text-cyan-400 transition-colors">Modern CSS with Tailwind</h4>
                        <p class="text-[11px] text-slate-500 mt-1 font-mono">Noel Rappin</p>
                        <p class="text-[11px] text-slate-400 mt-3 leading-relaxed line-clamp-2">Flexible Styling Without the Fuss.</p>
                    </div>
                </a>
                <a href="#" class="group glass-card p-6 rounded-2xl flex items-start gap-5 hover:bg-white/5 transition-all duration-300">
                    <div class="w-16 h-20 bg-gradient-to-br from-indigo-900 to-slate-900 rounded-lg shadow-xl shrink-0 flex items-center justify-center border border-white/10 group-hover:scale-105 transition-transform">
                        <span class="text-[10px] font-bold text-white/20 writing-vertical-rl rotate-180">BOOK</span>
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-sm leading-tight group-hover:text-indigo-400 transition-colors">Tailwind CSS (SitePoint)</h4>
                        <p class="text-[11px] text-slate-500 mt-1 font-mono">Ivaylo Gerchev</p>
                        <p class="text-[11px] text-slate-400 mt-3 leading-relaxed line-clamp-2">Craft Beautiful, Flexible, and Responsive Designs.</p>
                    </div>
                </a>
                <a href="#" class="group glass-card p-6 rounded-2xl flex items-start gap-5 hover:bg-white/5 transition-all duration-300">
                    <div class="w-16 h-20 bg-gradient-to-br from-fuchsia-900 to-slate-900 rounded-lg shadow-xl shrink-0 flex items-center justify-center border border-white/10 group-hover:scale-105 transition-transform">
                        <span class="text-[10px] font-bold text-white/20 writing-vertical-rl rotate-180">BOOK</span>
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-sm leading-tight group-hover:text-fuchsia-400 transition-colors">Ultimate Handbook</h4>
                        <p class="text-[11px] text-slate-500 mt-1 font-mono">Kartik Bhat</p>
                        <p class="text-[11px] text-slate-400 mt-3 leading-relaxed line-clamp-2">Build sleek and modern websites with immersive UIs.</p>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection