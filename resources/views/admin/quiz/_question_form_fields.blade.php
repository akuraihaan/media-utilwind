@php
    $questionOutcomeBlueprints = collect(\App\Support\LearningOutcomeAnalytics::quizOutcomeBlueprints())
        ->map(fn (array $objectives) => collect($objectives)
            ->map(fn (array $objective) => [
                'code' => $objective['code'] ?? '',
                'title' => $objective['title'] ?? '',
                'material' => $objective['material'] ?? ($objective['title'] ?? ''),
            ])
            ->values()
            ->all())
        ->all();
@endphp

<input type="hidden" id="questionId" name="id">
<script type="application/json" id="question-outcome-options-json">@json($questionOutcomeBlueprints)</script>

<div class="question-form-grid">
    <div class="space-y-4 min-w-0">
        <section class="question-panel p-4 sm:p-5">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
                <div>
                    <p class="question-kicker">Konten Soal</p>
                    <h3 class="text-base font-black text-slate-950 dark:text-white">Pertanyaan dan identitas materi</h3>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="question-label" for="inputQuestion">Teks Pertanyaan</label>
                    <textarea name="question_text" id="inputQuestion" rows="4" class="question-input min-h-[132px] resize-y" placeholder="Tuliskan pertanyaan dengan jelas dan spesifik..." required></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="question-label" for="inputChapter">Materi Bab</label>
                        <select name="chapter_id" id="inputChapter" class="question-input cursor-pointer">
                            <option value="1">Bab 1: Pendahuluan</option>
                            <option value="2">Bab 2: Layouting</option>
                            <option value="3">Bab 3: Styling</option>
                            <option value="99">Evaluasi Akhir</option>
                        </select>
                    </div>
                    <div id="correctAnswerField">
                        <label class="question-label" for="inputCorrect">Jawaban Benar</label>
                        <select name="correct_answer" id="inputCorrect" class="question-input cursor-pointer font-bold text-emerald-700 dark:text-emerald-300" required>
                            <option value="option_a">Pilihan A</option>
                            <option value="option_b">Pilihan B</option>
                            <option value="option_c">Pilihan C</option>
                            <option value="option_d">Pilihan D</option>
                        </select>
                    </div>
                </div>

                <div>
                    <input type="hidden" name="learning_objective_code" id="inputLearningObjectiveCode">
                    <input type="hidden" name="learning_objective_title" id="inputLearningObjectiveTitle">
                    <input type="hidden" name="remediation_hint" id="inputRemediationHint">
                    <div>
                        <label class="question-label" for="inputLearningObjectiveSelect">Pemetaan TP</label>
                        <select id="inputLearningObjectiveSelect" class="question-input cursor-pointer" required>
                            <option value="">Pilih TP resmi</option>
                        </select>
                        <p id="selectedLearningObjectiveHint" class="mt-2 text-[11px] font-semibold leading-5 text-slate-500 dark:text-white/45">Pilih TP sesuai bab. Kode dan arahan materi tersimpan otomatis.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="question-panel p-4 sm:p-5">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
                <div>
                    <p class="question-kicker">Format</p>
                    <h3 class="text-base font-black text-slate-950 dark:text-white">Jenis soal dan media</h3>
                </div>
                <label id="removeMediaField" class="hidden inline-flex w-fit items-center gap-2 rounded-full border border-red-200 bg-red-50 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-300">
                    <input type="checkbox" name="remove_media" id="inputRemoveMedia" value="1" class="rounded border-red-300 text-red-600 focus:ring-red-500">
                    Hapus Media
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="question-label" for="inputInteractionType">Format Soal</label>
                    <select name="interaction_type" id="inputInteractionType" class="question-input cursor-pointer">
                        <option value="multiple_choice">Pilihan Ganda Biasa</option>
                        <option value="image_context">Soal Gambar</option>
                    </select>
                </div>
                <div id="imageUploadField">
                    <label class="question-label" for="inputMediaFile">Upload Gambar</label>
                    <input type="file" name="media_file" id="inputMediaFile" accept="image/*" class="sr-only">
                    <label for="inputMediaFile" class="flex min-h-[46px] cursor-pointer items-center justify-between gap-3 rounded-[0.9rem] border border-slate-200 bg-white px-3 py-2 text-sm transition hover:border-cyan-300 hover:bg-cyan-50/60 dark:border-white/10 dark:bg-[#020617]/70 dark:hover:border-cyan-400/50 dark:hover:bg-cyan-500/10">
                        <span id="mediaFileDisplay" class="min-w-0 flex-1 truncate font-semibold text-slate-600 dark:text-white/65">Pilih gambar</span>
                        <span id="mediaFileBadge" class="shrink-0 rounded-lg bg-slate-900 px-2.5 py-1.5 text-[10px] font-black uppercase tracking-widest text-white dark:bg-white dark:text-slate-900">Pilih File</span>
                    </label>
                </div>
            </div>

            <div id="imageMetaFields" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="question-label" for="inputMediaUrl">URL Gambar</label>
                    <input type="text" name="media_url" id="inputMediaUrl" maxlength="1000" class="question-input" placeholder="https://... atau /uploads/quiz-media/...">
                </div>
                <div>
                    <label class="question-label" for="inputMediaCaption">Caption Media</label>
                    <input type="text" name="media_caption" id="inputMediaCaption" maxlength="255" class="question-input" placeholder="Contoh: Tampilan layout kartu responsif">
                </div>
            </div>
            <input type="hidden" name="interaction_prompt" id="inputInteractionPrompt" value="">
        </section>

        <section id="optionFields" class="question-panel p-4 sm:p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                <div>
                    <p class="question-kicker">Jawaban</p>
                    <h3 class="text-base font-black text-slate-950 dark:text-white">Opsi pilihan</h3>
                </div>
            </div>

            <div class="space-y-3">
                @foreach(['a','b','c','d'] as $opt)
                    <div class="answer-input-row">
                        <span class="answer-letter">{{ strtoupper($opt) }}</span>
                        <input type="text" name="option_{{ $opt }}" id="inputOption_{{ $opt }}" class="question-input" placeholder="Tulis opsi jawaban {{ strtoupper($opt) }}..." required>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <aside id="mediaPratinjauPanel" class="question-preview-panel lg:sticky lg:top-4 self-start">
        <div class="flex items-start justify-between gap-3 mb-4">
            <div>
                <p class="question-kicker">Pratinjau</p>
                <h3 id="previewTypeLabel" class="text-base font-black text-slate-950 dark:text-white">Pilihan Ganda</h3>
            </div>
            <span id="previewStatus" class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-white/45">Draft</span>
        </div>

        <div id="previewMediaWrap" class="hidden mb-4 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 dark:border-white/10 dark:bg-black/20">
            <img id="previewMedia" src="" alt="Pratinjau media" class="h-44 w-full object-contain">
            <p id="previewCaption" class="hidden border-t border-slate-200 px-3 py-2 text-xs text-slate-500 dark:border-white/10 dark:text-white/50"></p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-[#020617]/55">
            <p id="previewQuestion" class="min-h-[72px] text-sm font-semibold leading-relaxed text-slate-800 dark:text-white/80">Teks pertanyaan akan tampil di sini.</p>
            <div class="mt-4 space-y-2" id="previewOptions">
                @foreach(['a','b','c','d'] as $opt)
                    <div id="preview_{{ $opt }}" class="preview-option">
                        <span class="preview-letter">{{ strtoupper($opt) }}</span>
                        <span class="preview-text truncate">Pilihan {{ strtoupper($opt) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div id="questionStudentInsightPanel" class="mt-4 rounded-2xl border border-slate-200 bg-slate-50/80 p-4 dark:border-white/10 dark:bg-[#020617]/55">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="question-kicker">Insight Siswa</p>
                    <h4 class="text-sm font-black text-slate-950 dark:text-white">Jawaban pada soal ini</h4>
                </div>
                <span id="studentInsightStatus" class="rounded-full border border-slate-200 bg-white px-2.5 py-1 text-[9px] font-black uppercase tracking-widest text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-white/45">Baru</span>
            </div>

            <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                <div class="rounded-xl border border-slate-200 bg-white px-2 py-3 dark:border-white/10 dark:bg-white/5">
                    <p id="studentInsightTotal" class="text-lg font-black text-slate-900 dark:text-white">0</p>
                    <p class="mt-1 text-[9px] font-bold uppercase tracking-widest text-slate-400">Menjawab</p>
                </div>
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-2 py-3 dark:border-emerald-500/20 dark:bg-emerald-500/10">
                    <p id="studentInsightCorrect" class="text-lg font-black text-emerald-700 dark:text-emerald-300">0</p>
                    <p class="mt-1 text-[9px] font-bold uppercase tracking-widest text-emerald-700/70 dark:text-emerald-200/70">Benar</p>
                </div>
                <div class="rounded-xl border border-rose-200 bg-rose-50 px-2 py-3 dark:border-rose-500/20 dark:bg-rose-500/10">
                    <p id="studentInsightWrong" class="text-lg font-black text-rose-700 dark:text-rose-300">0</p>
                    <p class="mt-1 text-[9px] font-bold uppercase tracking-widest text-rose-700/70 dark:text-rose-200/70">Salah</p>
                </div>
            </div>

            <div class="mt-4">
                <div class="h-2 overflow-hidden rounded-full bg-slate-200 dark:bg-white/10">
                    <div id="studentInsightBar" class="h-full rounded-full bg-emerald-500 transition-all" style="width: 0%"></div>
                </div>
                <p id="studentInsightNote" class="mt-2 text-[11px] font-semibold leading-5 text-slate-500 dark:text-white/45">Data siswa akan tampil setelah soal tersimpan dan dijawab.</p>
            </div>
        </div>
    </aside>
</div>
