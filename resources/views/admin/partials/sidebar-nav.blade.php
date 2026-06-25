@php
    $dashboardAktif = request()->routeIs('admin.dashboard');
    $guideAktif = request()->routeIs('admin.guide');
    $quizAktif = request()->routeIs('admin.analytics.questions')
        || request()->routeIs('admin.learning-outcomes.*')
        || request()->routeIs('admin.questions.*');
    $bankSoalAktif = request()->routeIs('admin.analytics.questions');
    $tpAktif = request()->routeIs('admin.learning-outcomes.*');
    $questionBuatAktif = request()->routeIs('admin.questions.create');
    $labAktif = request()->routeIs('admin.labs.*')
        || request()->routeIs('admin.lab.analytics')
        || request()->routeIs('admin.student.analytics');
    $labConfigAktif = request()->routeIs('admin.labs.index');
    $labAnalyticsAktif = request()->routeIs('admin.lab.analytics');
    $labResultAktif = request()->routeIs('admin.labs.results.show');
    $studentAnalyticsAktif = request()->routeIs('admin.student.analytics');
    $studentDirectoryAktif = request()->routeIs('admin.students.*');
    $studentDetailAktif = request()->routeIs('admin.student.detail');
    $studentAktif = $studentDirectoryAktif
        || request()->routeIs('admin.classes.*')
        || $studentDetailAktif
        || request()->routeIs('admin.user.*')
        || request()->routeIs('admin.users.*');
    $classAktif = request()->routeIs('admin.classes.*');

    $sidebarStudent = $sidebarStudent ?? ($user ?? ($student ?? null));
    $sidebarStudentId = is_object($sidebarStudent)
        ? ($sidebarStudent->id ?? null)
        : (is_array($sidebarStudent) ? ($sidebarStudent['id'] ?? null) : null);
    $sidebarStudentName = is_object($sidebarStudent)
        ? ($sidebarStudent->name ?? 'Detail Siswa')
        : (is_array($sidebarStudent) ? ($sidebarStudent['name'] ?? 'Detail Siswa') : 'Detail Siswa');

    $navIconClass = fn (bool $active) => $active
        ? 'text-indigo-600 dark:text-indigo-400'
        : 'text-slate-400 dark:text-slate-500';
    $subLinkBase = 'sidebar-subnav-link group flex items-center gap-2 rounded-md px-2 py-1.5 text-[11px] font-semibold transition-colors';
    $subLinkIdle = 'text-slate-500 hover:text-slate-900 dark:text-white/45 dark:hover:text-white';
    $subLinkAktif = 'text-indigo-700 dark:text-indigo-200';
@endphp

