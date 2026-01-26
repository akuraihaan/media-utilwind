<?php
use App\Http\Controllers\CourseProgressController;
use App\Http\Controllers\DashboardController;

Route::middleware('auth:sanctum')->group(function () {

  Route::middleware('auth:sanctum')->group(function () {
    Route::post('/course/progress/save', [CourseProgressController::class, 'save']);
    Route::get('/course/progress/get', [CourseProgressController::class, 'get']);
});

});
Route::get('/course/lesson/{id}', function ($id) {
    return \App\Models\CourseLesson::findOrFail($id);
});
// routes/api.php
Route::middleware('auth:sanctum')->get('/admin/monitoring', function () {
    return response()->json([
        'total_users' => \App\Models\User::where('role','student')->count(),
        'total_quiz_attempts' => \App\Models\QuizAttempt::count(),
        'avg_score' => round(\App\Models\QuizAttempt::avg('score')),
        'latest_attempts' => \App\Models\QuizAttempt::latest()->take(5)->with('user')->get()
    ]);
});

