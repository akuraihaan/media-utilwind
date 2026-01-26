<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCourseProgress extends Model
{
  protected $fillable=[
    'user_id','course_slug','completed_lessons',
    'progress_percent','current_level'
  ];
  protected $casts=['completed_lessons'=>'array'];
}