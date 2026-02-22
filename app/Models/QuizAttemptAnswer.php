<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttemptAnswer extends Model
{
    use HasFactory;

    protected $table = 'quiz_attempt_answers';

    // DAFTARKAN SEMUA KOLOM INI AGAR BISA DIISI
    protected $fillable = [
        'quiz_attempt_id',
        'quiz_question_id',
        'quiz_option_id',  // Pastikan ejaannya sama persis dengan di database
        'is_flagged',
        'is_correct'       // Tambahkan ini agar analisis jawaban benar/salah tersimpan
    ];

    // Relasi (Opsional tapi berguna nanti)
    public function attempt() {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }
}