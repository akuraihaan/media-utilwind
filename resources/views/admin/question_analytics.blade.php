<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bank Soal & Analisis · Utilwind Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    {{-- RESOURCES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    {{-- KONFIGURASI DARK MODE TAILWIND --}}
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>

    {{-- SCRIPT PENGECEKAN TEMA OTOMATIS --}}
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        /* --- THEME CONFIG --- */
        body { font-family: 'Inter', sans-serif; transition: background-color 0.3s, color 0.3s; overflow-x: hidden; }
        body { background-color: #f8fafc; color: #0f172a; }
        body.dark { background-color: #020617; color: #e2e8f0; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        /* --- SCROLLBAR --- */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6366f1; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #818cf8; }

        /* --- GLASS COMPONENTS (ADAPTIF TERANG/GELAP) --- */
        .glass-sidebar { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-right: 1px solid rgba(0,0,0,0.05); z-index: 50; transition: 0.3s; }
        .dark .glass-sidebar { background: rgba(5, 8, 16, 0.95); border-right: 1px solid rgba(255, 255, 255, 0.08); }

        .glass-header { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(0,0,0,0.05); z-index: 40; transition: 0.3s; }
        .dark .glass-header { background: rgba(2, 6, 23, 0.8); border-bottom: 1px solid rgba(255, 255, 255, 0.08); }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.85); border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03); backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative; overflow: visible !important; z-index: 10;
        }
        .dark .glass-card {
            background: rgba(10, 14, 23, 0.85); border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
        }
        .glass-card:hover { border-color: rgba(99, 102, 241, 0.4); transform: translateY(-4px); box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1); z-index: 30; }
        .dark .glass-card:hover { box-shadow: 0 10px 40px -10px rgba(0,0,0,0.5); }
        
        .card-bg-gfx { position: absolute; inset: 0; overflow: hidden; border-radius: 1rem; pointer-events: none; z-index: 0; }

        /* --- INPUTS & NAV --- */
        .glass-input { background: rgba(0, 0, 0, 0.03); border: 1px solid rgba(0, 0, 0, 0.1); color: #0f172a; transition: 0.3s; }
        .dark .glass-input { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); color: white; }
        .glass-input:focus { border-color: #6366f1; background: rgba(0, 0, 0, 0.05); outline: none; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }
        .dark .glass-input:focus { background: rgba(255, 255, 255, 0.05); border-color: #818cf8; box-shadow: 0 0 0 2px rgba(129, 140, 248, 0.2); }
        
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #64748b; font-weight: 600; font-size: 0.9rem; transition: all 0.2s; border: 1px solid transparent; }
        .dark .nav-link { color: #94a3b8; font-weight: 500; }
        .nav-link:hover { background: rgba(0, 0, 0, 0.03); color: #0f172a; }
        .dark .nav-link:hover { background: rgba(255, 255, 255, 0.03); color: white; }
        .nav-link.active { background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0) 100%); color: #6366f1; border-left: 3px solid #6366f1; border-radius: 4px 12px 12px 4px; }
        .dark .nav-link.active { color: #818cf8; border-left-color: #818cf8; }

        .reveal { opacity: 0; transform: translateY(20px); animation: revealAnim 0.6s forwards ease-out; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
        
        .table-row { transition: background 0.2s; border-bottom: 1px solid rgba(0,0,0,0.03); }
        .table-row:hover { background: rgba(0,0,0,0.02); }
        .dark .table-row { border-bottom: 1px solid rgba(255,255,255,0.03); }
        .dark .table-row:hover { background: rgba(255,255,255,0.02); }

        /* =========================================================================
           SISTEM TOOLTIP SUPER SOLID
           ========================================================================= */
        .tooltip-container { position: relative; display: inline-flex; align-items: center; justify-content: center; z-index: 50; }
        .tooltip-container:hover { z-index: 99999; }
        .tooltip-trigger { width: 18px; height: 18px; border-radius: 50%; color: #64748b; font-size: 11px; font-weight: 900; display: flex; align-items: center; justify-content: center; cursor: help; transition: all 0.2s; border: 1px solid rgba(0,0,0,0.2); }
        .dark .tooltip-trigger { color: white; border-color: rgba(255,255,255,0.2); }
        .tooltip-trigger:hover { transform: scale(1.15); }
        
        .tooltip-content { opacity: 0; visibility: hidden; position: absolute; pointer-events: none; width: max-content; min-width: 220px; max-width: 280px; white-space: normal; text-align: left; background-color: #ffffff; color: #1e293b; font-size: 11px; padding: 14px 16px; line-height: 1.5; border-radius: 12px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); z-index: 99999; border: 1px solid #e2e8f0; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .dark .tooltip-content { background-color: #020617; color: #e2e8f0; box-shadow: 0 20px 60px rgba(0,0,0,1); border: 1px solid rgba(255,255,255,0.05); }

        .tooltip-down .tooltip-content { top: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(-10px); }
        .tooltip-down:hover .tooltip-content { transform: translateX(-50%) translateY(0); opacity: 1; visibility: visible; }
        .tooltip-down .tooltip-content::after { content: ''; position: absolute; bottom: 100%; left: 50%; margin-left: -6px; border-width: 6px; border-style: solid; border-color: transparent transparent #ffffff transparent; }
        .dark .tooltip-down .tooltip-content::after { border-color: transparent transparent #020617 transparent; }
        
        .tooltip-left .tooltip-content { left: auto; right: -12px; transform: translateX(0) translateY(-10px); }
        .tooltip-down.tooltip-left:hover .tooltip-content { transform: translateX(0) translateY(0); }
        .tooltip-left .tooltip-content::after { left: auto; right: 15px; margin-left: 0; }

        .tooltip-indigo .tooltip-trigger { background-color: #e0e7ff; color: #4f46e5; border-color: #c7d2fe; }
        .dark .tooltip-indigo .tooltip-trigger { background-color: #6366f1; color: white; border-color: transparent; box-shadow: 0 0 10px rgba(99,102,241,0.5); }
        .tooltip-cyan .tooltip-trigger { background-color: #cffafe; color: #0891b2; border-color: #a5f3fc; }
        .dark .tooltip-cyan .tooltip-trigger { background-color: #06b6d4; color: white; border-color: transparent; box-shadow: 0 0 10px rgba(6,182,212,0.5); }
        .tooltip-emerald .tooltip-trigger { background-color: #d1fae5; color: #059669; border-color: #a7f3d0; }
        .dark .tooltip-emerald .tooltip-trigger { background-color: #10b981; color: white; border-color: transparent; box-shadow: 0 0 10px rgba(16,185,129,0.5); }
        .tooltip-red .tooltip-trigger { background-color: #fecaca; color: #dc2626; border-color: #fca5a5; }
        .dark .tooltip-red .tooltip-trigger { background-color: #ef4444; color: white; border-color: transparent; box-shadow: 0 0 10px rgba(239,68,68,0.5); }
        .tooltip-violet .tooltip-trigger { background-color: #ede9fe; color: #7c3aed; border-color: #ddd6fe; }
        .dark .tooltip-violet .tooltip-trigger { background-color: #8b5cf6; color: white; border-color: transparent; box-shadow: 0 0 10px rgba(139,92,246,0.5); }

        .modal-open { overflow: hidden; padding-right: 5px; } 
        .text-adaptive { color: #1e293b; }
        .dark .text-adaptive { color: #f8fafc; }
        .text-adaptive-muted { color: #64748b; }
        .dark .text-adaptive-muted { color: rgba(255,255,255,0.4); }
    </style>
</head>

{{-- ==============================================================================
     LOGIKA BLADE BULLETPROOF (Aman dari null reference & Syntax Error)
     ============================================================================== --}}
@php
    $totalAttempts = $totalAttempts ?? \App\Models\QuizAttempt::whereNotNull('completed_at')->count();
    $avgScore = $avgScore ?? \App\Models\QuizAttempt::whereNotNull('completed_at')->avg('score') ?? 0;
    $passRate = $passRate ?? ($totalAttempts > 0 ? (\App\Models\QuizAttempt::where('score', '>=', 70)->count() / $totalAttempts) * 100 : 0);
    $recentAttempts = isset($recentAttempts) ? collect($recentAttempts) : \App\Models\QuizAttempt::with('user')->whereNotNull('completed_at')->latest()->take(5)->get();

    // Data Master Soal & Akurasi
    $questionsRaw = \App\Models\QuizQuestion::with('options')->get();
    $questions = $questionsRaw->map(function($q) {
        $stats = \Illuminate\Support\Facades\DB::table('quiz_attempt_answers')
            ->where('quiz_question_id', $q->id)
            ->selectRaw('count(*) as total_attempts')
            ->selectRaw('sum(case when is_correct = 1 then 1 else 0 end) as correct_count')
            ->selectRaw('sum(case when is_correct = 0 then 1 else 0 end) as wrong_count')
            ->first();

        $q->total_attempts = $stats->total_attempts ?? 0;
        $q->correct_count = $stats->correct_count ?? 0;
        $q->wrong_count = $stats->wrong_count ?? 0;
        $q->accuracy = $q->total_attempts > 0 ? round(($q->correct_count / $q->total_attempts) * 100) : 0;

        if ($q->accuracy >= 80) $q->status = 'Mudah';
        elseif ($q->accuracy >= 50) $q->status = 'Sedang';
        else $q->status = 'Sulit';

        $answersData = \Illuminate\Support\Facades\DB::table('quiz_attempt_answers')
            ->join('quiz_attempts', 'quiz_attempt_answers.quiz_attempt_id', '=', 'quiz_attempts.id')
            ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
            ->where('quiz_attempt_answers.quiz_question_id', $q->id)
            ->select('users.name', 'quiz_attempt_answers.is_correct')
            ->get();
            
        $q->list_correct = $answersData->where('is_correct', 1)->pluck('name')->toArray();
        $q->list_wrong = $answersData->where('is_correct', 0)->pluck('name')->toArray();

        return $q;
    });

    $totalQuestions = $questions->count();
    $totalAnswersGlobal = $questions->sum('total_attempts');
    $globalAcc = $totalQuestions > 0 ? round($questions->avg('accuracy'), 1) : 0;
    $hardQuestionsCount = $questions->where('status', 'Sulit')->count();

    $chapterGroups = $questions->where('chapter_id', '!=', 99)->groupBy('chapter_id');
    $finalExam = $questions->where('chapter_id', 99);
    $hardestQuestions = $questions->where('status', 'Sulit')->sortBy('accuracy')->take(5)->map(function($q) {
        $q->failure_rate = 100 - $q->accuracy;
        return $q;
    });

    // Daftar Semua Siswa (Digunakan di UI Tabel Pencarian)
    $studentStats = \Illuminate\Support\Facades\DB::table('quiz_attempts')
        ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
        ->select('users.name', 'users.email', \Illuminate\Support\Facades\DB::raw('AVG(quiz_attempts.score) as avg_score'), \Illuminate\Support\Facades\DB::raw('COUNT(quiz_attempts.id) as total_attempts'))
        ->whereNotNull('completed_at')
        ->groupBy('users.id', 'users.name', 'users.email')
        ->orderByDesc('avg_score')
        ->get()
        ->map(function($stat) {
            $stat->avg_score = round($stat->avg_score, 1);
            return $stat;
        });
        
    $totalParticipants = \Illuminate\Support\Facades\DB::table('quiz_attempts')->whereNotNull('completed_at')->distinct('user_id')->count('user_id');

    // ==========================================================================
    // EXTRAKSI DETAIL JAWABAN SISWA (TETAP DIAMAN-KAN UNTUK ALPINEJS)
    // ==========================================================================
    $studentDetailsMap = [];
    $allAnswers = \Illuminate\Support\Facades\DB::table('quiz_attempt_answers')
        ->join('quiz_attempts', 'quiz_attempt_answers.quiz_attempt_id', '=', 'quiz_attempts.id')
        ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
        ->join('quiz_questions', 'quiz_attempt_answers.quiz_question_id', '=', 'quiz_questions.id')
        ->leftJoin('quiz_options as chosen', 'quiz_attempt_answers.quiz_option_id', '=', 'chosen.id')
        ->select(
            'users.email', 'users.name', 'quiz_attempts.chapter_id', 'quiz_attempts.score',
            'quiz_questions.question_text', 'quiz_attempt_answers.is_correct',
            'chosen.option_text as chosen_text', 'quiz_questions.id as question_id'
        )
        ->whereNotNull('quiz_attempts.completed_at')
        ->get();

    $correctOptions = \Illuminate\Support\Facades\DB::table('quiz_options')->where('is_correct', 1)->pluck('option_text', 'quiz_question_id');

    foreach($allAnswers as $ans) {
        $email = $ans->email;
        if(!isset($studentDetailsMap[$email])) {
            $studentDetailsMap[$email] = [
                'name' => $ans->name, 
                'email' => $email, 
                'summary_score' => $studentStats->where('email', $email)->first()->avg_score ?? 0,
                'summary_total' => $studentStats->where('email', $email)->first()->total_attempts ?? 0,
                'chapters' => []
            ];
        }
        $chId = $ans->chapter_id;
        if(!isset($studentDetailsMap[$email]['chapters'][$chId])) {
            $studentDetailsMap[$email]['chapters'][$chId] = [
                'title' => $chId == 99 ? 'Evaluasi Akhir' : 'Bab ' . $chId,
                'score' => $ans->score,
                'answers' => []
            ];
        }
        $studentDetailsMap[$email]['chapters'][$chId]['answers'][] = [
            'question' => $ans->question_text,
            'is_correct' => $ans->is_correct,
            'chosen' => $ans->chosen_text ?? 'Tidak dijawab',
            'correct' => $correctOptions[$ans->question_id] ?? 'Tidak ada kunci'
        ];
    }
@endphp

{{-- Inject JSON data for AlpineJS --}}
<script id="student-data-json" type="application/json">
    {!! json_encode($studentDetailsMap) !!}
</script>

<body class="flex h-screen w-full bg-slate-50 dark:bg-[#020617] text-slate-800 dark:text-slate-200 transition-colors duration-500" 
      x-data="{ 
          sidebarOpen: false,
          isFullscreen: false,
          currentView: 'dashboard', 
          activeChapter: null,
          activeChapterName: '',
          search: '',
          difficulty: 'all',
          searchStudent: '',
          
          showQuestionsModal: false,
          showParticipantsModal: false,
          showAccuracyModal: false,
          showHardModal: false,
          
          // State Detail Siswa
          studentDetails: {},
          showStudentDetailModal: false,
          selectedStudent: null,

          init() {
              const dataElement = document.getElementById('student-data-json');
              if(dataElement) {
                  this.studentDetails = JSON.parse(dataElement.textContent);
              }
          },

          selectChapter(id, name) {
              this.activeChapter = id;
              this.activeChapterName = name;
              this.currentView = 'table';
              this.search = '';
          },
          resetView() {
              this.currentView = 'dashboard';
              this.activeChapter = null;
          },
          openStudentDetail(email) {
              this.selectedStudent = this.studentDetails[email] || null;
              this.showStudentDetailModal = true;
          }
      }" 
      @keydown.escape.window="isFullscreen = false; document.exitFullscreen(); showQuestionsModal = false; showParticipantsModal = false; showAccuracyModal = false; showHardModal = false; showStudentDetailModal = false; closeModal(); closeInsightModal();" 
      :class="{'modal-open': sidebarOpen || showQuestionsModal || showParticipantsModal || showAccuracyModal || showHardModal || showStudentDetailModal}">

    <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/60 dark:bg-[#020617]/80 backdrop-blur-sm z-[90] md:hidden transition-opacity" @click="sidebarOpen = false" x-transition.opacity style="display: none;" x-cloak></div>

     {{-- ==================== 1. SIDEBAR ==================== --}}
    <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/60 dark:bg-[#020617]/80 backdrop-blur-sm z-[90] md:hidden transition-colors" @click="sidebarOpen = false" x-transition.opacity style="display: none;" x-cloak></div>

    <aside class="glass-sidebar w-72 h-full flex flex-col fixed md:relative z-[100] transition-transform duration-300 transform md:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="h-24 flex items-center justify-between px-8 border-b border-slate-200 dark:border-white/5 relative overflow-hidden group transition-colors">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-20 h-20 bg-indigo-200/50 dark:bg-indigo-500/20 rounded-full blur-[40px] opacity-0 group-hover:opacity-100 transition duration-500"></div>
            
            <a href="{{ route('landing') }}" class="flex items-center gap-3 relative z-10">
                <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto object-contain block dark:hidden" style="filter: brightness(0.1);" alt="Logo">
                <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto object-contain hidden dark:block drop-shadow-sm" alt="Logo Dark">
                <div>
                    <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight leading-none transition-colors">Util<span class="text-indigo-600 dark:text-indigo-400">wind</span></h1>
                    <span class="text-[9px] font-bold text-slate-500 dark:text-white/40 tracking-[0.2em] uppercase transition-colors">Admin Panel</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="md:hidden text-slate-500 dark:text-white/50 hover:text-slate-800 dark:hover:text-white relative z-10 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto custom-scrollbar py-8 px-4 space-y-8">
            <div>
                <p class="px-4 text-[10px] font-extrabold text-slate-400 dark:text-white/30 uppercase tracking-widest mb-3 transition-colors">Overview</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        Dashboard
                    </a>
                </div>
            </div>

            <div>
                <p class="px-4 text-[10px] font-extrabold text-slate-400 dark:text-white/30 uppercase tracking-widest mb-3 transition-colors">Academic</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.analytics.questions') }}" class="nav-link {{ request()->routeIs('admin.analytics.questions') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.analytics.questions') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Quiz Management
                    </a>
                    <a href="{{ route('admin.labs.index') }}" class="nav-link {{ request()->routeIs('admin.labs.index') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.labs.index') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        Lab Configuration
                    </a>
                    <a href="{{ route('admin.lab.analytics') }}" class="nav-link {{ request()->routeIs('admin.lab.analytics') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.lab.analytics') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        Lab Analytics
                    </a>
                    <a href="{{ route('admin.classes.index') }}" class="nav-link {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.classes.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Class Management
                    </a>
                </div>
            </div>
        </nav>

        {{-- USER PROFILE Bawah Sidebar --}}
        <div class="p-4 border-t border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-[#05080f]/50 transition-colors">
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white text-xs shadow-lg">AD</div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-slate-900 dark:text-white truncate transition-colors">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-white/40 truncate transition-colors">System Admin</p>
                </div>
            </div>
            
            {{-- THEME TOGGLE BUTTON --}}
            <button id="theme-toggle-sidebar" type="button" class="w-full mb-2 flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-slate-200/50 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 text-slate-700 dark:text-slate-300 transition-colors border border-transparent dark:border-transparent text-xs font-bold shadow-sm dark:shadow-none">
                <svg id="theme-toggle-dark-icon-sidebar" class="hidden w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                <svg id="theme-toggle-light-icon-sidebar" class="hidden w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path></svg>
                <span id="theme-toggle-text-sidebar">Ubah Tema</span>
            </button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500 hover:text-red-700 dark:hover:text-white transition-colors text-xs font-bold border border-red-200 dark:border-red-500/20 hover:border-red-300 dark:hover:border-red-500 group shadow-sm dark:shadow-none">
                    <svg class="w-3.5 h-3.5 transition group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ==================== MAIN CONTENT ==================== --}}
    <main class="flex-1 flex flex-col relative z-10 transition-colors duration-300 h-full overflow-y-auto overflow-x-hidden">
        
        {{-- Background FX --}}
        <div class="fixed inset-0 pointer-events-none z-0">
            <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-cyan-400/20 dark:bg-cyan-600/10 rounded-full blur-[120px] transition-colors"></div>
            <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-indigo-400/20 dark:bg-indigo-600/10 rounded-full blur-[120px] transition-colors"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02] dark:opacity-[0.04] mix-blend-overlay"></div>
        </div>

        {{-- HEADER RESPONSIVE --}}
        <header class="h-24 glass-header flex flex-col justify-center px-6 md:px-10 shrink-0 sticky top-0 z-40 transition-colors">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="md:hidden p-2 bg-slate-200 dark:bg-white/5 rounded-lg text-slate-700 dark:text-white hover:bg-slate-300 dark:hover:bg-white/10 transition-colors shadow-sm dark:shadow-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    
                    <div class="flex items-center gap-3">
                        <button x-show="currentView === 'table'" @click="resetView()" x-cloak x-transition class="p-2 rounded-full bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 text-slate-700 dark:text-white transition-colors group border border-transparent dark:border-white/10 shadow-sm" title="Kembali ke Overview">
                            <svg class="w-4 h-4 text-slate-500 dark:text-white/70 group-hover:text-slate-900 dark:group-hover:text-white transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        </button>

                        <div>
                            <nav class="flex text-[10px] text-slate-500 dark:text-white/50 mb-1.5 font-bold hidden sm:flex transition-colors" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1">
                                    <li class="inline-flex items-center"><a href="{{ route('admin.dashboard') ?? '#' }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Dashboard</a></li>
                                    <li>
                                        <div class="flex items-center transition-colors">
                                            <svg class="w-3 h-3 text-slate-400 dark:text-white/30 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            <span class="text-slate-700 dark:text-white transition-colors">Quiz Management</span>
                                        </div>
                                    </li>
                                </ol>
                            </nav>
                            <h2 class="text-adaptive font-bold text-lg md:text-xl tracking-tight transition-colors" x-text="currentView === 'dashboard' ? 'Bank Soal & Analisis Evaluasi' : 'Detail Bab: ' + activeChapterName"></h2>
                            <p class="text-[9px] md:text-xs text-adaptive-muted flex items-center gap-1.5 mt-0.5 transition-colors">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                                <span x-text="currentView === 'dashboard' ? 'Tinjauan Performa Kuis ' : 'Mode Bank Soal'"></span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 sm:gap-6">
                    <button onclick="window.location.reload()" class="p-2.5 text-slate-500 dark:text-white/40 hover:text-slate-900 dark:hover:text-white transition-colors rounded-full hover:bg-slate-200 dark:hover:bg-white/5 hidden sm:block border border-transparent dark:hover:border-white/10" title="Refresh Data">
                        <svg class="w-4 h-4 hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>
                    <button @click="isFullscreen = !isFullscreen; isFullscreen ? document.documentElement.requestFullscreen() : document.exitFullscreen()" class="p-2.5 text-slate-500 dark:text-white/40 hover:text-slate-900 dark:hover:text-white transition-colors rounded-full hover:bg-slate-200 dark:hover:bg-white/5 hidden md:block border border-transparent dark:hover:border-white/10" title="Fullscreen Mode">
                        <svg x-show="!isFullscreen" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        <svg x-show="isFullscreen" style="display: none;" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    {{-- Action Button (Hanya Muncul di Mode Tabel Bank Soal) --}}
                    <div class="border-l border-slate-300 dark:border-white/10 pl-5 ml-1 hidden lg:block transition-colors" x-show="currentView === 'table'">
                        <button onclick="openModal('create')" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md dark:shadow-[0_0_15px_rgba(99,102,241,0.3)] transition border border-indigo-500 dark:border-indigo-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> Tambah Soal
                        </button>
                    </div>

                    <div class="text-right hidden lg:block border-l border-slate-300 dark:border-white/10 pl-5 ml-1 transition-colors">
                        <p class="text-sm font-bold text-adaptive">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</p>
                        <p class="text-[10px] text-adaptive-muted font-mono mt-0.5">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
                    </div>

                    {{-- Mobile Add Button --}}
                    <button onclick="openModal('create')" x-show="currentView === 'table'" class="lg:hidden p-2 rounded-lg bg-indigo-600 text-white shadow-lg">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </button>
                </div>
            </div>
        </header>

        {{-- CONTENT SCROLLABLE --}}
        <div class="flex-1 p-6 md:p-10 relative z-10">
            <div class="max-w-7xl mx-auto space-y-8 md:space-y-12">

                {{-- =======================================================
                     VIEW 1: DASHBOARD GRID (OVERVIEW)
                     ======================================================= --}}
                <div x-show="currentView === 'dashboard'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    
                    {{-- 1. OVERVIEW STATS HERO CARDS --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6 mb-8 reveal">
                        
                        {{-- Total Pertanyaan --}}
                        <div class="glass-card rounded-2xl p-5 border-l-4 border-cyan-500 cursor-pointer group transition-all" @click="showQuestionsModal = true">
                            <div class="flex justify-between items-start">
                                <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">Total Soal</p>
                                <div class="tooltip-container tooltip-cyan tooltip-down tooltip-left">
                                    <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-slate-400 dark:text-white/30 group-hover:text-cyan-600 dark:group-hover:text-cyan-400">?</div>
                                    <div class="tooltip-content">
                                        <span class="block font-bold text-cyan-600 dark:text-cyan-400 mb-1 border-b border-slate-200 dark:border-white/10 pb-1">Total Soal Database</span>
                                        Total akumulasi seluruh soal teori yang ada di dalam database saat ini.
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mt-2 transition-colors">{{ $totalQuestions }}</h3>
                            <p class="text-[9px] text-cyan-600 dark:text-cyan-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0">Lihat Database &rarr;</p>
                        </div>

                        {{-- Peserta Ujian --}}
                        <div class="glass-card rounded-2xl p-5 border-l-4 border-indigo-500 cursor-pointer group transition-all" @click="showParticipantsModal = true">
                            <div class="flex justify-between items-start">
                                <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Peserta Ujian</p>
                                <div class="tooltip-container tooltip-indigo tooltip-down tooltip-left">
                                    <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-slate-400 dark:text-white/30 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">?</div>
                                    <div class="tooltip-content">
                                        <span class="block font-bold text-indigo-600 dark:text-indigo-400 mb-1 border-b border-slate-200 dark:border-white/10 pb-1">Total Peserta Unik</span>
                                        Total siswa unik yang telah mengumpulkan setidaknya satu evaluasi kuis.
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mt-2 transition-colors">{{ $totalParticipants }}</h3>
                            <p class="text-[9px] text-indigo-600 dark:text-indigo-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0">Lihat Daftar &rarr;</p>
                        </div>

                        {{-- Akurasi Global --}}
                        <div class="glass-card rounded-2xl p-5 border-l-4 border-emerald-500 cursor-pointer group transition-all" @click="showAccuracyModal = true">
                            <div class="flex justify-between items-start">
                                <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Akurasi Global</p>
                                <div class="tooltip-container tooltip-emerald tooltip-down tooltip-left">
                                    <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-slate-400 dark:text-white/30 group-hover:text-emerald-600 dark:group-hover:text-emerald-400">?</div>
                                    <div class="tooltip-content">
                                        <span class="block font-bold text-emerald-600 dark:text-emerald-400 mb-1 border-b border-slate-200 dark:border-white/10 pb-1">Akurasi Rata-rata</span>
                                        Kalkulasi persentase ketepatan jawaban rata-rata dari total {{ $totalAnswersGlobal }} jawaban yang terkumpul.
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 mt-2">
                                <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white transition-colors">{{ $globalAcc }}</h3>
                                <span class="text-[10px] text-emerald-600 dark:text-emerald-500 font-bold">%</span>
                            </div>
                            <p class="text-[9px] text-emerald-600 dark:text-emerald-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0">Lihat Rincian &rarr;</p>
                        </div>

                        {{-- Soal Sulit --}}
                        <div class="glass-card rounded-2xl p-5 border-l-4 border-red-500 cursor-pointer group transition-all" @click="showHardModal = true">
                            <div class="flex justify-between items-start">
                                <p class="text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">Soal Sulit</p>
                                <div class="tooltip-container tooltip-red tooltip-down tooltip-left">
                                    <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-slate-400 dark:text-white/30 group-hover:text-red-600 dark:group-hover:text-red-400">?</div>
                                    <div class="tooltip-content">
                                        <span class="block font-bold text-red-600 dark:text-red-400 mb-1 border-b border-slate-200 dark:border-white/10 pb-1">Butuh Perhatian</span>
                                        Jumlah spesifik soal yang memiliki tingkat kegagalan (salah jawab) di atas 50%.
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-black text-red-600 dark:text-red-500 mt-2 drop-shadow-[0_0_8px_rgba(239,68,68,0.3)] transition-colors">{{ $hardQuestionsCount }}</h3>
                            <p class="text-[9px] text-red-600 dark:text-red-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0">Tinjau Soal &rarr;</p>
                        </div>
                    </div>

                    {{-- 2. CHAPTER CARDS (REGULAR) --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8 md:mb-12">
                        {{-- KOLOM KIRI: MATERI REGULER --}}
                        <div class="lg:col-span-2 space-y-6">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors"><svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg> Direktori Bank Soal</h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 reveal" style="animation-delay: 0.1s;">
                                @php
                                    // Meta mapping warna & judul untuk chapter standard
                                    $chapterMeta = [
                                        1 => ['title' => 'BAB 1: Pendahuluan', 'desc' => 'Dasar HTML, CSS & Tailwind', 'color' => 'cyan'],
                                        2 => ['title' => 'BAB 2: Layouting', 'desc' => 'Sistem Flexbox & Grid', 'color' => 'indigo'],
                                        3 => ['title' => 'BAB 3: Styling', 'desc' => 'Efek, Dekorasi & Tipografi', 'color' => 'fuchsia'],
                                    ];
                                @endphp

                                @if($chapterGroups->count() > 0)
                                    @foreach($chapterGroups as $id => $chQs)
                                        @php
                                            $meta = $chapterMeta[$id] ?? ['title' => 'BAB '.$id.': Lanjutan', 'desc' => 'Materi Tambahan', 'color' => 'emerald'];
                                            $cnt = $chQs->count();
                                            $acc = $cnt > 0 ? round($chQs->avg('accuracy')) : 0;
                                        @endphp
                                        <div @click="selectChapter({{ $id }}, '{{ addslashes($meta['title']) }}')" 
                                             class="glass-card rounded-3xl p-6 cursor-pointer group hover:border-{{ $meta['color'] }}-400/50 flex flex-col justify-between h-48 md:h-56 transition-colors">
                                            
                                            <div class="card-bg-gfx">
                                                <div class="absolute -right-8 -top-8 w-32 h-32 bg-{{ $meta['color'] }}-400/20 dark:bg-{{ $meta['color'] }}-500/10 rounded-full blur-2xl group-hover:bg-{{ $meta['color'] }}-400/30 dark:group-hover:bg-{{ $meta['color'] }}-500/20 transition duration-500"></div>
                                            </div>
                                            
                                            <div class="relative z-10">
                                                <div class="flex justify-between items-start mb-4">
                                                    <span class="text-xl md:text-2xl font-black font-mono text-{{ $meta['color'] }}-600 dark:text-{{ $meta['color'] }}-400 bg-{{ $meta['color'] }}-50 dark:bg-{{ $meta['color'] }}-500/10 px-3 py-1 rounded-lg border border-{{ $meta['color'] }}-200 dark:border-{{ $meta['color'] }}-500/20 transition-colors">0{{ $id }}</span>
                                                    <div class="text-right">
                                                        <p class="text-xl md:text-2xl font-black text-slate-900 dark:text-white transition-colors">{{ $cnt }}</p>
                                                        <p class="text-[9px] md:text-[10px] text-slate-500 dark:text-white/40 uppercase tracking-widest font-bold transition-colors">Soal</p>
                                                    </div>
                                                </div>
                                                <h3 class="text-base md:text-lg font-bold text-slate-900 dark:text-white group-hover:text-{{ $meta['color'] }}-600 dark:group-hover:text-{{ $meta['color'] }}-400 transition-colors">{{ $meta['title'] }}</h3>
                                                <p class="text-[10px] md:text-xs text-slate-600 dark:text-white/50 mt-1 transition-colors">{{ $meta['desc'] }}</p>
                                            </div>
                                            <div class="relative z-10 w-full bg-slate-200 dark:bg-white/10 h-1.5 rounded-full overflow-hidden mt-4 shadow-inner transition-colors">
                                                <div class="h-full bg-{{ $meta['color'] }}-500 transition-all duration-1000" style="width: {{ $acc }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-span-2 text-center py-10 text-slate-500 dark:text-white/30 text-xs italic bg-slate-50 dark:bg-[#0a0e17]/50 rounded-xl border border-dashed border-slate-300 dark:border-white/10 transition-colors">Belum ada soal terdaftar untuk bab reguler.</div>
                                @endif
                            </div>
                        </div>

                        {{-- KOLOM KANAN: DISTRIBUTION CHART --}}
                        <div class="lg:col-span-1 space-y-6 reveal" style="animation-delay: 0.2s;">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors"><svg class="w-5 h-5 text-emerald-500 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg> Rasio Kesulitan Soal</h3>
                            <div class="glass-card rounded-3xl p-6 flex flex-col items-center justify-center h-auto lg:h-[calc(100%-3rem)] transition-colors">
                                <div class="w-40 h-40 md:w-48 md:h-48 relative">
                                    <canvas id="difficultyChart"></canvas>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                        <span class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white transition-colors">{{ $totalQuestions }}</span>
                                        <span class="text-[9px] md:text-[10px] uppercase text-slate-500 dark:text-white/40 font-bold tracking-widest transition-colors">Total</span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-2 mt-6 w-full text-center">
                                    <div class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 transition-colors">
                                        <span class="block text-emerald-600 dark:text-emerald-400 font-bold text-xs md:text-sm">{{ $questions->where('status', 'Mudah')->count() }}</span>
                                        <span class="text-[8px] md:text-[9px] text-slate-500 dark:text-white/40 uppercase transition-colors">Mudah</span>
                                    </div>
                                    <div class="p-2 rounded-xl bg-yellow-50 dark:bg-yellow-500/10 border border-yellow-200 dark:border-yellow-500/20 transition-colors">
                                        <span class="block text-yellow-600 dark:text-yellow-400 font-bold text-xs md:text-sm">{{ $questions->where('status', 'Sedang')->count() }}</span>
                                        <span class="text-[8px] md:text-[9px] text-slate-500 dark:text-white/40 uppercase transition-colors">Sedang</span>
                                    </div>
                                    <div class="p-2 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 transition-colors">
                                        <span class="block text-red-600 dark:text-red-400 font-bold text-xs md:text-sm">{{ $hardQuestionsCount }}</span>
                                        <span class="text-[8px] md:text-[9px] text-slate-500 dark:text-white/40 uppercase transition-colors">Sulit</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. FINAL EXAM CARD (SPECIAL) --}}
                    @if($finalExam->count() > 0)
                    @php
                        $finalCnt = $finalExam->count();
                        $finalAcc = $finalCnt > 0 ? round($finalExam->avg('accuracy')) : 0;
                    @endphp
                    <div class="mb-8 md:mb-12 reveal" style="animation-delay: 0.3s;">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2 transition-colors"><svg class="w-5 h-5 text-yellow-500 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg> Evaluasi Akhir</h3>
                        <div @click="selectChapter(99, 'FINAL EXAM: Evaluasi Akhir')" 
                            class="glass-card rounded-3xl p-6 md:p-8 cursor-pointer group hover:border-yellow-400/50 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 md:gap-8 bg-gradient-to-r from-yellow-50 to-transparent dark:from-yellow-900/10 dark:to-transparent border-t-2 border-yellow-400 dark:border-yellow-500/50 transition-colors">
                            
                            <div class="card-bg-gfx">
                                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03] dark:opacity-10 transition-opacity"></div>
                                <div class="absolute -left-20 -top-20 w-64 h-64 bg-yellow-400/20 dark:bg-yellow-500/10 rounded-full blur-[80px] group-hover:bg-yellow-400/30 dark:group-hover:bg-yellow-500/20 transition duration-500"></div>
                            </div>

                            <div class="relative z-10 flex-1">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-yellow-100 dark:bg-yellow-500/10 border border-yellow-200 dark:border-yellow-500/20 text-yellow-700 dark:text-yellow-400 text-[10px] md:text-xs font-bold uppercase tracking-widest mb-3 transition-colors">
                                    <span class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></span> 
                                </div>
                                <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mb-2 transition-colors">Evaluasi Akhir (Bab 1 - 3)</h3>
                                <p class="text-xs text-slate-600 dark:text-white/60 transition-colors">Kumpulan seluruh soal teori dari semua materi untuk menguji tingkat pemahaman komprehensif siswa.</p>
                            </div>

                            <div class="relative z-10 flex gap-6 md:gap-8 text-center w-full md:w-auto justify-around md:justify-end border-t md:border-none border-slate-200 dark:border-white/10 pt-4 md:pt-0 transition-colors">
                                <div>
                                    <p class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white group-hover:scale-110 transition">{{ $finalCnt }}</p>
                                    <p class="text-[9px] md:text-[10px] text-slate-500 dark:text-white/40 uppercase font-bold tracking-widest mt-1 transition-colors">Soal</p>
                                </div>
                                <div>
                                    <p class="text-3xl md:text-4xl font-black {{ $finalAcc >= 70 ? 'text-emerald-600 dark:text-emerald-400' : 'text-yellow-600 dark:text-yellow-400' }} group-hover:scale-110 transition">{{ $finalAcc }}%</p>
                                    <p class="text-[9px] md:text-[10px] text-slate-500 dark:text-white/40 uppercase font-bold tracking-widest mt-1 transition-colors">Avg Skor</p>
                                </div>
                                <div class="flex items-center hidden sm:flex">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-500 dark:text-white group-hover:bg-yellow-500 group-hover:text-slate-900 transition shadow-sm dark:shadow-none">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- 4. TABEL SELURUH SISWA DENGAN TOMBOL "LEMBAR JAWABAN" --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 reveal" style="animation-delay: 0.4s;">
                        
                        {{-- KIRI: Daftar Seluruh Siswa dengan Search Bar --}}
                        <div class="glass-card rounded-2xl p-6 flex flex-col h-full border-t-2 border-amber-400 dark:border-amber-500/50" x-data="{ searchStudent: '' }">
                            <div class="flex flex-col gap-3 mb-4 pb-4 border-b border-slate-200 dark:border-white/5 transition-colors">
                                <div>
                                    <h3 class="text-lg font-bold text-adaptive flex items-center gap-2">
                                        <svg class="w-5 h-5 text-amber-500 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                        Daftar Seluruh Evaluasi Siswa
                                    </h3>
                                    <p class="text-[10px] text-adaptive-muted mt-1 font-mono">Diurutkan berdasarkan rata-rata nilai kuis tertinggi.</p>
                                </div>
                                {{-- Pencarian Siswa Khusus Box Ini --}}
                                <div class="relative w-full group mt-1">
                                    <input x-model="searchStudent" type="text" placeholder="Cari nama atau email siswa..." class="w-full bg-white dark:bg-[#0a0e17] border border-slate-200 dark:border-white/10 rounded-xl pl-9 pr-4 py-2.5 text-xs text-slate-900 dark:text-white focus:border-amber-500 outline-none transition shadow-sm dark:shadow-inner placeholder-slate-400 dark:placeholder-white/30">
                                    <div class="absolute left-3 top-2.5 text-slate-400 dark:text-white/30 group-focus-within:text-amber-500 transition"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
                                </div>
                            </div>
                            
                            <div class="flex-1 overflow-y-auto custom-scrollbar space-y-3 pr-2 max-h-[400px]">
                                @forelse($studentStats as $index => $stat)
                                <div class="flex items-center gap-4 p-3 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/50 border border-slate-200 dark:border-white/5 transition-colors hover:border-yellow-300 dark:hover:border-yellow-500/30 group"
                                     x-show="searchStudent === '' || '{{ strtolower(addslashes($stat->name)) }}'.includes(searchStudent.toLowerCase()) || '{{ strtolower(addslashes($stat->email)) }}'.includes(searchStudent.toLowerCase())"
                                     x-transition>
                                 
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-adaptive truncate">{{ $stat->name }}</p>
                                        <p class="text-[9px] text-adaptive-muted mt-0.5 truncate">{{ $stat->email }}</p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="text-right">
                                            <span class="block text-sm font-black text-emerald-600 dark:text-emerald-400">{{ $stat->avg_score }}</span>
                                            <div class="w-16 h-1 bg-slate-200 dark:bg-white/10 rounded-full mt-1 ml-auto">
                                                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $stat->avg_score }}%"></div>
                                            </div>
                                            <span class="text-[8px] text-adaptive-muted font-mono bg-slate-200 dark:bg-white/5 px-1.5 py-0.5 rounded mt-1 inline-block">{{ $stat->total_attempts }} Kuis</span>
                                        </div>
                                        {{-- TOMBOL: BUKA MODAL DETAIL JAWABAN SISWA --}}
                                        <div class="tooltip-container tooltip-indigo tooltip-down tooltip-left">
                                            <button @click="openStudentDetail('{{ addslashes($stat->email) }}')" class="p-2 rounded-lg bg-white dark:bg-[#020617] hover:bg-indigo-600 dark:hover:bg-indigo-500 text-slate-400 hover:text-white transition-colors shadow-sm border border-slate-200 dark:border-white/10">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>
                                            <div class="tooltip-content" style="width:150px; text-align:center;">Lihat Lembar Jawaban</div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-10 text-adaptive-muted text-xs italic">Belum ada data siswa.</div>
                                @endforelse
                            </div>
                        </div>

                        {{-- KANAN: Aktivitas Pengerjaan Kuis Terbaru --}}
                        <div class="glass-card rounded-2xl p-6 flex flex-col h-full border-t-2 border-cyan-500 dark:border-cyan-500/50">
                            <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-200 dark:border-white/5 transition-colors">
                                <div>
                                    <h3 class="text-lg font-bold text-adaptive flex items-center gap-2">
                                        <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Log Evaluasi Terbaru
                                    </h3>
                                    <p class="text-[10px] text-adaptive-muted mt-1 font-mono">Penyelesaian kuis secara *real-time*.</p>
                                </div>
                            </div>

                            <div class="flex-1 overflow-y-auto custom-scrollbar space-y-3 pr-2 max-h-[400px]">
                                @forelse($recentAttempts as $act)
                                    @php $isPassed = $act->score >= 70; @endphp
                                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/80 border border-slate-200 dark:border-white/5 hover:border-cyan-300 dark:hover:border-cyan-500/30 transition-colors group">
                                        <div class="flex justify-between items-start mb-2 gap-2">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-white/10 text-adaptive flex items-center justify-center text-[10px] font-bold shrink-0 shadow-inner transition-colors">
                                                    {{ substr($act->user->name ?? 'U', 0, 1) }}
                                                </div>
                                                <p class="text-xs font-bold text-adaptive truncate">{{ $act->user->name ?? 'Unknown User' }}</p>
                                            </div>
                                            <span class="text-[8px] font-bold px-2 py-0.5 rounded border transition-colors shrink-0 {{ $isPassed ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/20' : 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 border-red-200 dark:border-red-500/20' }}">
                                                {{ $isPassed ? 'Lulus' : 'Gagal' }}
                                            </span>
                                        </div>
                                        
                                        <div class="flex items-start gap-3 mt-1.5">
                                            <div class="flex-1 min-w-0 pl-8">
                                                <p class="text-[11px] font-medium leading-snug transition-colors text-adaptive">
                                                    {{ $act->chapter_id == 99 ? 'Evaluasi Akhir' : 'Kuis Bab ' . $act->chapter_id }}
                                                    <span class="{{ $isPassed ? 'text-emerald-600 dark:text-emerald-500' : 'text-red-600 dark:text-red-500' }} font-black ml-1">({{ $act->score }} Pts)</span>
                                                </p>
                                                <div class="flex items-center justify-between mt-1.5">
                                                    <p class="text-[9px] text-adaptive-muted font-mono transition-colors flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                        {{ \Carbon\Carbon::parse($act->created_at)->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-10 text-adaptive-muted text-xs italic">Belum ada aktivitas kuis terbaru.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- =======================================================
                     VIEW 2: DETAIL TABLE BANK SOAL (DRILL DOWN PER BAB)
                     ======================================================= --}}
                <div x-show="currentView === 'table'" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    
                    {{-- Controls --}}
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                        <div class="relative w-full md:w-96 group">
                            <input x-model="search" type="text" placeholder="Cari teks pertanyaan, atau opsi jawaban..." class="w-full bg-white dark:bg-[#0a0e17] border border-slate-200 dark:border-white/10 rounded-xl pl-10 pr-4 py-3 text-xs md:text-sm text-slate-900 dark:text-white focus:border-indigo-500 outline-none transition shadow-sm dark:shadow-inner placeholder-slate-400 dark:placeholder-white/30">
                            <div class="absolute left-3 top-3 md:top-3.5 text-slate-400 dark:text-white/30 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
                        </div>
                        <div class="flex gap-3 w-full md:w-auto">
                            <select x-model="difficulty" class="w-full md:w-auto bg-white dark:bg-[#0a0e17] border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white text-xs rounded-xl px-4 py-3 outline-none focus:border-indigo-500 cursor-pointer min-w-[150px] shadow-sm dark:shadow-inner transition-colors">
                                <option value="all">Semua Kesulitan</option>
                                <option value="Sulit">🔥 Sulit (< 50%)</option>
                                <option value="Sedang">⚖️ Sedang (50-79%)</option>
                                <option value="Mudah">✅ Mudah (≥ 80%)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Main Table Bank Soal (Tampil jika ada $questions) --}}
                    @if($questions->count() > 0)
                    <div class="glass-card rounded-2xl overflow-hidden border-t border-slate-200 dark:border-white/10 transition-colors">
                        <div class="overflow-x-auto custom-scrollbar rounded-2xl bg-white/50 dark:bg-transparent">
                            <table class="w-full text-sm text-left min-w-[800px]">
                                <thead class="bg-slate-100 dark:bg-[#0f141e] text-slate-500 dark:text-white/40 text-[10px] uppercase font-bold border-b border-slate-200 dark:border-white/5 sticky top-0 z-10 shadow-sm dark:shadow-lg transition-colors">
                                    <tr>
                                        <th class="px-6 py-4 w-[50%]">Teks Pertanyaan & Opsi Jawaban</th>
                                        <th class="px-6 py-4 text-center">Analisis Rasio Jawaban</th>
                                        <th class="px-6 py-4 text-center">Status Label</th>
                                        <th class="px-6 py-4 text-right">Aksi Panel</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-white/5 bg-slate-50/50 dark:bg-[#0a0e17]/30 transition-colors">
                                    @foreach($questions as $q)
                                    <tr class="hover:bg-slate-100/50 dark:hover:bg-white/5 transition-colors group question-row" 
                                        x-show="(activeChapter === 'all' || activeChapter == {{ $q->chapter_id ?? 0 }}) && 
                                                ('{{ strtolower($q->question_text ?? '') }}'.includes(search.toLowerCase())) &&
                                                (difficulty === 'all' || difficulty === '{{ $q->status ?? '' }}')"
                                        x-transition>
                                        <td class="px-6 py-5 align-top">
                                            <div class="flex items-start gap-3">
                                                <span class="px-2 py-1 bg-slate-200 dark:bg-white/10 text-slate-500 dark:text-white/50 text-[9px] font-bold rounded-md whitespace-nowrap mt-0.5 shadow-inner transition-colors">BAB {{ $q->chapter_id }}</span>
                                                <div>
                                                    <p class="text-slate-900 dark:text-white font-medium text-xs md:text-sm leading-relaxed mb-3 group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors pr-4">{{ $q->question_text }}</p>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 opacity-80 sm:opacity-60 group-hover:opacity-100 transition-opacity">
                                                        @if(isset($q->options))
                                                            @foreach($q->options as $idx => $opt)
                                                                <div class="flex items-start gap-2 text-[10px] md:text-xs transition-colors {{ $opt->is_correct ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-600 dark:text-white/50 sm:text-slate-500 sm:dark:text-white/40' }}">
                                                                    <span class="uppercase w-4 shrink-0">{{ ['A','B','C','D'][$idx] ?? '' }}.</span>
                                                                    <span class="truncate pr-2">{{ Str::limit($opt->option_text, 50) }}</span>
                                                                    @if($opt->is_correct) <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> @endif
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 align-middle">
                                            <div class="flex flex-col gap-2">
                                                <div class="flex justify-between text-[9px] md:text-[10px] font-bold text-slate-500 dark:text-white/60 px-4 transition-colors">
                                                    <span class="text-emerald-600 dark:text-emerald-400">{{ $q->correct_count ?? 0 }} Benar</span>
                                                    <span class="text-red-600 dark:text-red-400">{{ $q->wrong_count ?? 0 }} Salah</span>
                                                </div>
                                                <div class="w-24 md:w-32 h-1.5 md:h-2 bg-slate-200 dark:bg-[#020617] rounded-full overflow-hidden flex mx-auto border border-slate-300 dark:border-white/5 shadow-inner transition-colors">
                                                    @if(isset($q->total_attempts) && $q->total_attempts > 0)
                                                        <div class="h-full bg-emerald-500" style="width: {{ $q->accuracy }}%"></div>
                                                        <div class="h-full bg-red-500" style="width: {{ 100 - $q->accuracy }}%"></div>
                                                    @else
                                                        <div class="w-full h-full bg-slate-300 dark:bg-white/5 transition-colors"></div>
                                                    @endif
                                                </div>
                                                <div class="text-center mt-1 transition-colors"><span class="text-xs font-black text-slate-800 dark:text-white">{{ $q->total_attempts ?? 0 }}</span> <span class="text-[9px] md:text-[10px] text-slate-500 dark:text-white/30">Total Dicoba</span></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 text-center align-middle">
                                            <span class="px-2 py-1 rounded text-[9px] md:text-[10px] font-bold uppercase border whitespace-nowrap transition-colors
                                                {{ ($q->status ?? '') == 'Sulit' ? 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border-red-200 dark:border-red-500/20' : 
                                                  (($q->status ?? '') == 'Sedang' ? 'bg-yellow-50 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 border-yellow-200 dark:border-yellow-500/20' : 
                                                  'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20') }}">
                                                {{ $q->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-right align-middle">
                                            <div class="flex justify-end gap-2 sm:opacity-0 group-hover:opacity-100 transition duration-300">
                                                <button onclick='openInsightModal(@json($q->list_correct ?? []), @json($q->list_wrong ?? []))' class="p-2 rounded-lg bg-white dark:bg-[#020617] hover:bg-indigo-600 dark:hover:bg-indigo-500 text-slate-500 dark:text-indigo-400 hover:text-white transition shadow-sm dark:shadow-inner border border-slate-200 dark:border-white/10 hover:border-indigo-500 dark:hover:border-indigo-400" title="Lihat Daftar Siswa"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>
                                                <button onclick='openModal("edit", @json($q))' class="p-2 rounded-lg bg-white dark:bg-[#020617] hover:bg-amber-500 dark:hover:bg-amber-500 text-slate-500 dark:text-amber-400 hover:text-white transition shadow-sm dark:shadow-inner border border-slate-200 dark:border-white/10 hover:border-amber-500 dark:hover:border-amber-400" title="Edit Soal"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                                <button onclick="confirmDelete('{{ $q->id }}')" class="p-2 rounded-lg bg-white dark:bg-[#020617] hover:bg-red-500 dark:hover:bg-red-500 text-slate-500 dark:text-red-400 hover:text-white transition shadow-sm dark:shadow-inner border border-slate-200 dark:border-white/10 hover:border-red-500 dark:hover:border-red-400" title="Hapus Soal"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="glass-card rounded-2xl p-10 text-center flex flex-col items-center justify-center min-h-[300px] opacity-60">
                        <div class="text-4xl mb-4 grayscale">📂</div>
                        <h3 class="text-slate-900 dark:text-white font-bold transition-colors">Data Bank Soal Kosong</h3>
                        <p class="text-xs text-slate-500 dark:text-white/50 mt-2 transition-colors">Tidak ada data pertanyaan yang ditemukan di database untuk bab ini.</p>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </main>
</div>

{{-- ==================== MODALS HERO INSIGHTS ==================== --}}

{{-- 1. MODAL BARU ULTIMATE: DETAIL JAWABAN PER SISWA (GRADING SHEET) --}}
<div x-show="showStudentDetailModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 md:p-10" style="display: none;" x-transition.opacity>
    <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showStudentDetailModal = false"></div>
    <div class="relative w-full max-w-4xl bg-white dark:bg-[#0f141e] border border-indigo-200 dark:border-indigo-500/40 rounded-2xl shadow-2xl dark:shadow-[0_20px_70px_rgba(99,102,241,0.15)] p-0 transition-colors duration-500 overflow-hidden flex flex-col h-full max-h-[85vh] md:max-h-full" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
        
        {{-- Header Modal Detail --}}
        <div class="p-6 border-b border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-[#0a0e17] flex justify-between items-center transition-colors shrink-0">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center text-xl font-bold text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/30 transition-colors" x-text="selectedStudent ? selectedStudent.name.charAt(0) : 'U'"></div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white transition-colors" x-text="selectedStudent ? selectedStudent.name : 'Memuat data...'"></h3>
                        <p class="text-xs text-slate-500 dark:text-white/50 font-mono transition-colors" x-text="selectedStudent ? selectedStudent.email : '...'"></p>
                    </div>
                </div>
                {{-- Global Score Info inside Header --}}
                <div class="border-l border-slate-200 dark:border-white/10 pl-6 flex gap-4 hidden sm:flex">
                    <div>
                        <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 dark:text-white/40">Total Evaluasi</p>
                        <p class="text-base font-black text-slate-700 dark:text-white" x-text="selectedStudent?.summary_total + ' Sesi'"></p>
                    </div>
                    <div>
                        <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 dark:text-white/40">Rata-Rata Nilai</p>
                        <p class="text-base font-black text-emerald-600 dark:text-emerald-400" x-text="selectedStudent?.summary_score + ' Pts'"></p>
                    </div>
                </div>
            </div>
            <button @click="showStudentDetailModal = false" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition bg-slate-200 dark:bg-white/5 hover:bg-red-100 dark:hover:bg-red-500/20 p-2.5 rounded-full border border-transparent dark:hover:border-red-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        {{-- Body Modal Scrollable (Looping Data Jawaban Asli AlpineJS) --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-6">
            <template x-if="selectedStudent && Object.keys(selectedStudent.chapters).length > 0">
                <div>
                    <template x-for="(chapter, chId) in selectedStudent.chapters" :key="chId">
                        <div class="border border-slate-200 dark:border-white/10 rounded-xl overflow-hidden transition-colors mb-6" x-data="{ open: true }">
                            {{-- Accordion Header --}}
                            <div class="bg-slate-50 dark:bg-[#0a0e17]/50 p-4 flex justify-between items-center cursor-pointer hover:bg-slate-100 dark:hover:bg-white/5 transition-colors" @click="open = !open">
                                <div class="flex items-center gap-3">
                                    <span class="px-2 py-1 bg-slate-200 dark:bg-white/10 text-slate-500 dark:text-white/50 text-[10px] font-bold rounded uppercase tracking-widest transition-colors" x-text="chId == 99 ? 'FINAL' : 'BAB ' + chId"></span>
                                    <h4 class="font-bold text-slate-900 dark:text-white text-sm transition-colors" x-text="chapter.title"></h4>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-xs font-bold" :class="chapter.score >= 70 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400'" x-text="'Skor: ' + chapter.score"></span>
                                    <span class="text-[10px] text-slate-500 dark:text-white/40 transition-colors border-l border-slate-300 dark:border-white/10 pl-4"><span x-text="chapter.answers.length"></span> Soal</span>
                                    <svg class="w-4 h-4 text-slate-400 dark:text-white/40 transition-transform" :class="{'rotate-180': !open}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            
                            {{-- Accordion Body (Cards Jawaban Per Soal) --}}
                            <div x-show="open" x-transition class="bg-white dark:bg-transparent border-t border-slate-200 dark:border-white/5 transition-colors p-4 md:p-6">
                                <template x-for="(ans, index) in chapter.answers" :key="index">
                                    <div class="p-4 border border-slate-100 dark:border-white/5 rounded-xl mb-4 bg-slate-50/50 dark:bg-white/[0.02] transition-colors relative overflow-hidden">
                                        <div class="flex justify-between items-start gap-4 mb-3">
                                            <p class="font-medium text-xs md:text-sm text-slate-800 dark:text-white leading-relaxed">
                                                <span class="text-slate-400 dark:text-white/40 font-bold mr-1" x-text="(index + 1) + '.'"></span>
                                                <span x-text="ans.question"></span>
                                            </p>
                                            <span class="px-2 py-1 rounded text-[9px] font-bold shrink-0 shadow-sm transition-colors"
                                                  :class="ans.is_correct == 1 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30' : 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400 border border-red-200 dark:border-red-500/30'"
                                                  x-text="ans.is_correct == 1 ? 'BENAR' : 'SALAH'"></span>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
                                            {{-- Box Jawaban Siswa --}}
                                            <div class="p-3 rounded-lg border border-slate-200 dark:border-white/5 bg-white dark:bg-[#0a0e17] transition-colors">
                                                <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 dark:text-white/40 mb-1.5 flex items-center gap-1.5">
                                                    Jawaban Siswa
                                                </p>
                                                <p class="text-xs font-mono font-medium transition-colors" 
                                                   :class="ans.is_correct == 1 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400 line-through opacity-80'" 
                                                   x-text="ans.chosen"></p>
                                            </div>
                                            
                                            {{-- Box Kunci Jawaban (Hanya muncul jika salah) --}}
                                            <div class="p-3 rounded-lg border border-emerald-200 dark:border-emerald-500/20 bg-emerald-50 dark:bg-emerald-500/10 transition-colors" x-show="ans.is_correct == 0">
                                                <p class="text-[9px] uppercase tracking-widest font-bold text-emerald-600/70 dark:text-emerald-400/70 mb-1.5 flex items-center gap-1.5">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                    Kunci Jawaban
                                                </p>
                                                <p class="text-xs font-mono text-emerald-700 dark:text-emerald-400 font-bold transition-colors" x-text="ans.correct"></p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
            
            {{-- Fallback Kosong --}}
            <template x-if="!selectedStudent || Object.keys(selectedStudent.chapters).length === 0">
                <div class="flex flex-col items-center justify-center py-20 opacity-60">
                    <div class="text-4xl mb-4 grayscale">📂</div>
                    <h3 class="text-slate-900 dark:text-white font-bold transition-colors">Belum Ada Riwayat Jawaban</h3>
                    <p class="text-xs text-slate-500 dark:text-white/50 mt-2 transition-colors">Siswa ini belum menyelesaikan evaluasi apapun.</p>
                </div>
            </template>
        </div>
    </div>
</div>

{{-- 2. MODAL: TOTAL QUESTIONS --}}
<div x-show="showQuestionsModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
    <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showQuestionsModal = false"></div>
    <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f141e] border border-cyan-200 dark:border-cyan-500/40 rounded-2xl shadow-xl dark:shadow-[0_20px_70px_rgba(6,182,212,0.15)] p-6 transition-colors duration-500 overflow-hidden" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
        <div class="flex justify-between items-center mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Tinjauan Basis Data Soal
                </h3>
                <p class="text-[10px] text-cyan-600 dark:text-cyan-400 mt-1 font-mono transition-colors">Daftar seluruh soal teori yang tersedia di sistem.</p>
            </div>
            <button @click="showQuestionsModal = false" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-red-500/20 rounded-full p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-2 pr-2">
            @forelse($questions as $q)
            <div class="flex items-center justify-between gap-4 p-3.5 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/80 border border-slate-200 dark:border-white/5 hover:border-cyan-300 dark:hover:border-cyan-500/30 transition-colors group">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate transition-colors" title="{{ $q->question_text }}">{{ $q->question_text }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-white/50 font-mono mt-0.5 transition-colors">Bab {{ $q->chapter_id }}</p>
                </div>
                <div class="text-right shrink-0">
                    <span class="text-[9px] font-bold uppercase tracking-widest border px-2 py-1 rounded transition-colors {{ ($q->status ?? '') == 'Sulit' ? 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border-red-200 dark:border-red-500/20' : (($q->status ?? '') == 'Sedang' ? 'bg-yellow-50 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 border-yellow-200 dark:border-yellow-500/20' : 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20') }}">{{ $q->status }}</span>
                </div>
            </div>
            @empty
            <p class="text-[11px] text-slate-500 dark:text-white/40 text-center py-10 transition-colors">Belum ada data soal yang dimasukkan.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- 3. MODAL: PARTICIPANTS --}}
<div x-show="showParticipantsModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
    <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showParticipantsModal = false"></div>
    <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f141e] border border-indigo-200 dark:border-indigo-500/40 rounded-2xl shadow-xl dark:shadow-[0_20px_70px_rgba(99,102,241,0.15)] p-6 transition-colors duration-500 overflow-hidden" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
        <div class="flex justify-between items-center mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Daftar Peserta Kuis
                </h3>
                <p class="text-[10px] text-indigo-600 dark:text-indigo-400 mt-1 font-mono transition-colors">Data siswa unik yang telah berpartisipasi mencoba kuis.</p>
            </div>
            <button @click="showParticipantsModal = false" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-red-500/20 rounded-full p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-2 pr-2">
            @forelse($studentStats as $stat)
            <div class="flex items-center gap-4 p-3.5 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/80 border border-slate-200 dark:border-white/5 hover:border-indigo-300 dark:hover:border-indigo-500/30 transition-colors group">
                <div class="w-10 h-10 rounded-full bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-sm font-bold text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/30 shrink-0 transition-colors">{{ substr($stat->name, 0, 2) }}</div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate transition-colors">{{ $stat->name }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-white/50 font-mono mt-0.5 transition-colors">{{ $stat->email }}</p>
                </div>
                <div class="text-right shrink-0">
                    <span class="block text-sm font-black transition-colors {{ $stat->avg_score >= 70 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">{{ $stat->avg_score }} Avg</span>
                    <span class="text-[9px] text-slate-500 dark:text-white/40 transition-colors">{{ $stat->total_attempts }} Evaluasi Selesai</span>
                </div>
            </div>
            @empty
            <p class="text-[11px] text-slate-500 dark:text-white/40 text-center py-10 transition-colors">Belum ada siswa yang berpartisipasi.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- 4. MODAL: GLOBAL ACCURACY (PER CHAPTER) --}}
<div x-show="showAccuracyModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
    <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showAccuracyModal = false"></div>
    <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f141e] border border-emerald-200 dark:border-emerald-500/40 rounded-2xl shadow-xl dark:shadow-[0_20px_70px_rgba(16,185,129,0.15)] p-6 transition-colors duration-500 overflow-hidden" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
        <div class="flex justify-between items-center mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Rincian Akurasi per Bab
                </h3>
                <p class="text-[10px] text-emerald-600 dark:text-emerald-400 mt-1 font-mono transition-colors">Menampilkan persentase ketepatan jawaban rata-rata untuk setiap materi.</p>
            </div>
            <button @click="showAccuracyModal = false" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-red-500/20 rounded-full p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
            @forelse($chapterGroups as $id => $chQs)
            @php 
                $avgCh = $chQs->count() > 0 ? round($chQs->avg('accuracy'), 1) : 0; 
            @endphp
            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/80 border border-slate-200 dark:border-white/5 hover:border-emerald-300 dark:hover:border-emerald-500/30 transition-colors group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center font-bold text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30 transition-colors">
                        0{{ $id }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900 dark:text-white transition-colors">Materi Bab {{ $id }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-white/50 mt-0.5 transition-colors">{{ $chQs->count() }} Soal Dievaluasi</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-black transition-colors {{ $avgCh >= 70 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">{{ $avgCh }}%</span>
                </div>
            </div>
            @empty
            <p class="text-[11px] text-slate-500 dark:text-white/40 text-center py-10 transition-colors">Belum ada data akurasi yang dihitung.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- 5. MODAL: HARD QUESTIONS (SULIT SAJA) --}}
<div x-show="showHardModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
    <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showHardModal = false"></div>
    <div class="relative w-full max-w-3xl bg-white dark:bg-[#0f141e] border border-red-200 dark:border-red-500/40 rounded-2xl shadow-xl dark:shadow-[0_20px_70px_rgba(239,68,68,0.15)] p-6 transition-colors duration-500 overflow-hidden" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
        <div class="flex justify-between items-center mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77-1.333.192 3 1.732 3z"/></svg>
                    Daftar Soal Kritis (Banyak yang Gagal)
                </h3>
                <p class="text-[10px] text-red-600 dark:text-red-400 mt-1 font-mono transition-colors">Hanya menampilkan soal dengan rasio kegagalan > 50%</p>
            </div>
            <button @click="showHardModal = false" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-red-500/20 rounded-full p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <div class="max-h-[60vh] overflow-y-auto custom-scrollbar pr-2">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-white/40 text-[10px] uppercase font-bold sticky top-0 z-10 transition-colors">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg border-b border-slate-200 dark:border-white/5">Kutipan Soal</th>
                        <th class="px-4 py-3 text-center border-b border-slate-200 dark:border-white/5">Bab</th>
                        <th class="px-4 py-3 text-center border-b border-slate-200 dark:border-white/5">Salah / Total Coba</th>
                        <th class="px-4 py-3 text-right rounded-tr-lg border-b border-slate-200 dark:border-white/5">Rasio Kegagalan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-white/5 bg-slate-50/50 dark:bg-[#0a0e17]/30 transition-colors">
                    @forelse($questions->where('status', 'Sulit') as $q)
                    <tr class="hover:bg-red-50 dark:hover:bg-red-500/5 transition-colors group">
                        <td class="px-4 py-4 text-slate-700 dark:text-white/80 text-[11px] transition-colors" title="{{ $q->question_text }}">
                            {{ \Illuminate\Support\Str::limit($q->question_text, 60) }}
                        </td>
                        <td class="px-4 py-4 text-center text-slate-500 dark:text-white/50 text-[10px] font-bold transition-colors">Bab {{ $q->chapter_id }}</td>
                        <td class="px-4 py-4 text-center transition-colors">
                            <span class="text-red-600 dark:text-red-400 font-bold">{{ $q->wrong_count }}</span> <span class="text-slate-400 dark:text-white/30">/ {{ $q->total_attempts }}</span>
                        </td>
                        <td class="px-4 py-4 text-right transition-colors">
                            <span class="px-2 py-1 rounded bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 font-black text-[10px] transition-colors">{{ 100 - $q->accuracy }}%</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-10 text-emerald-600 dark:text-emerald-400 text-xs font-bold uppercase tracking-widest transition-colors">Aman! Tidak Ditemukan Soal Sulit.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ==================== MODALS PENGOLAHAN SOAL (FORM/INSIGHT) ==================== --}}

{{-- MODAL CREATE/EDIT QUESTION --}}
<div id="quizModal" class="fixed inset-0 z-[999999] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/90 dark:bg-[#020617]/90 backdrop-blur-md transition-opacity" onclick="closeModal()"></div>
    <div id="modalContent" class="relative w-full max-w-2xl bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-3xl shadow-2xl dark:shadow-[0_20px_70px_rgba(0,0,0,0.9)] transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[90vh]">
        <div class="p-5 md:p-6 border-b border-slate-200 dark:border-white/5 flex justify-between items-center bg-slate-50 dark:bg-[#0a0e17] rounded-t-3xl transition-colors">
            <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors" id="modalTitle"><span class="p-1.5 bg-indigo-100 dark:bg-indigo-500/20 rounded-lg text-indigo-700 dark:text-indigo-400 text-[10px] tracking-widest border border-indigo-200 dark:border-indigo-500/30 shadow-inner transition-colors">BARU</span> Tambah Soal</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition bg-slate-200 dark:bg-white/5 hover:bg-red-100 dark:hover:bg-red-500/20 p-2 rounded-full border border-transparent dark:hover:border-red-500/30"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <div class="p-5 md:p-6 overflow-y-auto custom-scrollbar flex-1 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] relative">
            <div class="absolute inset-0 bg-white/95 dark:bg-[#0f141e]/95 mix-blend-overlay pointer-events-none transition-colors"></div>
            <form id="quizForm" class="relative z-10">
                @csrf
                <input type="hidden" id="questionId" name="id">
                <div class="space-y-6">
                    <div><label class="text-[10px] font-bold text-slate-500 dark:text-white/50 uppercase tracking-wider mb-2 block transition-colors">Teks Pertanyaan</label><textarea name="question_text" id="inputQuestion" rows="3" class="w-full bg-white dark:bg-[#0a0e17] border border-slate-200 dark:border-white/10 rounded-xl p-4 text-slate-900 dark:text-white text-sm outline-none focus:border-indigo-500 transition resize-none shadow-sm dark:shadow-inner" placeholder="Tuliskan pertanyaan di sini..." required></textarea></div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 dark:text-white/50 uppercase tracking-wider mb-2 block transition-colors">Materi Bab</label>
                            <select name="chapter_id" id="inputChapter" class="w-full bg-white dark:bg-[#0a0e17] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-slate-900 dark:text-white text-sm outline-none focus:border-indigo-500 cursor-pointer shadow-sm dark:shadow-inner transition-colors">
                                <option value="1">Bab 1: Pendahuluan</option>
                                <option value="2">Bab 2: Layouting</option>
                                <option value="3">Bab 3: Styling</option>
                                <option value="99">Evaluasi Akhir (Final)</option>
                            </select>
                        </div>
                        <div><label class="text-[10px] font-bold text-slate-500 dark:text-white/50 uppercase tracking-wider mb-2 block transition-colors">Jawaban Benar</label><select name="correct_answer" id="inputCorrect" class="w-full bg-white dark:bg-[#0a0e17] border border-emerald-500/30 rounded-xl px-4 py-3 text-emerald-600 dark:text-emerald-400 text-sm font-bold outline-none focus:border-emerald-500 cursor-pointer shadow-sm dark:shadow-inner transition-colors"><option value="option_a">Pilihan A</option><option value="option_b">Pilihan B</option><option value="option_c">Pilihan C</option><option value="option_d">Pilihan D</option></select></div>
                    </div>
                    <div class="space-y-3"><label class="text-[10px] font-bold text-slate-500 dark:text-white/50 uppercase tracking-wider block transition-colors">Opsi Jawaban</label>
                        @foreach(['a','b','c','d'] as $opt)
                        <div class="flex items-center gap-3"><span class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-[#0a0e17] border border-slate-200 dark:border-white/10 shadow-inner flex items-center justify-center font-black text-slate-500 dark:text-white/50 text-xs uppercase shrink-0 transition-colors">{{ $opt }}</span><input type="text" name="option_{{ $opt }}" id="inputOption_{{ $opt }}" class="flex-1 bg-white dark:bg-[#0a0e17] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-slate-900 dark:text-white outline-none focus:border-indigo-500 text-sm shadow-sm dark:shadow-inner transition placeholder-slate-400 dark:placeholder-white/30" placeholder="Pilihan {{ strtoupper($opt) }}" required></div>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>
        <div class="p-5 md:p-6 border-t border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-[#0a0e17] flex justify-end gap-3 rounded-b-3xl transition-colors">
            <button onclick="closeModal()" class="px-5 md:px-6 py-2.5 rounded-xl text-slate-600 dark:text-white/60 hover:text-slate-900 dark:hover:text-white hover:bg-slate-200 dark:hover:bg-white/5 font-bold text-xs transition border border-transparent dark:hover:border-white/10">Batal</button>
            <button onclick="submitForm()" class="px-6 md:px-8 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md dark:shadow-[0_0_15px_rgba(99,102,241,0.4)] transition transform hover:-translate-y-0.5 border border-indigo-500 dark:border-indigo-400">Simpan Soal</button>
        </div>
    </div>
</div>

{{-- MODAL INSIGHT (DETAIL PENJAWAB BENAR/SALAH - BAGIAN TABEL BANK SOAL) --}}
<div id="insightModal" class="fixed inset-0 z-[999999] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/90 dark:bg-[#020617]/90 backdrop-blur-md transition-opacity" onclick="closeInsightModal()"></div>
    <div id="insightContent" class="relative w-full max-w-md bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-3xl shadow-2xl dark:shadow-[0_20px_70px_rgba(0,0,0,0.9)] transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[85vh]">
        <div class="p-5 md:p-6 border-b border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-[#0a0e17] flex justify-between items-center rounded-t-3xl transition-colors">
            <h3 class="font-bold text-slate-900 dark:text-white text-lg flex items-center gap-2 transition-colors"><svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> Tinjauan Siswa</h3>
            <button onclick="closeInsightModal()" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition bg-slate-200 dark:bg-white/5 hover:bg-red-100 dark:hover:bg-red-500/20 p-2 rounded-full border border-transparent dark:hover:border-red-500/30"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <div class="p-5 md:p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6">
            <div>
                <div class="flex items-center justify-between mb-3"><p class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest flex items-center gap-2 transition-colors"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Jawaban Benar</p><span id="countCorrect" class="text-[10px] bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 px-3 py-1 rounded-lg font-bold border border-emerald-200 dark:border-emerald-500/20 transition-colors">0 Siswa</span></div>
                <div id="listCorrect" class="grid grid-cols-1 sm:grid-cols-2 gap-2"></div>
            </div>
            <div class="h-px bg-slate-200 dark:bg-white/5 w-full transition-colors"></div>
            <div>
                <div class="flex items-center justify-between mb-3"><p class="text-[10px] font-bold text-red-600 dark:text-red-400 uppercase tracking-widest flex items-center gap-2 transition-colors"><span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Jawaban Salah</p><span id="countWrong" class="text-[10px] bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 px-3 py-1 rounded-lg font-bold border border-red-200 dark:border-red-500/20 transition-colors">0 Siswa</span></div>
                <div id="listWrong" class="grid grid-cols-1 sm:grid-cols-2 gap-2"></div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT: TEMA, AJAX & SWEETALERT --}}
<script>
    // --- SINKRONISASI TEMA GELAP/TERANG ---
    document.addEventListener('DOMContentLoaded', () => {
        const themeToggleBtnSidebar = document.getElementById('theme-toggle-sidebar');
        const themeToggleDarkIconSidebar = document.getElementById('theme-toggle-dark-icon-sidebar');
        const themeToggleLightIconSidebar = document.getElementById('theme-toggle-light-icon-sidebar');
        const themeToggleTextSidebar = document.getElementById('theme-toggle-text-sidebar');

        // Fungsi sinkronisasi ikon berdasarkan tema saat ini
        const syncIcons = (isDark) => {
            if (isDark) {
                themeToggleLightIconSidebar?.classList.remove('hidden');
                themeToggleDarkIconSidebar?.classList.add('hidden');
                if(themeToggleTextSidebar) themeToggleTextSidebar.textContent = "Tema Terang";
            } else {
                themeToggleLightIconSidebar?.classList.add('hidden');
                themeToggleDarkIconSidebar?.classList.remove('hidden');
                if(themeToggleTextSidebar) themeToggleTextSidebar.textContent = "Tema Gelap";
            }
        };

        // Inisialisasi awal
        const isDarkTheme = document.documentElement.classList.contains('dark');
        syncIcons(isDarkTheme);

        // Event listener saat tombol diklik
        themeToggleBtnSidebar?.addEventListener('click', function() {
            const willBeDark = !document.documentElement.classList.contains('dark');
            
            if (willBeDark) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            }
            
            syncIcons(willBeDark);
            window.dispatchEvent(new Event('theme-toggled'));
        });
    });

    // --- SETUP AJAX ---
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // --- CHART DONUT (TEMA ADAPTIF) ---
    let myChart = null;

    function initChart() {
        const ctx = document.getElementById('difficultyChart');
        if(ctx) {
            if(myChart) myChart.destroy();
            
            const isDark = document.documentElement.classList.contains('dark');
            const borderColor = isDark ? '#020617' : '#ffffff';

            myChart = new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Mudah', 'Sedang', 'Sulit'],
                    datasets: [{
                        data: [
                            {{ $questions->where('status', 'Mudah')->count() }},
                            {{ $questions->where('status', 'Sedang')->count() }},
                            {{ $questions->where('status', 'Sulit')->count() }}
                        ],
                        backgroundColor: ['#10b981', '#eab308', '#ef4444'],
                        borderColor: borderColor,
                        borderWidth: 2, 
                        hoverOffset: 4
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: {display: false} }, cutout: '75%' }
            });
        }
    }

    document.addEventListener("DOMContentLoaded", initChart);
    window.addEventListener('theme-toggled', initChart);

    // --- MODAL FUNCTIONS UNTUK BANK SOAL ---
    function openModal(mode, data = null) {
        $('#quizModal').removeClass('hidden');
        setTimeout(() => { $('#modalContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100'); }, 10);
        
        const isDark = document.documentElement.classList.contains('dark');
        
        if(mode === 'create') {
            const badge = isDark ? 'bg-indigo-500/20 text-indigo-400 border-indigo-500/30' : 'bg-indigo-100 text-indigo-700 border-indigo-200';
            $('#modalTitle').html(`<span class="p-1.5 ${badge} rounded-lg text-[10px] tracking-widest border shadow-inner transition-colors">BARU</span> Tambah Soal`);
            $('#quizForm')[0].reset(); $('#questionId').val(''); $('#inputChapter').val(1);
        } else {
            const badge = isDark ? 'bg-amber-500/20 text-amber-400 border-amber-500/30' : 'bg-amber-100 text-amber-700 border-amber-200';
            $('#modalTitle').html(`<span class="p-1.5 ${badge} rounded-lg text-[10px] tracking-widest border shadow-inner transition-colors">EDIT</span> Perbarui Soal`);
            $('#questionId').val(data.id); $('#inputQuestion').val(data.question_text); $('#inputChapter').val(data.chapter_id);
            if(data.options && data.options.length >= 4) {
                $('#inputOption_a').val(data.options[0].option_text); $('#inputOption_b').val(data.options[1].option_text);
                $('#inputOption_c').val(data.options[2].option_text); $('#inputOption_d').val(data.options[3].option_text);
                if(data.options[0].is_correct) $('#inputCorrect').val('option_a'); else if(data.options[1].is_correct) $('#inputCorrect').val('option_b');
                else if(data.options[2].is_correct) $('#inputCorrect').val('option_c'); else if(data.options[3].is_correct) $('#inputCorrect').val('option_d');
            }
        }
    }
    function closeModal() { $('#modalContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0'); setTimeout(() => { $('#quizModal').addClass('hidden'); }, 300); }
    
    function submitForm() {
        const form = $('#quizForm'); const id = $('#questionId').val(); const url = id ? `/admin/questions/update/${id}` : `{{ route('admin.questions.store') }}`;
        if(!form[0].checkValidity()) { form[0].reportValidity(); return; }
        
        const isDark = document.documentElement.classList.contains('dark');
        const bg = isDark ? '#0f141e' : '#ffffff';
        const color = isDark ? '#fff' : '#1e293b';

        Swal.fire({ title: 'Menyimpan...', didOpen: () => Swal.showLoading(), background: bg, color: color });
        $.post(url, form.serialize()).done((res) => { 
            Swal.fire({ title: 'Berhasil!', text: res.message, icon: 'success', background: bg, color: color, confirmButtonColor: '#6366f1' }).then(() => location.reload()); 
        }).fail((err) => { 
            Swal.fire({ title: 'Error', text: err.responseJSON?.message || 'Terjadi kesalahan sistem', icon: 'error', background: bg, color: color, confirmButtonColor: '#ef4444' }); 
        });
    }

    function confirmDelete(id) {
        const isDark = document.documentElement.classList.contains('dark');
        const bg = isDark ? '#0f141e' : '#ffffff';
        const color = isDark ? '#fff' : '#1e293b';
        const cancelBg = isDark ? '#334155' : '#e2e8f0';

        Swal.fire({ 
            title: 'Hapus Pertanyaan?', 
            text: "Tindakan ini akan menghapus soal secara permanen!", 
            icon: 'warning', 
            showCancelButton: true, 
            confirmButtonColor: '#ef4444', 
            cancelButtonColor: cancelBg, 
            confirmButtonText: 'Ya, Hapus!', 
            cancelButtonText: 'Batal', 
            background: bg, 
            color: color 
        }).then((result) => { 
            if (result.isConfirmed) { 
                $.ajax({ url: `/admin/questions/delete/${id}`, type: 'DELETE', success: function(res) { 
                    Swal.fire({ title: 'Terhapus!', text: res.message, icon: 'success', background: bg, color: color, confirmButtonColor: '#6366f1' }).then(() => location.reload()); 
                }}); 
            } 
        });
    }

    function openInsightModal(correct, wrong) {
        const isDark = document.documentElement.classList.contains('dark');

        $('#countCorrect').text(correct.length + ' Siswa'); $('#countWrong').text(wrong.length + ' Siswa');
        
        const renderList = (list, color) => list.length ? list.map(name => `
            <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-white dark:bg-[#0a0e17] border border-${color}-200 dark:border-${color}-500/20 text-xs text-slate-700 dark:text-white shadow-sm dark:shadow-inner transition hover:border-${color}-400 dark:hover:border-${color}-500/50">
                <div class="w-6 h-6 rounded-lg bg-${color}-50 dark:bg-${color}-500/20 border border-${color}-200 dark:border-${color}-500/30 text-${color}-600 dark:text-${color}-400 flex items-center justify-center font-bold text-[10px] shadow-sm dark:shadow-inner shrink-0">${name.charAt(0)}</div>
                <span class="font-medium truncate">${name}</span>
            </div>
        `).join('') : `
            <div class="col-span-full"><p class="text-[10px] text-slate-500 dark:text-white/30 italic pl-1 border border-dashed border-slate-300 dark:border-white/10 p-3 rounded-xl text-center bg-slate-50 dark:bg-[#0a0e17]/50 transition-colors">Tidak ada peserta pada kategori ini.</p></div>
        `;
        
        $('#listCorrect').html(renderList(correct, 'emerald')); $('#listWrong').html(renderList(wrong, 'red'));
        $('#insightModal').removeClass('hidden'); setTimeout(() => { $('#insightContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100'); }, 10);
    }
    function closeInsightModal() { $('#insightContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0'); setTimeout(() => { $('#insightModal').addClass('hidden'); }, 300); }
</script>
</body>
</html>