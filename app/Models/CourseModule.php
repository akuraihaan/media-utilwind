<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseModule extends Model
{
      protected $fillable = ['course_id','title','order'];
    public function lessons() {
        return $this->hasMany(CourseLesson::class);
    }
}
