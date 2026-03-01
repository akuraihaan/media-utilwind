<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UserLessonProgress;
use App\Models\CourseLesson;
use App\Models\Lab;
use App\Models\LabHistory;
use App\Models\QuizAttempt;
use App\Models\ClassGroup;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        // =========================================================
        // 1-4. STATS: MATERI, LABS, KUIS, BAB LULUS (HERO MODAL)
        // =========================================================
        $totalLessons = CourseLesson::count();
        $lessonsCompleted = UserLessonProgress::where('user_id', $userId)
            ->where('completed', true)
            ->count();

        $totalLabs = Lab::count();
        $labsCompleted = LabHistory::where('user_id', $userId)
            ->where('final_score', '>=', 70)
            ->distinct('lab_id')
            ->count('lab_id');

        $quizAttempts = QuizAttempt::where('user_id', $userId)->whereNotNull('completed_at')->get();
        $quizzesCompleted = $quizAttempts->count();
        $quizAverage = $quizzesCompleted > 0 ? $quizAttempts->avg('score') : 0;

        $chaptersPassed = $quizAttempts->where('score', '>=', 70)->unique('chapter_id')->count();

        // =========================================================
        // 5. CHART DATA (Nilai Terbaik per Bab)
        // =========================================================
        $bestQuizScores = QuizAttempt::where('user_id', $userId)
            ->select('chapter_id', DB::raw('MAX(score) as max_score'))
            ->groupBy('chapter_id')
            ->orderBy('chapter_id')
            ->get();

        $chartData = [
            'labels' => $bestQuizScores->map(fn($q) => 'Bab ' . $q->chapter_id)->toArray(),
            'scores' => $bestQuizScores->pluck('max_score')->toArray(),
        ];

        // =========================================================
        // 6. RIWAYAT AKTIVITAS + EXP LOGIC (NO DUPLICATE)
        // =========================================================
        // Ambil data Lab
        $rawLabs = LabHistory::where('user_id', $userId)->with('lab')->latest()->limit(15)->get();
        $mappedLabs = $rawLabs->map(function ($item) {
            return [
                'id' => 'lab-' . $item->lab_id,
                'name' => $item->lab->title ?? 'Praktik Lab',
                'type' => 'lab',
                'score' => $item->final_score,
                'exp' => ($item->final_score >= 70) ? 50 : 0, // Kriteria: Lulus = 50 XP
                'date' => $item->updated_at,
                'icon' => '💻'
            ];
        });

        // Ambil data Quiz
        $rawQuizzes = QuizAttempt::where('user_id', $userId)->whereNotNull('completed_at')->latest()->limit(15)->get();
        $mappedQuizzes = $rawQuizzes->map(function ($item) {
            return [
                'id' => 'quiz-' . $item->chapter_id,
                'name' => 'Evaluasi Bab ' . $item->chapter_id,
                'type' => 'quiz',
                'score' => $item->score,
                'exp' => $item->score, // Kriteria: 1 Point Skor = 1 XP
                'date' => $item->completed_at,
                'icon' => '📝'
            ];
        });

        // Ambil data Materi (Baru: Untuk memperkaya log riwayat)
        $rawLessons = UserLessonProgress::where('user_id', $userId)->where('completed', true)->latest('updated_at')->limit(10)->get();
        $mappedLessons = $rawLessons->map(function ($item) {
            return [
                'id' => 'materi-' . $item->lesson_id,
                'name' => 'Membaca Materi Modul',
                'type' => 'materi',
                'score' => null,
                'exp' => 10, // Kriteria: Membaca Selesai = 10 XP
                'date' => $item->updated_at,
                'icon' => '📖'
            ];
        });

        // Ambil data Lencana/Badge (Baru: Memperkaya log)
        $rawBadges = DB::table('user_badges')
                        ->join('badges', 'user_badges.badge_id', '=', 'badges.id')
                        ->where('user_badges.user_id', $userId)
                        ->select('badges.name', 'user_badges.created_at')
                        ->latest('user_badges.created_at')
                        ->limit(5)->get();
        
        $mappedBadges = $rawBadges->map(function ($item) {
            return [
                'id' => 'badge-' . uniqid(),
                'name' => 'Lencana: ' . $item->name,
                'type' => 'badge',
                'score' => null,
                'exp' => 0, // Lencana adalah reward estetis, bukan penambah EXP
                'date' => $item->created_at,
                'icon' => '🎖️'
            ];
        });

        // Gabungkan, urutkan, dan pastikan tidak duplikat di tampilan
        $historyCombined = collect()
            ->merge($mappedLabs)
            ->merge($mappedQuizzes)
            ->merge($mappedLessons)
            ->merge($mappedBadges)
            ->sortByDesc('date')
            ->unique('name') // Hindari spam nama aktivitas yang sama berulang kali
            ->take(6)
            ->values();

        // =========================================================
        // 7. ACTIVE SESSION (LAB RESUME)
        // =========================================================
        $activeSession = LabHistory::where('user_id', $userId)
            ->where('status', 'in_progress')
            ->with('lab')
            ->latest()
            ->first();

        // =========================================================
        // 8. GAMIFIKASI: BADGES
        // =========================================================
        $unlockedBadges = DB::table('user_badges')->where('user_id', $userId)->pluck('badge_id')->toArray();
        $allBadges = DB::table('badges')->get();

        // =========================================================
        // 9. GAMIFIKASI: LEADERBOARD KELAS
        // =========================================================
        $leaderboard = [];
        if (!empty($user->class_group)) {
            $leaderboard = User::where('class_group', $user->class_group)
                ->where('role', 'student')
                ->orderByDesc('xp') 
                ->take(5)
                ->get();
        }

        return view('dashboard', compact(
            'user', 
            'totalLessons', 'lessonsCompleted',
            'totalLabs', 'labsCompleted',
            'quizAverage', 'quizzesCompleted',
            'chaptersPassed',
            'chartData',
            'historyCombined',
            'activeSession',
            'unlockedBadges', 'allBadges', 'leaderboard'
        ));
    }

    /**
     * API Progress (Heatmap & Sidebar Log Realtime)
     */
    public function progress()
    {
        $userId = Auth::id();

        // 1. HEATMAP DATA
        $lessonActivity = UserLessonProgress::where('user_id', $userId)
            ->where('updated_at', '>=', now()->subDays(90))
            ->select(DB::raw('DATE(updated_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->get()
            ->map(fn($i) => ['date' => $i->date, 'count' => $i->count]);

        // 2. ACTIVITY LOG (Membawa Data EXP untuk JS)
        $rawLabs = LabHistory::where('user_id', $userId)->with('lab')->latest()->limit(15)->get();
        $rawQuizzes = QuizAttempt::where('user_id', $userId)->latest()->limit(15)->get();
        $rawLessons = UserLessonProgress::where('user_id', $userId)->where('completed', true)->latest('updated_at')->limit(10)->get();

        $mappedLabs = $rawLabs->map(fn($i) => [
            'activity' => $i->lab->title ?? 'Praktik Lab',
            'type' => 'Lab',
            'raw_date' => $i->updated_at,
            'time' => $i->updated_at->diffForHumans(),
            'status' => $i->final_score >= 70 ? 'Lulus' : 'Remedial',
            'exp' => $i->final_score >= 70 ? 50 : 0
        ]);

        $mappedQuizzes = $rawQuizzes->map(fn($i) => [
            'activity' => 'Evaluasi Bab ' . $i->chapter_id,
            'type' => 'Kuis',
            'raw_date' => $i->completed_at ?? $i->updated_at,
            'time' => ($i->completed_at ?? $i->updated_at)->diffForHumans(),
            'status' => $i->score >= 70 ? 'Lulus' : 'Remedial',
            'exp' => $i->score
        ]);

        $mappedLessons = $rawLessons->map(fn($i) => [
            'activity' => 'Membaca Materi Modul',
            'type' => 'Materi',
            'raw_date' => $i->updated_at,
            'time' => $i->updated_at->diffForHumans(),
            'status' => 'Selesai',
            'exp' => 10
        ]);

        // LOGIKA FILTERING DUPLIKAT SIDEBAR
        $activityLog = collect()
            ->merge($mappedLabs)
            ->merge($mappedQuizzes)
            ->merge($mappedLessons)
            ->sortByDesc('raw_date')
            ->unique('activity') // Pastikan nama aktivitas unik agar rapi
            ->take(5)            // Tampilkan 5 terakhir di JS
            ->values();

        return response()->json([
            'activity_timeline' => $lessonActivity,
            'activity_log' => $activityLog
        ]);
    }

    /**
     * FUNGSI GABUNG KELAS MENGGUNAKAN TOKEN
     */
    public function joinClass(Request $request)
    {
        // 1. Validasi Token (Wajib 6 Karakter)
        $request->validate([
            'token' => 'required|string|size:6'
        ], [
            'token.size' => 'Token kelas harus terdiri dari 6 karakter.'
        ]);

        // Pastikan kapital
        $token = strtoupper($request->token);

        // 2. Cek eksistensi dan status kelas di Database
        $class = ClassGroup::where('token', $token)->first();

        if (!$class) {
            return redirect()->back()->with('error', 'Token tidak ditemukan. Periksa kembali token yang diberikan instruktur Anda.');
        }

        if (!$class->is_active) {
            return redirect()->back()->with('error', 'Gagal! Kelas ini sudah ditutup dan tidak lagi menerima siswa baru.');
        }

        // 3. Eksekusi Join Kelas
        $user = Auth::user();
        
        // Cek jika siswa sudah berada di kelas ini
        if ($user->class_group === $class->name) {
            return redirect()->back()->with('info', 'Anda sudah tergabung di kelas ' . $class->name . '.');
        }

        // Update data siswa
        $user->class_group = $class->name;
        $user->save();

        return redirect()->back()->with('success', 'Berhasil bergabung dengan kelas ' . $class->name . '!');
    }
}