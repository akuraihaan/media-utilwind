<?php

namespace App\Support;

class LabResultAnalyzer
{
    public static function analyze(object $history): array
    {
        $lab = $history->lab;
        $steps = $lab?->steps ?? collect();
        $sourceCode = (string) ($history->last_code_snapshot ?? $history->source_code ?? '');
        $completedStepIds = collect(json_decode($history->completed_steps ?? '[]', true) ?: [])
            ->map(fn ($id) => (int) $id)
            ->all();

        $reviewItems = $steps->values()->map(function ($step, $index) use ($completedStepIds, $sourceCode) {
            $failedRule = null;
            $rulesSatisfied = self::validateStepRules($step->validation_rules, $sourceCode, $failedRule);
            $isCompleted = in_array((int) $step->id, $completedStepIds, true) || $rulesSatisfied;

            return [
                'number' => $index + 1,
                'step' => $step,
                'points' => (int) ($step->points ?? 0),
                'rules' => self::decodeValidationRules($step->validation_rules),
                'is_completed' => $isCompleted,
                'failed_rule' => $isCompleted ? null : $failedRule,
                'status' => $isCompleted ? 'Selesai' : 'Perlu diperbaiki',
            ];
        });

        $score = (int) ($history->final_score ?? 0);
        $passingGrade = (int) ($lab->passing_grade ?? 70);
        $completedCount = $reviewItems->where('is_completed', true)->count();
        $totalSteps = max(1, $reviewItems->count());
        $durationSeconds = max(0, (int) ($history->duration_seconds ?? 0));
        $codeMetrics = self::codeMetrics($sourceCode);

        $metrics = [
            'completed_steps' => $completedCount,
            'total_steps' => $totalSteps,
            'completion_percent' => round(($completedCount / $totalSteps) * 100),
            'earned_points' => $reviewItems->where('is_completed', true)->sum('points'),
            'total_points' => max(1, $reviewItems->sum('points')),
            'duration_seconds' => $durationSeconds,
            'duration_text' => gmdate($durationSeconds >= 3600 ? 'H:i:s' : 'i:s', $durationSeconds),
            'passing_grade' => $passingGrade,
        ];

        $isPassed = ($history->status === 'passed') || $score >= $passingGrade;
        $feedback = self::buildFeedback($score, $metrics, $passingGrade);
        $riskFlags = self::buildRiskFlags($sourceCode, $score, $passingGrade, $metrics, $codeMetrics, $reviewItems);
        $recommendations = self::buildRecommendations($score, $passingGrade, $metrics, $codeMetrics, $reviewItems);
        $outcomeAnalytics = LearningOutcomeAnalytics::forLabReview($reviewItems, $metrics);

        return [
            'history' => $history,
            'lab' => $lab,
            'source_code' => $sourceCode,
            'score' => $score,
            'is_passed' => $isPassed,
            'status_label' => self::statusLabel($history->status ?? null, $isPassed),
            'review_items' => $reviewItems,
            'metrics' => $metrics,
            'code_metrics' => $codeMetrics,
            'feedback' => $feedback,
            'risk_flags' => $riskFlags,
            'recommendations' => $recommendations,
            'outcome_analytics' => $outcomeAnalytics,
        ];
    }

    public static function validateStepRules($jsonRules, $code, &$failedRule = null): bool
    {
        if (empty($jsonRules)) {
            return true;
        }

        $rules = is_string($jsonRules) ? json_decode($jsonRules, true) : $jsonRules;
        if (!$rules || !is_array($rules)) {
            return true;
        }

        $code = (string) $code;
        preg_match_all('/class\s*=\s*["\']([^"\']*)["\']/i', $code, $matches);
        $allClassesFound = strtolower(implode(' ', $matches[1] ?? []));
        $cleanCode = preg_replace('/\s+/', '', $code);

        foreach ($rules as $rule) {
            $rule = strtolower((string) $rule);
            $isValid = false;

            if (str_starts_with($rule, '<')) {
                $tagName = str_replace(['<', '>', '/'], '', $rule);
                if (str_contains(strtolower($cleanCode), $rule) || str_contains(strtolower($cleanCode), "<$tagName")) {
                    $isValid = true;
                }
            } elseif (str_contains($allClassesFound, $rule)) {
                $isValid = true;
            }

            if (!$isValid) {
                $failedRule = $rule;
                return false;
            }
        }

        return true;
    }

    public static function decodeValidationRules($jsonRules): array
    {
        if (empty($jsonRules)) {
            return [];
        }

        $rules = is_string($jsonRules) ? json_decode($jsonRules, true) : $jsonRules;

        return is_array($rules) ? array_values($rules) : [];
    }

    private static function codeMetrics(string $sourceCode): array
    {
        preg_match_all('/class\s*=\s*["\']([^"\']*)["\']/i', $sourceCode, $classMatches);
        $classes = collect($classMatches[1] ?? [])
            ->flatMap(fn ($classValue) => preg_split('/\s+/', trim((string) $classValue)) ?: [])
            ->filter()
            ->values();

        preg_match_all('/<\s*([a-zA-Z][a-zA-Z0-9-]*)\b/', $sourceCode, $tagMatches);

        return [
            'line_count' => trim($sourceCode) === '' ? 0 : substr_count($sourceCode, "\n") + 1,
            'character_count' => strlen($sourceCode),
            'class_count' => $classes->count(),
            'unique_class_count' => $classes->unique()->count(),
            'html_tag_count' => count($tagMatches[1] ?? []),
            'has_script' => stripos($sourceCode, '<script') !== false,
            'has_inline_style' => preg_match('/\sstyle\s*=/i', $sourceCode) === 1,
        ];
    }

