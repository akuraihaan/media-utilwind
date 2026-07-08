@extends('layouts.landing')

@section('title', 'Buat Soal Kuis · Panel Admin Utilwind')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .quiz-admin-bg {
        background:
            linear-gradient(180deg, rgba(248, 250, 252, 0.96), rgba(241, 245, 249, 1)),
            radial-gradient(circle at top left, rgba(14, 165, 233, 0.08), transparent 34%);
    }
    .dark .quiz-admin-bg {
        background:
            linear-gradient(180deg, rgba(2, 6, 23, 0.98), rgba(15, 23, 42, 1)),
            radial-gradient(circle at top left, rgba(14, 165, 233, 0.10), transparent 34%);
    }
    .question-form-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 1.25rem;
    }
    @media (min-width: 1024px) {
        .question-form-grid {
            grid-template-columns: minmax(0, 1fr) minmax(300px, 360px);
            align-items: start;
        }
    }
    .question-panel,
    .question-preview-panel {
        border: 1px solid rgba(226, 232, 240, 0.95);
        background: rgba(255, 255, 255, 0.92);
        border-radius: 1.25rem;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.06);
    }
    .dark .question-panel,
    .dark .question-preview-panel {
        border-color: rgba(255, 255, 255, 0.09);
        background: rgba(15, 23, 42, 0.78);
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.22);
    }
    .question-preview-panel { padding: 1rem; }
    @media (min-width: 640px) { .question-preview-panel { padding: 1.25rem; } }
    .question-kicker {
        margin-bottom: 0.25rem;
        font-size: 10px;
        font-weight: 900;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: #0891b2;
    }
    .dark .question-kicker { color: #67e8f9; }
    .question-label {
        display: block;
        margin-bottom: 0.5rem;
        font-size: 10px;
        font-weight: 900;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #64748b;
    }
    .dark .question-label { color: rgba(255,255,255,0.48); }
    .question-input {
        width: 100%;
        border-radius: 0.9rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        padding: 0.8rem 0.95rem;
        color: #0f172a;
        font-size: 0.875rem;
        outline: none;
        transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
    }
    .question-input:focus {
        border-color: #0891b2;
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12);
    }
    .dark .question-input {
        border-color: rgba(255,255,255,0.10);
        background: rgba(2, 6, 23, 0.72);
        color: #f8fafc;
    }
    .dark .question-input:focus {
        border-color: #22d3ee;
        box-shadow: 0 0 0 4px rgba(34, 211, 238, 0.12);
    }
    .question-type-card {
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        min-height: 92px;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        background: #fff;
        padding: 0.85rem;
        text-align: left;
        transition: border-color .2s ease, background .2s ease, box-shadow .2s ease, transform .2s ease;
    }
    .question-type-card:hover {
        border-color: #67e8f9;
        transform: translateY(-1px);
    }
    .question-type-card.is-active {
        border-color: #0891b2;
        background: #ecfeff;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.12);
    }
    .dark .question-type-card {
        border-color: rgba(255,255,255,0.10);
        background: rgba(2, 6, 23, 0.58);
    }
    .dark .question-type-card:hover { border-color: rgba(103, 232, 249, 0.55); }
    .dark .question-type-card.is-active {
        border-color: rgba(103, 232, 249, 0.72);
        background: rgba(8, 145, 178, 0.14);
        box-shadow: 0 0 0 3px rgba(34, 211, 238, 0.12);
    }
    .answer-input-row { display: flex; align-items: center; gap: 0.75rem; }
    .answer-letter,
    .preview-letter {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 0.8rem;
        font-size: 0.75rem;
        font-weight: 900;
    }
    .answer-letter {
        width: 2.5rem;
        height: 2.5rem;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #64748b;
    }
    .dark .answer-letter {
        border-color: rgba(255,255,255,0.10);
        background: rgba(255,255,255,0.04);
        color: rgba(255,255,255,0.62);
    }
    .preview-option {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        border-radius: 0.9rem;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        padding: 0.7rem;
        color: #475569;
        font-size: 0.82rem;
        font-weight: 700;
    }
    .preview-letter {
        width: 2rem;
        height: 2rem;
        background: #e2e8f0;
        color: #475569;
    }
    .preview-option.is-correct {
        border-color: #86efac;
        background: #f0fdf4;
        color: #166534;
    }
    .preview-option.is-correct .preview-letter {
        background: #16a34a;
        color: white;
    }
    .dark .preview-option {
        border-color: rgba(255,255,255,0.09);
        background: rgba(255,255,255,0.04);
        color: rgba(255,255,255,0.68);
    }
    .dark .preview-option.is-correct {
        border-color: rgba(74, 222, 128, 0.45);
        background: rgba(34, 197, 94, 0.12);
        color: #bbf7d0;
    }
    .swal2-container.quiz-alert-layer { z-index: 2147483647 !important; }
    .quiz-alert-popup { border-radius: 1.25rem !important; }
