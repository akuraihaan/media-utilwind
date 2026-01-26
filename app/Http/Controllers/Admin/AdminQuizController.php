<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\CourseLesson;

class AdminQuizController extends Controller
{
    public function index()
    {
        // 1. STATISTIK GLOBAL
        $totalAttempts = QuizAttempt::whereNotNull('completed_at')->count();
        $avgScore = QuizAttempt::whereNotNull('completed_at')->avg('score') ?? 0;
        $passRate = $totalAttempts > 0 
            ? (QuizAttempt::where('score', '>=', 70)->count() / $totalAttempts) * 100 
            : 0;

        // 2. ANALISIS PER BAB (CHAPTER)
        // Kita kelompokkan berdasarkan chapter_id
        $chapterStats = QuizAttempt::select('chapter_id', 
                DB::raw('count(*) as total'), 
                DB::raw('avg(score) as average'),
                DB::raw('sum(case when score >= 70 then 1 else 0 end) as passed')
            )
            ->whereNotNull('completed_at')
            ->groupBy('chapter_id')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->chapter_id,
                    'title' => 'Evaluasi Bab ' . $item->chapter_id, // Bisa diganti query ke table chapters jika ada
                    'total' => $item->total,
                    'average' => round($item->average, 1),
                    'pass_rate' => $item->total > 0 ? round(($item->passed / $item->total) * 100) : 0
                ];
            });

        // 3. SOAL TERSULIT (BUTIR SOAL BERMASALAH)
        // Menganggap jawaban salah jika is_correct = 0 di tabel quiz_attempt_answers
        // Note: Pastikan relasi model QuizAttemptAnswer ada
        $hardestQuestions = DB::table('quiz_attempt_answers as a')
            ->join('quiz_questions as q', 'q.id', '=', 'a.quiz_question_id')
            ->select('q.id', 'q.question_text', 'q.chapter_id')
            ->selectRaw('count(*) as total_answers')
            ->selectRaw('sum(case when a.is_correct = 0 then 1 else 0 end) as wrong_count')
            ->groupBy('q.id', 'q.question_text', 'q.chapter_id')
            ->havingRaw('(wrong_count / total_answers) > 0.5') // Jika > 50% salah
            ->orderByDesc('wrong_count')
            ->limit(5)
            ->get()
            ->map(function($q) {
                $q->failure_rate = round(($q->wrong_count / $q->total_answers) * 100);
                return $q;
            });

        // 4. RIWAYAT TERBARU
        $recentAttempts = QuizAttempt::with('user')
            ->whereNotNull('completed_at')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.quiz_manager', compact(
            'totalAttempts', 'avgScore', 'passRate', 
            'chapterStats', 'hardestQuestions', 'recentAttempts'
        ));
    }
}