    private static function buildFeedback(int $score, array $metrics, int $passingGrade): array
    {
        if ($score >= 90) {
            $level = 'Sangat Baik';
            $message = 'Implementasi lab sudah sangat kuat. Struktur kode dan pemenuhan tugas menunjukkan pemahaman praktik yang matang.';
        } elseif ($score >= $passingGrade) {
            $level = 'Lulus';
            $message = 'Lab sudah memenuhi nilai minimal. Tetap tinjau tugas yang belum sempurna agar pola implementasi lebih stabil.';
        } else {
            $level = 'Perlu Penguatan';
            $message = 'Nilai lab belum mencapai batas kelulusan. Fokus ulang pada instruksi tugas, class yang diwajibkan, dan hubungan kode dengan pratinjau.';
        }

        $remainingSteps = max(0, ($metrics['total_steps'] ?? 0) - ($metrics['completed_steps'] ?? 0));

        if ($remainingSteps > 0) {
            $message .= ' Masih ada ' . $remainingSteps . ' tugas yang perlu dilengkapi atau diperbaiki.';
        }

        return compact('level', 'message');
    }

    private static function buildRiskFlags(
        string $sourceCode,
        int $score,
        int $passingGrade,
        array $metrics,
        array $codeMetrics,
        object $reviewItems
    ): array {
        $flags = [];

        if (trim($sourceCode) === '') {
            $flags[] = [
                'level' => 'tinggi',
                'title' => 'Snapshot kode kosong',
                'description' => 'Riwayat ini tidak menyimpan kode akhir, sehingga admin perlu memeriksa apakah proses submit berjalan lengkap.',
            ];
        }

        if ($score < $passingGrade) {
            $flags[] = [
                'level' => 'tinggi',
                'title' => 'Nilai belum mencapai batas kelulusan',
                'description' => 'Siswa membutuhkan tindak lanjut pada aturan validasi yang belum terpenuhi.',
            ];
        }

        if (($metrics['duration_seconds'] ?? 0) > 0 && ($metrics['duration_seconds'] ?? 0) < 60) {
            $flags[] = [
                'level' => 'sedang',
                'title' => 'Durasi pengerjaan sangat singkat',
                'description' => 'Cek apakah siswa hanya membuka ulang lab, memakai kode siap pakai, atau sudah memahami instruksi dengan sangat cepat.',
            ];
        }

        if (($codeMetrics['has_script'] ?? false) || ($codeMetrics['has_inline_style'] ?? false)) {
            $flags[] = [
                'level' => 'rendah',
                'title' => 'Ada script atau style inline',
                'description' => 'Bagian ini tidak selalu salah, tetapi dapat ditinjau bila lab menekankan penggunaan utility class Tailwind.',
            ];
        }

        $unfinished = $reviewItems->where('is_completed', false)->count();
        if ($unfinished > 0) {
            $flags[] = [
                'level' => 'sedang',
                'title' => $unfinished . ' tugas belum tervalidasi',
                'description' => 'Periksa aturan yang gagal agar umpan balik ke siswa lebih spesifik.',
            ];
        }

        return $flags ?: [[
            'level' => 'aman',
            'title' => 'Tidak ada catatan risiko utama',
            'description' => 'Hasil lab terlihat konsisten dengan aturan validasi dan metadata pengerjaan.',
        ]];
    }

    private static function buildRecommendations(
        int $score,
        int $passingGrade,
        array $metrics,
        array $codeMetrics,
        object $reviewItems
    ): array {
        $recommendations = [];
        $unfinishedRules = $reviewItems
            ->where('is_completed', false)
            ->pluck('failed_rule')
            ->filter()
            ->unique()
            ->values();

        if ($unfinishedRules->isNotEmpty()) {
            $recommendations[] = 'Bahas ulang aturan yang belum terpenuhi: ' . $unfinishedRules->implode(', ') . '.';
        }

        if ($score < $passingGrade) {
            $recommendations[] = 'Minta siswa memperbaiki tugas yang belum selesai sebelum melanjutkan ke bab berikutnya.';
        }

        if (($codeMetrics['unique_class_count'] ?? 0) < 3 && ($metrics['total_steps'] ?? 0) > 1) {
            $recommendations[] = 'Dorong siswa memakai variasi utility class yang lebih lengkap sesuai instruksi lab.';
        }

        if (($metrics['completion_percent'] ?? 0) >= 100 && $score >= $passingGrade) {
            $recommendations[] = 'Berikan penguatan berupa tantangan kecil, misalnya merapikan responsivitas atau konsistensi spacing.';
        }

        return $recommendations ?: [
            'Gunakan hasil ini sebagai bahan umpan balik singkat pada sesi praktikum berikutnya.',
        ];
    }

    private static function statusLabel(?string $status, bool $isPassed): string
    {
        return match ($status) {
            'passed' => 'Lulus',
            'failed' => 'Belum Lulus',
            'completed' => $isPassed ? 'Lulus' : 'Selesai',
            'active' => 'Masih Berjalan',
            default => $isPassed ? 'Lulus' : 'Tidak Diketahui',
        };
    }
}
