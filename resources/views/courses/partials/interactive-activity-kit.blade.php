<style>
    .interactive-activity-shell { display:grid; gap:1rem; }
    .interactive-activity-grid { display:grid; grid-template-columns:minmax(0,1fr); gap:1rem; }
    @media (min-width:1024px) { .interactive-activity-grid { grid-template-columns:minmax(0,1.05fr) minmax(320px,.95fr); } }
    .interactive-editor {
        min-height: 340px;
        width: 100%;
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid var(--border-color);
        background: #0f172a;
    }
    .interactive-fallback-editor {
        min-height: 340px;
        width: 100%;
        resize: vertical;
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        background: #0f172a;
        color: #e2e8f0;
        padding: 1rem;
        outline: none;
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        line-height: 1.7;
    }
    .interactive-preview-frame {
        width: 100%;
        min-height: 340px;
        border: 0;
        border-radius: 1rem;
        background: #fff;
    }
    .interactive-task-row {
        border: 1px solid var(--border-color);
        border-radius: .875rem;
        padding: .8rem .9rem;
        background: var(--card-bg);
        transition: border-color .2s, background .2s, transform .2s;
    }
    .interactive-task-row.is-pass { border-color: rgba(16,185,129,.55); background: rgba(16,185,129,.08); }
    .interactive-task-row.is-fail { border-color: rgba(239,68,68,.45); background: rgba(239,68,68,.08); }
    .drag-order-list { display:grid; gap:.75rem; }
    .drag-order-item {
        display:flex;
        align-items:flex-start;
        gap:.75rem;
        border:1px solid var(--border-color);
        background:var(--card-bg);
        border-radius:.875rem;
        padding:.85rem;
        cursor:grab;
        transition:border-color .2s, transform .2s, background .2s;
    }
    .drag-order-item:active { cursor:grabbing; }
    .drag-order-item.dragging { opacity:.55; transform:scale(.99); border-color:#6366f1; }
    .drag-order-item.is-pass { border-color:rgba(16,185,129,.55); background:rgba(16,185,129,.08); }
    .drag-order-item.is-fail { border-color:rgba(239,68,68,.45); background:rgba(239,68,68,.08); }
    .drag-order-handle {
        width:2rem;
        height:2rem;
        display:grid;
        place-items:center;
        border-radius:.65rem;
        border:1px solid var(--border-color);
        color:var(--text-muted);
        font-weight:900;
        flex-shrink:0;
    }
    .drag-order-actions { display:flex; gap:.35rem; margin-left:auto; flex-shrink:0; }
    .drag-order-actions button {
        width:1.75rem;
        height:1.75rem;
        display:grid;
        place-items:center;
        border-radius:.55rem;
        border:1px solid var(--border-color);
        color:var(--text-muted);
        transition:background .2s, color .2s;
    }
    .drag-order-actions button:hover { background:rgba(99,102,241,.10); color:#6366f1; }
    .choice-builder-options { display:grid; gap:.75rem; }
    .choice-builder-option {
        width:100%;
        display:flex;
        align-items:flex-start;
        gap:.75rem;
        text-align:left;
        border:1px solid var(--border-color);
        border-radius:.875rem;
        padding:.85rem;
        background:var(--card-bg);
        transition:border-color .2s, background .2s, transform .2s;
    }
    .choice-builder-option:hover { border-color:#6366f1; transform:translateY(-1px); }
    .choice-builder-option.is-selected { border-color:#6366f1; background:rgba(99,102,241,.10); }
    .choice-builder-option.is-pass { border-color:rgba(16,185,129,.55); background:rgba(16,185,129,.08); }
    .choice-builder-option.is-fail { border-color:rgba(239,68,68,.45); background:rgba(239,68,68,.08); }
    .choice-builder-swatch {
        width:1.65rem;
        height:1.65rem;
        border-radius:.55rem;
        border:1px solid rgba(148,163,184,.55);
        flex-shrink:0;
        margin-top:.05rem;
    }
    .choice-builder-preview {
        min-height:340px;
        display:grid;
        place-items:center;
        padding:1rem;
        border-radius:0 0 1rem 1rem;
        background:#f8fafc;
        overflow:hidden;
    }
</style>

<script>
    (function () {
        if (window.CourseActivityKit) return;

        const aceState = { promise: null };

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, char => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            }[char]));
        }

        function loadScript(src) {
            return new Promise((resolve, reject) => {
                const existing = document.querySelector(`script[src="${src}"]`);
                if (existing) {
                    existing.addEventListener('load', resolve, { once: true });
                    existing.addEventListener('error', reject, { once: true });
                    if (existing.dataset.loaded === 'true') resolve();
                    return;
                }
                const script = document.createElement('script');
                script.src = src;
                script.async = true;
                script.onload = () => {
                    script.dataset.loaded = 'true';
                    resolve();
                };
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }

        function loadAce() {
            if (window.ace) return Promise.resolve(window.ace);
            if (!aceState.promise) {
                aceState.promise = loadScript('https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.6/ace.js')
                    .then(() => Promise.all([
                        loadScript('https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.6/mode-html.js'),
                        loadScript('https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.6/theme-one_dark.js'),
                        loadScript('https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.6/ext-language_tools.js')
                    ]))
                    .then(() => window.ace);
            }
            return aceState.promise;
        }

        function previewDocument(code, useTailwind) {
            const tailwind = useTailwind ? '<script src="https://cdn.tailwindcss.com"><\/script>' : '';
            if (/<html[\s>]/i.test(code)) {
                return code.replace(/<head([^>]*)>/i, `<head$1>${tailwind}`);
            }
            return `<!doctype html><html><head><meta charset="utf-8">${tailwind}</head><body>${code}</body></html>`;
        }

        function writePreview(frame, code, useTailwind) {
            if (!frame) return;
            frame.srcdoc = previewDocument(code, useTailwind);
        }

        function updateTaskRows(root, result, checked) {
            root.querySelectorAll('[data-kit-task]').forEach(row => {
                const index = Number(row.dataset.kitTask);
                row.classList.remove('is-pass', 'is-fail');
                if (!checked) return;
                row.classList.add(result.details[index] ? 'is-pass' : 'is-fail');
            });
        }

        function runTests(code, tests) {
            const source = String(code ?? '');
            const details = tests.map(test => {
                if (typeof test.check === 'function') return !!test.check(source);
                if (test.regex) return new RegExp(test.regex, test.flags || 'i').test(source);
                if (test.includes) return source.toLowerCase().includes(String(test.includes).toLowerCase());
                if (Array.isArray(test.any)) return test.any.some(item => source.toLowerCase().includes(String(item).toLowerCase()));
                return false;
            });
            const score = details.filter(Boolean).length;
            return { score, total: tests.length, details, passed: score >= (tests.minScore || tests.length) };
        }

        function mountCodeActivity(config) {
            const root = document.querySelector(config.root || '#activityForm');
            if (!root) return null;

            let code = config.initialCode || '';
            let checked = false;
            let editor = null;
            let locked = false;
            const minScore = config.minScore || config.tests.length;

            root.innerHTML = `
                <div class="interactive-activity-shell">
                    <div class="rounded-2xl border border-adaptive bg-white/70 dark:bg-white/[0.03] p-4 md:p-5">
                        <p class="text-[10px] uppercase tracking-widest font-black text-muted mb-2">${escapeHtml(config.badge || 'Aktivitas Kode')}</p>
                        <h3 class="text-base md:text-lg font-black text-heading">${escapeHtml(config.title || 'Lengkapi kode')}</h3>
                        <p class="mt-2 text-xs text-muted leading-relaxed">${escapeHtml(config.description || 'Tulis kode sesuai kebutuhan materi, lalu amati pratinjau di sebelah kanan.')}</p>
                    </div>
                    <div class="interactive-activity-grid">
                        <div class="space-y-4 min-w-0">
                            <div class="rounded-2xl border border-adaptive overflow-hidden bg-slate-950">
                                <div class="flex items-center justify-between gap-3 border-b border-white/10 px-4 py-3">
                                    <span class="text-[10px] uppercase tracking-widest font-black text-slate-300">${escapeHtml(config.fileLabel || 'index.html')}</span>
                                    <button type="button" data-kit-reset class="text-[10px] font-black uppercase tracking-widest text-slate-300 hover:text-white transition">Reset</button>
                                </div>
                                <div data-kit-editor class="interactive-editor"></div>
                                <textarea data-kit-textarea class="interactive-fallback-editor hidden" spellcheck="false"></textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                ${(config.tests || []).map((test, index) => `
                                    <div class="interactive-task-row" data-kit-task="${index}">
                                        <p class="text-[10px] uppercase tracking-widest font-black text-muted mb-1">Tugas ${index + 1}</p>
                                        <p class="text-xs font-bold text-heading leading-relaxed">${escapeHtml(test.label)}</p>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        <div class="space-y-4 min-w-0">
                            <div class="rounded-2xl border border-adaptive bg-white dark:bg-slate-950 overflow-hidden">
                                <div class="flex items-center justify-between gap-3 border-b border-adaptive px-4 py-3">
                                    <span class="text-[10px] uppercase tracking-widest font-black text-muted">Live Preview</span>
                                    <span data-kit-preview-status class="text-[10px] font-black text-muted">Otomatis diperbarui</span>
                                </div>
                                <iframe data-kit-preview class="interactive-preview-frame" title="Pratinjau aktivitas"></iframe>
                            </div>
                            <div data-kit-message class="rounded-2xl border border-adaptive bg-slate-50 dark:bg-white/[0.03] p-4 text-xs text-muted leading-relaxed">
                                Pratinjau akan berubah setiap kode diperbarui!
                            </div>
                        </div>
                    </div>
                </div>
            `;

            const editorEl = root.querySelector('[data-kit-editor]');
            const textarea = root.querySelector('[data-kit-textarea]');
            const preview = root.querySelector('[data-kit-preview]');
            const message = root.querySelector('[data-kit-message]');
            const resetBtn = root.querySelector('[data-kit-reset]');

            function getCode() {
                if (editor) return editor.getValue();
                return textarea.value;
            }

            function setCode(value) {
                code = value;
                if (editor) editor.setValue(code, -1);
                textarea.value = code;
                writePreview(preview, code, !!config.useTailwind);
            }

            function onChange() {
                if (locked) return;
                code = getCode();
                checked = false;
                updateTaskRows(root, { details: [] }, false);
                writePreview(preview, code, !!config.useTailwind);
            }

            textarea.value = code;
            textarea.addEventListener('input', onChange);
            writePreview(preview, code, !!config.useTailwind);

            if (config.useAce !== false) {
                loadAce().then(ace => {
                    editor = ace.edit(editorEl);
                    editor.setTheme('ace/theme/one_dark');
                    editor.session.setMode('ace/mode/html');
                    editor.setOptions({
                        fontSize: '12px',
                        minLines: 18,
                        maxLines: 24,
                        showPrintMargin: false,
                        enableBasicAutocompletion: true,
                        enableLiveAutocompletion: true,
                        tabSize: 2,
                        useSoftTabs: true
                    });
                    editor.setValue(code, -1);
                    editor.session.on('change', onChange);
                    if (locked) editor.setReadOnly(true);
                }).catch(() => {
                    editorEl.classList.add('hidden');
                    textarea.classList.remove('hidden');
                    if (locked) textarea.disabled = true;
                });
            } else {
                editorEl.classList.add('hidden');
                textarea.classList.remove('hidden');
            }

            resetBtn.addEventListener('click', () => {
                if (locked) return;
                checked = false;
                setCode(config.initialCode || '');
                updateTaskRows(root, { details: [] }, false);
                message.className = 'rounded-2xl border border-adaptive bg-slate-50 dark:bg-white/[0.03] p-4 text-xs text-muted leading-relaxed';
                message.textContent = 'Kode dikembalikan ke template awal.';
            });

            return {
                check() {
                    checked = true;
                    code = getCode();
                    const result = runTests(code, config.tests || []);
                    result.passed = result.score >= minScore;
                    updateTaskRows(root, result, true);
                    message.className = `rounded-2xl border p-4 text-xs leading-relaxed ${result.passed ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300' : 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300'}`;
                    message.textContent = result.passed
                        ? 'Aktivitas sudah sesuai. Pratinjau menampilkan hasil kode yang dibuat.'
                        : 'Belum tepat. Periksa kembali kebutuhan aktivitas, lalu ulangi pengaturan kode.';
                    return result;
                },
                reset() {
                    if (locked) return;
                    resetBtn.click();
                },
                lock() {
                    locked = true;
                    if (editor) editor.setReadOnly(true);
                    textarea.disabled = true;
                    resetBtn.disabled = true;
                    root.querySelectorAll('button').forEach(btn => btn.disabled = true);
                }
            };
        }

        function mountDragOrderActivity(config) {
            const root = document.querySelector(config.root || '#activityForm');
            if (!root) return null;
            const minScore = config.minScore || config.items.length;
            let items = (config.initialOrder || config.items.map(item => item.id)).map(id => config.items.find(item => item.id === id)).filter(Boolean);
            let checked = false;
            let locked = false;

            function render() {
                root.innerHTML = `
                    <div class="interactive-activity-shell">
                        <div class="rounded-2xl border border-adaptive bg-white/70 dark:bg-white/[0.03] p-4 md:p-5">
                            <p class="text-[10px] uppercase tracking-widest font-black text-muted mb-2">${escapeHtml(config.badge || 'Drag and Drop')}</p>
                            <h3 class="text-base md:text-lg font-black text-heading">${escapeHtml(config.title || 'Susun urutan kerja')}</h3>
                            <p class="mt-2 text-xs text-muted leading-relaxed">${escapeHtml(config.description || 'Geser kartu untuk menyusun alur yang paling sesuai dengan materi.')}</p>
                        </div>
                        <div class="interactive-activity-grid">
                            <div class="space-y-4 min-w-0">
                                <div class="drag-order-list" data-order-list>
                                    ${items.map((item, index) => `
                                        <div class="drag-order-item ${checked ? (item.id === config.correctOrder[index] ? 'is-pass' : 'is-fail') : ''}" draggable="${locked ? 'false' : 'true'}" data-id="${escapeHtml(item.id)}">
                                            <span class="drag-order-handle">${index + 1}</span>
                                            <div class="min-w-0">
                                                <p class="text-sm font-black text-heading">${escapeHtml(item.title)}</p>
                                                <p class="mt-1 text-xs text-muted leading-relaxed">${escapeHtml(item.desc)}</p>
                                                ${item.code ? `<code class="mt-2 block rounded-lg bg-slate-100 dark:bg-black/30 px-3 py-2 text-[11px] text-heading whitespace-pre-wrap">${escapeHtml(item.code)}</code>` : ''}
                                            </div>
                                            <div class="drag-order-actions">
                                                <button type="button" data-move-up="${index}" aria-label="Naik">↑</button>
                                                <button type="button" data-move-down="${index}" aria-label="Turun">↓</button>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                            <div class="space-y-4 min-w-0">
                                <div class="rounded-2xl border border-adaptive bg-white dark:bg-slate-950 overflow-hidden">
                                    <div class="border-b border-adaptive px-4 py-3">
                                        <span class="text-[10px] uppercase tracking-widest font-black text-muted">Preview Alur</span>
                                    </div>
                                    <div class="p-4 space-y-3">
                                        ${items.map((item, index) => `
                                            <div class="flex gap-3">
                                                <span class="w-7 h-7 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-300 grid place-items-center text-xs font-black shrink-0">${index + 1}</span>
                                                <div>
                                                    <p class="text-xs font-black text-heading">${escapeHtml(item.title)}</p>
                                                    <p class="text-[11px] text-muted leading-relaxed">${escapeHtml(item.preview || item.desc)}</p>
                                                </div>
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                                <div data-kit-message class="rounded-2xl border border-adaptive bg-slate-50 dark:bg-white/[0.03] p-4 text-xs text-muted leading-relaxed">
                                    Urutan preview mengikuti susunan kartu di sebelah kiri!
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                bind();
            }

            function move(from, to) {
                if (locked || to < 0 || to >= items.length) return;
                const [item] = items.splice(from, 1);
                items.splice(to, 0, item);
                checked = false;
                render();
            }

            function bind() {
                const list = root.querySelector('[data-order-list]');
                let draggedId = null;
                root.querySelectorAll('[data-move-up]').forEach(btn => btn.addEventListener('click', () => move(Number(btn.dataset.moveUp), Number(btn.dataset.moveUp) - 1)));
                root.querySelectorAll('[data-move-down]').forEach(btn => btn.addEventListener('click', () => move(Number(btn.dataset.moveDown), Number(btn.dataset.moveDown) + 1)));
                root.querySelectorAll('.drag-order-item').forEach(itemEl => {
                    itemEl.addEventListener('dragstart', event => {
                        if (locked) return;
                        draggedId = itemEl.dataset.id;
                        itemEl.classList.add('dragging');
                        event.dataTransfer.effectAllowed = 'move';
                    });
                    itemEl.addEventListener('dragend', () => itemEl.classList.remove('dragging'));
                    itemEl.addEventListener('dragover', event => event.preventDefault());
                    itemEl.addEventListener('drop', event => {
                        event.preventDefault();
                        if (!draggedId || locked) return;
                        const from = items.findIndex(item => item.id === draggedId);
                        const to = items.findIndex(item => item.id === itemEl.dataset.id);
                        move(from, to);
                    });
                });
            }

            render();

            return {
                check() {
                    checked = true;
                    const details = items.map((item, index) => item.id === config.correctOrder[index]);
                    const score = details.filter(Boolean).length;
                    const result = { score, total: items.length, details, passed: score >= minScore };
                    render();
                    const message = root.querySelector('[data-kit-message]');
                    if (message) {
                        message.className = `rounded-2xl border p-4 text-xs leading-relaxed ${result.passed ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300' : 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300'}`;
                        message.textContent = result.passed
                            ? 'Urutan aktivitas sudah sesuai dengan alur materi.'
                            : 'Belum tepat. Susun kembali kartu berdasarkan alur materi, lalu periksa lagi.';
                    }
                    return result;
                },
                reset() {
                    if (locked) return;
                    items = (config.initialOrder || config.items.map(item => item.id)).map(id => config.items.find(item => item.id === id)).filter(Boolean);
                    checked = false;
                    render();
                },
                lock() {
                    locked = true;
                    render();
                }
            };
        }

        function mountChoiceBuilderActivity(config) {
            const root = document.querySelector(config.root || '#activityForm');
            if (!root) return null;

            const minScore = config.minScore || config.groups.length;
            let locked = false;
            let checked = false;
            const state = {};
            config.groups.forEach(group => {
                state[group.id] = group.default || group.options[0]?.id;
            });

            function selectedOption(group) {
                return group.options.find(option => option.id === state[group.id]) || group.options[0];
            }

            function getSelections() {
                const selections = {};
                config.groups.forEach(group => {
                    selections[group.id] = selectedOption(group);
                });
                return selections;
            }

            function renderPreview() {
                const preview = root.querySelector('[data-builder-preview]');
                if (!preview) return;
                const selections = getSelections();
                preview.innerHTML = typeof config.renderPreview === 'function'
                    ? config.renderPreview(state, selections)
                    : `<div class="rounded-2xl bg-white p-6 shadow-sm">Preview aktivitas</div>`;
            }

            function render() {
                const details = config.groups.map(group => state[group.id] === group.correct);
                root.innerHTML = `
                    <div class="interactive-activity-shell">
                        <div class="rounded-2xl border border-adaptive bg-white/70 dark:bg-white/[0.03] p-4 md:p-5">
                            <p class="text-[10px] uppercase tracking-widest font-black text-muted mb-2">${escapeHtml(config.badge || 'Builder Interaktif')}</p>
                            <h3 class="text-base md:text-lg font-black text-heading">${escapeHtml(config.title || 'Bangun tampilan')}</h3>
                            <p class="mt-2 text-xs text-muted leading-relaxed">${escapeHtml(config.description || 'Pilih pengaturan yang paling sesuai dengan kebutuhan tampilan!')}</p>
                        </div>
                        <div class="interactive-activity-grid">
                            <div class="space-y-4 min-w-0">
                                ${config.groups.map((group, groupIndex) => `
                                    <section class="rounded-2xl border border-adaptive bg-white/70 dark:bg-white/[0.03] p-4">
                                        <div class="flex items-start gap-3 mb-3">
                                            <span class="w-7 h-7 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-300 grid place-items-center text-xs font-black shrink-0">${groupIndex + 1}</span>
                                            <div>
                                                <h4 class="text-sm font-black text-heading">${escapeHtml(group.label)}</h4>
                                                <p class="mt-1 text-xs text-muted leading-relaxed">${escapeHtml(group.desc || '')}</p>
                                            </div>
                                        </div>
                                        <div class="choice-builder-options">
                                            ${group.options.map(option => {
                                                const selected = state[group.id] === option.id;
                                                const statusClass = checked && selected ? (option.id === group.correct ? 'is-pass' : 'is-fail') : '';
                                                return `
                                                    <button type="button" class="choice-builder-option ${selected ? 'is-selected' : ''} ${statusClass}" data-builder-group="${escapeHtml(group.id)}" data-builder-option="${escapeHtml(option.id)}" ${locked ? 'disabled' : ''}>
                                                        ${option.color ? `<span class="choice-builder-swatch" style="background:${escapeHtml(option.color)}"></span>` : `<span class="choice-builder-swatch" style="background:${escapeHtml(option.sample || '#eef2ff')}"></span>`}
                                                        <span class="min-w-0">
                                                            <span class="block text-xs font-black text-heading">${escapeHtml(option.label)}</span>
                                                            ${option.classText ? `<code class="mt-1 block text-[11px] text-muted break-words">${escapeHtml(option.classText)}</code>` : ''}
                                                            ${option.desc ? `<span class="mt-1 block text-[11px] text-muted leading-relaxed">${escapeHtml(option.desc)}</span>` : ''}
                                                        </span>
                                                    </button>
                                                `;
                                            }).join('')}
                                        </div>
                                    </section>
                                `).join('')}
                            </div>
                            <div class="space-y-4 min-w-0">
                                <div class="rounded-2xl border border-adaptive bg-white dark:bg-slate-950 overflow-hidden">
                                    <div class="flex items-center justify-between gap-3 border-b border-adaptive px-4 py-3">
                                        <span class="text-[10px] uppercase tracking-widest font-black text-muted">${escapeHtml(config.previewLabel || 'Preview Interaktif')}</span>
                                        <span class="text-[10px] font-black text-muted">Berubah langsung</span>
                                    </div>
                                    <div data-builder-preview class="choice-builder-preview"></div>
                                </div>
                                <div data-kit-message class="rounded-2xl border border-adaptive bg-slate-50 dark:bg-white/[0.03] p-4 text-xs text-muted leading-relaxed">
                                    Pilih setiap pengaturan, lalu amati perubahan preview!
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                bind();
                renderPreview();
            }

            function bind() {
                root.querySelectorAll('[data-builder-group]').forEach(button => {
                    button.addEventListener('click', () => {
                        if (locked) return;
                        state[button.dataset.builderGroup] = button.dataset.builderOption;
                        checked = false;
                        render();
                    });
                });
            }

            render();

            return {
                check() {
                    checked = true;
                    const details = config.groups.map(group => state[group.id] === group.correct);
                    const score = details.filter(Boolean).length;
                    const result = { score, total: config.groups.length, details, passed: score >= minScore };
                    render();
                    const message = root.querySelector('[data-kit-message]');
                    if (message) {
                        message.className = `rounded-2xl border p-4 text-xs leading-relaxed ${result.passed ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300' : 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300'}`;
                        message.textContent = result.passed
                            ? 'Pengaturan sudah sesuai dengan kebutuhan tampilan.'
                            : 'Belum tepat. Ubah pilihan pada panel sesuai kebutuhan aktivitas, lalu periksa lagi.';
                    }
                    return result;
                },
                reset() {
                    if (locked) return;
                    config.groups.forEach(group => {
                        state[group.id] = group.default || group.options[0]?.id;
                    });
                    checked = false;
                    render();
                },
                lock() {
                    locked = true;
                    render();
                }
            };
        }

        window.CourseActivityKit = {
            mountCodeActivity,
            mountDragOrderActivity,
            mountChoiceBuilderActivity
        };
    })();
</script>
