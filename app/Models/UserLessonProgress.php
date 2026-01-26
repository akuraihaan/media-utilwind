<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLessonProgress extends Model
{
     protected $table = 'user_lesson_progress';

     protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'course_lesson_id',
        'completed'
    ];
}
