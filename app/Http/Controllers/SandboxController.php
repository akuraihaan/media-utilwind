<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserActivityProgress;

class SandboxController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Activity ID khusus sandbox (misal: 99)
        $sandboxCompleted = UserActivityProgress::where('user_id', $userId)
            ->where('course_activity_id', 99)
            ->where('completed', true)
            ->exists();

        return view('sandbox.index', [
            'sandboxCompleted' => $sandboxCompleted
        ]);
    }

    public function complete(Request $request)
    {
        UserActivityProgress::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'course_activity_id' => 99,
            ],
            [
                'completed' => true,
                'score'     => 100,
            ]
        );

        return response()->json(['status' => 'ok']);
    }
}
