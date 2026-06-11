@extends('layouts.landing')

@section('title', 'Utilwind')

@section('content')
@php
    $safeRoute = function ($name, $fallback = '#') {
        return \Illuminate\Support\Facades\Route::has($name) ? route($name) : $fallback;
    };

    $primaryAction = auth()->check()
        ? ['label' => 'Lanjutkan Belajar', 'href' => $safeRoute('courses.curriculum')]
        : ['label' => 'Mulai Belajar Sekarang', 'href' => $safeRoute('register')];

    $techStacks = [
        'HTML',
        'CSS',
        'Tailwind CSS',
        'Laravel',
        'Live Sandbox',
        'Mini Quiz',
    ];

    $stats = [
        ['num' => 10, 'suffix' => '+', 'title' => 'Modul', 'text' => 'Materi terstruktur', 'color' => 'cyan'],
        ['num' => 1, 'suffix' => '', 'title' => 'Live Editor', 'text' => 'Koding tanpa setup', 'color' => 'fuchsia'],
        ['num' => 1, 'suffix' => '', 'title' => 'Mini Quiz', 'text' => 'Evaluasi pemahaman', 'color' => 'indigo'],
        ['num' => 100, 'suffix' => '%', 'title' => 'Gratis', 'text' => 'Tanpa biaya tersembunyi', 'color' => 'emerald'],
    ];

    $features = [
        [
            'label' => 'Materi',
            'title' => 'Materi Terstruktur',
            'desc' => 'Tidak perlu bingung mulai dari mana. Materi disusun bertahap, mulai dari dasar HTML dan CSS sampai teknik layout responsif menggunakan Tailwind CSS.',
            'href' => $safeRoute('courses.htmldancss'),
            'button' => 'Lihat Silabus',
            'tone' => 'indigo',
        ],
        [
            'label' => 'Kamus',
            'title' => 'Kamus Class',
            'desc' => 'Kamus mini terintegrasi untuk mencari class Tailwind dengan mudah, cepat, dan efisien saat belajar.',
            'href' => $safeRoute('cheatsheet.index'),
            'button' => 'Buka Kamus',
            'tone' => 'fuchsia',
        ],
        [
            'label' => 'Editor',
            'title' => 'Editor Native',
            'desc' => 'Tidak perlu install ekstensi. Siswa dapat mencoba kode langsung di browser melalui ruang praktik yang sudah tersedia.',
            'href' => $safeRoute('sandbox'),
            'button' => 'Mulai Koding',
            'tone' => 'cyan',
        ],
        [
            'label' => 'Progres',
            'title' => 'Pantau Progres Belajarmu',
            'desc' => 'Mulai dari materi yang selesai, aktivitas praktik, sampai nilai kuis dapat dipantau melalui dashboard belajar.',
            'href' => $safeRoute('dashboard'),
            'button' => 'Lihat Dasbor',
            'tone' => 'emerald',
        ],
    ];

    $modules = [
        [
            'tab' => 'Dasar',
            'title' => 'Mulai dari HTML, CSS, dan utility-first.',
            'desc' => 'Bagian awal membantu siswa memahami fondasi halaman web sebelum masuk ke konsep Tailwind CSS.',
            'items' => ['HTML dan CSS', 'Konsep utility-first', 'Dasar Tailwind CSS'],
        ],
        [
            'tab' => 'Praktik',
            'title' => 'Coba kode langsung di ruang praktik.',
            'desc' => 'Siswa dapat menulis class, melihat perubahan tampilan, dan memperbaiki hasil secara langsung.',
            'items' => ['Live Sandbox', 'Editor Native', 'Preview langsung'],
        ],
        [
            'tab' => 'Evaluasi',
            'title' => 'Ukur pemahaman dengan kuis dan progres.',
            'desc' => 'Setelah belajar, siswa dapat mengerjakan mini quiz dan melihat perkembangan belajarnya.',
            'items' => ['Mini Quiz', 'Riwayat belajar', 'Dashboard progres'],
        ],
    ];

    $steps = [
        ['title' => 'Pilih Materi', 'desc' => 'Tentukan kelas atau topik Tailwind CSS yang ingin dikuasai.'],
        ['title' => 'Baca Teori Singkat', 'desc' => 'Pahami konsep utility-first dengan bahasa yang sederhana dan langsung ke inti.'],
        ['title' => 'Langsung Praktik', 'desc' => 'Gunakan Live Sandbox untuk menulis dan melihat hasil kode secara instan.'],
        ['title' => 'Kerjakan Quiz', 'desc' => 'Jawab kuis ringan di akhir materi untuk memastikan pemahaman.'],
    ];

    $audiences = [
        ['title' => 'Pemula', 'desc' => 'Cocok untuk yang baru belajar HTML dan CSS lalu ingin mengenal framework modern.'],
        ['title' => 'Frontend Developer', 'desc' => 'Cocok untuk yang ingin membuat UI cepat, rapi, dan responsif dengan utility class.'],
        ['title' => 'Mahasiswa IT', 'desc' => 'Cocok sebagai bahan belajar, portofolio, atau pendukung tugas akhir.'],
    ];

    $faqs = [
        ['q' => 'Apa itu Utilwind?', 'a' => 'Utilwind adalah media pembelajaran interaktif untuk belajar HTML, CSS, dan Tailwind CSS.'],
        ['q' => 'Apa fitur utamanya?', 'a' => 'Fitur utamanya mencakup materi terstruktur, kamus class, Live Sandbox, mini quiz, dan dashboard progres.'],
        ['q' => 'Apakah perlu instalasi tambahan?', 'a' => 'Tidak untuk belajar awal. Siswa dapat langsung mencoba kode melalui Live Sandbox di browser.'],
        ['q' => 'Apakah cocok untuk pemula?', 'a' => 'Ya. Materinya dimulai dari dasar dan dilanjutkan secara bertahap menuju praktik Tailwind CSS.'],
    ];
