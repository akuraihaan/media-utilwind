<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{

protected $guarded = ['id'];
    protected $fillable = [
        'course_module_id',
        'title',
        'content',
        'order',
        'estimated_minutes'
    ];

    public function module()
    {
        return $this->belongsTo(CourseModule::class);
    }

    public function progress()
    {
        return $this->hasMany(UserLessonProgress::class);
    }
}
