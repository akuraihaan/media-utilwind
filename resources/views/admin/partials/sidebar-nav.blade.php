@php
    $dashboardAktif = request()->routeIs('admin.dashboard');
    $guideAktif = request()->routeIs('admin.guide');

    $quizStudentAnalyticsAktif = request()->routeIs('admin.quiz.student.analytics');
    $questionAnalyticsAktif = request()->routeIs('admin.analytics.questions') || $quizStudentAnalyticsAktif;
    $questionCreateAktif = request()->routeIs('admin.questions.create');

    $labModuleAktif = request()->routeIs('admin.labs.index');
    $labResultAktif = request()->routeIs('admin.labs.results.show');
    $studentAnalyticsAktif = request()->routeIs('admin.student.analytics');
    $labAnalyticsAktif = request()->routeIs('admin.lab.analytics') || $studentAnalyticsAktif || $labResultAktif;

    $studentDirectoryAktif = request()->routeIs('admin.students.*');
    $studentDetailAktif = request()->routeIs('admin.student.detail');
    $studentAktif = $studentDirectoryAktif
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
        : 'text-slate-400 dark:text-slate-500 group-hover:text-slate-600 dark:group-hover:text-white/70';
    $navLinkClass = fn (bool $active) => 'nav-link group min-h-[46px] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-[#05080f] ' . ($active ? 'active' : '');
    $sectionTitleClass = 'sidebar-section-title px-4 text-[10px] font-extrabold text-slate-400 dark:text-white/30 uppercase tracking-widest transition-colors';

    $navSections = [
        [
            'label' => 'Utama',
            'items' => [
                ['label' => 'Dasbor', 'href' => route('admin.dashboard'), 'active' => $dashboardAktif, 'icon' => 'dashboard'],
                ['label' => 'Panduan', 'href' => route('admin.guide'), 'active' => $guideAktif, 'icon' => 'guide'],
            ],
        ],
        [
            'label' => 'Analitik Belajar',
            'items' => [
                ['label' => 'Kuis', 'href' => route('admin.analytics.questions'), 'active' => $questionAnalyticsAktif, 'icon' => 'quiz'],
                ['label' => 'Praktik Lab', 'href' => route('admin.lab.analytics'), 'active' => $labAnalyticsAktif, 'icon' => 'analytics'],
            ],
        ],
        [
            'label' => 'Materi',
            'items' => [
                ['label' => 'Modul Praktik', 'href' => route('admin.labs.index'), 'active' => $labModuleAktif, 'icon' => 'lab'],
                ['label' => 'Buat Soal', 'href' => route('admin.questions.create'), 'active' => $questionCreateAktif, 'icon' => 'plus'],
            ],
        ],
        [
            'label' => 'Pengelolaan',
            'items' => [
                ['label' => 'Siswa', 'href' => route('admin.students.index'), 'active' => $studentAktif, 'icon' => 'students'],
                ['label' => 'Kelas', 'href' => route('admin.classes.index'), 'active' => $classAktif, 'icon' => 'class'],
            ],
        ],
    ];
@endphp

@once
    <style>
        .sidebar-section + .sidebar-section { margin-top: 1.35rem; }
        .sidebar-nav .nav-link { position: relative; }
        .sidebar-nav .nav-link.active::after {
            content: '';
            position: absolute;
            right: .85rem;
            top: 50%;
            width: .42rem;
            height: .42rem;
            border-radius: 999px;
            background: currentColor;
            opacity: .8;
            transform: translateY(-50%);
        }
        .sidebar-context-card a:focus-visible {
            outline: 2px solid rgba(99, 102, 241, .55);
            outline-offset: 2px;
            border-radius: .45rem;
        }
        @media (prefers-reduced-motion: reduce) {
            .sidebar-nav .nav-link,
            .sidebar-nav .nav-link * {
                transition-duration: .01ms !important;
            }
        }
    </style>
@endonce

<a href="#admin-main-content" class="sr-only focus:not-sr-only focus:mx-4 focus:mb-3 focus:flex focus:min-h-[40px] focus:items-center focus:justify-center focus:rounded-xl focus:bg-indigo-600 focus:px-4 focus:py-2 focus:text-xs focus:font-black focus:uppercase focus:tracking-widest focus:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-[#05080f]">
    Lewati navigasi
</a>

<nav class="sidebar-nav custom-scrollbar flex-1 overflow-y-auto px-4 py-6" aria-label="Navigasi admin utama">
    @foreach($navSections as $section)
        <section class="sidebar-section" aria-labelledby="sidebar-section-{{ \Illuminate\Support\Str::slug($section['label']) }}">
            <div class="mb-3 flex items-center justify-between gap-3 px-4">
                <p id="sidebar-section-{{ \Illuminate\Support\Str::slug($section['label']) }}" class="{{ $sectionTitleClass }}">{{ $section['label'] }}</p>
                @if(!empty($section['badge']))
                    <span class="rounded-full border border-indigo-200 bg-indigo-50 px-2 py-0.5 text-[8px] font-black uppercase tracking-widest text-indigo-600 dark:border-indigo-500/20 dark:bg-indigo-500/10 dark:text-indigo-300">
                        {{ $section['badge'] }}
                    </span>
                @endif
            </div>

            <div class="sidebar-menu space-y-1">
                @foreach($section['items'] as $item)
                    @php
                        $isActive = (bool) $item['active'];
                        $iconClass = 'h-5 w-5 shrink-0 transition-colors ' . $navIconClass($isActive);
                    @endphp
                    <a href="{{ $item['href'] }}" class="{{ $navLinkClass($isActive) }}" @if($isActive) aria-current="page" @endif>
                        @switch($item['icon'])
                            @case('dashboard')
                                <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                @break
                            @case('guide')
                                <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13c1.168-.776 2.754-1.253 4.5-1.253s3.332.477 4.5 1.253v13c-1.168-.776-2.754-1.253-4.5-1.253s-3.332.477-4.5 1.253"/></svg>
                                @break
                            @case('quiz')
                                <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                @break
                            @case('target')
                                <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                @break
                            @case('analytics')
                                <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                                @break
                            @case('lab')
                                <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                @break
                            @case('plus')
                                <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                @break
                            @case('students')
                                <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                @break
                            @case('class')
                                <svg class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/></svg>
                                @break
                        @endswitch
                        <span class="min-w-0 flex-1 truncate">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endforeach

    @if(($studentAnalyticsAktif || $studentDetailAktif) && $sidebarStudentId)
        <section class="sidebar-section sidebar-context-card mx-1 rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-3 dark:border-white/10 dark:bg-white/[0.04]" aria-label="Konteks siswa yang sedang dibuka">
            <p class="text-[8px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Konteks Siswa</p>
            <p class="mt-1 truncate text-[11px] font-bold text-slate-800 dark:text-white">{{ \Illuminate\Support\Str::limit($sidebarStudentName, 28) }}</p>
            <div class="mt-2 text-[10px] font-bold">
                @if($studentAnalyticsAktif)
                    <a href="{{ route('admin.student.detail', $sidebarStudentId) }}" class="text-indigo-700 hover:text-indigo-900 dark:text-indigo-200 dark:hover:text-white">
                        Buka profil siswa
                    </a>
                @else
                    <a href="{{ route('admin.student.analytics', $sidebarStudentId) }}" class="text-indigo-700 hover:text-indigo-900 dark:text-indigo-200 dark:hover:text-white">
                        Buka analitik lab
                    </a>
                @endif
            </div>
        </section>
    @endif
</nav>
