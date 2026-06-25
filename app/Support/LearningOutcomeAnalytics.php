<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LearningOutcomeAnalytics
{
    public static function quizOutcomeBlueprints(): array
    {
        return collect(self::quizOutcomeProfiles())
            ->map(fn (array $profiles) => collect($profiles)
                ->map(fn (array $profile) => collect($profile)->except('keywords')->all())
                ->values()
                ->all())
            ->all();
    }

    public static function quizOutcomeMetadata(object|null $question): array
    {
        $outcome = self::resolveQuizOutcome($question);
        $row = [
            'code' => $outcome['code'],
            'title' => $outcome['title'],
            'chapter_id' => $outcome['chapter_id'],
            'order' => self::objectiveNumber(['code' => $outcome['code']]) - 1,
        ];

        $outcome['display_code'] = self::displayOutcomeCode($row);
        $outcome['label'] = $outcome['display_code'] . ' - ' . $outcome['title'];

        return $outcome;
    }

    public static function forQuizAttempt(Collection $questions, Collection $answersByQuestion, object|null $attempt = null): array
    {
        $groups = collect();

        $questions->values()->each(function ($question, int $index) use ($answersByQuestion, $groups) {
            $answer = $answersByQuestion->get($question->id);
            $outcome = self::resolveQuizOutcome($question);

            if (!$groups->has($outcome['key'])) {
                $groups->put($outcome['key'], [
                    'key' => $outcome['key'],
                    'code' => $outcome['code'],
                    'title' => $outcome['title'],
                    'label' => $outcome['label'],
                    'material' => $outcome['material'],
                    'chapter_id' => $outcome['chapter_id'],
                    'order' => $index,
                    'total_questions' => 0,
                    'correct_count' => 0,
                    'wrong_count' => 0,
                    'unanswered_count' => 0,
                    'flagged_count' => 0,
                    'answer_change_count' => 0,
                    'question_numbers' => [],
                ]);
            }

            $row = $groups->get($outcome['key']);
            $isAnswered = self::answerHasResponse($answer);
            $isCorrect = (bool) ($answer?->is_correct);

            $row['total_questions']++;
            $row['question_numbers'][] = $index + 1;
            $row['correct_count'] += $isCorrect ? 1 : 0;
            $row['wrong_count'] += ($isAnswered && !$isCorrect) ? 1 : 0;
            $row['unanswered_count'] += $isAnswered ? 0 : 1;
            $row['flagged_count'] += (bool) ($answer?->is_flagged) ? 1 : 0;
            $row['answer_change_count'] += (int) ($answer?->answer_change_count ?? 0);

            $groups->put($outcome['key'], $row);
        });

        $outcomes = $groups
            ->values()
            ->map(fn (array $row) => self::completeQuizOutcome($row))
            ->sortBy('order')
            ->values();

        return self::summarizeQuizOutcomes($outcomes, $attempt);
    }

    public static function aggregateQuizOutcomes(Collection $questions): array
    {
        $groups = collect();

        $questions->values()->each(function ($question, int $index) use ($groups) {
            $outcome = self::resolveQuizOutcome($question);

            if (!$groups->has($outcome['key'])) {
                $groups->put($outcome['key'], [
                    'key' => $outcome['key'],
                    'code' => $outcome['code'],
                    'title' => $outcome['title'],
                    'label' => $outcome['label'],
                    'material' => $outcome['material'],
                    'chapter_id' => $outcome['chapter_id'],
                    'order' => $index,
                    'question_count' => 0,
                    'total_answers' => 0,
                    'correct_count' => 0,
                    'wrong_count' => 0,
                    'chapters' => [],
                ]);
            }

            $row = $groups->get($outcome['key']);
            $row['question_count']++;
            $row['total_answers'] += (int) ($question->total_attempts ?? 0);
            $row['correct_count'] += (int) ($question->correct_count ?? 0);
            $row['wrong_count'] += (int) ($question->wrong_count ?? 0);
            $row['chapters'][] = (int) ($question->chapter_id ?? 0);

            $groups->put($outcome['key'], $row);
        });

        $outcomes = $groups
            ->values()
            ->map(fn (array $row) => self::completeAggregateQuizOutcome($row))
            ->sortBy('order')
            ->values();

        return [
            'outcomes' => $outcomes,
            'needs_attention' => $outcomes
                ->filter(fn ($row) => ($row['mastery_percent'] ?? 0) < 70 || ($row['total_answers'] ?? 0) === 0)
                ->sortBy('mastery_percent')
                ->values(),
            'strongest' => $outcomes->sortByDesc('mastery_percent')->first(),
            'weakest' => $outcomes->sortBy('mastery_percent')->first(),
            'summary_text' => self::aggregateSummaryText($outcomes),
        ];
    }

    public static function forLabReview(Collection $reviewItems, array $metrics = []): array
    {
        $groups = collect();

        $reviewItems->values()->each(function (array $item, int $index) use ($groups) {
            $step = $item['step'] ?? null;
            $outcome = self::resolveOutcome(
                $step,
                'TP Praktik',
                'Tujuan praktik umum',
                'Tinjau ulang instruksi langkah praktik dan aturan validasi yang belum terpenuhi.'
            );

            if (!$groups->has($outcome['key'])) {
                $groups->put($outcome['key'], [
                    'key' => $outcome['key'],
                    'code' => $outcome['code'],
                    'title' => $outcome['title'],
                    'label' => $outcome['label'],
                    'material' => $outcome['material'],
                    'order' => $index,
                    'total_steps' => 0,
                    'completed_steps' => 0,
                    'incomplete_steps' => 0,
                    'earned_points' => 0,
                    'total_points' => 0,
                    'failed_rules' => [],
                    'step_titles' => [],
                ]);
            }

            $row = $groups->get($outcome['key']);
            $isCompleted = (bool) ($item['is_completed'] ?? false);
            $points = (int) ($item['points'] ?? 0);

            $row['total_steps']++;
            $row['completed_steps'] += $isCompleted ? 1 : 0;
            $row['incomplete_steps'] += $isCompleted ? 0 : 1;
            $row['earned_points'] += $isCompleted ? $points : 0;
            $row['total_points'] += $points;
            $row['step_titles'][] = (string) ($step->title ?? 'Langkah praktik');

            if (!$isCompleted && !empty($item['failed_rule'])) {
                $row['failed_rules'][] = (string) $item['failed_rule'];
            }

            $groups->put($outcome['key'], $row);
        });

        $outcomes = $groups
            ->values()
            ->map(fn (array $row) => self::completeLabOutcome($row))
            ->sortBy('order')
            ->values();

        return self::summarizeLabOutcomes($outcomes, $metrics);
    }

    private static function completeQuizOutcome(array $row): array
    {
        $total = max(1, (int) $row['total_questions']);
        $percent = round(((int) $row['correct_count'] / $total) * 100, 1);
        $judgement = self::judgement($percent);
        $questionList = implode(', ', $row['question_numbers']);

        $row['mastery_percent'] = $percent;
        $row['status'] = $judgement['status'];
        $row['tone'] = $judgement['tone'];
        $row['decision'] = $judgement['decision'];
        $row['display_code'] = self::displayOutcomeCode($row);
        $row['label'] = $row['display_code'] . ' - ' . ($row['title'] ?? 'TP');
        $row['learning_description'] = self::learningDescription($row);
        $row['activity_data'] = sprintf(
            'Menjawab benar %s dari %s soal pada %s. Soal: %s. Salah: %s, kosong: %s, ragu-ragu: %s, perubahan jawaban: %s.',
            $row['correct_count'],
            $row['total_questions'],
            $row['label'],
            $questionList ?: '-',
            $row['wrong_count'],
            $row['unanswered_count'],
            $row['flagged_count'],
            $row['answer_change_count']
        );
        $row['mastery_statement'] = self::quizMasteryStatement($row);
        $row['material_direction'] = self::quizMaterialDirection($row);
        $row['interpretation'] = $row['mastery_statement'];
        $row['recommendation'] = $row['material_direction'];

        return $row;
    }

    private static function completeAggregateQuizOutcome(array $row): array
    {
        $answers = (int) $row['total_answers'];
        $percent = $answers > 0 ? round(((int) $row['correct_count'] / $answers) * 100, 1) : 0;
        $judgement = $answers > 0
            ? self::judgement($percent)
            : ['status' => 'Belum Ada Data', 'tone' => 'slate', 'decision' => 'Kumpulkan data pengerjaan'];

        $row['mastery_percent'] = $percent;
        $row['status'] = $judgement['status'];
        $row['tone'] = $judgement['tone'];
        $row['decision'] = $judgement['decision'];
        $row['chapters'] = collect($row['chapters'])->unique()->values()->all();
        $row['chapter_id'] = count($row['chapters']) === 1 ? (int) $row['chapters'][0] : null;
        $row['display_code'] = self::displayOutcomeCode($row);
        $row['label'] = $row['display_code'] . ' - ' . ($row['title'] ?? 'TP');
        $row['learning_description'] = self::learningDescription($row);
        $row['activity_data'] = sprintf(
            '%s jawaban terkumpul dari %s soal pada %s. Benar: %s, salah: %s.',
            $row['total_answers'],
            $row['question_count'],
            $row['label'],
            $row['correct_count'],
            $row['wrong_count']
        );
        $row['mastery_statement'] = $answers > 0
            ? self::aggregateMasteryStatement($row)
            : 'Belum cukup data aktivitas siswa pada ' . $row['label'] . '.';
        $row['material_direction'] = $answers > 0
            ? self::aggregateMaterialDirection($row)
            : 'Kumpulkan data pengerjaan kuis/evaluasi terlebih dahulu agar arahan materi per TP terbentuk.';
        $row['interpretation'] = $row['mastery_statement'];
        $row['recommendation'] = $row['material_direction'];

        return $row;
    }

    private static function completeLabOutcome(array $row): array
    {
        $points = (int) $row['total_points'];
        $percent = $points > 0
            ? round(((int) $row['earned_points'] / max(1, $points)) * 100, 1)
            : round(((int) $row['completed_steps'] / max(1, (int) $row['total_steps'])) * 100, 1);
        $judgement = self::judgement($percent);

        $row['mastery_percent'] = $percent;
        $row['status'] = $judgement['status'];
        $row['tone'] = $judgement['tone'];
        $row['decision'] = $judgement['decision'];
        $row['failed_rules'] = collect($row['failed_rules'])->unique()->values()->all();
        $row['learning_description'] = self::learningDescription($row);
        $row['activity_data'] = sprintf(
            'Menyelesaikan %s dari %s langkah praktik pada %s. Poin tervalidasi: %s dari %s. Aturan belum terpenuhi: %s.',
            $row['completed_steps'],
            $row['total_steps'],
            $row['label'],
            $row['earned_points'],
            max(1, $row['total_points']),
            count($row['failed_rules']) ? implode(', ', $row['failed_rules']) : 'tidak ada'
        );
        $row['mastery_statement'] = self::labMasteryStatement($row);
        $row['material_direction'] = self::labMaterialDirection($row);
        $row['interpretation'] = $row['mastery_statement'];
        $row['recommendation'] = $row['material_direction'];

        return $row;
    }

    private static function summarizeQuizOutcomes(Collection $outcomes, object|null $attempt): array
    {
        $needsReview = $outcomes
            ->filter(fn ($row) => ($row['mastery_percent'] ?? 0) < 70 || ($row['wrong_count'] ?? 0) > 0 || ($row['unanswered_count'] ?? 0) > 0)
            ->sortBy('mastery_percent')
            ->values();

        $weakest = $outcomes->sortBy('mastery_percent')->first();
        $strongest = $outcomes->sortByDesc('mastery_percent')->first();

        return [
            'outcomes' => $outcomes,
            'needs_review' => $needsReview,
            'weakest' => $weakest,
            'strongest' => $strongest,
            'decision' => self::overallDecision($outcomes),
            'summary_text' => self::quizSummaryText($outcomes, $attempt),
            'primary_recommendation' => $needsReview->first()['recommendation'] ?? 'Pertahankan ritme belajar dan lanjutkan latihan pengayaan.',
        ];
    }

    private static function summarizeLabOutcomes(Collection $outcomes, array $metrics): array
    {
        $needsReview = $outcomes
            ->filter(fn ($row) => ($row['mastery_percent'] ?? 0) < 70 || ($row['incomplete_steps'] ?? 0) > 0)
            ->sortBy('mastery_percent')
            ->values();

        return [
            'outcomes' => $outcomes,
            'needs_review' => $needsReview,
            'weakest' => $outcomes->sortBy('mastery_percent')->first(),
            'strongest' => $outcomes->sortByDesc('mastery_percent')->first(),
            'decision' => self::overallDecision($outcomes),
            'summary_text' => self::labSummaryText($outcomes, $metrics),
            'primary_recommendation' => $needsReview->first()['recommendation'] ?? 'Lanjutkan praktik pengayaan dan rapikan implementasi kode.',
        ];
    }

    private static function resolveOutcome(object|null $item, string $fallbackCode, string $fallbackTitle, string $fallbackMaterial): array
    {
        $code = trim((string) ($item->learning_objective_code ?? ''));
        $title = trim((string) ($item->learning_objective_title ?? ''));
        $material = trim((string) ($item->remediation_hint ?? ''));

        if ($code === '' && $title === '') {
            $code = $fallbackCode;
            $title = $fallbackTitle;
        }

        if ($code === '') {
            $code = Str::upper(Str::limit(Str::slug($title, '-'), 40, ''));
        }

        if ($title === '') {
            $title = $code;
        }

        return [
            'key' => Str::lower($code . '|' . $title),
            'code' => $code,
            'title' => $title,
            'label' => $code . ' - ' . $title,
            'material' => $material !== '' ? $material : ($title !== '' ? $title : $fallbackMaterial),
        ];
    }

    private static function resolveQuizOutcome(object|null $question): array
    {
        $fallback = self::inferQuizOutcome($question);
        $chapterId = (int) ($question->chapter_id ?? 0);
        $profiles = collect(self::quizOutcomeProfiles()[$chapterId] ?? []);
        $storedCode = Str::upper(trim((string) ($question->learning_objective_code ?? '')));
        $storedTitle = Str::lower(trim((string) ($question->learning_objective_title ?? '')));

        $profile = $profiles->first(fn (array $profile) => Str::upper($profile['code']) === $storedCode)
            ?? $profiles->first(fn (array $profile) => Str::lower($profile['title']) === $storedTitle)
            ?? $profiles->first(fn (array $profile) => $profile['code'] === $fallback['code'])
            ?? $fallback;

        $outcome = [
            'code' => $profile['code'],
            'title' => $profile['title'],
            'label' => $profile['code'] . ' - ' . $profile['title'],
            'material' => $profile['material'] ?? $profile['title'],
            'chapter_id' => $chapterId,
        ];

        $outcome['key'] = Str::lower($outcome['chapter_id'] . '|' . $outcome['code'] . '|' . $outcome['title']);

        return $outcome;
    }

    private static function inferQuizOutcome(object|null $question): array
    {
        $text = Str::lower(strip_tags((string) ($question->question_text ?? '')));
        $chapterId = (int) ($question->chapter_id ?? 0);

        $profilesByChapter = self::quizOutcomeProfiles();

        $profiles = $profilesByChapter[$chapterId] ?? collect($profilesByChapter)
            ->flatMap(fn (array $profiles) => $profiles)
            ->values()
            ->all();

        $bestProfile = null;
        $bestScore = 0;

        foreach ($profiles as $profile) {
            $score = 0;

            foreach ($profile['keywords'] as $keyword) {
                if (str_contains($text, $keyword)) {
                    $score += max(1, min(4, (int) floor(strlen($keyword) / 4)));
                }
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestProfile = $profile;
            }
        }

        if ($bestProfile) {
            return [
                'code' => $bestProfile['code'],
                'title' => $bestProfile['title'],
                'material' => $bestProfile['material'],
            ];
        }

        return match ($chapterId) {
            1 => [
                'code' => 'TP1',
                'title' => 'Menjelaskan fungsi HTML dan CSS dalam halaman web.',
                'material' => 'Menjelaskan fungsi HTML dan CSS dalam halaman web.',
            ],
            2 => [
                'code' => 'TP1',
                'title' => 'Memahami konsep layout dalam penyusunan elemen halaman web.',
                'material' => 'Memahami konsep layout dalam penyusunan elemen halaman web.',
            ],
            3 => [
                'code' => 'TP1',
                'title' => 'Memahami fungsi styling dalam memperjelas tampilan web.',
                'material' => 'Memahami fungsi styling dalam memperjelas tampilan web.',
            ],
            99 => [
                'code' => 'TP1',
                'title' => 'Rangkuman Bab 1.',
                'material' => 'Rangkuman Bab 1.',
            ],
            default => [
                'code' => 'TP Umum',
                'title' => 'Tujuan pembelajaran umum.',
                'material' => 'Tujuan pembelajaran umum.',
            ],
        };
    }

    private static function judgement(float $percent): array
    {
        if ($percent >= 85) {
            return [
                'status' => 'Sangat Kuat',
                'tone' => 'emerald',
                'decision' => 'Lanjut pengayaan',
            ];
        }

        if ($percent >= 70) {
            return [
                'status' => 'Cukup',
                'tone' => 'cyan',
                'decision' => 'Penguatan materi singkat',
            ];
        }

        if ($percent >= 50) {
            return [
                'status' => 'Belum Stabil',
                'tone' => 'amber',
                'decision' => 'Kembali ke materi terkait',
            ];
        }

        return [
            'status' => 'Belum Mencukupi',
            'tone' => 'red',
            'decision' => 'Wajib kembali ke materi terkait',
        ];
    }

    private static function answerHasResponse(object|null $answer): bool
    {
        if (!$answer) {
            return false;
        }

        return !empty($answer->quiz_option_id);
    }

    private static function quizOutcomeProfiles(): array
    {
        return [
            1 => [
                ['code' => 'TP1', 'title' => 'Menjelaskan fungsi HTML dan CSS dalam halaman web.', 'material' => 'Menjelaskan fungsi HTML dan CSS dalam halaman web.', 'keywords' => ['html', 'tag', 'elemen', 'selector', 'style', 'struktur', 'body', 'head', 'href']],
                ['code' => 'TP2', 'title' => 'Memahami konsep dasar Tailwind CSS.', 'material' => 'Memahami konsep dasar Tailwind CSS.', 'keywords' => ['tailwind', 'utility', 'utility class', 'class']],
                ['code' => 'TP3', 'title' => 'Menerapkan Tailwind CSS pada HTML melalui CDN.', 'material' => 'Menerapkan Tailwind CSS pada HTML melalui CDN.', 'keywords' => ['cdn', 'script', 'link cdn', 'browser']],
                ['code' => 'TP4', 'title' => 'Melakukan instalasi dan konfigurasi Tailwind CSS.', 'material' => 'Melakukan instalasi dan konfigurasi Tailwind CSS.', 'keywords' => ['install', 'instalasi', 'konfigurasi', 'config', 'build', 'input.css', 'output.css', 'package', 'npm', 'npx']],
            ],
            2 => [
                ['code' => 'TP1', 'title' => 'Memahami konsep layout dalam penyusunan elemen halaman web.', 'material' => 'Memahami konsep layout dalam penyusunan elemen halaman web.', 'keywords' => ['layout', 'susunan', 'elemen halaman', 'container', 'spacing', 'margin', 'padding', 'width', 'height', 'gap']],
                ['code' => 'TP2', 'title' => 'Menerapkan class flex dan grid untuk mengatur layout.', 'material' => 'Menerapkan class flex dan grid untuk mengatur layout.', 'keywords' => ['flex', 'justify', 'align', 'items-', 'grid', 'cols', 'row', 'col-span']],
                ['code' => 'TP3', 'title' => 'Mengatur layout responsif sederhana menggunakan breakpoint.', 'material' => 'Mengatur layout responsif sederhana menggunakan breakpoint.', 'keywords' => ['responsive', 'responsif', 'breakpoint', 'mobile', 'tablet', 'desktop', 'md:', 'lg:']],
            ],
            3 => [
                ['code' => 'TP1', 'title' => 'Memahami fungsi styling dalam memperjelas tampilan web.', 'material' => 'Memahami fungsi styling dalam memperjelas tampilan web.', 'keywords' => ['styling', 'tampilan', 'memperjelas', 'hierarki', 'visual']],
                ['code' => 'TP2', 'title' => 'Menerapkan class Tailwind CSS untuk mengatur tampilan elemen web.', 'material' => 'Menerapkan class Tailwind CSS untuk mengatur tampilan elemen web.', 'keywords' => ['font', 'text-', 'color', 'warna', 'background', 'bg-', 'border', 'radius', 'rounded', 'ring', 'shadow', 'effect', 'efek', 'filter', 'component', 'komponen', 'card', 'button', 'tombol', 'form']],
            ],
            99 => [
                ['code' => 'TP1', 'title' => 'Rangkuman Bab 1.', 'material' => 'Rangkuman Bab 1.', 'keywords' => ['html', 'css', 'tailwind', 'utility', 'class', 'cdn', 'install', 'instalasi', 'konfigurasi']],
                ['code' => 'TP2', 'title' => 'Rangkuman Bab 2.', 'material' => 'Rangkuman Bab 2.', 'keywords' => ['layout', 'spacing', 'margin', 'padding', 'flex', 'justify', 'align', 'grid', 'cols', 'responsive', 'breakpoint']],
                ['code' => 'TP3', 'title' => 'Rangkuman Bab 3.', 'material' => 'Rangkuman Bab 3.', 'keywords' => ['styling', 'tipografi', 'font', 'color', 'warna', 'background', 'border', 'ring', 'shadow', 'effect', 'efek']],
            ],
        ];
    }

    private static function displayOutcomeCode(array $row): string
    {
        $chapterId = self::singleChapterId($row);
        $objectiveNumber = self::objectiveNumber($row);

        if ($chapterId === 99) {
            return 'Evaluasi Akhir - TP' . $objectiveNumber;
        }

        if ($chapterId > 0) {
            return 'Bab ' . $chapterId . ' - TP' . $objectiveNumber;
        }

        return 'TP' . $objectiveNumber;
    }

    private static function singleChapterId(array $row): int
    {
        if (isset($row['chapter_id']) && (int) $row['chapter_id'] > 0) {
            return (int) $row['chapter_id'];
        }

        $chapters = collect($row['chapters'] ?? [])->filter(fn ($chapter) => (int) $chapter > 0)->unique()->values();

        return $chapters->count() === 1 ? (int) $chapters->first() : 0;
    }

    private static function objectiveNumber(array $row): int
    {
        $code = (string) ($row['code'] ?? '');

        if (preg_match('/(\d+)/', $code, $matches)) {
            return max(1, (int) $matches[1]);
        }

        return max(1, ((int) ($row['order'] ?? 0)) + 1);
    }

    private static function learningDescription(array $row): string
    {
        return ($row['display_code'] ?? $row['code'] ?? 'TP') . ': ' . ($row['title'] ?? 'TP terkait');
    }

    private static function quizMasteryStatement(array $row): string
    {
        $base = ($row['display_code'] ?? $row['code'] ?? 'TP') . ': benar '
            . ($row['correct_count'] ?? 0) . '/' . ($row['total_questions'] ?? 0)
            . ' soal (' . ($row['mastery_percent'] ?? 0) . '%).';

        if (($row['mastery_percent'] ?? 0) >= 70) {
            return $base . ' Capaian sudah cukup.';
        }

        return $base . ' Perlu penguatan.';
    }

    private static function quizMaterialDirection(array $row): string
    {
        if ($row['mastery_percent'] >= 85) {
            return 'Pengayaan: ' . $row['title'];
        }

        if ($row['mastery_percent'] >= 70) {
            return 'Penguatan singkat: ' . $row['title'];
        }

        return 'Pelajari ulang: ' . $row['title'];
    }

    private static function labMasteryStatement(array $row): string
    {
        $base = ($row['display_code'] ?? $row['code'] ?? 'TP') . ' memuat praktik ' . Str::lower($row['title'] ?? 'tujuan praktik')
            . '. Siswa menyelesaikan ' . ($row['completed_steps'] ?? 0)
            . ' dari ' . ($row['total_steps'] ?? 0)
            . ' langkah (' . ($row['mastery_percent'] ?? 0) . '%).';

        if (($row['mastery_percent'] ?? 0) >= 70) {
            return $base . ' Capaian praktik sudah mencukupi, dengan penguatan pada langkah yang belum tervalidasi.';
        }

        return $base . ' Capaian praktik belum mencukupi, sehingga siswa perlu kembali mengerjakan materi praktik terkait.';
    }

    private static function labMaterialDirection(array $row): string
    {
        if ($row['mastery_percent'] >= 85) {
            return 'Arahkan ke tantangan pengayaan pada ' . $row['material'] . ', misalnya responsivitas atau konsistensi spacing.';
        }

        if ($row['mastery_percent'] >= 70) {
            return 'Arahkan siswa merapikan ulang ' . $row['material'] . ' dan mengecek langkah yang belum tervalidasi.';
        }

        return 'Arahkan siswa kembali ke praktik ' . $row['material'] . ' mulai dari instruksi langkah yang belum selesai.';
    }

    private static function aggregateMasteryStatement(array $row): string
    {
        $base = ($row['display_code'] ?? $row['code'] ?? 'TP') . ': '
            . ($row['correct_count'] ?? 0) . ' benar dari '
            . ($row['total_answers'] ?? 0) . ' jawaban (' . ($row['mastery_percent'] ?? 0) . '%).';

        if (($row['mastery_percent'] ?? 0) >= 70) {
            return $base . ' Capaian kelas cukup.';
        }

        return $base . ' Perlu penguatan kelas.';
    }

    private static function aggregateMaterialDirection(array $row): string
    {
        if ($row['mastery_percent'] >= 85) {
            return 'Siapkan pengayaan: ' . $row['title'];
        }

        if ($row['mastery_percent'] >= 70) {
            return 'Bahas soal yang sering salah pada TP ini.';
        }

        return 'Jadwalkan penguatan: ' . $row['title'];
    }

    private static function overallDecision(Collection $outcomes): string
    {
        if ($outcomes->isEmpty()) {
            return 'Belum ada data analitik.';
        }

        $lowest = (float) ($outcomes->min('mastery_percent') ?? 0);

        if ($lowest < 50) {
            return 'Prioritaskan kembali ke materi pada TP terlemah sebelum lanjut.';
        }

        if ($lowest < 70) {
            return 'Arahkan penguatan materi pada TP yang belum mencapai 70%.';
        }

        if ($lowest < 85) {
            return 'Pembelajaran dapat lanjut dengan penguatan ringan.';
        }

        return 'Pembelajaran dapat lanjut ke pengayaan.';
    }

    private static function quizSummaryText(Collection $outcomes, object|null $attempt): string
    {
        if ($outcomes->isEmpty()) {
            return 'Belum ada soal yang dapat dianalisis.';
        }

        $weakest = $outcomes->sortBy('mastery_percent')->first();
        $score = $attempt ? (int) ($attempt->score ?? 0) : null;
        $scoreText = $score !== null ? 'Skor akhir ' . $score . '. ' : '';

        return $scoreText . $weakest['label'] . ' menjadi TP yang perlu diarahkan ulang (' . $weakest['mastery_percent'] . '%). ' . $weakest['material_direction'];
    }

    private static function labSummaryText(Collection $outcomes, array $metrics): string
    {
        if ($outcomes->isEmpty()) {
            return 'Belum ada langkah praktik yang dapat dianalisis.';
        }

        $weakest = $outcomes->sortBy('mastery_percent')->first();

        return $weakest['label'] . ' menjadi TP praktik yang perlu diarahkan ulang (' . $weakest['mastery_percent'] . '%). ' . $weakest['material_direction'];
    }

    private static function aggregateSummaryText(Collection $outcomes): string
    {
        if ($outcomes->isEmpty()) {
            return 'Belum ada soal untuk analitik tujuan pembelajaran.';
        }

        $weakest = $outcomes->sortBy('mastery_percent')->first();

        return $weakest['label'] . ' menjadi TP kelas yang perlu diarahkan ulang dengan capaian ' . $weakest['mastery_percent'] . '%.';
    }
}
