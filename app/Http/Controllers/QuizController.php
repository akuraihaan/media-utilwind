<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// Models
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\QuizOption;

class QuizController extends Controller
{
    // === 1. HALAMAN INTRO / TERMS OF SERVICE (Ini yang tadi Missing) ===
    public function intro($chapterId)
    {
        $userId = Auth::id();

        // Cek apakah user SUDAH PERNAH menyelesaikan kuis ini?
        $completed = QuizAttempt::where('user_id', $userId)
            ->where('chapter_id', $chapterId)
            ->whereNotNull('completed_at')
            ->exists();

        if ($completed) {
            // Jika sudah selesai, redirect ke dashboard atau halaman hasil (opsional)
            return redirect()->route('dashboard')->with('info', 'Anda sudah menyelesaikan evaluasi bab ini.');
        }

        // Cek apakah ada sesi GANTUNG (Sedang dikerjakan tapi belum submit)
        $activeAttempt = QuizAttempt::where('user_id', $userId)
            ->where('chapter_id', $chapterId)
            ->whereNull('completed_at')
            ->first();

        // Jika ada sesi aktif, langsung lempar ke soal (skip intro)
        if ($activeAttempt) {
            return redirect()->route('quiz.show', ['chapterId' => $chapterId]);
        }

        return view('quiz.intro', compact('chapterId'));
    }

    // === 2. MULAI SESI (POST) ===
    public function startSession(Request $request)
    {
        $userId = Auth::id();
        $chapterId = $request->chapter_id;

        // Cek sesi aktif
        $existing = QuizAttempt::where('user_id', $userId)
            ->where('chapter_id', $chapterId)
            ->whereNull('completed_at')
            ->first();

        if (!$existing) {
            QuizAttempt::create([
                'user_id' => $userId,
                'chapter_id' => $chapterId,
                'score' => 0,
                'time_spent_seconds' => 0,
                'created_at' => Carbon::now(),
                'completed_at' => null
            ]);
        }

        return redirect()->route('quiz.show', ['chapterId' => $chapterId]);
    }

    // === 3. HALAMAN PENGERJAAN ===
    public function show($chapterId)
    {
        $user = Auth::user();
        $limitMinutes = 20;
        $limitSeconds = $limitMinutes * 60;

        // Ambil sesi aktif
        $attempt = QuizAttempt::where('user_id', $user->id)
            ->where('chapter_id', $chapterId)
            ->whereNull('completed_at')
            ->first();

        // Jika tidak ada sesi aktif, tendang ke intro
        if (!$attempt) {
            return redirect()->route('quiz.intro', ['chapterId' => $chapterId]);
        }

        // Hitung sisa waktu server-side
        $startTime = Carbon::parse($attempt->created_at);
        $now = Carbon::now();
        $elapsed = $startTime->diffInSeconds($now);
        $remaining = $limitSeconds - $elapsed;

        if ($remaining <= 0) {
            return $this->forceSubmit($attempt);
        }

        $questions = QuizQuestion::where('chapter_id', $chapterId)
            ->with('options')
            ->inRandomOrder()
            ->limit(10)
            ->get();

        return view('quiz.interface', [
            'questions' => $questions,
            'chapterId' => $chapterId,
            'remainingSeconds' => max(0, $remaining),
            'attemptId' => $attempt->id
        ]);
    }

    // === 4. SUBMIT JAWABAN ===
    public function submit(Request $request)
    {
        $request->validate([
            'attempt_id' => 'required|integer',
            'time_spent' => 'required|integer'
        ]);

        try {
            DB::beginTransaction();

            $attempt = QuizAttempt::find($request->attempt_id);
            
            if (!$attempt || $attempt->user_id != Auth::id()) {
                throw new \Exception("Sesi tidak valid.");
            }

            if ($attempt->completed_at != null) {
                return response()->json(['status' => 'success', 'redirect' => route('dashboard')]);
            }

            $answers = $request->answers ?? [];
            $correctCount = 0;
            $totalQuestions = 10; 

            if (!empty($answers) && is_array($answers)) {
                foreach ($answers as $qId => $optId) {
                    if(!is_numeric($optId)) continue;
                    $option = QuizOption::find($optId);
                    if(!$option) continue; 

                    $isCorrect = $option->is_correct;
                    if ($isCorrect) $correctCount++;
                    
                    QuizAttemptAnswer::updateOrCreate(
                        ['quiz_attempt_id' => $attempt->id, 'quiz_question_id' => $qId],
                        ['quiz_option_id' => $optId, 'is_correct' => $isCorrect]
                    );
                }
            }

            $score = round(($correctCount / $totalQuestions) * 100);

            $attempt->update([
                'score' => $score,
                'completed_at' => Carbon::now(),
                'time_spent_seconds' => $request->time_spent
            ]);

            DB::commit();

            return response()->json(['status' => 'success', 'score' => $score, 'redirect' => route('dashboard')]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Quiz Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    private function forceSubmit($attempt) {
        $attempt->update(['completed_at' => Carbon::now()]);
        return redirect()->route('dashboard')->with('info', 'Waktu habis.');
    }
}