@endphp

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<script>document.documentElement.classList.add('js-ready');</script>

<div id="landingExperience" class="min-h-screen overflow-hidden bg-slate-50 text-slate-950 dark:bg-[#020617] dark:text-white">
    @include('layouts.partials.navbar')

    <style>
        #landingExperience {
            --event-shadow: 0 24px 70px rgba(15, 23, 42, 0.20);
            --soft-shadow: 0 18px 45px rgba(15, 23, 42, 0.10);
            --hero-line: rgba(255, 255, 255, 0.10);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        #landingExperience .font-mono {
            font-family: 'Fira Code', monospace;
        }

        #landingExperience .landing-wrap {
            width: min(100% - 2rem, 1200px);
            margin-inline: auto;
        }

        #landingExperience .hero-scene {
            background-image:
                radial-gradient(circle at 18% 20%, rgba(6, 182, 212, 0.28), transparent 34%),
                radial-gradient(circle at 82% 18%, rgba(217, 70, 239, 0.26), transparent 32%),
                radial-gradient(circle at 55% 86%, rgba(79, 70, 229, 0.30), transparent 36%),
                linear-gradient(rgba(2, 6, 23, 0.68), rgba(2, 6, 23, 0.82)),
                url("{{ asset('image/event.jpeg') }}");
            background-size: cover;
            background-position: center;
        }

        #landingExperience .hero-scene::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(var(--hero-line) 1px, transparent 1px),
                linear-gradient(90deg, var(--hero-line) 1px, transparent 1px);
            background-size: 56px 56px;
            mask-image: linear-gradient(to bottom, transparent, black 14%, black 86%, transparent);
            opacity: 0.55;
        }

        #landingExperience .hero-scene::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent 0%, rgba(2, 6, 23, 0.30) 62%, rgba(2, 6, 23, 0.92) 100%);
            pointer-events: none;
        }

        #landingExperience .event-card,
        #landingExperience .feature-card,
        #landingExperience .module-card,
        #landingExperience .faq-card,
        #landingExperience .stat-card {
            border-radius: 1.35rem;
            box-shadow: var(--soft-shadow);
        }

        #landingExperience .glass-card {
            background: rgba(255, 255, 255, 0.10);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.14);
        }

        #landingExperience .feature-card,
        #landingExperience .stat-card,
        #landingExperience .step-card,
        #landingExperience .audience-card {
            transition: transform 180ms ease, box-shadow 180ms ease, border-color 180ms ease;
        }

        #landingExperience .feature-card:hover,
        #landingExperience .stat-card:hover,
        #landingExperience .step-card:hover,
        #landingExperience .audience-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--event-shadow);
        }

        #landingExperience .module-tab[aria-selected="true"] {
            background: linear-gradient(135deg, #06b6d4, #d946ef, #4f46e5);
            color: #ffffff;
            box-shadow: 0 12px 26px rgba(217, 70, 239, 0.22);
        }

        /* Fallback aman:
           Konten tetap terlihat jika JavaScript gagal dimuat.
           Animasi reveal hanya aktif saat class js-ready berhasil dipasang. */
        #landingExperience .reveal-item {
            opacity: 1;
            transform: none;
        }

        html.js-ready #landingExperience .reveal-item {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 520ms ease, transform 520ms ease;
        }

        html.js-ready #landingExperience .reveal-item.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        #landingExperience .gradient-text {
            background: linear-gradient(90deg, #06b6d4, #d946ef, #4f46e5);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            background-size: 200% 200%;
            animation: gradientMove 6s ease infinite;
        }

        @keyframes gradientMove {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        #scrollProgress {
            width: 0%;
        }
    </style>

    <div id="scrollProgress" class="fixed left-0 top-16 z-[60] h-[3px] bg-gradient-to-r from-cyan-400 via-fuchsia-500 to-indigo-500 shadow-[0_0_16px_rgba(217,70,239,0.85)]"></div>

    <main class="pt-16">
        <section id="hero" class="hero-scene relative min-h-[calc(100vh-4rem)] border-b border-white/10">
            <div class="relative z-10 grid min-h-[calc(100vh-4rem)] px-4 py-14">
                <div class="landing-wrap grid items-center gap-10 lg:grid-cols-[0.95fr_1.05fr]">
                    <div class="reveal-item text-center lg:text-left">
                        

                        <h1 class="max-w-4xl text-4xl font-black leading-tight text-white sm:text-5xl lg:text-7xl">
                            Belajar <span class="font-mono font-light italic text-slate-300">Utility-First</span>
                            <span class="block gradient-text">Tailwind CSS</span>
                        </h1>

                        <p class="mt-6 max-w-2xl text-base leading-8 text-slate-200 sm:text-lg lg:mx-0">
                            Pelajari cara merancang antarmuka modern dengan cepat, terstruktur, dan coba langsung di fitur <strong class="font-black text-cyan-300">Live Sandbox</strong>.
                        </p>

                        <div class="mt-9 flex flex-col justify-center gap-3 sm:flex-row lg:justify-start">
                            <a href="{{ $primaryAction['href'] }}" class="inline-flex items-center justify-center rounded-full bg-white px-7 py-3 text-sm font-black text-slate-950 shadow-xl transition hover:-translate-y-0.5 hover:bg-slate-100">
                                {{ $primaryAction['label'] }}
                            </a>

                            <a href="#fitur" class="inline-flex items-center justify-center gap-2 rounded-full border border-white/25 bg-white/10 px-7 py-3 text-sm font-black text-white backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/20">
                                Lihat Fitur
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="event-card glass-card reveal-item overflow-hidden p-4 text-white sm:p-5">
                        <div class="mb-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-black">Live Sandbox</p>
                                <p class="text-xs text-slate-300">Ketik kode dan lihat hasilnya.</p>
                            </div>
                            
                        </div>

                        <div class="grid gap-3 sm:grid-cols-[1fr_0.85fr]">
                            <div id="tiltEditor" class="rounded-2xl bg-[#020617]/90 p-4 transition-transform duration-200">
                                <div class="mb-4 flex gap-2">
                                    <span class="h-3 w-3 rounded-full bg-red-400"></span>
                                    <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                                    <span class="h-3 w-3 rounded-full bg-emerald-400"></span>
                                </div>

                                <pre class="overflow-hidden text-xs leading-6 text-slate-200"><code>&lt;button class="<span id="autoType" class="text-cyan-300"></span>"&gt;
  Mulai Belajar