</style>

<main id="admin-main-content" class="quiz-admin-bg min-h-screen pt-20 pb-12 text-slate-900 dark:text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="min-w-0">
                <a href="{{ route('admin.analytics.questions') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/80 px-3 py-1.5 text-xs font-bold text-slate-600 shadow-sm transition hover:border-cyan-300 hover:text-cyan-700 dark:border-white/10 dark:bg-white/5 dark:text-white/60 dark:hover:text-cyan-200">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke Analitik
                </a>
                <p class="mt-5 text-[10px] font-black uppercase tracking-[0.22em] text-cyan-700 dark:text-cyan-300">Kuis Utilwind</p>
                <h1 class="mt-2 text-2xl font-black tracking-tight text-slate-950 dark:text-white sm:text-4xl">Buat Soal Kuis</h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-slate-600 dark:text-white/58">
                    Susun soal, opsi, TP, dan media gambar.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button id="questionGuideOpen" type="button" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white/80 px-5 py-3 text-sm font-black text-slate-700 shadow-sm transition hover:border-cyan-300 hover:text-cyan-700 dark:border-white/10 dark:bg-white/5 dark:text-white/70 dark:hover:text-cyan-200">
                    Panduan
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 18a6 6 0 100-12 6 6 0 000 12z"/></svg>
                </button>
                <button id="submitQuestionBtn" type="button" onclick="submitForm()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-sm font-black text-white shadow-lg shadow-slate-950/10 transition hover:bg-cyan-700 dark:bg-cyan-500 dark:text-slate-950 dark:hover:bg-cyan-300">
                    <span id="btnText">Simpan Soal</span>
                    <svg id="btnLoader" class="hidden h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                </button>
            </div>
        </div>

        <form id="quizForm" action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.quiz._question_form_fields')
        </form>
    </div>
</main>

<div id="questionGuideModal" class="fixed inset-0 z-[999999] hidden items-center justify-center p-4 sm:p-6" aria-hidden="true">
    <button type="button" class="absolute inset-0 bg-slate-900/60 backdrop-blur-md dark:bg-[#020617]/80" data-question-guide-close aria-label="Tutup panduan"></button>
    <section class="relative max-h-[92vh] w-full max-w-6xl overflow-y-auto rounded-[2rem] border border-slate-200 bg-white/95 p-6 shadow-2xl dark:border-white/10 dark:bg-[#0f141e]/95 sm:p-8">
        <button type="button" data-question-guide-close class="absolute right-5 top-5 z-10 rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-white/5 dark:hover:text-white" aria-label="Tutup panduan">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        @php
            $guideTitle = 'Panduan Buat Soal';
            $guideSubtitle = 'Menyusun evaluasi kuis';
            $guideImage = 'images/guides/current-admin-question-create.png';
            $guideIntro = 'Gunakan nomor pada gambar untuk membaca area penyusunan soal, opsi jawaban, tujuan pembelajaran, dan media pendukung.';
            $guidePoints = [
                ['x' => 50, 'y' => 28, 'title' => 'Informasi soal', 'description' => 'Isi bab, tipe soal, teks pertanyaan, dan TP sebelum menyimpan.'],
                ['x' => 45, 'y' => 62, 'title' => 'Opsi jawaban', 'description' => 'Lengkapi pilihan jawaban dan tandai kunci yang benar.'],
                ['x' => 82, 'y' => 56, 'title' => 'Pratinjau', 'description' => 'Gunakan pratinjau untuk mengecek keterbacaan soal dan media.'],
            ];
        @endphp
        @include('admin.partials.analytics_guide_mockup')

        <div class="mt-8 border-t border-slate-200 pt-6 dark:border-white/5">
            <button type="button" data-question-guide-close class="w-full rounded-xl bg-slate-900 py-3 text-sm font-bold text-white shadow-md transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">Mengerti, Tutup Panduan</button>
        </div>
    </section>
