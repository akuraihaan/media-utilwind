<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivityProgress extends Model
{
protected $fillable = [
        'user_id',
        'course_activity_id',
        'completed',
        'score',
        'completed_at'
    ];

    public function activity()
    {
        return $this->belongsTo(CourseActivity::class, 'course_activity_id');
    }
}
