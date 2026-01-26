<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\QuizAttempt;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. DATA STATISTIK (Menggunakan Query Builder agar lebih stabil)
        $totalStudents = DB::table('users')->where('role', 'student')->count();
        $totalAttempts = DB::table('quiz_attempts')->count();
        $globalAverageRaw = DB::table('quiz_attempts')->avg('score');
        $globalAverage = round($globalAverageRaw ?? 0, 1);
        $remedialCount = DB::table('quiz_attempts')->where('score', '<', 70)->count();

        // 2. DATA CHART (7 Hari Terakhir)
        $chartDataRaw = DB::table('quiz_attempts')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('AVG(score) as avg_score'))
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartLabels = $chartDataRaw->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'));
        $chartScores = $chartDataRaw->pluck('avg_score')->map(fn($s) => round($s));

        // 3. ANALISIS SOAL (Top 10)
        $questionStats = DB::table('quiz_questions as q')
            ->leftJoin('quiz_attempt_answers as a', 'q.id', '=', 'a.quiz_question_id')
            ->select(
                'q.id', 'q.question_text', 'q.chapter_id',
                DB::raw('count(a.id) as total_answers'),
                DB::raw('sum(case when a.is_correct = 1 then 1 else 0 end) as correct_count'),
                DB::raw('sum(case when a.is_correct = 0 then 1 else 0 end) as wrong_count')
            )
            ->groupBy('q.id', 'q.question_text', 'q.chapter_id')
            ->orderByDesc('total_answers')
            ->limit(10)
            ->get()
            ->map(function($q) {
                $q->accuracy = $q->total_answers > 0 ? round(($q->correct_count / $q->total_answers) * 100) : 0;
                if($q->accuracy >= 80) $q->difficulty = 'Mudah';
                elseif($q->accuracy >= 50) $q->difficulty = 'Sedang';
                else $q->difficulty = 'Sulit';
                return $q;
            });

        // 4. LEADERBOARD & RECENT
        $topStudents = DB::table('users')
            ->join('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
            ->select('users.id', 'users.name', 'users.email', DB::raw('AVG(quiz_attempts.score) as avg_score'))
            ->where('users.role', 'student')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('avg_score')
            ->limit(5)
            ->get();

        $recentActivities = QuizAttempt::with('user')->latest()->take(5)->get();
        
        // 5. DATA USER MANAGEMENT
        $users = User::orderByDesc('created_at')->get();

        // 6. DEBUGGING (HAPUS TANDA KOMENTAR JIKA MASIH ERROR)
        // dd($totalStudents, $users); 

        // 7. RETURN VIEW (ARRAY EKSPLISIT)
        return view('admin.dashboard', [
            'totalStudents'    => $totalStudents,
            'totalAttempts'    => $totalAttempts,
            'globalAverage'    => $globalAverage,
            'remedialCount'    => $remedialCount,
            'chartLabels'      => $chartLabels,
            'chartScores'      => $chartScores,
            'questionStats'    => $questionStats,
            'topStudents'      => $topStudents,
            'recentActivities' => $recentActivities,
            'users'            => $users
        ]);
    }

    // --- CRUD METHODS ---
    public function updateUser(Request $request, $id) {
        User::findOrFail($id)->update($request->only('name', 'email', 'role'));
        return response()->json(['status' => 'success']);
    }

    public function deleteUser($id) {
        if(auth()->id() == $id) return response()->json(['status' => 'error'], 403);
        User::findOrFail($id)->delete();
        return response()->json(['status' => 'success']);
    }

    // --- INSIGHT METHODS ---
    public function studentDetail($id) {
        $student = User::findOrFail($id);
        $attempts = DB::table('quiz_attempts')->where('user_id', $id)->orderByDesc('created_at')->get();
        $stats = [
            'total' => $attempts->count(),
            'avg' => $attempts->avg('score') ? round($attempts->avg('score')) : 0,
            'max' => $attempts->max('score') ?? 0,
            // 'last' => $attempts->first() ? Carbon::parse($attempts->first()->created_at)->diffForHumans() : '-'
            'last_active' => $attempts->first() ? \Carbon\Carbon::parse($attempts->first()->created_at)->diffForHumans() : 'Belum aktif'
        ];
        return view('admin.student_detail', compact('student', 'stats', 'attempts'));
    }

    public function questionAnalytics() {
        $questions = DB::table('quiz_questions as q')
            ->leftJoin('quiz_attempt_answers as a', 'q.id', '=', 'a.quiz_question_id')
            ->select('q.id', 'q.chapter_id', 'q.question_text', DB::raw('count(a.id) as total_attempts'), DB::raw('sum(case when a.is_correct = 1 then 1 else 0 end) as correct_count'), DB::raw('sum(case when a.is_correct = 0 then 1 else 0 end) as wrong_count'))
            ->groupBy('q.id', 'q.chapter_id', 'q.question_text')->orderBy('q.chapter_id')->get()
            ->map(function($q) {
                $q->accuracy = $q->total_attempts > 0 ? round(($q->correct_count / $q->total_attempts) * 100) : 0;
                if($q->accuracy >= 80) $q->status = 'Mudah'; elseif($q->accuracy >= 50) $q->status = 'Sedang'; else $q->status = 'Sulit';
                return $q;
            });
        return view('admin.question_analytics', compact('questions'));
    }
}