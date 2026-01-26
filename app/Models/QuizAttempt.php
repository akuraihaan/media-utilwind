<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    // Jika nama tabel di database bukan 'quiz_attempts' (jamak), definisikan disini:
    protected $table = 'quiz_attempts'; 

    protected $guarded = [];

    protected $casts = [
        'completed_at' => 'datetime', // Agar fungsi diffForHumans() bisa jalan
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}