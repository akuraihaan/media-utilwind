@php
    $analyticsTitle = $analyticsTitle ?? 'Ringkasan';
    $analyticsSubtitle = $analyticsSubtitle ?? null;
    $analyticsItems = collect($analyticsItems ?? []);
    $analyticsActions = collect($analyticsActions ?? []);
    $analyticsColumnCount = min(4, max(1, $analyticsItems->count()));
    $analyticsStripId = $analyticsId ?? 'analytics-strip-' . substr(md5($analyticsTitle), 0, 8);
    $analyticsHeadingId = $analyticsStripId . '-heading';
@endphp

@once
    <style>
        .analytics-strip-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
        }
        .analytics-strip-value {
            font-variant-numeric: tabular-nums;
        }
        .analytics-strip-action:focus-visible,
        .analytics-strip-card a:focus-visible {
            outline: 2px solid rgba(99, 102, 241, .58);
            outline-offset: 3px;
        }
        @media (min-width: 768px) {
            .analytics-strip-grid {
                grid-template-columns: repeat(var(--analytics-strip-columns), minmax(0, 1fr));
            }
        }
        @media (prefers-reduced-motion: reduce) {
            .analytics-strip-card,
            .analytics-strip-card * {
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }
    </style>
@endonce

<section id="{{ $analyticsStripId }}" class="analytics-strip-card glass-card reveal rounded-2xl p-5 md:p-6" aria-labelledby="{{ $analyticsHeadingId }}">
    <div class="flex flex-col gap-4 border-b border-slate-200 pb-4 dark:border-white/5 lg:flex-row lg:items-center lg:justify-between">
        <div class="min-w-0">
            <h3 id="{{ $analyticsHeadingId }}" class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-600 dark:text-indigo-300">{{ $analyticsTitle }}</h3>
            @if(filled($analyticsSubtitle))
                <p class="mt-1 max-w-3xl text-xs font-semibold leading-relaxed text-slate-500 dark:text-white/45">{{ $analyticsSubtitle }}</p>
            @endif
        </div>

        @if($analyticsActions->isNotEmpty())
            <div class="flex flex-wrap gap-2" aria-label="Aksi ringkasan">
                @foreach($analyticsActions as $action)
                    <a href="{{ $action['href'] ?? '#' }}"
                       class="analytics-strip-action inline-flex min-h-[40px] items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[10px] font-black uppercase tracking-widest text-slate-600 transition hover:border-indigo-200 hover:text-indigo-700 focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:border-white/10 dark:bg-white/[0.03] dark:text-white/55 dark:hover:text-indigo-200 dark:focus-visible:ring-offset-[#05080f]"
                       aria-label="{{ $action['aria'] ?? ($action['label'] ?? 'Buka rincian') }}">
                        {{ $action['label'] ?? 'Buka' }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <dl class="analytics-strip-grid divide-y divide-slate-200 dark:divide-white/5 md:divide-x md:divide-y-0" style="--analytics-strip-columns: {{ $analyticsColumnCount }};">
        @forelse($analyticsItems as $index => $item)
            @php
                $tone = $item['tone'] ?? 'slate';
                $toneClass = match ($tone) {
                    'indigo' => 'text-indigo-600 dark:text-indigo-300',
                    'cyan' => 'text-cyan-600 dark:text-cyan-300',
                    'emerald' => 'text-emerald-600 dark:text-emerald-300',
                    'amber' => 'text-amber-600 dark:text-amber-300',
                    'rose', 'red' => 'text-rose-600 dark:text-rose-300',
                    'fuchsia' => 'text-fuchsia-600 dark:text-fuchsia-300',
                    default => 'text-slate-900 dark:text-white',
                };
            @endphp
            <div class="py-4 {{ $index === 0 ? 'md:pr-4' : 'md:px-4' }} {{ $loop->last ? 'md:pr-0' : '' }}">
                <dt class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/30">{{ $item['label'] ?? 'Metrik' }}</dt>
                <dd class="mt-2">
                    <span class="analytics-strip-value block text-xl font-black {{ $toneClass }}">{{ $item['value'] ?? '-' }}</span>
                    @if(!empty($item['hint']))
                        <span class="mt-1 block text-[11px] font-semibold leading-relaxed text-slate-500 dark:text-white/45">{{ $item['hint'] }}</span>
                    @endif
                </dd>
            </div>
        @empty
            <div class="py-5 text-xs font-semibold text-slate-500 dark:text-white/45">Belum ada ringkasan yang dapat ditampilkan.</div>
        @endforelse
    </dl>
</section>
