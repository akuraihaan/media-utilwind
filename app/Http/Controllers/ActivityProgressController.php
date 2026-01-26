<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserActivityProgress;
use App\Models\CourseActivity;

class ActivityProgressController extends Controller
{
    /**
     * SIMPAN PROGRESS ACTIVITY (AJAX)
     */
      public function store(Request $request)
    {
        $data = $request->validate([
            'activity_id' => 'required|exists:course_activities,id',
            'score' => 'required|integer|min:0|max:100',
        ]);

        $progress = UserActivityProgress::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'course_activity_id' => $data['activity_id'],
            ],
            [
                'completed' => true,
                'score' => $data['score'],
            ]
        );

        return response()->json([
            'success' => true,
            'activity_id' => $data['activity_id'],
        ]);
    }
}
