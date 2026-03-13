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
        // =====================================================================
        // 1. STATISTIK GLOBAL KUIS
        // =====================================================================
        $totalAttempts = QuizAttempt::whereNotNull('completed_at')->count();
        $avgScore = QuizAttempt::whereNotNull('completed_at')->avg('score') ?? 0;
        $passRate = $totalAttempts > 0 
            ? (QuizAttempt::where('score', '>=', 70)->count() / $totalAttempts) * 100 
            : 0;

        // =====================================================================
        // 2. ANALISIS PER BAB (CHAPTER)
        // =====================================================================
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
                    'title' => $item->chapter_id == 99 ? 'Evaluasi Akhir' : 'Evaluasi Bab ' . $item->chapter_id,
                    'total' => $item->total,
                    'average' => round($item->average, 1),
                    'pass_rate' => $item->total > 0 ? round(($item->passed / $item->total) * 100) : 0
                ];
            });

        // =====================================================================
        // 3. DATA BANK SOAL & ANALISIS KESULITAN (AKURASI)
        // =====================================================================
        $questions = QuizQuestion::with('options')->get()->map(function($q) {
            $stats = DB::table('quiz_attempt_answers')
                ->where('quiz_question_id', $q->id)
                ->selectRaw('count(*) as total_attempts')
                ->selectRaw('sum(case when is_correct = 1 then 1 else 0 end) as correct_count')
                ->selectRaw('sum(case when is_correct = 0 then 1 else 0 end) as wrong_count')
                ->first();

            $q->total_attempts = $stats->total_attempts ?? 0;
            $q->correct_count = $stats->correct_count ?? 0;
            $q->wrong_count = $stats->wrong_count ?? 0;
            $q->accuracy = $q->total_attempts > 0 ? round(($q->correct_count / $q->total_attempts) * 100) : 0;

            if ($q->accuracy >= 80) $q->status = 'Mudah';
            elseif ($q->accuracy >= 50) $q->status = 'Sedang';
            else $q->status = 'Sulit';

            return $q;
        });

        $hardestQuestions = $questions->where('status', 'Sulit')->sortBy('accuracy')->take(5)->map(function($q) {
            $q->failure_rate = 100 - $q->accuracy;
            return $q;
        });

        // =====================================================================
        // 4. STATISTIK SISWA & RIWAYAT TERBARU
        // =====================================================================
        $recentAttempts = QuizAttempt::with('user')
            ->whereNotNull('completed_at')
            ->latest('completed_at')
            ->take(10)
            ->get();

        $studentStats = DB::table('quiz_attempts')
            ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
            ->select('users.name', 'users.email', DB::raw('AVG(quiz_attempts.score) as avg_score'), DB::raw('COUNT(quiz_attempts.id) as total_attempts'))
            ->whereNotNull('completed_at')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('avg_score')
            ->limit(10)
            ->get()
            ->map(function($stat) {
                $stat->avg_score = round($stat->avg_score, 1);
                return $stat;
            });

        // =====================================================================
        // 5. DETAIL LEMBAR JAWABAN PER SISWA (UNTUK MODAL ALPINE.JS)
        // =====================================================================
        $studentDetailsMap = [];
        
        // Ambil semua jawaban
        $allAnswers = DB::table('quiz_attempt_answers')
            ->join('quiz_attempts', 'quiz_attempt_answers.quiz_attempt_id', '=', 'quiz_attempts.id')
            ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
            ->join('quiz_questions', 'quiz_attempt_answers.quiz_question_id', '=', 'quiz_questions.id')
            ->leftJoin('quiz_options as chosen', 'quiz_attempt_answers.quiz_option_id', '=', 'chosen.id')
            ->select(
                'users.email', 'users.name', 'quiz_attempts.chapter_id', 'quiz_attempts.score',
                'quiz_questions.question_text', 'quiz_attempt_answers.is_correct',
                'chosen.option_text as chosen_text', 'quiz_questions.id as question_id'
            )
            ->get();

        // Cache kunci jawaban yang benar agar cepat
        $correctOptions = DB::table('quiz_options')->where('is_correct', 1)->pluck('option_text', 'quiz_question_id');

        foreach($allAnswers as $ans) {
            $email = $ans->email;
            
            // Inisialisasi struktur user jika belum ada
            if(!isset($studentDetailsMap[$email])) {
                $studentDetailsMap[$email] = ['name' => $ans->name, 'email' => $email, 'chapters' => []];
            }
            
            $chId = $ans->chapter_id;
            
            // Inisialisasi struktur bab (chapter)
            if(!isset($studentDetailsMap[$email]['chapters'][$chId])) {
                $studentDetailsMap[$email]['chapters'][$chId] = [
                    'title' => $chId == 99 ? 'Evaluasi Akhir' : 'Kuis Bab ' . $chId,
                    'score' => $ans->score,
                    'answers' => []
                ];
            }

            // Masukkan data jawaban per soal
            $studentDetailsMap[$email]['chapters'][$chId]['answers'][] = [
                'question' => $ans->question_text,
                'is_correct' => $ans->is_correct,
                'chosen' => $ans->chosen_text ?? 'Tidak dijawab',
                'correct' => $correctOptions[$ans->question_id] ?? 'Tidak ada kunci'
            ];
        }

        // =====================================================================
        // RETURN KE VIEW
        // =====================================================================
        return view('admin.quiz_manager', compact(
            'totalAttempts', 'avgScore', 'passRate', 'chapterStats', 'hardestQuestions', 'recentAttempts',
            'questions', 'studentStats', 'studentDetailsMap'
        ));
    }
}