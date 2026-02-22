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
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ClassManagementController;


// ====================================================
// 1. PUBLIC ROUTES (JANGAN DIHAPUS - PENYEBAB ERROR)
// ====================================================
Route::get('/', fn () => view('landing'))->name('landing'); // <-- INI YANG HILANG

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
// 2. AUTHENTICATED ROUTES
// ====================================================
Route::middleware(['auth'])->group(function () {
Route::get('/cheatsheet', function () {
    return view('cheatsheet.index');
})->name('cheatsheet.index');

// routes/web.php

// Tambahkan ini (Bisa di luar middleware auth jika ingin publik, atau di dalam jika khusus member)
    Route::get('/gallery', function () { return view('components.gallery'); })->name('gallery.index');
    // --- DASHBOARD ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Rute untuk siswa bergabung ke kelas via Token
    Route::post('/student/join-class', [DashboardController::class, 'joinClass'])->name('student.join_class');
    Route::get('/api/dashboard/progress', [DashboardController::class, 'progress'])->name('api.dashboard.progress');
    // Route khusus untuk akses Peta Konsep
    Route::get('/learning-path', function () {
        return view('courses.curriculum');
    })->name('courses.curriculum');
    
    // --- UTILITIES ---
    Route::post('/activity/complete', [ActivityProgressController::class, 'store'])->name('activity.complete');
    Route::post('/lesson/complete', [CourseController::class, 'completeLesson'])->name('lesson.complete');
    Route::get('/sandbox', [SandboxController::class, 'index'])->name('sandbox');
    Route::post('/sandbox/complete', [SandboxController::class, 'complete'])->name('sandbox.complete');

    // ====================================================
    // 3. COURSE ROUTES
    // ====================================================
    // Contoh yang benar
Route::get('/learning-path', [CourseController::class, 'showSyllabus'])->name('courses.curriculum');
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

    // ====================================================
    // 4. HANDS-ON LAB SYSTEM (Gunakan prefix 'labs')
    // ====================================================
    // Masuk via Sidebar -> Redirect Logic
    // 1. Start Lab (Pemicu dari Sidebar) - Menggunakan ID
    Route::get('/labs/start/{id}', [LabController::class, 'start'])->name('lab.start');
    
    // 2. Halaman Workspace
    Route::get('/labs/workspace/{id}', [LabController::class, 'workspace'])->name('lab.workspace');
    
    // 3. Aksi (Check & Submit)
    Route::post('/labs/session/{id}/check', [LabController::class, 'check'])->name('lab.check');
    Route::post('/labs/session/{id}/end', [LabController::class, 'end'])->name('lab.end');
});

// ====================================================
// 5. QUIZ SYSTEM
// ====================================================
Route::middleware(['auth', 'verified'])->group(function () {
    // Halaman Intro
    Route::get('/quiz/intro/{chapterId}', [QuizController::class, 'intro'])->name('quiz.intro');
    
    // Mulai Sesi (POST)
    Route::post('/quiz/start', [QuizController::class, 'startSession'])->name('quiz.startSession');
    
    // Halaman Pengerjaan
    Route::get('/quiz/attempt/{chapterId}', [QuizController::class, 'show'])->name('quiz.show');
    
    // --- TAMBAHAN PENTING (FIX ERROR ANDA) ---
    // Simpan Progress per soal (Ragu-ragu & Auto-save)
    Route::post('/quiz/save-progress', [QuizController::class, 'saveProgress'])->name('quiz.save-progress');
    
    // Submit Akhir
    Route::post('/quiz/submit', [QuizController::class, 'submit'])->name('quiz.submit');
});

