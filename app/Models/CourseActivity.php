<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseActivity extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'type'
    ];

    public function progress()
    {
        return $this->hasMany(UserActivityProgress::class);
    }
}
