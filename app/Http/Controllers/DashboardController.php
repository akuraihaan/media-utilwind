<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Models
use App\Models\UserLessonProgress;
use App\Models\CourseLesson;
use App\Models\QuizAttempt;

class DashboardController extends Controller
{
    /**
     * HALAMAN UTAMA DASHBOARD
     * Mengirim data statistik utama dan riwayat kuis ke Blade.
     */
    public function index()
    {
        $userId = Auth::id();

        // 1. STATISTIK MATERI (LESSON)
        $totalLessons = DB::table('course_lessons')->count();
        $lessonsCompleted = DB::table('user_lesson_progress')
            ->where('user_id', $userId)
            ->count();

        // 2. STATISTIK KUIS (EVALUASI)
        $quizzes = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('completed_at') // Hanya yang sudah selesai
            ->get();

        $quizAverage = $quizzes->avg('score') ?? 0;
        $quizzesCompleted = $quizzes->count(); // Total kali mencoba
        
        // Menghitung jumlah bab unik yang sudah lulus (misal KKM 70)
        $chaptersPassed = $quizzes->where('score', '>=', 70)->unique('chapter_id')->count();

        // 3. DATA GRAFIK NILAI KUIS (Untuk Chart.js di Blade)
        // Kita ambil nilai terbaik per bab atau riwayat urut waktu
        $chartAttempts = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->orderBy('created_at', 'asc') // Urutkan dari yang pertama dikerjakan
            ->take(10) // Ambil 10 terakhir agar grafik tidak kepanjangan
            ->get();

        $chartData = [
            'labels' => $chartAttempts->map(function($attempt) {
                return 'Bab ' . $attempt->chapter_id . ' (' . $attempt->created_at->format('d/m') . ')';
            }),
            'scores' => $chartAttempts->pluck('score')
        ];

        // 4. RIWAYAT AKTIVITAS TERBARU (Gabungan Kuis & Lesson)
        $recentAttempts = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->latest()
            ->take(5)
            ->get();

        // Mengirim semua data ke View
        return view('dashboard', [
            'user' => Auth::user(),
            'stats' => [
                'lessons_completed' => $lessonsCompleted,
                'total_lessons'     => $totalLessons,
                'quiz_average'      => round($quizAverage),
                'quizzes_completed' => $quizzesCompleted,
                'chapters_passed'   => $chaptersPassed
            ],
            'recentAttempts' => $recentAttempts,
            'chartData'      => $chartData
        ]);
    }

    /**
     * API ENDPOINT UNTUK DATA CHART KOMPLEKS (AJAX)
     * Digunakan untuk Heatmap & Activity Log Timeline agar loading halaman utama tidak berat.
     */
    public function progress(Request $request)
    {
        $userId = $request->user()->id;

        // A. Progress Pelajaran
        $totalLessons = DB::table('course_lessons')->count();
        $lessonCompleted = DB::table('user_lesson_progress')->where('user_id', $userId)->count();
        
        // B. Progress Aktivitas
        $totalActivities = DB::table('course_activities')->count();
        $activityCompleted = DB::table('user_activity_progress')
            ->where('user_id', $userId)
            ->where('completed', true)
            ->count();

        // C. Hitung Persentase Gabungan (Lesson + Activity + Quiz Passing)
        // Asumsi: Total Units = Lesson + Activity + 3 Bab Quiz
        $totalUnits = $totalLessons + $totalActivities + 3; 
        
        // Hitung bab kuis yang lulus (>70)
        $quizPassedCount = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->where('score', '>=', 70)
            ->distinct('chapter_id')
            ->count('chapter_id');

        $completedUnits = $lessonCompleted + $activityCompleted + $quizPassedCount;
        $progressPercent = $totalUnits > 0 ? round(($completedUnits / $totalUnits) * 100) : 0;

        // D. Timeline Lesson (Line Chart)
        $lessonTimeline = DB::table('user_lesson_progress')
            ->where('user_id', $userId)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as value')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // E. Timeline Gabungan (Heatmap - Lesson + Quiz)
        // Menggabungkan tanggal pengerjaan lesson dan kuis untuk heatmap aktivitas
        $lessonDates = DB::table('user_lesson_progress')
            ->where('user_id', $userId)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date');

        $quizDates = DB::table('quiz_attempts')
            ->where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date');

        // Union keduanya untuk heatmap konsistensi belajar
        $activityTimeline = $lessonDates->unionAll($quizDates)
            ->get()
            ->groupBy('date')
            ->map(function ($row) {
                return [
                    'date' => $row->first()->date,
                    'count' => $row->sum('count')
                ];
            })->values();

        // F. Activity Log (Gabungan Lesson & Quiz untuk List Sidebar)
        $logs = collect();

        // Ambil Log Lesson
        $lessonLogs = DB::table('user_lesson_progress as ulp')
            ->join('course_lessons as cl', 'cl.id', '=', 'ulp.course_lesson_id')
            ->where('ulp.user_id', $userId)
            ->select('cl.title as activity', 'ulp.created_at', DB::raw("'Lesson' as type"))
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Ambil Log Quiz
        $quizLogs = DB::table('quiz_attempts')
            ->where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->select(DB::raw("CONCAT('Evaluasi Bab ', chapter_id) as activity"), 'completed_at as created_at', DB::raw("'Kuis' as type"))
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Merge & Sort
        $activityLog = $lessonLogs->merge($quizLogs)
            ->sortByDesc('created_at')
            ->take(10)
            ->values()
            ->map(function ($item) {
                return [
                    'activity' => $item->activity,
                    'status'   => $item->type == 'Kuis' ? 'Selesai' : 'Tuntas',
                    'time'     => Carbon::parse($item->created_at)->diffForHumans(),
                    'type'     => $item->type
                ];
            });

        return response()->json([
            'progress_percent'   => $progressPercent,
            'lesson_completed'   => $lessonCompleted,
            'activity_completed' => $activityCompleted + $quizPassedCount, // Digabung biar terlihat banyak
            'lesson_timeline'    => $lessonTimeline,
            'activity_timeline'  => $activityTimeline, // Untuk Heatmap
            'activity_log'       => $activityLog,
        ]);
    }
}