// ====================================================
// 6. ADMIN ROUTES
// ====================================================
// Perhatikan: saya menambahkan titik (.) pada name('admin.') agar pemanggilan menjadi rapi (admin.dashboard, admin.questions.store, dll)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // 1. Dashboard Utama
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
// Aksi Manajemen User (Tetap di Dashboard)
    Route::post('/dashboard/user/store', [AdminDashboardController::class, 'storeUser'])->name('user.store');
    Route::post('/dashboard/user/import', [AdminDashboardController::class, 'importUsers'])->name('user.import');
    // EXPORT ROUTES
    Route::get('/dashboard/user/export-csv', [AdminDashboardController::class, 'exportUsers'])->name('user.export.csv');
    Route::get('/dashboard/user/export-pdf', [AdminDashboardController::class, 'exportPdf'])->name('user.export.pdf');
    
    Route::get('/admin/student/{id}/export/csv', [AdminDashboardController::class, 'exportStudentCsv'])->name('student.export.csv');
    Route::get('/admin/student/{id}/export/pdf', [AdminDashboardController::class, 'exportStudentPdf'])->name('student.export.pdf');
    // Route::get('/dashboard/user/export', [AdminDashboardController::class, 'exportUsers'])->name('user.export');
    // 2. User Management
    Route::post('/users/{id}/update', [AdminDashboardController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminDashboardController::class, 'deleteUser'])->name('users.delete');
    
  

    // 3. Student Insight
    Route::get('/student/{id}', [AdminDashboardController::class, 'studentDetail'])->name('student.detail');

    // 4. Analytics & Quiz Management
    Route::get('/analytics/questions', [AdminDashboardController::class, 'questionAnalytics'])->name('analytics.questions');
// ACTION UPDATE: Tambahkan ini untuk menyimpan perubahan data siswa
    Route::put('/student/{id}/update', [AdminDashboardController::class, 'updateStudent'])->name('student.update');
    // --- BAGIAN STORE QUIZ (PERBAIKAN UTAMA) ---
    
    // Menampilkan Form (GET) -> Hasil route: admin.questions.create
    Route::get('/questions/create', [AdminDashboardController::class, 'createQuestion'])->name('questions.create');

    // Menyimpan Data (POST) -> Hasil route: admin.questions.store
    Route::post('/questions/store', [AdminDashboardController::class, 'storeQuestion'])->name('questions.store');
    Route::post('/questions/update/{id}', [AdminDashboardController::class, 'updateQuestion'])->name('questions.update');
    Route::delete('/questions/delete/{id}', [AdminDashboardController::class, 'destroyQuestion'])->name('questions.destroy');

    // ==========================================
    // LAB CONFIGURATION & ANALYTICS ROUTES
    // ==========================================

    // 1. Lab Configuration (CRUD Modul)
    Route::get('/labs', [LabController::class, 'index'])->name('labs.index');
    Route::post('/labs', [LabController::class, 'store'])->name('labs.store');
    Route::put('/labs/{id}', [LabController::class, 'update'])->name('labs.update');
    Route::delete('/labs/{id}', [LabController::class, 'destroy'])->name('labs.destroy');
    Route::patch('/labs/{id}/toggle', [LabController::class, 'toggleStatus'])->name('labs.toggle');

    // 2. Lab Tasks (Soal di dalam Lab)
   // Get List Task (dipanggil saat tombol "Steps" diklik)
    Route::get('/labs/{id}/tasks', [LabController::class, 'getTasks'])->name('labs.tasks.index');
    
    // Create Task Baru
    Route::post('/labs/tasks', [LabController::class, 'storeTask'])->name('labs.tasks.store');
    
    // Update Task (Method PUT untuk Edit)
    Route::put('/labs/tasks/{id}', [LabController::class, 'updateTask'])->name('labs.tasks.update');
    
    // Delete Task
    Route::delete('/labs/tasks/{id}', [LabController::class, 'destroyTask'])->name('labs.tasks.destroy');
    // 3. LAB ANALYTICS (Halaman Statistik) -> INI YANG ANDA MINTA
    // Parameter {labId?} bersifat opsional agar bisa menampilkan "Semua Modul" atau "Spesifik Modul"
    Route::get('/analytics/lab/{labId?}', [LabController::class, 'analytics'])->name('lab.analytics');
    Route::get('/analytics/student/{userId}', [LabController::class, 'studentAnalytics'])->name('student.analytics');
// New

   // CLASS & TOKEN MANAGEMENT
    Route::get('/classes', [ClassManagementController::class, 'index'])->name('classes.index');
    Route::post('/classes', [ClassManagementController::class, 'store'])->name('classes.store');
    Route::put('/classes/{id}', [ClassManagementController::class, 'update'])->name('classes.update');
    Route::delete('/classes/{id}', [ClassManagementController::class, 'destroy'])->name('classes.destroy');
    Route::post('/classes/{id}/token', [ClassManagementController::class, 'regenerateToken'])->name('classes.token');
});
use App\Http\Controllers\ProfileController;

Route::middleware(['auth'])->group(function () {
    // ... route lain ...
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

