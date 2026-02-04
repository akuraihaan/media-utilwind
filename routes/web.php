<?php

use Illuminate\Support\Facades\Route;

// --- CONTROLLERS ---
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ActivityProgressController;
use App\Http\Controllers\SandboxController;
use App\Http\Controllers\QuizController;

// --- ADMIN CONTROLLERS (BARU) ---
use App\Http\Controllers\Admin\AdminDashboardController;

// ====================================================
// 1. PUBLIC ROUTES
// ====================================================
Route::get('/', fn () => view('landing'))->name('landing');

// --- AUTHENTICATION ---
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- FORGOT PASSWORD ---
Route::get('/forgot-password', [AuthController::class, 'forgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'resetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// ====================================================
// 2. AUTHENTICATED ROUTES (USER / STUDENT)
// ====================================================
Route::middleware(['auth'])->group(function () {

    // DASHBOARD SISWA & API
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/dashboard/progress', [DashboardController::class, 'progress'])->name('api.dashboard.progress');
    Route::post('/activity/complete', [ActivityProgressController::class, 'store'])->name('activity.complete');

    // SANDBOX (CODING PLAYGROUND)
    Route::get('/sandbox', [SandboxController::class, 'index'])->name('sandbox');
    Route::post('/sandbox/complete', [SandboxController::class, 'complete'])->name('sandbox.complete');

    // AJAX: SIMPAN PROGRESS LESSON
    Route::post('/lesson/complete', [CourseController::class, 'completeLesson'])->name('lesson.complete');

    // ----------------------------------------------------
    // MATERI PEMBELAJARAN (COURSE ROUTES)
    // ----------------------------------------------------
    
    // BAB 1: PENDAHULUAN
    Route::get('/courses/htmldancss', [CourseController::class, 'tailwind'])->name('courses.htmldancss');
    Route::get('/courses/tailwindcss', [CourseController::class, 'subbabTailwindCss'])->name('courses.tailwindcss');
    Route::get('/courses/latarbelakang', [CourseController::class, 'background'])->name('courses.latarbelakang');
    Route::get('/courses/implementasi', [CourseController::class, 'implementation'])->name('courses.implementation');
    Route::get('/courses/keunggulan', [CourseController::class, 'advantages'])->name('courses.advantages');
    Route::get('/courses/instalasi', [CourseController::class, 'installation'])->name('courses.installation');

    // BAB 2: LAYOUTING
    Route::get('/courses/flexbox', [CourseController::class, 'flexbox'])->name('courses.flexbox');
    Route::get('/courses/grid', [CourseController::class, 'grid'])->name('courses.grid');
    Route::get('/courses/layout-management', [CourseController::class, 'layoutMgmt'])->name('courses.layout-mgmt');

    // BAB 3: STYLING
    Route::get('/courses/typography', [CourseController::class, 'typography'])->name('courses.typography');
    Route::get('/courses/background', [CourseController::class, 'backgrounds'])->name('courses.backgrounds');
    Route::get('/courses/borders', [CourseController::class, 'borders'])->name('courses.borders');
    Route::get('/courses/effects', [CourseController::class, 'effects'])->name('courses.effects');

});

// ====================================================
// 3. QUIZ SYSTEM (VERIFIED USERS)
// ====================================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/quiz/intro/{chapterId}', [QuizController::class, 'intro'])->name('quiz.intro');
    Route::post('/quiz/start', [QuizController::class, 'startSession'])->name('quiz.startSession');
    Route::get('/quiz/attempt/{chapterId}', [QuizController::class, 'show'])->name('quiz.show');
    Route::post('/quiz/submit', [QuizController::class, 'submit'])->name('quiz.submit');
});

// ====================================================
// 4. ADMIN DASHBOARD & ANALYTICS (ULTIMATE)
// ====================================================
// Menggunakan Controller AdminDashboardController yang baru
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // PERUBAHAN: Menggunakan '/' agar bisa diakses via /admin langsung
    // Nama route tetap 'admin.dashboard' agar tidak merusak link di blade
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD User
    Route::post('/users/{id}/update', [AdminDashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminDashboardController::class, 'deleteUser'])->name('users.delete');

    // Insight
    Route::get('/student/{id}', [AdminDashboardController::class, 'studentDetail'])->name('student.detail');
    Route::get('/analytics/questions', [AdminDashboardController::class, 'questionAnalytics'])->name('analytics.questions');
});