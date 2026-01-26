<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $guarded = [];

    // Soal punya banyak opsi jawaban
    public function options()
    {
        return $this->hasMany(QuizOption::class);
    }
}