<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Lab | {{ $lab->title ?? 'Praktik' }} | Utilwind</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .result-card {
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(148, 163, 184, 0.24);
            box-shadow: 0 18px 50px rgba(15, 23, 42, 0.07);
            backdrop-filter: blur(18px);
        }
        .custom-scrollbar::-webkit-scrollbar { width: 7px; height: 7px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.45); border-radius: 999px; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 selection:bg-cyan-200/70">
    @php
        $summary = $chapterSummary ?? [];
        $theme = $summary['theme'] ?? 'cyan';
        $themeMap = [
            'cyan' => [
                'soft' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                'text' => 'text-cyan-700',
                'solid' => 'bg-cyan-600 hover:bg-cyan-700',
                'bar' => 'bg-cyan-500',
                'wash' => 'from-cyan-50 via-white to-blue-50',
            ],
            'indigo' => [
                'soft' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                'text' => 'text-indigo-700',
                'solid' => 'bg-indigo-600 hover:bg-indigo-700',
                'bar' => 'bg-indigo-500',
                'wash' => 'from-indigo-50 via-white to-violet-50',
            ],
            'fuchsia' => [
                'soft' => 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200',
                'text' => 'text-fuchsia-700',
                'solid' => 'bg-fuchsia-600 hover:bg-fuchsia-700',
                'bar' => 'bg-fuchsia-500',
                'wash' => 'from-fuchsia-50 via-white to-pink-50',
            ],
        ];
        $tone = $themeMap[$theme] ?? $themeMap['cyan'];
        $scoreTone = $score >= 90
            ? ['text' => 'text-emerald-700', 'soft' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'bar' => 'bg-emerald-500']
            : ($isPassed
                ? ['text' => 'text-cyan-700', 'soft' => 'bg-cyan-50 text-cyan-700 border-cyan-200', 'bar' => 'bg-cyan-500']
                : ['text' => 'text-red-700', 'soft' => 'bg-red-50 text-red-700 border-red-200', 'bar' => 'bg-red-500']);
        $remainingSteps = max(0, (int) ($metrics['total_steps'] ?? 0) - (int) ($metrics['completed_steps'] ?? 0));
        $scoreGap = max(0, (int) ($metrics['passing_grade'] ?? 70) - (int) $score);
        $firstFailedRule = null;

        foreach (($reviewItems ?? collect()) as $reviewItem) {
            if (!($reviewItem['is_completed'] ?? false) && !empty($reviewItem['failed_rule'])) {
                $firstFailedRule = $reviewItem['failed_rule'];
                break;
            }
        }

        $labPriorityItems = collect();

        if ($remainingSteps > 0) {
            $labPriorityItems->push([
                'label' => 'Selesaikan tugas tersisa',
                'detail' => $remainingSteps . ' tugas belum tervalidasi. Mulai dari instruksi yang statusnya belum selesai.',
                'tone' => 'border-red-200 bg-red-50 text-red-700',
            ]);
        }

        if ($scoreGap > 0) {
            $labPriorityItems->push([
                'label' => 'Kejar batas lulus',
                'detail' => 'Tambahkan minimal ' . $scoreGap . ' poin agar hasil praktik memenuhi standar lab.',
                'tone' => 'border-amber-200 bg-amber-50 text-amber-700',
            ]);
        }

        if ($firstFailedRule) {
            $labPriorityItems->push([
                'label' => 'Periksa aturan validasi',
                'detail' => 'Aturan pertama yang belum terpenuhi: ' . $firstFailedRule . '.',
                'tone' => 'border-cyan-200 bg-cyan-50 text-cyan-700',
            ]);
        }

        if (trim((string) ($sourceCode ?? '')) === '') {
            $labPriorityItems->push([
                'label' => 'Simpan bukti kode',
                'detail' => 'Cuplikan kode belum tersedia, pastikan kode tersimpan saat mengumpulkan lab berikutnya.',
                'tone' => 'border-slate-200 bg-slate-50 text-slate-700',
            ]);
        }

        if ($labPriorityItems->isEmpty()) {
            $labPriorityItems->push([
                'label' => 'Rapikan implementasi',
                'detail' => 'Tugas utama sudah terpenuhi. Gunakan waktu lanjutan untuk merapikan struktur, penamaan class, dan konsistensi tampilan.',
                'tone' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            ]);
        }

        $studyRoutes = [
            1 => ['title' => 'Bab 1: Pendahuluan Tailwind CSS', 'route' => 'courses.htmldancss'],
            2 => ['title' => 'Bab 2: Layouting', 'route' => 'courses.layout-basics'],
            3 => ['title' => 'Bab 3: Styling Komponen', 'route' => 'courses.typography'],
        ];
        $studyTarget = $studyRoutes[(int) ($lab->chapter_id ?? $lab->id ?? 1)] ?? $studyRoutes[1];
        $studyUrl = \Illuminate\Support\Facades\Route::has($studyTarget['route'])
            ? route($studyTarget['route'])
            : route('courses.curriculum');
        $outcomeAnalytics = $analysis['outcome_analytics'] ?? [];
        $outcomeRows = collect($outcomeAnalytics['outcomes'] ?? []);
        $outcomeNeedsReview = collect($outcomeAnalytics['needs_review'] ?? []);
        $outcomeToneMap = [
            'emerald' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'cyan' => 'border-cyan-200 bg-cyan-50 text-cyan-700',
            'amber' => 'border-amber-200 bg-amber-50 text-amber-700',
            'red' => 'border-red-200 bg-red-50 text-red-700',
            'slate' => 'border-slate-200 bg-slate-50 text-slate-700',
        ];
    @endphp

    <div class="min-h-screen bg-gradient-to-br {{ $tone['wash'] }}">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-8">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border {{ $tone['soft'] }} text-[10px] font-black tracking-[0.2em] uppercase mb-4">
                        Umpan Balik Akhir Lab
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black tracking-tight text-slate-950">
                        {{ $lab->title ?? 'Praktik Lab' }}
                    </h1>
                    <p class="text-sm md:text-base text-slate-600 mt-3 max-w-2xl leading-relaxed">
                        Ringkasan ini menampilkan capaian tugas, skor praktik, kode akhir, dan arah belajar setelah lab dikumpulkan.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    @if(!$isPassed && $lab)
                        <a href="{{ route('lab.start', ['id' => $lab->id]) }}" class="inline-flex items-center justify-center px-5 py-3 rounded-xl {{ $tone['solid'] }} text-white text-sm font-bold shadow-sm transition">
                            Ulangi Lab
                        </a>
                    @endif
                    @if($lab)
                        <a href="{{ route('lab.workspace.history', ['historyId' => $history->id]) }}" class="inline-flex items-center justify-center px-5 py-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-sm font-bold shadow-sm transition">
                            Tinjau Ruang Kerja
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-5 py-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-sm font-bold shadow-sm transition">
                        Dasbor
                    </a>
                </div>
            </div>

            @if(session('info') || session('success') || session('error'))
                <div class="mb-6 rounded-2xl border px-5 py-4 text-sm font-semibold shadow-sm
                    {{ session('error') ? 'border-red-200 bg-red-50 text-red-700' : 'border-emerald-200 bg-emerald-50 text-emerald-700' }}">
                    {{ session('error') ?? session('success') ?? session('info') }}
                </div>
            @endif

            @if(!$isPassed)
                <section class="result-card rounded-2xl p-5 md:p-6 mb-6 border-red-200 bg-red-50/60">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                        <div class="max-w-2xl">
                            <p class="text-[10px] uppercase tracking-widest font-black text-red-700 mb-2">Opsi Perbaikan Lab</p>
                            <h2 class="text-xl font-black text-slate-950">Hasil praktik belum mencapai standar</h2>
                            <p class="text-sm text-slate-600 leading-relaxed mt-2">
                                Mulai dari tugas yang belum tervalidasi, baca ulang {{ $studyTarget['title'] }}, perbaiki kode pada ruang kerja, lalu kumpulkan ulang saat skor sudah memenuhi batas lulus.
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 shrink-0">
                            <a href="{{ $studyUrl }}" class="inline-flex items-center justify-center px-4 py-3 rounded-xl bg-slate-950 text-white text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition">
                                Pelajari Materi Pendukung
                            </a>
                            @if($lab)
                                <a href="#reviewLabTasks" class="inline-flex items-center justify-center px-4 py-3 rounded-xl border border-red-200 bg-white text-red-700 text-xs font-black uppercase tracking-widest hover:bg-red-50 transition">
                                    Tinjau Tugas
                                </a>
                                <a href="{{ route('lab.start', ['id' => $lab->id]) }}" class="inline-flex items-center justify-center px-4 py-3 rounded-xl {{ $tone['solid'] }} text-white text-xs font-black uppercase tracking-widest transition">
                                    Ulangi Lab
                                </a>
                            @endif
                        </div>
                    </div>
                </section>
            @endif

            <section class="grid lg:grid-cols-[360px,1fr] gap-6 mb-6">
                <div class="result-card rounded-2xl p-6 relative overflow-hidden">
                    <div class="h-1.5 rounded-full bg-slate-100 overflow-hidden mb-6">
                        <div class="h-full rounded-full {{ $scoreTone['bar'] }}" style="width: {{ min(100, max(0, $score)) }}%"></div>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Skor Praktik</p>
                    <div class="flex items-end gap-2 mb-4">
                        <span class="text-7xl font-black leading-none {{ $scoreTone['text'] }}">{{ $score }}</span>
                        <span class="text-lg font-bold text-slate-400 mb-2">/100</span>
                    </div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border text-xs font-bold {{ $scoreTone['soft'] }}">
                        <span class="w-2 h-2 rounded-full {{ $scoreTone['bar'] }}"></span>
                        {{ $feedback['level'] }}
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed mt-5">{{ $feedback['message'] }}</p>
                </div>

                <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4">
                    <div class="result-card rounded-2xl p-5">
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-black">Tugas Selesai</p>
                        <p class="text-3xl font-black mt-2">{{ $metrics['completed_steps'] }}/{{ $metrics['total_steps'] }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $metrics['completion_percent'] }}% tugas terpenuhi</p>
                    </div>
                    <div class="result-card rounded-2xl p-5">
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-black">Poin</p>
                        <p class="text-3xl font-black mt-2 text-emerald-700">{{ $metrics['earned_points'] }}</p>
                        <p class="text-xs text-slate-500 mt-1">dari {{ $metrics['total_points'] }} poin tugas</p>
                    </div>
                    <div class="result-card rounded-2xl p-5">
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-black">Durasi</p>
                        <p class="text-3xl font-black mt-2 font-mono">{{ $metrics['duration_text'] }}</p>
                        <p class="text-xs text-slate-500 mt-1">Waktu pengerjaan lab</p>
                    </div>
                    <div class="result-card rounded-2xl p-5">
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-black">Batas Lulus</p>
                        <p class="text-3xl font-black mt-2 text-amber-600">{{ $metrics['passing_grade'] }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $isPassed ? 'Memenuhi standar' : 'Belum memenuhi standar' }}</p>
                    </div>
                </div>
            </section>

            <section class="result-card rounded-2xl p-5 md:p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-5">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest font-black {{ $tone['text'] }}">Analitik Tujuan Praktik</p>
                        <h2 class="text-xl font-black text-slate-950 mt-1">Keputusan Belajar Berdasarkan TP</h2>
                        <p class="text-sm text-slate-600 leading-relaxed mt-2 max-w-3xl">
                            {{ $outcomeAnalytics['summary_text'] ?? 'Belum ada analitik tujuan praktik.' }}
                        </p>
                    </div>
                    <div class="rounded-xl border {{ $scoreTone['soft'] }} px-4 py-3">
                        <p class="text-[10px] uppercase tracking-widest font-black">Keputusan</p>
                        <p class="text-sm font-black mt-1">{{ $outcomeAnalytics['decision'] ?? 'Belum ada data' }}</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">
                    @forelse($outcomeRows as $tp)
                        @php $tpTone = $outcomeToneMap[$tp['tone'] ?? 'slate'] ?? $outcomeToneMap['slate']; @endphp
                        <article class="rounded-2xl border {{ $tpTone }} p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-[10px] uppercase tracking-widest font-black opacity-75">{{ $tp['display_code'] ?? $tp['code'] ?? 'TP' }}</p>
                                    <h3 class="font-black text-sm leading-snug mt-1">{{ $tp['title'] ?? 'Tujuan Praktik' }}</h3>
                                </div>
                                <span class="shrink-0 text-3xl font-black">{{ $tp['mastery_percent'] ?? 0 }}%</span>
                            </div>
                            <div class="mt-4 rounded-xl bg-white/70 border border-white/70 px-3 py-2">
                                <p class="text-[10px] uppercase tracking-widest font-black opacity-70">Data Aktivitas</p>
                                <p class="text-xs leading-relaxed mt-1">{{ $tp['activity_data'] ?? '-' }}</p>
                            </div>
                            <div class="mt-3 space-y-2 text-xs leading-relaxed">
                                <p><span class="font-black">Uraian TP:</span> {{ $tp['learning_description'] ?? '-' }}</p>
                                <p><span class="font-black">Capaian Praktik:</span> {{ $tp['mastery_statement'] ?? '-' }}</p>
                                <p><span class="font-black">Arahan Materi:</span> {{ $tp['material_direction'] ?? '-' }}</p>
                            </div>
                            <div class="mt-4 flex items-center justify-between gap-3">
                                <span class="text-[10px] uppercase tracking-widest font-black opacity-75">{{ $tp['status'] ?? '-' }}</span>
                                <span class="text-[10px] font-black rounded-lg bg-white/70 border border-white/70 px-2 py-1">{{ $tp['decision'] ?? '-' }}</span>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500">
                            Belum ada tujuan praktik untuk dianalisis.
                        </div>
                    @endforelse
                </div>

                @if($outcomeNeedsReview->isNotEmpty())
                    <div class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 p-4">
                        <p class="text-[10px] uppercase tracking-widest font-black text-amber-700 mb-2">Prioritas Perbaikan Praktik</p>
                        <div class="grid md:grid-cols-2 gap-3">
                            @foreach($outcomeNeedsReview->take(4) as $tp)
                                <div class="rounded-xl border border-white/70 bg-white/70 px-4 py-3">
                                    <p class="text-sm font-black text-slate-950">{{ $tp['label'] }}</p>
                                    <p class="text-xs text-slate-600 leading-relaxed mt-1">{{ $tp['material_direction'] ?? '-' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </section>

            <section class="grid lg:grid-cols-[1fr,380px] gap-6">
                <div class="space-y-6 min-w-0">
                    <div id="reviewLabTasks" class="result-card rounded-2xl overflow-hidden">
                        <div class="p-5 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h2 class="font-black text-xl text-slate-950">Tinjauan Tugas Lab</h2>
                                <p class="text-xs text-slate-500 mt-1">Setiap tugas dicek dari aturan validasi dan kode akhir yang dikumpulkan.</p>
                            </div>
                            <div class="text-xs text-slate-500 font-mono">
                                {{ $metrics['completed_steps'] }} selesai / {{ max(0, $metrics['total_steps'] - $metrics['completed_steps']) }} perlu ditinjau
                            </div>
                        </div>

                        <div class="divide-y divide-slate-200/80">
                            @forelse($reviewItems as $item)
                                <article class="p-5 hover:bg-slate-50/70 transition">
                                    <div class="flex items-start justify-between gap-4 mb-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <span class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-black border {{ $item['is_completed'] ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                                {{ $item['number'] }}
                                            </span>
                                            <div class="min-w-0">
                                                <h3 class="font-black text-slate-950 truncate">{{ $item['step']->title }}</h3>
                                                <p class="text-[10px] uppercase tracking-widest font-black {{ $item['is_completed'] ? 'text-emerald-700' : 'text-red-700' }}">{{ $item['status'] }}</p>
                                            </div>
                                        </div>
                                        <span class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-[10px] font-black text-slate-600">{{ $item['points'] }} poin</span>
                                    </div>

                                    @if($item['step']->instruction)
                                        <p class="text-sm text-slate-600 leading-relaxed mb-4">{{ $item['step']->instruction }}</p>
                                    @endif

                                    <div class="flex flex-wrap gap-2">
                                        @forelse($item['rules'] as $rule)
                                            <span class="px-2.5 py-1 rounded-lg border {{ $item['is_completed'] ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-50 text-slate-600' }} text-[11px] font-mono font-bold">{{ $rule }}</span>
                                        @empty
                                            <span class="px-2.5 py-1 rounded-lg border border-slate-200 bg-slate-50 text-slate-500 text-[11px] font-bold">Tidak ada aturan khusus</span>
                                        @endforelse
                                    </div>

                                    @if(!$item['is_completed'] && $item['failed_rule'])
                                        <p class="mt-3 text-xs font-bold text-red-700">Aturan yang belum terpenuhi: <span class="font-mono">{{ $item['failed_rule'] }}</span></p>
                                    @endif
                                </article>
                            @empty
                                <div class="p-8 text-center text-sm text-slate-500">
                                    Belum ada tugas yang tercatat untuk lab ini.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="result-card rounded-2xl overflow-hidden">
                        <div class="p-5 border-b border-slate-200/80">
                            <h2 class="font-black text-xl text-slate-950">Cuplikan Kode Akhir</h2>
                            <p class="text-xs text-slate-500 mt-1">Kode yang tersimpan saat lab dikumpulkan.</p>
                        </div>
                        <pre class="max-h-[360px] overflow-auto custom-scrollbar bg-slate-950 text-slate-100 p-5 text-xs leading-relaxed font-mono whitespace-pre-wrap break-words"><code>{{ $sourceCode ?: 'Tidak ada kode yang tersimpan.' }}</code></pre>
                    </div>
                </div>

                <aside class="space-y-4">
                    <div class="result-card rounded-2xl p-6">
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <div>
                                <p class="text-[10px] uppercase tracking-widest font-black {{ $tone['text'] }}">Rangkuman Bab {{ $summary['number'] ?? '' }}</p>
                                <h2 class="font-black text-lg text-slate-950 mt-1">{{ $summary['title'] ?? 'Materi Tailwind CSS' }}</h2>
                            </div>
                            <span class="shrink-0 px-2.5 py-1 rounded-lg border {{ $tone['soft'] }} text-[10px] font-black">{{ $summary['subtitle'] ?? 'Ringkasan' }}</span>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed mb-4">{{ $summary['summary'] ?? '' }}</p>
                        <div class="space-y-3">
                            @foreach(($summary['key_points'] ?? []) as $point)
                                <div class="flex gap-3">
                                    <span class="mt-1.5 w-2 h-2 rounded-full {{ $tone['bar'] }} shrink-0"></span>
                                    <p class="text-sm text-slate-700 leading-relaxed">{{ $point }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <p class="text-[10px] uppercase tracking-widest font-black text-slate-400 mb-1">Langkah Berikutnya</p>
                            <p class="text-sm text-slate-700 leading-relaxed">{{ $summary['next_step'] ?? 'Tinjau bagian yang belum stabil lalu lanjutkan latihan.' }}</p>
                        </div>
                    </div>

                    <div class="result-card rounded-2xl p-6">
                        <h2 class="font-black text-lg mb-4 text-slate-950">Catatan Praktik</h2>
                        <div class="space-y-3">
                            <div class="rounded-xl bg-slate-50 border border-slate-200 px-4 py-3">
                                <p class="text-[10px] uppercase tracking-widest font-black text-slate-400 mb-1">Status</p>
                                <p class="text-sm font-black {{ $isPassed ? 'text-emerald-700' : 'text-red-700' }}">{{ $isPassed ? 'Lulus' : 'Remedial' }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-50 border border-slate-200 px-4 py-3">
                                <p class="text-[10px] uppercase tracking-widest font-black text-slate-400 mb-1">Refleksi Singkat</p>
                                <p class="text-sm text-slate-700 leading-relaxed">
                                    @if($isPassed)
                                        Pertahankan pola implementasi yang sudah benar, lalu bandingkan kode akhir dengan instruksi tugas untuk menemukan bagian yang masih bisa dibuat lebih rapi.
                                    @else
                                        Ulangi lab setelah meninjau tugas yang belum terpenuhi. Perhatikan kembali class wajib, struktur HTML, dan pratinjau hasil.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="result-card rounded-2xl p-6">
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <div>
                                <p class="text-[10px] uppercase tracking-widest font-black text-slate-400">Fokus Tindak Lanjut</p>
                                <h2 class="font-black text-lg text-slate-950 mt-1">Prioritas Perbaikan</h2>
                            </div>
                            <span class="shrink-0 px-2.5 py-1 rounded-lg border {{ $scoreTone['soft'] }} text-[10px] font-black">{{ $labPriorityItems->count() }} poin</span>
                        </div>
                        <div class="space-y-3">
                            @foreach($labPriorityItems->take(3) as $item)
                                <div class="rounded-xl border {{ $item['tone'] }} px-4 py-3">
                                    <p class="text-sm font-black">{{ $item['label'] }}</p>
                                    <p class="text-xs leading-relaxed mt-1 opacity-80">{{ $item['detail'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </section>
        </main>
    </div>
</body>
</html>
