<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tinjauan Hasil Lab · {{ $student->name ?? 'Siswa' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    boxShadow: {
                        soft: '0 18px 60px -28px rgba(15, 23, 42, 0.35)',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #0f172a; }
        .code-scroll::-webkit-scrollbar { height: 8px; width: 8px; }
        .code-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
        .code-scroll::-webkit-scrollbar-track { background: transparent; }
    </style>
</head>
<body class="min-h-screen antialiased">
@php
    $metrics = $analysis['metrics'];
    $codeMetrics = $analysis['code_metrics'];
    $reviewItems = $analysis['review_items'];
    $riskFlags = $analysis['risk_flags'];
    $recommendations = $analysis['recommendations'];
    $completedAt = $history->completed_at ?: $history->created_at;
    $statusClass = $analysis['is_passed']
        ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
        : 'bg-rose-50 text-rose-700 border-rose-200';
    $riskTone = [
        'tinggi' => 'border-rose-200 bg-rose-50 text-rose-800',
        'sedang' => 'border-amber-200 bg-amber-50 text-amber-800',
        'rendah' => 'border-sky-200 bg-sky-50 text-sky-800',
        'aman' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
    ];
    $outcomeAnalytics = $analysis['outcome_analytics'] ?? [];
    $outcomeRows = collect($outcomeAnalytics['outcomes'] ?? []);
    $outcomeTone = [
        'emerald' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
        'cyan' => 'border-cyan-200 bg-cyan-50 text-cyan-800',
        'amber' => 'border-amber-200 bg-amber-50 text-amber-800',
        'red' => 'border-rose-200 bg-rose-50 text-rose-800',
        'slate' => 'border-slate-200 bg-slate-50 text-slate-700',
    ];
@endphp

<div class="min-h-screen">
    <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:px-8 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex min-w-0 items-center gap-4">
                <a href="{{ route('admin.student.analytics', $student->id ?? 0) }}" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600 transition hover:border-indigo-200 hover:text-indigo-600" title="Kembali ke analitik siswa">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div class="min-w-0">
                    <nav class="mb-1 hidden text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400 sm:block">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">Dasbor</a>
                        <span class="mx-2">/</span>
                        <a href="{{ route('admin.lab.analytics') }}" class="hover:text-indigo-600">Analitik Lab</a>
                        <span class="mx-2">/</span>
                        <span class="text-slate-600">Tinjauan Hasil</span>
                    </nav>
                    <h1 class="truncate text-xl font-black tracking-tight text-slate-950 sm:text-2xl">Tinjauan Hasil Lab Siswa</h1>
                    <p class="mt-1 truncate text-sm text-slate-500">{{ $lab->title ?? 'Lab' }} · {{ $student->name ?? 'Siswa' }}</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.lab.analytics') }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 shadow-sm transition hover:border-indigo-200 hover:text-indigo-600">Analitik Lab</a>
                <a href="{{ route('admin.student.detail', $student->id ?? 0) }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 shadow-sm transition hover:border-indigo-200 hover:text-indigo-600">Profil Siswa</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-soft">
            <div class="grid gap-0 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-extrabold uppercase tracking-[0.16em] {{ $statusClass }}">{{ $analysis['status_label'] }}</span>
                        <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-bold text-slate-500">Dikumpulkan {{ $completedAt ? \Carbon\Carbon::parse($completedAt)->translatedFormat('d M Y, H:i') : '-' }}</span>
                    </div>

                    <div class="mt-8 grid gap-6 sm:grid-cols-[auto_1fr] sm:items-end">
                        <div class="relative h-40 w-40">
                            <svg class="h-full w-full -rotate-90" viewBox="0 0 120 120">
                                <circle cx="60" cy="60" r="52" stroke="#e2e8f0" stroke-width="12" fill="none"/>
                                <circle cx="60" cy="60" r="52" stroke="{{ $analysis['is_passed'] ? '#10b981' : '#f43f5e' }}" stroke-width="12" fill="none" stroke-linecap="round" stroke-dasharray="{{ min(100, max(0, $analysis['score'])) * 3.27 }} 327"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-4xl font-black text-slate-950">{{ $analysis['score'] }}</span>
                                <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Skor</span>
                            </div>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-indigo-600">Umpan Balik Sistem</p>
                            <h2 class="mt-2 text-2xl font-black text-slate-950">{{ $analysis['feedback']['level'] }}</h2>
                            <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">{{ $analysis['feedback']['message'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-200 bg-slate-50 p-6 sm:p-8 lg:border-l lg:border-t-0">
                    <h2 class="text-sm font-black uppercase tracking-[0.16em] text-slate-500">Rangkuman Bab</h2>
                    <p class="mt-3 text-xl font-black text-slate-950">Bab {{ $chapterSummary['number'] }} · {{ $chapterSummary['title'] }}</p>
                    <p class="mt-2 text-sm leading-7 text-slate-600">{{ $chapterSummary['summary'] }}</p>
                    <div class="mt-5 space-y-3">
                        @foreach(array_slice($chapterSummary['key_points'], 0, 3) as $point)
                            <div class="flex gap-3 rounded-xl border border-slate-200 bg-white p-3 text-sm text-slate-600">
                                <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-indigo-500"></span>
                                <span>{{ $point }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-6">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Tugas Selesai</p>
                <p class="mt-2 text-2xl font-black text-slate-950">{{ $metrics['completed_steps'] }}/{{ $metrics['total_steps'] }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Poin Tervalidasi</p>
                <p class="mt-2 text-2xl font-black text-slate-950">{{ $metrics['earned_points'] }}/{{ $metrics['total_points'] }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Durasi</p>
                <p class="mt-2 font-mono text-2xl font-black text-cyan-700">{{ $metrics['duration_text'] }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Kelas Utility CSS</p>
                <p class="mt-2 text-2xl font-black text-slate-950">{{ $codeMetrics['unique_class_count'] }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Tag HTML</p>
                <p class="mt-2 text-2xl font-black text-slate-950">{{ $codeMetrics['html_tag_count'] }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Rata-rata Lab</p>
                <p class="mt-2 text-2xl font-black text-indigo-700">{{ $labStats['average_score'] }}</p>
            </div>
        </section>

        <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-indigo-600">Keputusan Pembelajaran</p>
                    <h2 class="mt-2 text-xl font-black text-slate-950">Analitik TP Praktik</h2>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">{{ $outcomeAnalytics['summary_text'] ?? 'Belum ada data TP praktik.' }}</p>
                </div>
                <span class="rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-3 text-sm font-black text-indigo-700">{{ $outcomeAnalytics['decision'] ?? 'Belum ada data' }}</span>
            </div>

            <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @forelse($outcomeRows as $tp)
                    <article class="rounded-2xl border p-4 {{ $outcomeTone[$tp['tone'] ?? 'slate'] ?? $outcomeTone['slate'] }}">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-75">{{ $tp['code'] ?? 'TP' }}</p>
                                <h3 class="mt-1 text-sm font-black leading-snug">{{ $tp['title'] ?? 'Tujuan Praktik' }}</h3>
                            </div>
                            <span class="text-3xl font-black">{{ $tp['mastery_percent'] ?? 0 }}%</span>
                        </div>
                        <p class="mt-3 text-xs leading-6 opacity-85">{{ $tp['activity_data'] ?? '-' }}</p>
                        <div class="mt-3 rounded-xl border border-white/70 bg-white/70 p-3">
                            <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Uraian TP</p>
                            <p class="mt-1 text-xs leading-6">{{ $tp['learning_description'] ?? '-' }}</p>
                            <p class="mt-3 text-[10px] font-black uppercase tracking-widest opacity-70">Capaian Praktik</p>
                            <p class="mt-1 text-xs leading-6">{{ $tp['mastery_statement'] ?? '-' }}</p>
                            <p class="mt-3 text-[10px] font-black uppercase tracking-widest opacity-70">Arahan Materi</p>
                            <p class="mt-1 text-xs leading-6">{{ $tp['material_direction'] ?? '-' }}</p>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500">Belum ada TP praktik untuk dianalisis.</div>
                @endforelse
            </div>
        </section>

        <section class="mt-6 grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-rose-500">Pemeriksaan Rinci</p>
                        <h2 class="mt-2 text-xl font-black text-slate-950">Analisis Tersembunyi</h2>
                    </div>
                    <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-bold text-slate-500">{{ count($riskFlags) }} catatan</span>
                </div>
                <div class="mt-5 space-y-3">
                    @foreach($riskFlags as $flag)
                        <div class="rounded-xl border p-4 {{ $riskTone[$flag['level']] ?? 'border-slate-200 bg-slate-50 text-slate-700' }}">
                            <div class="flex items-center justify-between gap-3">
                                <h3 class="font-black">{{ $flag['title'] }}</h3>
                                <span class="shrink-0 rounded-full bg-white/70 px-2 py-0.5 text-[10px] font-black uppercase tracking-widest">{{ $flag['level'] }}</span>
                            </div>
                            <p class="mt-2 text-sm leading-6 opacity-80">{{ $flag['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-indigo-600">Tindak Lanjut</p>
                <h2 class="mt-2 text-xl font-black text-slate-950">Arahan Tindak Lanjut Admin</h2>
                <div class="mt-5 grid gap-3">
                    @foreach($recommendations as $recommendation)
                        <div class="flex gap-3 rounded-xl border border-indigo-100 bg-indigo-50/70 p-4 text-sm leading-6 text-indigo-950">
                            <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-[10px] font-black text-white">{{ $loop->iteration }}</span>
                            <span>{{ $recommendation }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-5 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Percobaan Lab Ini</p>
                        <p class="mt-1 text-xl font-black text-slate-950">{{ $labStats['attempts'] }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Lulus</p>
                        <p class="mt-1 text-xl font-black text-emerald-700">{{ $labStats['passed'] }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Rasio Lulus</p>
                        <p class="mt-1 text-xl font-black text-indigo-700">{{ $labStats['pass_rate'] }}%</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-6 grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 p-6">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-slate-400">Validasi Per Tugas</p>
                    <h2 class="mt-2 text-xl font-black text-slate-950">Tinjauan Tugas Lab</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($reviewItems as $item)
                        <div class="p-6">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $item['is_completed'] ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }} text-sm font-black">{{ $item['number'] }}</span>
                                        <h3 class="min-w-0 truncate font-black text-slate-950">{{ $item['step']->title ?? 'Tugas Lab' }}</h3>
                                    </div>
                                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ $item['step']->instruction ?? 'Tidak ada instruksi.' }}</p>
                                </div>
                                <span class="shrink-0 rounded-full border px-3 py-1 text-xs font-black {{ $item['is_completed'] ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-rose-200 bg-rose-50 text-rose-700' }}">{{ $item['status'] }}</span>
                            </div>
                            <div class="mt-4 flex flex-wrap gap-2">
                                @forelse($item['rules'] as $rule)
                                    <span class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 font-mono text-[11px] font-bold text-slate-600">{{ $rule }}</span>
                                @empty
                                    <span class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-bold text-slate-500">Tidak ada aturan khusus</span>
                                @endforelse
                            </div>
                            @if(!$item['is_completed'] && $item['failed_rule'])
                                <p class="mt-3 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-800">Aturan yang belum terpenuhi: <span class="font-mono">{{ $item['failed_rule'] }}</span></p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-slate-400">Riwayat Siswa</p>
                    <h2 class="mt-2 text-xl font-black text-slate-950">Percobaan pada Lab Ini</h2>
                    <div class="mt-5 space-y-3">
                        @foreach($previousAttempts as $attempt)
                            <a href="{{ route('admin.labs.results.show', $attempt->id) }}" class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-indigo-200 hover:bg-indigo-50/60">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-black text-slate-950">{{ \Carbon\Carbon::parse($attempt->created_at)->translatedFormat('d M Y, H:i') }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ gmdate(($attempt->duration_seconds ?? 0) >= 3600 ? 'H:i:s' : 'i:s', max(0, (int) ($attempt->duration_seconds ?? 0))) }}</p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <p class="text-lg font-black {{ ($attempt->final_score ?? 0) >= ($lab->passing_grade ?? 70) ? 'text-emerald-700' : 'text-rose-700' }}">{{ $attempt->final_score ?? 0 }}</p>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Skor</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-slate-400">Metrik Kode</p>
                    <h2 class="mt-2 text-xl font-black text-slate-950">Rincian Cuplikan Kode</h2>
                    <dl class="mt-5 grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-xl bg-slate-50 p-4"><dt class="font-bold text-slate-500">Baris</dt><dd class="mt-1 font-mono text-lg font-black">{{ $codeMetrics['line_count'] }}</dd></div>
                        <div class="rounded-xl bg-slate-50 p-4"><dt class="font-bold text-slate-500">Karakter</dt><dd class="mt-1 font-mono text-lg font-black">{{ $codeMetrics['character_count'] }}</dd></div>
                        <div class="rounded-xl bg-slate-50 p-4"><dt class="font-bold text-slate-500">Jumlah Kelas CSS</dt><dd class="mt-1 font-mono text-lg font-black">{{ $codeMetrics['class_count'] }}</dd></div>
                        <div class="rounded-xl bg-slate-50 p-4"><dt class="font-bold text-slate-500">Kelas CSS Unik</dt><dd class="mt-1 font-mono text-lg font-black">{{ $codeMetrics['unique_class_count'] }}</dd></div>
                    </dl>
                </div>
            </div>
        </section>

        <section class="mt-6 rounded-2xl border border-slate-200 bg-slate-950 shadow-soft">
            <div class="flex flex-col gap-3 border-b border-white/10 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-slate-400">Kode Akhir</p>
                    <h2 class="mt-2 text-xl font-black text-white">Cuplikan Kode Akhir</h2>
                </div>
                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-bold text-slate-300">kode-akhir.html</span>
            </div>
            <pre class="code-scroll max-h-[520px] overflow-auto whitespace-pre-wrap break-words p-6 font-mono text-[12px] leading-6 text-slate-100"><code>{{ $analysis['source_code'] ?: 'Tidak ada cuplikan kode yang tersimpan.' }}</code></pre>
        </section>
    </main>
</div>
</body>
</html>
