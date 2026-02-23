<?php

use Illuminate\Support\Facades\Route;

// --- CONTROLLERS ---
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ActivityProgressController;
use App\Http\Controllers\SandboxController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\LabController; 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ClassManagementController;
use App\Http\Middleware\EnsureHasActiveClass; // <-- IMPORT MIDDLEWARE BARU

// ====================================================
// 1. PUBLIC ROUTES
// ====================================================
Route::get('/', fn () => view('landing'))->name('landing');

// Authentication
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot Password
Route::get('/forgot-password', [AuthController::class, 'forgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'resetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// ====================================================
// 2. GENERAL AUTHENTICATED ROUTES (Tanpa Syarat Kelas)
// Area ini bebas diakses meski siswa belum punya kelas.
// ====================================================
Route::middleware(['auth'])->group(function () {
    
    // Utilities & Profil
    Route::get('/cheatsheet', function () { return view('cheatsheet.index'); })->name('cheatsheet.index');
    Route::get('/gallery', function () { return view('components.gallery'); })->name('gallery.index');
    
    // Dashboard & Sistem Token
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/student/join-class', [DashboardController::class, 'joinClass'])->name('student.join_class');
    Route::get('/api/dashboard/progress', [DashboardController::class, 'progress'])->name('api.dashboard.progress');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

});

// ====================================================
// 3. RESTRICTED LEARNING ROUTES (Wajib Punya Kelas)
// Siswa yang belum join kelas akan dilempar ke Dashboard.
// ====================================================
Route::middleware(['auth', EnsureHasActiveClass::class])->group(function () {
    
    // Peta Konsep
    Route::get('/learning-path', [CourseController::class, 'showSyllabus'])->name('courses.curriculum');
    
    // Progress Utilities
    Route::post('/activity/complete', [ActivityProgressController::class, 'store'])->name('activity.complete');
    Route::post('/lesson/complete', [CourseController::class, 'completeLesson'])->name('lesson.complete');
    
    // Sandbox (Latihan Bebas)
    Route::get('/sandbox', [SandboxController::class, 'index'])->name('sandbox');
    Route::post('/sandbox/complete', [SandboxController::class, 'complete'])->name('sandbox.complete');

    // --- MATERI COURSES ---
    // BAB 1
    Route::get('/courses/html-css', [CourseController::class, 'tailwind'])->name('courses.htmldancss');
    Route::get('/courses/tailwind-basic', [CourseController::class, 'subbabTailwindCss'])->name('courses.tailwindcss');
    Route::get('/courses/background-story', [CourseController::class, 'background'])->name('courses.latarbelakang');
    Route::get('/courses/implementation', [CourseController::class, 'implementation'])->name('courses.implementation');
    Route::get('/courses/advantages', [CourseController::class, 'advantages'])->name('courses.advantages');
    Route::get('/courses/installation', [CourseController::class, 'installation'])->name('courses.installation');

    // BAB 2
    Route::get('/courses/flexbox', [CourseController::class, 'flexbox'])->name('courses.flexbox');
    Route::get('/courses/grid', [CourseController::class, 'grid'])->name('courses.grid');
    Route::get('/courses/layout-management', [CourseController::class, 'layoutMgmt'])->name('courses.layout-mgmt');

    // BAB 3
    Route::get('/courses/typography', [CourseController::class, 'typography'])->name('courses.typography');
    Route::get('/courses/backgrounds', [CourseController::class, 'backgrounds'])->name('courses.backgrounds');
    Route::get('/courses/borders', [CourseController::class, 'borders'])->name('courses.borders');
    Route::get('/courses/effects', [CourseController::class, 'effects'])->name('courses.effects');

    // --- HANDS-ON LAB SYSTEM ---
    Route::get('/labs/start/{id}', [LabController::class, 'start'])->name('lab.start');
    Route::get('/labs/workspace/{id}', [LabController::class, 'workspace'])->name('lab.workspace');
    Route::post('/labs/session/{id}/check', [LabController::class, 'check'])->name('lab.check');
    Route::post('/labs/session/{id}/end', [LabController::class, 'end'])->name('lab.end');
});

// ====================================================
// 4. RESTRICTED QUIZ SYSTEM (Wajib Kelas + Verified)
// ====================================================
Route::middleware(['auth', 'verified', EnsureHasActiveClass::class])->group(function () {
    Route::get('/quiz/intro/{chapterId}', [QuizController::class, 'intro'])->name('quiz.intro');
    Route::post('/quiz/start', [QuizController::class, 'startSession'])->name('quiz.startSession');
    Route::get('/quiz/attempt/{chapterId}', [QuizController::class, 'show'])->name('quiz.show');
    Route::post('/quiz/save-progress', [QuizController::class, 'saveProgress'])->name('quiz.save-progress');
    Route::post('/quiz/submit', [QuizController::class, 'submit'])->name('quiz.submit');
});

// ====================================================
// 5. ADMIN ROUTES
// ====================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // 1. Dashboard Utama
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // User Actions
    Route::post('/dashboard/user/store', [AdminDashboardController::class, 'storeUser'])->name('user.store');
    Route::post('/dashboard/user/import', [AdminDashboardController::class, 'importUsers'])->name('user.import');
    Route::get('/dashboard/user/export-csv', [AdminDashboardController::class, 'exportUsers'])->name('user.export.csv');
    Route::get('/dashboard/user/export-pdf', [AdminDashboardController::class, 'exportPdf'])->name('user.export.pdf');
    
    Route::get('/student/{id}', [AdminDashboardController::class, 'studentDetail'])->name('student.detail');
    Route::put('/student/{id}/update', [AdminDashboardController::class, 'updateStudent'])->name('student.update');
    Route::get('/student/{id}/export/csv', [AdminDashboardController::class, 'exportStudentCsv'])->name('student.export.csv');
    Route::get('/student/{id}/export/pdf', [AdminDashboardController::class, 'exportStudentPdf'])->name('student.export.pdf');
    
    Route::post('/users/{id}/update', [AdminDashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminDashboardController::class, 'deleteUser'])->name('users.delete');

    // 2. Analytics & Quiz Management
    Route::get('/analytics/questions', [AdminDashboardController::class, 'questionAnalytics'])->name('analytics.questions');
    Route::get('/questions/create', [AdminDashboardController::class, 'createQuestion'])->name('questions.create');
    Route::post('/questions/store', [AdminDashboardController::class, 'storeQuestion'])->name('questions.store');
    Route::post('/questions/update/{id}', [AdminDashboardController::class, 'updateQuestion'])->name('questions.update');
    Route::delete('/questions/delete/{id}', [AdminDashboardController::class, 'destroyQuestion'])->name('questions.destroy');

    // 3. Lab Configuration & Analytics
    Route::get('/labs', [LabController::class, 'index'])->name('labs.index');
    Route::post('/labs', [LabController::class, 'store'])->name('labs.store');
    Route::put('/labs/{id}', [LabController::class, 'update'])->name('labs.update');
    Route::delete('/labs/{id}', [LabController::class, 'destroy'])->name('labs.destroy');
    Route::patch('/labs/{id}/toggle', [LabController::class, 'toggleStatus'])->name('labs.toggle');

    Route::get('/labs/{id}/tasks', [LabController::class, 'getTasks'])->name('labs.tasks.index');
    Route::post('/labs/tasks', [LabController::class, 'storeTask'])->name('labs.tasks.store');
    Route::put('/labs/tasks/{id}', [LabController::class, 'updateTask'])->name('labs.tasks.update');
    Route::delete('/labs/tasks/{id}', [LabController::class, 'destroyTask'])->name('labs.tasks.destroy');
    
    Route::get('/analytics/lab/{labId?}', [LabController::class, 'analytics'])->name('lab.analytics');
    Route::get('/analytics/student/{userId}', [LabController::class, 'studentAnalytics'])->name('student.analytics');

    // 4. CLASS & TOKEN MANAGEMENT
    Route::get('/classes', [ClassManagementController::class, 'index'])->name('classes.index');
    Route::post('/classes', [ClassManagementController::class, 'store'])->name('classes.store');
    Route::put('/classes/{id}', [ClassManagementController::class, 'update'])->name('classes.update');
    Route::delete('/classes/{id}', [ClassManagementController::class, 'destroy'])->name('classes.destroy');
    Route::post('/classes/{id}/token', [ClassManagementController::class, 'regenerateToken'])->name('classes.token');
});