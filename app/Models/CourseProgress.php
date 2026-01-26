<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseProgress extends Model
{
     protected $fillable = [
        'user_id',
        'course_id',
        'completed_lessons',
        'total_lessons',
        'current_module_index',
        'current_lesson_index'
    ];

protected $casts = [
    'completed_lessons' => 'array'
];

}
