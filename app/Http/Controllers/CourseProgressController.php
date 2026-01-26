<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseProgress;
use App\Models\LearningLog;

class CourseProgressController extends Controller
{
    public function save(Request $request)
{
    $request->validate([
        'course_id' => 'required|string',
        'completed_lessons' => 'required|array',
        'total_lessons' => 'required|integer'
    ]);

    $progressValue = round(
        (count($request->completed_lessons) / $request->total_lessons) * 100
    );

    $progress = CourseProgress::updateOrCreate(
        [
            'user_id' => auth()->id(),
            'course_id' => $request->course_id
        ],
        [
            'completed_lessons' => $request->completed_lessons,
            'total_lessons' => $request->total_lessons,
            'current_lesson_index' => count($request->completed_lessons) - 1,
        ]
    );

    LearningLog::create([
        'user_id' => auth()->id(),
        'title' => 'Belajar HTML & CSS',
        'description' => 'Progress: ' . $progressValue . '%',
        'logged_at' => now()
    ]);

    return response()->json([
        'status' => 'success',
        'progress' => round(
            (count($progress->completed_lessons) / $progress->total_lessons) * 100
        )
    ]);
}


    public function get(Request $request)
    {
        $progress = CourseProgress::where('user_id', auth()->id())
            ->where('course_id', $request->course_id)
            ->first();

        return response()->json($progress);
    }
}