<nav class="sidebar-nav flex-1 overflow-y-auto custom-scrollbar py-7 px-4 space-y-6">
    <section class="sidebar-section">
        <p class="sidebar-section-title px-4 text-[10px] font-extrabold text-slate-400 dark:text-white/30 uppercase tracking-widest mb-3 transition-colors">Ikhtisar</p>
        <div class="sidebar-menu space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ $dashboardAktif ? 'active' : '' }}">
                <svg class="w-5 h-5 {{ $navIconClass($dashboardAktif) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dasbor
            </a>
            <a href="{{ route('admin.guide') }}" class="nav-link {{ $guideAktif ? 'active' : '' }}">
                <svg class="w-5 h-5 {{ $navIconClass($guideAktif) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13c1.168-.776 2.754-1.253 4.5-1.253s3.332.477 4.5 1.253v13c-1.168-.776-2.754-1.253-4.5-1.253s-3.332.477-4.5 1.253"/></svg>
                Panduan Admin
            </a>
        </div>
    </section>

    <section class="sidebar-section">
        <p class="sidebar-section-title px-4 text-[10px] font-extrabold text-slate-400 dark:text-white/30 uppercase tracking-widest mb-3 transition-colors">Kuis & TP</p>
        <div class="sidebar-menu space-y-1">
            <a href="{{ route('admin.analytics.questions') }}" class="nav-link {{ $quizAktif ? 'active' : '' }}">
                <svg class="w-5 h-5 {{ $navIconClass($quizAktif) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Manajemen Kuis
            </a>
            <div class="sidebar-subnav ml-7 border-l border-slate-200/70 pl-3 dark:border-white/10 space-y-0.5">
                <a href="{{ route('admin.analytics.questions') }}" class="{{ $subLinkBase }} {{ $bankSoalAktif ? $subLinkAktif : $subLinkIdle }}" {{ $bankSoalAktif ? 'aria-current=page' : '' }}>
                    <span class="sidebar-subnav-dot h-1.5 w-1.5 rounded-full bg-current"></span>
                    Bank Soal
                </a>
                <a href="{{ route('admin.learning-outcomes.index') }}" class="{{ $subLinkBase }} {{ $tpAktif ? $subLinkAktif : $subLinkIdle }}" {{ $tpAktif ? 'aria-current=page' : '' }}>
                    <span class="sidebar-subnav-dot h-1.5 w-1.5 rounded-full bg-current"></span>
                    Pemetaan TP
                </a>
                <a href="{{ route('admin.questions.create') }}" class="{{ $subLinkBase }} {{ $questionBuatAktif ? $subLinkAktif : $subLinkIdle }}" {{ $questionBuatAktif ? 'aria-current=page' : '' }}>
                    <span class="sidebar-subnav-dot h-1.5 w-1.5 rounded-full bg-current"></span>
                    Buat Soal
                </a>
            </div>
        </div>
    </section>

    <section class="sidebar-section">
        <p class="sidebar-section-title px-4 text-[10px] font-extrabold text-slate-400 dark:text-white/30 uppercase tracking-widest mb-3 transition-colors">Lab</p>
        <div class="sidebar-menu space-y-1">
            <a href="{{ route('admin.labs.index') }}" class="nav-link {{ $labAktif ? 'active' : '' }}">
                <svg class="w-5 h-5 {{ $navIconClass($labAktif) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                Konfigurasi Lab
            </a>
            <div class="sidebar-subnav ml-7 border-l border-slate-200/70 pl-3 dark:border-white/10 space-y-0.5">
                <a href="{{ route('admin.labs.index') }}" class="{{ $subLinkBase }} {{ $labConfigAktif ? $subLinkAktif : $subLinkIdle }}" {{ $labConfigAktif ? 'aria-current=page' : '' }}>
                    <span class="sidebar-subnav-dot h-1.5 w-1.5 rounded-full bg-current"></span>
                    Daftar Lab
                </a>
                <a href="{{ route('admin.lab.analytics') }}" class="{{ $subLinkBase }} {{ $labAnalyticsAktif ? $subLinkAktif : $subLinkIdle }}" {{ $labAnalyticsAktif ? 'aria-current=page' : '' }}>
                    <span class="sidebar-subnav-dot h-1.5 w-1.5 rounded-full bg-current"></span>
                    Analitik Lab
                </a>
                @if($labResultAktif)
                    <span class="{{ $subLinkBase }} {{ $subLinkAktif }}" aria-current="page">
                        <span class="sidebar-subnav-dot h-1.5 w-1.5 rounded-full bg-current"></span>
                        Tinjauan Hasil Lab
                    </span>
                @endif
            </div>
            @if($studentAnalyticsAktif && $sidebarStudentId)
                <div class="ml-7 mt-2 rounded-xl border border-indigo-200 bg-indigo-50/80 px-3 py-2 dark:border-indigo-500/20 dark:bg-indigo-500/10">
                    <p class="text-[8px] font-black uppercase tracking-widest text-indigo-500 dark:text-indigo-300">Konteks Insight</p>
                    <p class="mt-1 truncate text-[11px] font-bold text-slate-800 dark:text-white">{{ \Illuminate\Support\Str::limit($sidebarStudentName, 24) }}</p>
                    <div class="mt-2 flex items-center gap-2 text-[10px] font-bold">
                        <a href="{{ route('admin.student.analytics', $sidebarStudentId) }}" class="text-indigo-700 dark:text-indigo-200">Analitik Lab</a>
                        <span class="text-slate-300 dark:text-white/20">/</span>
                        <a href="{{ route('admin.student.detail', $sidebarStudentId) }}" class="text-slate-500 hover:text-slate-900 dark:text-white/45 dark:hover:text-white">Profil</a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="sidebar-section">
        <p class="sidebar-section-title px-4 text-[10px] font-extrabold text-slate-400 dark:text-white/30 uppercase tracking-widest mb-3 transition-colors">Siswa</p>
        <div class="sidebar-menu space-y-1">
            <a href="{{ route('admin.students.index') }}" class="nav-link {{ $studentAktif ? 'active' : '' }}">
                <svg class="w-5 h-5 {{ $navIconClass($studentAktif) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Manajemen Siswa
            </a>
            <div class="sidebar-subnav ml-7 border-l border-slate-200/70 pl-3 dark:border-white/10 space-y-0.5">
                <a href="{{ route('admin.students.index') }}" class="{{ $subLinkBase }} {{ $studentDirectoryAktif ? $subLinkAktif : $subLinkIdle }}" {{ $studentDirectoryAktif ? 'aria-current=page' : '' }}>
                    <span class="sidebar-subnav-dot h-1.5 w-1.5 rounded-full bg-current"></span>
                    Direktori Siswa
                </a>
                <a href="{{ route('admin.classes.index') }}" class="{{ $subLinkBase }} {{ $classAktif ? $subLinkAktif : $subLinkIdle }}" {{ $classAktif ? 'aria-current=page' : '' }}>
                    <span class="sidebar-subnav-dot h-1.5 w-1.5 rounded-full bg-current"></span>
                    Kelas & Token
                </a>
            </div>
            @if($studentDetailAktif && $sidebarStudentId)
                <div class="ml-7 mt-2 rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 dark:border-white/10 dark:bg-white/5">
                    <p class="text-[8px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Sedang Dilihat</p>
                    <p class="mt-1 truncate text-[11px] font-bold text-slate-800 dark:text-white">{{ \Illuminate\Support\Str::limit($sidebarStudentName, 24) }}</p>
                    <div class="mt-2 flex items-center gap-2 text-[10px] font-bold">
                        <a href="{{ route('admin.student.detail', $sidebarStudentId) }}" class="text-indigo-700 dark:text-indigo-200">Profil</a>
                        <span class="text-slate-300 dark:text-white/20">/</span>
                        <a href="{{ route('admin.student.analytics', $sidebarStudentId) }}" class="text-slate-500 hover:text-slate-900 dark:text-white/45 dark:hover:text-white">Lab</a>
                    </div>
                </div>
            @endif
        </div>
    </section>
</nav>
