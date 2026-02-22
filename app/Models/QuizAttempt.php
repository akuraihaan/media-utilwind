<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $table = 'quiz_attempts';
    
    // Penting agar create([]) di controller berjalan
    protected $fillable = [
        'user_id',
        'chapter_id',
        'score',
        'time_spent_seconds',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime', // Agar bisa dimanipulasi Carbon
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAttemptAnswer::class, 'quiz_attempt_id');
    }
}