&lt;/button&gt;</code></pre>
                            </div>

                            <div class="space-y-3">
                                <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                                    <p class="text-xs font-black uppercase tracking-[0.16em] text-slate-300">Progres Materi</p>
                                    <div class="mt-3 h-2 rounded-full bg-white/15">
                                        <div class="progress-line h-2 rounded-full bg-cyan-400" data-progress="82%"></div>
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                                    <p class="text-xs font-black uppercase tracking-[0.16em] text-slate-300">Progres Lab</p>
                                    <div class="mt-3 h-2 rounded-full bg-white/15">
                                        <div class="progress-line h-2 rounded-full bg-fuchsia-400" data-progress="64%"></div>
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                                    <p class="text-sm font-black">Nilai rata-rata kuis</p>
                                    <p class="mt-1 text-4xl font-black text-white">95</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="border-b border-slate-200 bg-white px-4 py-10 dark:border-white/10 dark:bg-[#020617]">
            <div class="landing-wrap reveal-item text-center">
                <p class="mb-7 text-sm font-black uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">
                    Teknologi dan fasilitas yang digunakan
                </p>

                <div class="flex flex-wrap justify-center gap-3">
                    @foreach($techStacks as $tech)
                        <span class="rounded-full border border-slate-200 bg-slate-50 px-5 py-2 text-sm font-black text-slate-700 shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-slate-200">
                            {{ $tech }}
                        </span>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="border-b border-slate-200 bg-slate-50 px-4 py-12 dark:border-white/10 dark:bg-[#020617]">
            <div class="landing-wrap grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($stats as $stat)
                    @php
                        $statTone = [
                            'cyan' => 'text-cyan-500 dark:text-cyan-400',
                            'fuchsia' => 'text-fuchsia-500 dark:text-fuchsia-400',
                            'indigo' => 'text-indigo-500 dark:text-indigo-400',
                            'emerald' => 'text-emerald-500 dark:text-emerald-400',
                        ][$stat['color']];
                    @endphp

                    <article class="stat-card reveal-item border border-slate-200 bg-white p-5 dark:border-white/10 dark:bg-[#0f141e]">
                        <p class="text-4xl font-black {{ $statTone }}">
                            <span class="counter" data-target="{{ $stat['num'] }}">0</span>{{ $stat['suffix'] }}
                        </p>
                        <h3 class="mt-3 text-base font-black text-slate-900 dark:text-white">{{ $stat['title'] }}</h3>
                        <p class="mt-1 text-sm leading-6 text-slate-600 dark:text-slate-400">{{ $stat['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section id="sandbox" class="border-b border-slate-200 bg-white px-4 py-16 dark:border-white/10 dark:bg-[#020617] lg:py-24">
            <div class="landing-wrap">
                <div class="reveal-item mx-auto mb-10 max-w-3xl text-center">
                    <p class="text-sm font-black uppercase tracking-[0.16em] text-fuchsia-600 dark:text-fuchsia-400">Live Sandbox</p>
                    <h2 class="mt-3 text-3xl font-black text-slate-950 dark:text-white sm:text-5xl">
                        Ketik kodenya, lihat hasilnya.
                    </h2>
                    <p class="mt-4 text-base leading-8 text-slate-600 dark:text-slate-400">
                        Tidak perlu refresh dan tidak perlu instalasi. Pembelajar bisa mencoba komponen sederhana secara langsung.
                    </p>
                </div>

                <div class="event-card reveal-item grid overflow-hidden border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-[#0f141e] lg:grid-cols-2 lg:p-6">
                    <div class="rounded-2xl bg-[#020617] p-5">
                        <div class="mb-4 flex gap-2">
                            <span class="h-3 w-3 rounded-full bg-red-400"></span>
                            <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                            <span class="h-3 w-3 rounded-full bg-emerald-400"></span>
                        </div>

                        <pre class="overflow-x-auto text-xs leading-7 text-slate-200"><code>&lt;div class="bg-white rounded-2xl shadow-xl p-6 flex items-center gap-5"&gt;
  &lt;img src="utilwind.png" class="w-10 h-10"&gt;
  &lt;div&gt;
    &lt;h3 class="text-xl font-bold"&gt;Utilwind&lt;/h3&gt;
    &lt;p class="text-sm text-slate-500"&gt;Membuat UI itu mudah.&lt;/p&gt;
  &lt;/div&gt;
&lt;/div&gt;</code></pre>
                    </div>

                    <div class="grid min-h-[260px] place-items-center rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-[#020617]">
                        <div class="flex w-full max-w-sm items-center gap-5 rounded-2xl border border-slate-100 bg-white p-6 shadow-2xl dark:border-white/10 dark:bg-[#0f141e]">
                            <div class="grid h-16 w-16 shrink-0 place-items-center rounded-full border border-slate-200 bg-white dark:border-white/10 dark:bg-[#020617]">
                                <img src="{{ asset('images/logo.png') }}" alt="Utilwind Logo" class="h-10 w-10 object-contain" onerror="this.style.display='none'">
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-950 dark:text-white">Utilwind</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Membuat UI itu mudah.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="fitur" class="border-b border-slate-200 bg-slate-50 px-4 py-16 dark:border-white/10 dark:bg-[#020617] lg:py-24">
            <div class="landing-wrap">
                <div class="reveal-item mb-12 text-center">
                    <p class="text-sm font-black uppercase tracking-[0.16em] text-cyan-600 dark:text-cyan-400">Fasilitas Belajar</p>
                    <h2 class="mt-3 text-3xl font-black text-slate-950 dark:text-white sm:text-5xl">
                        Apa saja yang akan didapat?
                    </h2>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    @foreach($features as $feature)
                        @php
                            $tone = [
                                'cyan' => 'bg-cyan-50 text-cyan-700 border-cyan-200 dark:bg-cyan-500/10 dark:text-cyan-300 dark:border-cyan-500/20',
                                'fuchsia' => 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200 dark:bg-fuchsia-500/10 dark:text-fuchsia-300 dark:border-fuchsia-500/20',
                                'indigo' => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-300 dark:border-indigo-500/20',
                                'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:border-emerald-500/20',
                            ][$feature['tone']];
                        @endphp

                        <article class="feature-card reveal-item border border-slate-200 bg-white p-7 dark:border-white/10 dark:bg-[#0f141e]">
                            <span class="inline-flex rounded-full border px-3 py-1 text-xs font-black {{ $tone }}">
                                {{ $feature['label'] }}
                            </span>

                            <h3 class="mt-6 text-2xl font-black text-slate-950 dark:text-white">
                                {{ $feature['title'] }}
                            </h3>

                            <p class="mt-4 text-sm leading-7 text-slate-600 dark:text-slate-400">
                                {{ $feature['desc'] }}
                            </p>

                            <a href="{{ $feature['href'] }}" class="mt-7 inline-flex rounded-full bg-slate-950 px-5 py-2.5 text-sm font-black text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                                {{ $feature['button'] }}
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="materi" class="border-b border-slate-200 bg-white px-4 py-16 dark:border-white/10 dark:bg-[#020617] lg:py-24">
            <div class="landing-wrap grid gap-8 lg:grid-cols-[0.72fr_1.28fr] lg:items-start">
                <div class="reveal-item">
                    <p class="text-sm font-black uppercase tracking-[0.16em] text-fuchsia-600 dark:text-fuchsia-400">Ruang Belajar</p>
                    <h2 class="mt-3 text-3xl font-black text-slate-950 dark:text-white sm:text-5xl">
                        Baca. Coba. Evaluasi.
                    </h2>
                    <p class="mt-4 text-sm leading-7 text-slate-600 dark:text-slate-400">
                        Alur belajar dibuat sederhana agar siswa langsung paham bagian yang harus dibuka berikutnya.
                    </p>
                </div>

                <div class="module-card reveal-item border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-[#0f141e] sm:p-5">
                    <div class="flex gap-2 rounded-2xl bg-white p-2 dark:bg-[#020617]" role="tablist" aria-label="Alur belajar Utilwind">
                        @foreach($modules as $module)
                            <button type="button" class="module-tab flex-1 rounded-xl px-4 py-3 text-sm font-black text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-white/10" data-module-tab="{{ $loop->index }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                {{ $module['tab'] }}
                            </button>
                        @endforeach
                    </div>

                    <div class="mt-5">
                        @foreach($modules as $module)
                            <article class="{{ $loop->first ? '' : 'hidden' }}" data-module-panel="{{ $loop->index }}">
                                <h3 class="text-2xl font-black text-slate-950 dark:text-white">
                                    {{ $module['title'] }}
                                </h3>

                                <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-400">
                                    {{ $module['desc'] }}
                                </p>

                                <div class="mt-6 grid gap-3 sm:grid-cols-3">
                                    @foreach($module['items'] as $item)
                                        <div class="rounded-2xl border border-slate-200 bg-white p-4 text-sm font-black text-slate-700 dark:border-white/10 dark:bg-[#020617] dark:text-slate-200">
                                            {{ $item }}
                                        </div>
                                    @endforeach
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section id="alur" class="border-b border-slate-200 bg-slate-50 px-4 py-16 dark:border-white/10 dark:bg-[#020617] lg:py-24">
            <div class="landing-wrap">
                <div class="reveal-item mx-auto mb-12 max-w-3xl text-center">
                    <p class="text-sm font-black uppercase tracking-[0.16em] text-indigo-600 dark:text-indigo-400">Alur Belajar</p>
                    <h2 class="mt-3 text-3xl font-black text-slate-950 dark:text-white sm:text-5xl">
                        Dari memilih materi sampai mengukur pemahaman.
                    </h2>
                </div>

                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    @foreach($steps as $index => $step)
                        <article class="step-card reveal-item rounded-[1.35rem] border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-[#0f141e]">
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br from-cyan-400 via-fuchsia-500 to-indigo-500 text-sm font-black text-white">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </span>

                            <h3 class="mt-5 text-xl font-black text-slate-950 dark:text-white">
                                {{ $step['title'] }}
                            </h3>

                            <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-400">
                                {{ $step['desc'] }}
                            </p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="target" class="border-b border-slate-200 bg-white px-4 py-16 dark:border-white/10 dark:bg-[#020617] lg:py-24">
            <div class="landing-wrap">
                <div class="reveal-item mx-auto mb-12 max-w-3xl text-center">
                    <p class="text-sm font-black uppercase tracking-[0.16em] text-cyan-600 dark:text-cyan-400">Target Pengguna</p>
                    <h2 class="mt-3 text-3xl font-black text-slate-950 dark:text-white sm:text-5xl">
                        Siapa yang cocok belajar di sini?
                    </h2>
                </div>

                <div class="grid gap-5 md:grid-cols-3">
                    @foreach($audiences as $audience)
                        <article class="audience-card reveal-item rounded-[1.35rem] border border-slate-200 bg-slate-50 p-7 text-center dark:border-white/10 dark:bg-[#0f141e]">
                            <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-gradient-to-br from-cyan-400 via-fuchsia-500 to-indigo-500 text-lg font-black text-white">
                                {{ substr($audience['title'], 0, 1) }}
                            </div>

                            <h3 class="mt-5 text-xl font-black text-slate-950 dark:text-white">
                                {{ $audience['title'] }}
                            </h3>

                            <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-400">
                                {{ $audience['desc'] }}
                            </p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="faq" class="bg-slate-50 px-4 py-16 dark:bg-[#020617] lg:py-24">
            <div class="landing-wrap max-w-4xl">
                <div class="reveal-item text-center">
                    <p class="text-sm font-black uppercase tracking-[0.16em] text-fuchsia-600 dark:text-fuchsia-400">Tanya Jawab</p>
                    <h2 class="mt-3 text-3xl font-black text-slate-950 dark:text-white sm:text-5xl">
                        Pertanyaan singkat.
                    </h2>
                </div>

                <div class="faq-card reveal-item mt-10 divide-y divide-slate-200 border border-slate-200 bg-white dark:divide-white/10 dark:border-white/10 dark:bg-[#0f141e]">
                    @foreach($faqs as $faq)
                        <article>
                            <button type="button" class="faq-toggle flex w-full items-center justify-between gap-4 px-6 py-5 text-left" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                <span class="text-base font-black text-slate-950 dark:text-white">
                                    {{ $faq['q'] }}
                                </span>

                                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-slate-100 text-slate-700 dark:bg-[#020617] dark:text-slate-200">
                                    <svg class="h-4 w-4 transition-transform {{ $loop->first ? 'rotate-45' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5" />
                                    </svg>
                                </span>
                            </button>

                            <div class="faq-panel {{ $loop->first ? '' : 'hidden' }} px-6 pb-6">
                                <p class="text-sm leading-7 text-slate-600 dark:text-slate-400">
                                    {{ $faq['a'] }}
                                </p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="bg-[#020617] px-4 py-14 text-white">
            <div class="landing-wrap reveal-item flex flex-col gap-5 text-center lg:flex-row lg:items-center lg:justify-between lg:text-left">
                <div>
                    <h2 class="text-3xl font-black sm:text-4xl">
                        Siap menjadi Tailwind Expert?
                    </h2>

                    <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-300">
                        Buat akun gratis, akses materi, buka kamus class, dan coba Live Sandbox langsung di browser.
                    </p>
                </div>

                <a href="{{ $primaryAction['href'] }}" class="inline-flex justify-center rounded-full bg-white px-7 py-3 text-sm font-black text-slate-950 transition hover:bg-slate-100">
                    {{ auth()->check() ? 'Lanjutkan Belajar' : 'Daftar Sekarang - Gratis!' }}
                </a>
            </div>
        </section>

        <footer class="border-t border-slate-200 bg-white px-4 py-10 dark:border-white/10 dark:bg-[#020617]">
            <div class="landing-wrap flex flex-col gap-6 text-sm text-slate-500 dark:text-slate-400 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Utilwind" class="h-8 w-auto" onerror="this.style.display='none'">
                    <p>
                        <strong class="text-slate-900 dark:text-white">Utilwind</strong>
                        - media pembelajaran interaktif berbasis web.
                    </p>
                </div>

                <div class="flex flex-wrap gap-5">
                    <a href="{{ $safeRoute('courses.htmldancss') }}" class="hover:text-slate-950 dark:hover:text-white">Course</a>
                    <a href="{{ $safeRoute('sandbox') }}" class="hover:text-slate-950 dark:hover:text-white">Sandbox</a>
                    <a href="{{ $safeRoute('cheatsheet.index') }}" class="hover:text-slate-950 dark:hover:text-white">Cheat Sheet</a>
                    <a href="{{ $safeRoute('gallery.index') }}" class="hover:text-slate-950 dark:hover:text-white">Gallery</a>
                </div>
            </div>
        </footer>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const root = document.getElementById('landingExperience');
        const scrollProgress = document.getElementById('scrollProgress');

        function setAllVisible() {
            document.querySelectorAll('.reveal-item').forEach(function (item) {
                item.classList.add('is-visible');
            });
        }

        try {
            window.addEventListener('scroll', function () {
                if (!scrollProgress) return;

                const scrollTop = window.scrollY || document.documentElement.scrollTop;
                const height = document.documentElement.scrollHeight - window.innerHeight;
                const progress = height > 0 ? (scrollTop / height) * 100 : 0;

                scrollProgress.style.width = progress + '%';
            }, { passive: true });

            document.querySelectorAll('.progress-line').forEach(function (line) {
                const target = line.getAttribute('data-progress') || '70%';

                line.style.width = '0%';

                requestAnimationFrame(function () {
                    line.style.transition = 'width 850ms ease';
                    line.style.width = target;
                });
            });

            const typeList = [
                'rounded-full bg-white px-6 py-3',
                'hover:scale-105 transition shadow-xl',
                'font-black text-slate-950'
            ];

            let typeIndex = 0;
            let charIndex = 0;
            let isDeleting = false;
            const autoType = document.getElementById('autoType');

            function typingEffect() {
                if (!autoType) return;

                const currentText = typeList[typeIndex];

                charIndex = isDeleting ? charIndex - 1 : charIndex + 1;
                autoType.textContent = currentText.substring(0, charIndex);

                let speed = isDeleting ? 32 : 58;

                if (!isDeleting && charIndex === currentText.length) {
                    speed = 1400;
                    isDeleting = true;
                } else if (isDeleting && charIndex === 0) {
                    isDeleting = false;
                    typeIndex = (typeIndex + 1) % typeList.length;
                    speed = 350;
                }

                window.setTimeout(typingEffect, speed);
            }

            typingEffect();

            if (window.innerWidth > 1024) {
                document.querySelectorAll('.event-card').forEach(function (eventCard) {
                    eventCard.addEventListener('mousemove', function (event) {
                        const tiltEditor = document.getElementById('tiltEditor');
                        if (!tiltEditor) return;

                        const rect = eventCard.getBoundingClientRect();
                        const mouseX = event.clientX - rect.left - rect.width / 2;
                        const mouseY = event.clientY - rect.top - rect.height / 2;
                        const rotateX = (mouseY / rect.height) * -5;
                        const rotateY = (mouseX / rect.width) * 5;

                        tiltEditor.style.transform = `perspective(900px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
                    });

                    eventCard.addEventListener('mouseleave', function () {
                        const tiltEditor = document.getElementById('tiltEditor');
                        if (tiltEditor) {
                            tiltEditor.style.transform = 'perspective(900px) rotateX(0deg) rotateY(0deg)';
                        }
                    });
                });
            }

            document.querySelectorAll('[data-module-tab]').forEach(function (tab) {
                tab.addEventListener('click', function () {
                    const target = String(tab.getAttribute('data-module-tab'));

                    document.querySelectorAll('[data-module-tab]').forEach(function (item) {
                        item.setAttribute('aria-selected', 'false');
                    });

                    tab.setAttribute('aria-selected', 'true');

                    document.querySelectorAll('[data-module-panel]').forEach(function (panel) {
                        panel.classList.add('hidden');
                    });

                    const activePanel = document.querySelector('[data-module-panel="' + target + '"]');
                    if (activePanel) {
                        activePanel.classList.remove('hidden');
                    }
                });
            });

            document.querySelectorAll('.faq-toggle').forEach(function (button) {
                button.addEventListener('click', function () {
                    const article = button.closest('article');
                    if (!article) return;

                    const panel = article.querySelector('.faq-panel');
                    const icon = button.querySelector('svg');
                    const isOpen = button.getAttribute('aria-expanded') === 'true';

                    button.setAttribute('aria-expanded', isOpen ? 'false' : 'true');

                    if (icon) {
                        icon.classList.toggle('rotate-45', !isOpen);
                    }

                    if (panel) {
                        panel.classList.toggle('hidden', isOpen);
                    }
                });
            });

            document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
                anchor.addEventListener('click', function (event) {
                    const href = anchor.getAttribute('href');
                    if (!href || href === '#') return;

                    const target = document.querySelector(href);
                    if (!target) return;

                    event.preventDefault();

                    const top = target.getBoundingClientRect().top + window.scrollY - 86;
                    window.scrollTo({ top, behavior: 'smooth' });
                });
            });

            if ('IntersectionObserver' in window) {
                const revealObserver = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            revealObserver.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.16 });

                document.querySelectorAll('.reveal-item').forEach(function (item) {
                    revealObserver.observe(item);
                });

                const counterObserver = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (!entry.isIntersecting) return;

                        const counter = entry.target;
                        const target = parseInt(counter.getAttribute('data-target'), 10) || 0;
                        const duration = 850;
                        const startTime = performance.now();

                        function animateCounter(currentTime) {
                            const progress = Math.min((currentTime - startTime) / duration, 1);
                            const value = Math.floor(progress * target);

                            counter.textContent = value;

                            if (progress < 1) {
                                requestAnimationFrame(animateCounter);
                            } else {
                                counter.textContent = target;
                            }
                        }

                        requestAnimationFrame(animateCounter);
                        counterObserver.unobserve(counter);
                    });
                }, { threshold: 0.5 });

                document.querySelectorAll('.counter').forEach(function (counter) {
                    counterObserver.observe(counter);
                });
            } else {
                setAllVisible();

                document.querySelectorAll('.counter').forEach(function (counter) {
                    counter.textContent = counter.getAttribute('data-target') || '0';
                });
            }

            window.setTimeout(function () {
                setAllVisible();
            }, 1200);
        } catch (error) {
            console.error('Landing page script error:', error);
            setAllVisible();
        }
    });
</script>
@endsection