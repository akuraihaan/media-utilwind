<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttemptAnswer extends Model
{
    protected $guarded = [];
protected $fillable = [
        'quiz_attempt_id',
        'quiz_question_id',
        'quiz_option_id',
        'is_correct',
        'is_flagged', // <--- Tambahkan ini
    ];
    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id'); // Pastikan foreign key benar
    }

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }
}