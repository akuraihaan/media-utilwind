<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

// Models
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\QuizOption;
use App\Support\ChapterSummary;
use App\Support\LearningOutcomeAnalytics;

class QuizController extends Controller
{
    // =========================================================================
    // 1. LANDING PAGE (INTRO)
    // =========================================================================
    public function intro($chapterId)
    {
        $userId = Auth::id();

        // A. Cek Resume (Apakah ada sesi gantung?)
        // Jika user keluar browser lalu masuk lagi, langsung lempar ke soal.
        $activeAttempt = QuizAttempt::where('user_id', $userId)
            ->where('chapter_id', $chapterId)
            ->whereNull('completed_at')
            ->first();

        if ($activeAttempt) {
            return redirect()->route('quiz.show', ['chapterId' => $chapterId]);
        }

        // B. Cek Status Kelulusan
        $bestScore = QuizAttempt::where('user_id', $userId)
            ->where('chapter_id', $chapterId)
            ->max('score');

        // Jika sudah lulus (Nilai >= 70), blokir akses.
        if ($bestScore >= 70) {
            $latestPassedAttempt = QuizAttempt::where('user_id', $userId)
                ->where('chapter_id', $chapterId)
                ->where('score', '>=', 70)
                ->whereNotNull('completed_at')
                ->latest('completed_at')
                ->first();

            if ($latestPassedAttempt) {
                return redirect()->route('quiz.result', $latestPassedAttempt->id)
                    ->with('success', 'Selamat! Anda sudah lulus materi ini dengan nilai ' . $bestScore);
            }

            return redirect()->route('dashboard')
                ->with('success', 'Selamat! Anda sudah lulus materi ini dengan nilai ' . $bestScore);
        }

        // C. Tampilkan Halaman Intro
        $lastAttempt = QuizAttempt::where('user_id', $userId)
            ->where('chapter_id', $chapterId)
            ->latest()
            ->first();

        return view('quiz.intro', [
            'chapterId' => $chapterId,
            'lastScore' => $lastAttempt ? $lastAttempt->score : null
        ]);
    }

    // =========================================================================
    // 2. START SESSION (MEMBUAT TOKEN SESI)
    // =========================================================================
    public function startSession(Request $request)
    {
        $userId = Auth::id();
        $chapterId = $request->chapter_id;

        // Double Check Kelulusan (Security)
        $bestScore = QuizAttempt::where('user_id', $userId)
            ->where('chapter_id', $chapterId)
            ->max('score');

        if ($bestScore >= 70) {
            return redirect()->route('dashboard');
        }

        // Cek duplikasi sesi aktif
        $existing = QuizAttempt::where('user_id', $userId)
            ->where('chapter_id', $chapterId)
            ->whereNull('completed_at')
            ->first();

        if (!$existing) {
            QuizAttempt::create($this->filterColumns('quiz_attempts', [
                'user_id' => $userId,
                'chapter_id' => $chapterId,
                'started_at' => Carbon::now(),
                'score' => 0,
                'time_spent_seconds' => 0,
                'completed_at' => null // Null menandakan sesi sedang berjalan
            ]));
        }

        return redirect()->route('quiz.show', ['chapterId' => $chapterId]);
    }

    // =========================================================================
    // 3. INTERFACE PENGERJAAN (SHOW)
    // =========================================================================
    public function show($chapterId)
    {
        $user = Auth::user();
        
        // Konfigurasi Durasi (Bisa diambil dari DB jika ada tabel chapters)
        $limitMinutes = 20; 
        $limitSeconds = $limitMinutes * 60;

        // Ambil Sesi Aktif
        $attempt = QuizAttempt::where('user_id', $user->id)
            ->where('chapter_id', $chapterId)
            ->whereNull('completed_at')
            ->first();

        // Jika tidak ada sesi, tendang ke intro
        if (!$attempt) {
            return redirect()->route('quiz.intro', ['chapterId' => $chapterId]);
        }

        // --- SERVER SIDE TIMER CALCULATION ---
        // Menghitung sisa waktu berdasarkan waktu server, bukan waktu browser user
        $startTime = Carbon::parse($attempt->created_at);
        $now = Carbon::now();
        $elapsed = $startTime->diffInSeconds($now);
        $remaining = $limitSeconds - $elapsed;

        // Jika waktu habis, paksa submit
        if ($remaining <= 0) {
            return $this->forceSubmit($attempt);
        }

        // Ambil Soal
        // Gunakan get() biasa agar urutan konsisten saat refresh jika ID berurutan
        // Atau gunakan inRandomOrder jika ingin acak (tapi hati-hati saat refresh)
        $questions = QuizQuestion::where('chapter_id', $chapterId)
            ->with('options')
            ->get(); 

        // --- FITUR STATE RESTORATION ---
        // Mengambil semua jawaban yang sudah tersimpan di DB
        // Format array key-nya adalah ID Soal, supaya di Blade mudah diakses
        $savedAnswers = QuizAttemptAnswer::where('quiz_attempt_id', $attempt->id)
            ->get()
            ->keyBy('quiz_question_id');

        return view('quiz.interface', [
            'questions' => $questions,
            'chapterId' => $chapterId,
            'remainingSeconds' => max(0, $remaining),
            'attemptId' => $attempt->id,
            'savedAnswers' => $savedAnswers // Kirim history jawaban ke View
        ]);
    }

