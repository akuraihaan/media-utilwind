<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLessonProgress extends Model
{
    use HasFactory;

    protected $table = 'user_lesson_progress'; // Pastikan nama tabel benar
    protected $guarded = ['id'];

    /**
     * Relasi ke CourseLesson
     * Ini yang dicari oleh ->with('lesson') di Controller
     */
    public function lesson()
    {
        // Parameter ke-2 ('course_lesson_id') adalah nama kolom foreign key di tabel user_lesson_progress
        return $this->belongsTo(CourseLesson::class, 'course_lesson_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}