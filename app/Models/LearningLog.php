<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningLog extends Model
{
    protected $table = 'learning_logs';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'logged_at'
    ];
}
