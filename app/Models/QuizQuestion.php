<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $table = 'quiz_questions';
    protected $guarded = ['id'];

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