</div>

<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    const interactionMeta = {
        multiple_choice: { label: 'Pilihan Ganda', status: 'Standar' },
        image_context: { label: 'Soal Gambar', status: 'Media' },
    };
    let previewObjectUrl = null;
    const quizAlertDefaults = {
        customClass: {
            container: 'quiz-alert-layer',
            popup: 'quiz-alert-popup',
        },
    };

    function showQuizAlert(options = {}) {
        return Swal.fire({
            ...quizAlertDefaults,
            ...options,
            customClass: {
                ...quizAlertDefaults.customClass,
                ...(options.customClass || {}),
            },
        });
    }

    function renderQuestionStudentInsight() {
        $('#studentInsightTotal').text(0);
        $('#studentInsightCorrect').text(0);
        $('#studentInsightWrong').text(0);
        $('#studentInsightBar').css('width', '0%');
        $('#studentInsightStatus').text('Baru');
        $('#studentInsightNote').text('Data siswa akan tampil setelah soal tersimpan dan dijawab.');
    }

    function getQuestionOutcomeOptions() {
        const element = document.getElementById('question-outcome-options-json');
        if (!element) return {};

        try {
            return JSON.parse(element.textContent || '{}') || {};
        } catch (error) {
            return {};
        }
    }

    const questionOutcomeOptions = getQuestionOutcomeOptions();

    function setLearningOutcomeFields(row = null) {
        $('#inputLearningObjectiveCode').val(row?.code || '');
        $('#inputLearningObjectiveTitle').val(row?.title || '');
        $('#inputRemediationHint').val(row?.material || row?.title || '');
        $('#selectedLearningObjectiveHint').text(row
            ? `${row.code} - ${row.title}`
            : 'Pilih TP sesuai bab. Kode dan arahan materi tersimpan otomatis.');
    }

    function populateLearningOutcomeSelect(selectedCode = '') {
        const chapter = String($('#inputChapter').val() || '1');
        const rows = questionOutcomeOptions[chapter] || [];
        const currentCode = selectedCode || ($('#inputLearningObjectiveCode').val() || '').trim();
        const select = $('#inputLearningObjectiveSelect');

        if (!select.length) return;

        select.empty().append($('<option>', {
            value: '',
            text: rows.length ? 'Pilih TP resmi' : 'TP belum tersedia',
        }));

        rows.forEach((row) => {
            $('<option>', {
                value: row.code,
                text: `${row.code} - ${row.title}`,
            })
                .attr('data-title', row.title || '')
                .attr('data-material', row.material || row.title || '')
                .appendTo(select);
        });

        const selectedRow = rows.find((row) => row.code === currentCode) || null;
        select.val(selectedRow ? selectedRow.code : '');

        if (selectedRow) {
            setLearningOutcomeFields(selectedRow);
        } else if (!currentCode) {
            setLearningOutcomeFields(null);
        }
    }

    function applySelectedLearningOutcome() {
        const selected = $('#inputLearningObjectiveSelect option:selected');
        const code = selected.val() || '';
        if (!code) {
            setLearningOutcomeFields(null);
            return;
        }

        setLearningOutcomeFields({
            code,
            title: selected.attr('data-title') || '',
            material: selected.attr('data-material') || selected.attr('data-title') || '',
        });
    }

    function syncLearningOutcomeSelectFromFields() {
        const code = ($('#inputLearningObjectiveCode').val() || '').trim();
        const title = ($('#inputLearningObjectiveTitle').val() || '').trim().toLowerCase();
        const select = $('#inputLearningObjectiveSelect');
        if (!select.length) return;

        const matched = select.find('option').filter(function () {
            const option = $(this);
            return option.val() === code || (title && (option.attr('data-title') || '').trim().toLowerCase() === title);
        }).first();

        select.val(matched.length ? matched.val() : '');
        if (matched.length) {
            applySelectedLearningOutcome();
        }
    }

    function renderExistingMediaPath(mediaPath = '') {
        const currentValue = (mediaPath || '').trim();
        const existingPath = ($('#inputMediaFile').attr('data-existing-path') || '').trim();
        const existingUrl = ($('#inputMediaFile').attr('data-existing-url') || '').trim();
        const value = existingPath && (!currentValue || currentValue === existingUrl)
            ? existingPath
            : currentValue;
        const mediaFile = $('#inputMediaFile')[0]?.files?.[0] || null;
        const removingMedia = $('#inputRemoveMedia').is(':checked');
        let label = 'Pilih gambar';
        let badge = 'Pilih File';

        if (removingMedia) {
            label = 'Media akan dihapus';
            badge = 'Hapus';
        } else if (mediaFile) {
            label = mediaFile.name;
            badge = 'File Baru';
        } else if (value) {
            label = value;
            badge = 'Media Aktif';
        }

        $('#mediaFileDisplay').text(label).attr('title', label);
        $('#mediaFileBadge').text(badge);
    }

    function checkImageUrlAvailable(url) {
        return new Promise((resolve) => {
            if (!url) {
                resolve(false);
                return;
            }

            const image = new Image();
            const timer = window.setTimeout(() => {
                image.onload = null;
                image.onerror = null;
                resolve(false);
            }, 7000);

            image.onload = () => {
                window.clearTimeout(timer);
                resolve(true);
            };
            image.onerror = () => {
                window.clearTimeout(timer);
                resolve(false);
            };
            image.src = url;
        });
    }

    function setQuestionType(type) {
        $('#inputInteractionType').val(type);
        updateQuestionTypeUI();
        renderQuestionPreview();
    }

    function updateQuestionTypeUI() {
        const type = $('#inputInteractionType').val() || 'multiple_choice';
        const needsMedia = type === 'image_context';
        const hasFile = Boolean($('#inputMediaFile')[0]?.files?.length);
        const isEditing = Boolean($('#questionId').val());

        $('.question-type-card').removeClass('is-active');
        $(`.question-type-card[data-type="${type}"]`).addClass('is-active');

        $('#imageUploadField, #imageMetaFields').toggleClass('hidden', !needsMedia);
        $('#removeMediaField').toggleClass('hidden', !needsMedia || !isEditing);
        $('#inputMediaFile, #inputMediaUrl, #inputMediaCaption, #inputRemoveMedia').prop('disabled', !needsMedia);
        $('#inputMediaUrl').prop('required', needsMedia && !hasFile);
        $('#previewTypeLabel').text(interactionMeta[type]?.label || 'Pilihan Ganda');
        $('#previewStatus').text(interactionMeta[type]?.status || 'Draft');
    }

    function renderQuestionPreview() {
        const type = $('#inputInteractionType').val() || 'multiple_choice';
        const question = ($('#inputQuestion').val() || '').trim();
        const caption = ($('#inputMediaCaption').val() || '').trim();
        const mediaUrl = ($('#inputMediaUrl').val() || '').trim();
        const mediaFile = $('#inputMediaFile')[0]?.files?.[0] || null;
        const isEditing = Boolean($('#questionId').val());

        if (previewObjectUrl) {
            URL.revokeObjectURL(previewObjectUrl);
            previewObjectUrl = null;
        }

        const previewSrc = type === 'image_context' ? (mediaFile ? URL.createObjectURL(mediaFile) : mediaUrl) : '';
        if (mediaFile) previewObjectUrl = previewSrc;

        $('#previewQuestion').text(question || 'Teks pertanyaan akan tampil di sini.');
        renderExistingMediaPath(type === 'image_context' ? mediaUrl : '');

        if (previewSrc) {
            $('#previewMedia').attr('src', previewSrc);
            $('#previewMediaWrap').removeClass('hidden');
        } else {
            $('#previewMedia').attr('src', '');
            $('#previewMediaWrap').addClass('hidden');
        }

        $('#previewCaption').text(caption).toggleClass('hidden', !caption);

        const options = ['a', 'b', 'c', 'd'];
        const correctVal = $('#inputCorrect').val() || 'option_a';
        options.forEach((opt) => {
            const value = ($('#inputOption_' + opt).val() || '').trim();
            const previewEl = $('#preview_' + opt);
            previewEl.find('.preview-text').text(value || 'Pilihan ' + opt.toUpperCase());
            previewEl.toggleClass('is-correct', correctVal === 'option_' + opt);
        });
    }

    $(document).on('change', '#inputInteractionType', () => {
        updateQuestionTypeUI();
        renderQuestionPreview();
    });
    $(document).on('change', '#inputChapter', () => {
        setLearningOutcomeFields(null);
        populateLearningOutcomeSelect();
    });
    $(document).on('change', '#inputLearningObjectiveSelect', () => {
        applySelectedLearningOutcome();
    });
    $(document).on('input', '#inputLearningObjectiveCode, #inputLearningObjectiveTitle', () => {
        syncLearningOutcomeSelectFromFields();
    });
    $(document).on('input change', '#inputQuestion, #inputCorrect, #inputOption_a, #inputOption_b, #inputOption_c, #inputOption_d, #inputInteractionPrompt, #inputMediaUrl, #inputMediaCaption, #inputMediaFile, #inputRemoveMedia', () => {
        updateQuestionTypeUI();
        renderQuestionPreview();
    });

    async function submitForm() {
        const form = $('#quizForm');
        const interactionType = $('#inputInteractionType').val() || 'multiple_choice';
        const mediaUrl = ($('#inputMediaUrl').val() || '').trim();
        const mediaFile = $('#inputMediaFile')[0]?.files?.length || 0;

        if (!($('#inputLearningObjectiveSelect').val() || '').trim()) {
            showQuizAlert({
                title: 'Pemetaan TP belum dipilih',
                text: 'Pilih tujuan pembelajaran resmi sesuai bab sebelum menyimpan soal.',
                icon: 'warning',
                confirmButtonColor: '#0891b2',
            });
            return;
        }
        applySelectedLearningOutcome();

        if (interactionType === 'image_context' && !mediaUrl && !mediaFile) {
            showQuizAlert({
                title: 'Media belum tersedia',
                text: 'Soal gambar wajib memiliki upload gambar atau URL gambar. Pilih file gambar atau isi URL gambar terlebih dahulu.',
                icon: 'warning',
                confirmButtonColor: '#0891b2',
            });
            return;
        }
        if (interactionType === 'image_context' && mediaUrl && !mediaFile) {
            const imageAvailable = await checkImageUrlAvailable(mediaUrl);
            if (!imageAvailable) {
                showQuizAlert({
                    title: 'Gambar tidak dapat dimuat',
                    text: 'URL gambar tidak dapat dibuka. Periksa kembali URL gambar atau gunakan upload file.',
                    icon: 'error',
                    confirmButtonColor: '#ef4444',
                });
                return;
            }
        }

        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }

        $('#btnText').text('Menyimpan...');
        $('#btnLoader').removeClass('hidden');
        $('#submitQuestionBtn').prop('disabled', true).addClass('opacity-70');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: new FormData(form[0]),
            processData: false,
            contentType: false,
        }).done((res) => {
            showQuizAlert({
                title: 'Berhasil',
                text: res.message || 'Soal baru berhasil disimpan.',
                icon: 'success',
                confirmButtonColor: '#0891b2'
            }).then(() => {
                const target = new URL("{{ route('admin.analytics.questions') }}", window.location.origin);
                target.searchParams.set('chapter', $('#inputChapter').val() || '1');
                window.location.href = target.toString();
            });
        }).fail((err) => {
            showQuizAlert({
                title: 'Gagal',
                text: err.responseJSON?.message || 'Terjadi kesalahan saat menyimpan soal.',
                icon: 'error',
                confirmButtonColor: '#ef4444'
            });
            $('#btnText').text('Simpan Soal');
            $('#btnLoader').addClass('hidden');
            $('#submitQuestionBtn').prop('disabled', false).removeClass('opacity-70');
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const guideModal = document.getElementById('questionGuideModal');
        const openGuideButton = document.getElementById('questionGuideOpen');
        const closeGuideButtons = document.querySelectorAll('[data-question-guide-close]');

        openGuideButton?.addEventListener('click', () => {
            guideModal?.classList.remove('hidden');
            guideModal?.classList.add('flex');
            guideModal?.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
        });

        closeGuideButtons.forEach((button) => {
            button.addEventListener('click', () => {
                guideModal?.classList.add('hidden');
                guideModal?.classList.remove('flex');
                guideModal?.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                guideModal?.classList.add('hidden');
                guideModal?.classList.remove('flex');
                guideModal?.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');
            }
        });

        $('#inputCorrect').val('option_a');
        populateLearningOutcomeSelect();
        syncLearningOutcomeSelectFromFields();
        renderExistingMediaPath('');
        renderQuestionStudentInsight();
        updateQuestionTypeUI();
        renderQuestionPreview();
    });
</script>
@endsection
