<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class QuizQuestion extends Model
{
    protected $table = 'quiz_questions';
    protected $guarded = ['id'];

    public function getMediaUrlAttribute($value): ?string
    {
        if (blank($value)) {
            $mediaPath = $this->attributes['media_path'] ?? null;

            return blank($mediaPath) ? null : asset('uploads/' . ltrim(str_replace('\\', '/', $mediaPath), '/'));
        }

        $value = trim((string) $value);
        $localPath = $this->localMediaPathFromUrl($value);

        if ($localPath) {
            return asset('uploads/' . $localPath);
        }

        if (Str::startsWith($value, ['http://', 'https://', '//', 'data:', 'blob:'])) {
            return $value;
        }

        return Str::startsWith($value, '/') ? asset(ltrim($value, '/')) : $value;
    }

    private function localMediaPathFromUrl(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH) ?: $url;
        $path = trim(str_replace('\\', '/', rawurldecode($path)));

        foreach (['/uploads/quiz-media/', 'uploads/quiz-media/'] as $needle) {
            if (Str::contains($path, $needle)) {
                return 'quiz-media/' . ltrim(Str::after($path, $needle), '/');
            }
        }

        foreach (['/storage/quiz-media/', 'storage/quiz-media/'] as $needle) {
            if (Str::contains($path, $needle)) {
                return 'quiz-media/' . ltrim(Str::after($path, $needle), '/');
            }
        }

        return Str::startsWith($path, 'quiz-media/') ? $path : null;
    }

    // Relasi ke Opsi Jawaban (Pilihan A, B, C, D)
    public function options()
    {
        return $this->hasMany(QuizOption::class, 'quiz_question_id');
    }
    
    // [PENTING] Relasi ke Jawaban Siswa (Ini yang menyebabkan error sebelumnya)
    // Digunakan untuk menghitung statistik berapa orang yang menjawab benar/salah
    public function answers()
    {
        return $this->hasMany(QuizAttemptAnswer::class, 'quiz_question_id');
    }
}
