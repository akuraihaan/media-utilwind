<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User; // Pastikan Model User di-import
use App\Models\UserLessonProgress;
use App\Models\CourseLesson;
use App\Models\Lab;
use App\Models\LabHistory;
use App\Models\QuizAttempt;
use App\Models\ClassGroup; // TAMBAHKAN IMPORT INI

class DashboardController extends Controller
{
    public function index()
    {
       $user = Auth::user();
        $userId = $user->id;

        // 1. STATS: MATERI
        $totalLessons = CourseLesson::count();
        $lessonsCompleted = UserLessonProgress::where('user_id', $userId)
            ->where('completed', true)
            ->count();

        // 2. STATS: LABS (Hanya Lulus)
        $totalLabs = Lab::count();
        $labsCompleted = LabHistory::where('user_id', $userId)
            ->where('final_score', '>=', 70)
            ->distinct('lab_id')
            ->count('lab_id');

        // 3. STATS: KUIS
        $quizAttempts = QuizAttempt::where('user_id', $userId)->whereNotNull('completed_at')->get();
        $quizzesCompleted = $quizAttempts->count();
        $quizAverage = $quizzesCompleted > 0 ? $quizAttempts->avg('score') : 0;

        // 4. STATS: BAB LULUS
        $chaptersPassed = $quizAttempts->where('score', '>=', 70)->unique('chapter_id')->count();

        // 5. CHART DATA (Nilai Terbaik per Bab)
        $bestQuizScores = QuizAttempt::where('user_id', $userId)
            ->select('chapter_id', DB::raw('MAX(score) as max_score'))
            ->groupBy('chapter_id')
            ->orderBy('chapter_id')
            ->get();

        $chartData = [
            'labels' => $bestQuizScores->map(fn($q) => 'Bab ' . $q->chapter_id)->toArray(),
            'scores' => $bestQuizScores->pluck('max_score')->toArray(),
        ];

        // 6. RIWAYAT AKTIVITAS (NO DUPLICATE LOGIC)
        $rawLabs = LabHistory::where('user_id', $userId)->with('lab')->latest()->limit(20)->get();
        $rawQuizzes = QuizAttempt::where('user_id', $userId)->whereNotNull('completed_at')->latest()->limit(20)->get();

        $mappedLabs = $rawLabs->map(function ($item) {
            return [
                'id' => 'lab-' . $item->lab_id,
                'name' => $item->lab->title,
                'type' => 'lab',
                'score' => $item->final_score,
                'date' => $item->updated_at,
                'icon' => '💻'
            ];
        });

        $mappedQuizzes = $rawQuizzes->map(function ($item) {
            return [
                'id' => 'quiz-' . $item->chapter_id,
                'name' => 'Evaluasi Bab ' . $item->chapter_id,
                'type' => 'quiz',
                'score' => $item->score,
                'date' => $item->completed_at,
                'icon' => '📝'
            ];
        });

        $historyCombined = $mappedLabs->merge($mappedQuizzes)
            ->sortByDesc('date')
            ->unique('name')
            ->take(5)
            ->values();

        // 7. ACTIVE SESSION
        $activeSession = LabHistory::where('user_id', $userId)
            ->where('status', 'in_progress')
            ->with('lab')
            ->latest()
            ->first();

        // =========================================================
        // 8. GAMIFIKASI: BADGES
        // =========================================================
        // Ambil array ID badge yang sudah didapatkan user ini
        $unlockedBadges = DB::table('user_badges')->where('user_id', $userId)->pluck('badge_id')->toArray();
        // Ambil master data semua badge
        $allBadges = DB::table('badges')->get();

        // =========================================================
        // 9. GAMIFIKASI: LEADERBOARD KELAS
        // =========================================================
        $leaderboard = [];
        if (!empty($user->class_group)) {
            $leaderboard = User::where('class_group', $user->class_group)
                ->where('role', 'student')
                ->orderByDesc('xp') // Urutkan berdasarkan XP tertinggi
                ->take(5)
                ->get();
        }

        return view('dashboard', compact(
            'user', // Passing object user agar method accessor di model bisa dipanggil
            'totalLessons', 'lessonsCompleted',
            'totalLabs', 'labsCompleted',
            'quizAverage', 'quizzesCompleted',
            'chaptersPassed',
            'chartData',
            'historyCombined',
            'activeSession',
            'unlockedBadges', 'allBadges', 'leaderboard' // Variabel Gamifikasi
        ));
    }

    /**
     * API Progress (Heatmap & Sidebar Log)
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

        // 2. ACTIVITY LOG (NO DUPLICATE - SIDEBAR)
        // Sama seperti index, kita ambil pool data lalu filter unik
        $rawLabs = LabHistory::where('user_id', $userId)->with('lab')->latest()->limit(15)->get();
        $rawQuizzes = QuizAttempt::where('user_id', $userId)->latest()->limit(15)->get();

        $mappedLabs = $rawLabs->map(fn($i) => [
            'activity' => $i->lab->title,
            'type' => 'Lab',
            'raw_date' => $i->updated_at,
            'time' => $i->updated_at->diffForHumans(),
            'status' => $i->final_score >= 70 ? 'Lulus' : 'Remedial'
        ]);

        $mappedQuizzes = $rawQuizzes->map(fn($i) => [
            'activity' => 'Evaluasi Bab ' . $i->chapter_id,
            'type' => 'Kuis',
            'raw_date' => $i->completed_at,
            'time' => $i->completed_at->diffForHumans(),
            'status' => $i->score >= 70 ? 'Lulus' : 'Remedial'
        ]);

        // LOGIKA FILTERING DUPLIKAT SIDEBAR
        $activityLog = $mappedLabs->merge($mappedQuizzes)
            ->sortByDesc('raw_date')
            ->unique('activity') // Pastikan nama aktivitas unik
            ->take(4)            // Ambil 4 item unik terakhir
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