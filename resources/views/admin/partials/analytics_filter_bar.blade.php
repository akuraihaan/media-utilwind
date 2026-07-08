@php
    $filterId = $filterId ?? 'admin-analytics-filter';
    $filterTitle = $filterTitle ?? 'Filter Data';
    $filterSummary = $filterSummary ?? 'Semua data';
    $filterAction = $filterAction ?? url()->current();
    $filterMethod = $filterMethod ?? 'GET';
    $filterHidden = collect($filterHidden ?? []);
    $filterControls = collect($filterControls ?? []);
    $filterResetHref = $filterResetHref ?? null;
    $filterResetVisible = $filterResetVisible ?? false;
    $filterSubmitLabel = $filterSubmitLabel ?? 'Terapkan';
    $filterHeadingId = $filterId . '-heading';
    $filterSummaryId = $filterId . '-summary';
@endphp

@once
    <style>
        .admin-filter-card select:focus-visible,
        .admin-filter-card button:focus-visible,
        .admin-filter-card a:focus-visible {
            outline: 2px solid rgba(99, 102, 241, .58);
            outline-offset: 3px;
        }
        .admin-filter-card select {
            min-height: 2.75rem;
        }
        @media (prefers-reduced-motion: reduce) {
            .admin-filter-card,
            .admin-filter-card * {
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }
    </style>
@endonce

<section id="{{ $filterId }}" class="admin-filter-card glass-card reveal rounded-2xl p-4 md:p-5" style="animation-delay: .06s;" aria-labelledby="{{ $filterHeadingId }}">
    <form method="{{ $filterMethod }}" action="{{ $filterAction }}" class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        @foreach($filterHidden as $name => $value)
            @if(filled($value))
                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
            @endif
        @endforeach

        <div class="min-w-0">
            <h3 id="{{ $filterHeadingId }}" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 dark:text-white/40">{{ $filterTitle }}</h3>
            <p id="{{ $filterSummaryId }}" class="mt-1 text-xs font-semibold leading-relaxed text-slate-600 dark:text-white/55" aria-live="polite">
                {{ $filterSummary }}
            </p>
        </div>

        <div class="grid w-full gap-3 sm:grid-cols-2 lg:w-auto lg:grid-cols-[repeat(var(--filter-control-count),minmax(0,1fr))_auto]" style="--filter-control-count: {{ max(1, $filterControls->count()) }};">
            @foreach($filterControls as $control)
                @php
                    $controlName = $control['name'] ?? '';
                    $controlId = $control['id'] ?? ($filterId . '-' . $controlName);
                    $controlLabel = $control['label'] ?? 'Filter';
                    $controlSelected = (string) ($control['selected'] ?? '');
                    $controlOptions = collect($control['options'] ?? []);
                    $controlEmptyLabel = $control['emptyLabel'] ?? null;
                    $controlMinWidth = $control['minWidth'] ?? 'min-w-[180px]';
                @endphp
                <label for="{{ $controlId }}" class="block {{ $controlMinWidth }}">
                    <span class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-white/45">{{ $controlLabel }}</span>
                    <select id="{{ $controlId }}" name="{{ $controlName }}" class="glass-input w-full rounded-xl px-4 py-2.5 text-xs font-bold" aria-describedby="{{ $filterSummaryId }}">
                        @if($controlEmptyLabel !== null)
                            <option value="" @selected($controlSelected === '')>{{ $controlEmptyLabel }}</option>
                        @endif
                        @foreach($controlOptions as $value => $text)
                            <option value="{{ $value }}" @selected($controlSelected === (string) $value)>{{ $text }}</option>
                        @endforeach
                    </select>
                </label>
            @endforeach

            <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-1">
                <button type="submit" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-[10px] font-black uppercase tracking-widest text-white transition hover:bg-indigo-700 dark:bg-white dark:text-slate-950 dark:hover:bg-indigo-200">
                    {{ $filterSubmitLabel }}
                </button>
                @if($filterResetVisible && $filterResetHref)
                    <a href="{{ $filterResetHref }}" class="inline-flex min-h-[44px] items-center justify-center rounded-xl px-3 py-2.5 text-[10px] font-black uppercase tracking-widest text-slate-500 transition hover:text-indigo-600 dark:text-white/45 dark:hover:text-indigo-300">
                        Reset
                    </a>
                @endif
            </div>
        </div>
    </form>
</section>