    // =========================================================================
    // 4. REAL-TIME SAVE (VERSI PERBAIKAN TOTAL)
    // =========================================================================
    public function saveProgress(Request $request)
    {
        // 1. Validasi Input (Hapus 'nullable' di option_id validasi agar tidak conflict)
        $request->validate([
            'attempt_id'   => 'required|integer',
            'question_id'  => 'required|integer',
            'option_id'    => 'nullable|integer',
            'is_flagged'   => 'required|boolean',
            'client_elapsed_seconds' => 'nullable|integer|min:0'
        ]);

        try {
            // 2. Cek Validasi Sesi
            $attempt = QuizAttempt::find($request->attempt_id);
            if (!$attempt || $attempt->user_id != Auth::id()) {
                return response()->json(['status' => 'error', 'message' => 'Sesi tidak valid'], 403);
            }

            if ($attempt->completed_at) {
                return response()->json(['status' => 'error', 'message' => 'Evaluasi sudah dikumpulkan'], 422);
            }

            $question = QuizQuestion::find($request->question_id);
            if (!$question || (int) $question->chapter_id !== (int) $attempt->chapter_id) {
                return response()->json(['status' => 'error', 'message' => 'Soal tidak valid'], 422);
            }

            // 3. Persiapkan Data Jawaban
            $optionId = $request->option_id;
            $isCorrect = 0;

            // Jika user memilih jawaban (tidak kosong/null)
            if (!empty($optionId) && $optionId !== 'null') {
                // Cek Kunci Jawaban di Tabel Options
                $selectedOption = QuizOption::find($optionId);
                
                if ($selectedOption) {
                    // Pastikan opsi ini memang milik soal tersebut (Security Check)
                    if ($selectedOption->quiz_question_id == $request->question_id) {
                        $isCorrect = $selectedOption->is_correct ? 1 : 0;
                    }
                }
            } else {
                $optionId = null; // Pastikan tersimpan sebagai NULL database, bukan string "null"
            }

            $existingAnswer = QuizAttemptAnswer::where('quiz_attempt_id', $attempt->id)
                ->where('quiz_question_id', $request->question_id)
                ->first();

            $hasChangedAnswer = $existingAnswer
                && (
                    !empty($existingAnswer->quiz_option_id)
                    && !empty($optionId)
                    && (int) $existingAnswer->quiz_option_id !== (int) $optionId
                );

            $answerChangeCount = ($existingAnswer->answer_change_count ?? 0) + ($hasChangedAnswer ? 1 : 0);
            $hasResponse = !empty($optionId);

            $answerPayload = $this->filterColumns('quiz_attempt_answers', [
                'quiz_option_id' => $optionId,
                'is_flagged'     => $request->is_flagged,
                'is_correct'     => $isCorrect,
                'answer_change_count' => $answerChangeCount,
                'client_elapsed_seconds' => $request->client_elapsed_seconds,
                'first_answered_at' => $existingAnswer?->first_answered_at ?? ($hasResponse ? Carbon::now() : null),
                'last_answered_at' => $hasResponse ? Carbon::now() : ($existingAnswer?->last_answered_at),
            ]);

            // 4. SIMPAN KE DATABASE (Update jika ada, Create jika belum)
            // Menggunakan updateOrCreate agar tidak duplikat data per soal
            $answer = QuizAttemptAnswer::updateOrCreate(
                [
                    'quiz_attempt_id'  => $attempt->id, 
                    'quiz_question_id' => $request->question_id
                ],
                $answerPayload
            );

            return response()->json([
                'status' => 'success',
                'debug_info' => $answer // Mengembalikan data yang disimpan untuk cek di console browser
            ]);

        } catch (\Exception $e) {
            // Tampilkan error asli untuk debugging
            return response()->json([
                'status' => 'error', 
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    // =========================================================================
    // 5. SUBMIT FINAL (GRADING)
    // =========================================================================
    public function submit(Request $request)
    {
        $request->validate([
            'attempt_id' => 'required|integer',
            'time_spent' => 'nullable|integer|min:0',
            'focus_lost_count' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $attempt = QuizAttempt::find($request->attempt_id);
            
            // Security Check
            if (!$attempt || $attempt->user_id != Auth::id()) {
                throw new \Exception("Sesi ilegal.");
            }

            // Prevent Double Submit
            if ($attempt->completed_at != null) {
                return response()->json(['status' => 'success', 'redirect' => route('quiz.result', $attempt->id)]);
            }

            // --- LOGIKA PENILAIAN ---
            // Ambil semua jawaban yang tersimpan di DB untuk sesi ini
            $questions = QuizQuestion::where('chapter_id', $attempt->chapter_id)->get();
            $answers = QuizAttemptAnswer::where('quiz_attempt_id', $attempt->id)->get();
            
            // Hitung total soal (Ambil real dari tabel soal chapter ini)
            $totalQuestions = $questions->count();
            if ($totalQuestions == 0) {
                throw new \Exception("Soal evaluasi belum tersedia.");
            }

            $answeredCount = $answers->filter(fn ($answer) => $this->answerHasResponse($answer))
                ->unique('quiz_question_id')
                ->count();

            if ($answeredCount < $totalQuestions) {
                DB::rollBack();

                return response()->json([
                    'status' => 'error',
                    'message' => 'Evaluasi belum dapat dikumpulkan. Lengkapi ' . ($totalQuestions - $answeredCount) . ' soal yang masih kosong.',
                    'missing_count' => $totalQuestions - $answeredCount,
                ], 422);
            }

            // Kalkulasi Skor (Skala 100)
            $correctCount = $answers->where('is_correct', 1)->count();
            $score = round(($correctCount / $totalQuestions) * 100);
            $metrics = $this->buildAttemptMetrics($answers, $totalQuestions, $score);
            $feedback = $this->buildEvaluationFeedback($score, $metrics);

            // Finalisasi Data Sesi
            $attempt->update($this->filterColumns('quiz_attempts', [
                'score' => $score,
                'completed_at' => Carbon::now(),
                'time_spent_seconds' => $request->time_spent ?? 0,
                'answered_count' => $metrics['answered_count'],
                'unanswered_count' => $metrics['unanswered_count'],
                'flagged_count' => $metrics['flagged_count'],
                'focus_lost_count' => $request->focus_lost_count ?? 0,
                'feedback_level' => $feedback['level'],
                'feedback_message' => $feedback['message'],
            ]));

            DB::commit();

            return response()->json([
                'status' => 'success', 
                'score' => $score, 
                'redirect' => route('quiz.result', $attempt->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Quiz Submit Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // =========================================================================
    // 6. RESULT & FEEDBACK AKHIR EVALUASI
    // =========================================================================
    public function result($attemptId)
    {
        $attempt = QuizAttempt::with('answers')
            ->where('id', $attemptId)
            ->where('user_id', Auth::id())
            ->whereNotNull('completed_at')
            ->firstOrFail();

        $questions = QuizQuestion::where('chapter_id', $attempt->chapter_id)
            ->with('options')
            ->get();

        $answers = $attempt->answers->keyBy('quiz_question_id');
        $totalQuestions = max(1, $questions->count());
        $metrics = $this->buildAttemptMetrics($attempt->answers, $totalQuestions, (int) $attempt->score);
        $feedback = $this->buildEvaluationFeedback((int) $attempt->score, $metrics);
        $chapterSummary = ChapterSummary::for($attempt->chapter_id);
        $outcomeAnalytics = LearningOutcomeAnalytics::forQuizAttempt($questions, $answers, $attempt);

        $reviewItems = $questions->values()->map(function ($question, $index) use ($answers) {
            $answer = $answers->get($question->id);
            $selectedOption = $answer?->quiz_option_id
                ? $question->options->firstWhere('id', $answer->quiz_option_id)
                : null;
            $correctOption = $question->options->firstWhere('is_correct', true);
            $hasAnswer = $answer ? $this->answerHasResponse($answer) : false;

            return [
                'number' => $index + 1,
                'question' => $question,
                'answer' => $answer,
                'selected_option' => $selectedOption,
                'correct_option' => $correctOption,
                'is_correct' => (bool) ($answer?->is_correct),
                'is_flagged' => (bool) ($answer?->is_flagged),
                'has_answer' => $hasAnswer,
                'status' => $hasAnswer ? (($answer?->is_correct) ? 'Benar' : 'Perlu ditinjau') : 'Belum dijawab',
            ];
        });

        return view('quiz.result', [
            'attempt' => $attempt,
            'chapterId' => $attempt->chapter_id,
            'metrics' => $metrics,
            'feedback' => $feedback,
            'reviewItems' => $reviewItems,
            'chapterSummary' => $chapterSummary,
            'outcomeAnalytics' => $outcomeAnalytics,
        ]);
    }

    public function saveReflection(Request $request, $attemptId)
    {
        $validated = $request->validate([
            'reflection_note' => 'nullable|string|max:1000',
        ], [
            'reflection_note.max' => 'Catatan refleksi maksimal 1000 karakter.',
        ]);

        $attempt = QuizAttempt::where('id', $attemptId)
            ->where('user_id', Auth::id())
            ->whereNotNull('completed_at')
            ->firstOrFail();

        $attempt->update($this->filterColumns('quiz_attempts', [
            'reflection_note' => trim($validated['reflection_note'] ?? ''),
        ]));

        return redirect()
            ->route('quiz.result', $attempt->id)
            ->with('success', 'Catatan refleksi berhasil disimpan.');
    }

    // =========================================================================
    // 7. HELPER: FORCE SUBMIT (SAAT WAKTU HABIS)
    // =========================================================================
    private function forceSubmit($attempt) {
        // Logika sama dengan submit, tapi tanpa request dari user
        $questions = QuizQuestion::where('chapter_id', $attempt->chapter_id)->get();
        $answers = QuizAttemptAnswer::where('quiz_attempt_id', $attempt->id)->get();
        $totalQuestions = $questions->count();
        $correctCount = $answers->where('is_correct', 1)->count();
        
        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;
        $metrics = $this->buildAttemptMetrics($answers, max(1, $totalQuestions), $score);
        $feedback = $this->buildEvaluationFeedback($score, $metrics);
        $elapsed = Carbon::parse($attempt->created_at)->diffInSeconds(Carbon::now());

        $attempt->update($this->filterColumns('quiz_attempts', [
            'score' => $score,
            'completed_at' => Carbon::now(),
            'time_spent_seconds' => $elapsed,
            'answered_count' => $metrics['answered_count'],
            'unanswered_count' => $metrics['unanswered_count'],
            'flagged_count' => $metrics['flagged_count'],
            'feedback_level' => $feedback['level'],
            'feedback_message' => $feedback['message'],
        ]));
        
        return redirect()->route('quiz.result', $attempt->id)
            ->with('info', 'Waktu habis! Jawaban tersimpan otomatis dikumpulkan. Skor Anda: ' . $score);
    }

    private function buildAttemptMetrics($answers, int $totalQuestions, int $score): array
    {
        $answeredCount = $answers->filter(fn ($answer) => $this->answerHasResponse($answer))->count();
        $flaggedCount = $answers->where('is_flagged', true)->count();
        $wrongCount = $answers
            ->where('is_correct', false)
            ->filter(fn ($answer) => $this->answerHasResponse($answer))
            ->count();
        $changeCount = $answers->sum('answer_change_count');

        return [
            'total_questions' => $totalQuestions,
            'answered_count' => $answeredCount,
            'unanswered_count' => max(0, $totalQuestions - $answeredCount),
            'correct_count' => $answers->where('is_correct', true)->count(),
            'wrong_count' => $wrongCount,
            'flagged_count' => $flaggedCount,
            'answer_change_count' => $changeCount,
            'mastery_percent' => $score,
            'completion_percent' => $totalQuestions > 0 ? round(($answeredCount / $totalQuestions) * 100) : 0,
        ];
    }

    private function answerHasResponse(object|null $answer): bool
    {
        if (!$answer) {
            return false;
        }

        return !empty($answer->quiz_option_id);
    }

    private function buildEvaluationFeedback(int $score, array $metrics): array
    {
        if ($score >= 85) {
            $level = 'Sangat Baik';
            $message = 'Penguasaan materi sudah kuat. Pertahankan ritme belajar dan gunakan hasil ini untuk memperdalam bagian soal yang masih sempat ditandai ragu-ragu.';
        } elseif ($score >= 70) {
            $level = 'Lulus';
            $message = 'Anda sudah mencapai KKM. Agar pemahaman lebih stabil, tinjau kembali soal yang salah atau belum dijawab sebelum lanjut ke materi berikutnya.';
        } else {
            $level = 'Perlu Penguatan';
            $message = 'Skor belum mencapai KKM. Pelajari kembali materi bab ini, fokus pada soal yang salah, lalu ulangi evaluasi setelah latihan singkat.';
        }

        if (($metrics['unanswered_count'] ?? 0) > 0) {
            $message .= ' Masih ada ' . $metrics['unanswered_count'] . ' soal kosong; jadikan ini perhatian utama pada percobaan berikutnya.';
        }

        if (($metrics['flagged_count'] ?? 0) > 0) {
            $message .= ' Terdapat ' . $metrics['flagged_count'] . ' soal ditandai ragu-ragu, sehingga bagian tersebut layak ditinjau ulang.';
        }

        return compact('level', 'message');
    }

    private function filterColumns(string $table, array $attributes): array
    {
        return collect($attributes)
            ->filter(fn ($value, $column) => Schema::hasColumn($table, $column))
            ->all();
    }
}
