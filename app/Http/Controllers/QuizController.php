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
            QuizAttempt::create([
                'user_id' => $userId,
                'chapter_id' => $chapterId,
                'score' => 0,
                'time_spent_seconds' => 0,
                'created_at' => Carbon::now(),
                'completed_at' => null // Null menandakan sesi sedang berjalan
            ]);
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
    // 4. REAL-TIME SAVE (AJAX) - FITUR RAGU-RAGU & AUTO SAVE
    // =========================================================================
    public function saveProgress(Request $request)
    {
        // Validasi input
        $request->validate([
            'attempt_id' => 'required|integer',
            'question_id' => 'required|integer',
            'option_id' => 'nullable|integer', // Boleh null jika user hanya klik ragu-ragu tanpa jawab
            'is_flagged' => 'required|boolean' // Status Ragu-ragu (Mark for Review)
        ]);

        try {
            // Validasi kepemilikan sesi
            $attempt = QuizAttempt::find($request->attempt_id);
            if (!$attempt || $attempt->user_id != Auth::id() || $attempt->completed_at != null) {
                return response()->json(['status' => 'error', 'message' => 'Sesi tidak valid'], 403);
            }

            // Cek Kunci Jawaban (Disimpan tapi belum dihitung skor)
            $isCorrect = false;
            if ($request->option_id) {
                $option = QuizOption::find($request->option_id);
                // Pastikan opsi jawaban milik soal yang benar
                if ($option && $option->quiz_question_id == $request->question_id) {
                    $isCorrect = $option->is_correct;
                }
            }

            // SIMPAN KE DATABASE (Update or Create)
            // Logika: Cari baris data berdasarkan attempt_id dan question_id
            // Jika ada update, jika tidak buat baru.
            QuizAttemptAnswer::updateOrCreate(
                [
                    'quiz_attempt_id' => $attempt->id, 
                    'quiz_question_id' => $request->question_id
                ],
                [
                    'quiz_option_id' => $request->option_id, // Jawaban User
                    'is_correct' => $isCorrect,               // Status Benar/Salah (Hidden)
                    'is_flagged' => $request->is_flagged      // Status Ragu-ragu
                ]
            );

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error("Quiz Save Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan jawaban'], 500);
        }
    }

    // =========================================================================
    // 5. SUBMIT FINAL (GRADING)
    // =========================================================================
    public function submit(Request $request)
    {
        $request->validate([
            'attempt_id' => 'required|integer',
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
                return response()->json(['status' => 'success', 'redirect' => route('dashboard')]);
            }

            // --- LOGIKA PENILAIAN ---
            // Ambil semua jawaban yang tersimpan di DB untuk sesi ini
            $answers = QuizAttemptAnswer::where('quiz_attempt_id', $attempt->id)->get();
            
            // Hitung jumlah yang is_correct = 1
            $correctCount = $answers->where('is_correct', 1)->count();
            
            // Hitung total soal (Ambil real dari tabel soal chapter ini)
            $totalQuestions = QuizQuestion::where('chapter_id', $attempt->chapter_id)->count();
            if ($totalQuestions == 0) $totalQuestions = 10; // Fallback prevent division by zero

            // Kalkulasi Skor (Skala 100)
            $score = round(($correctCount / $totalQuestions) * 100);

            // Finalisasi Data Sesi
            $attempt->update([
                'score' => $score,
                'completed_at' => Carbon::now(),
                'time_spent_seconds' => $request->time_spent ?? 0 // Opsional dari frontend
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success', 
                'score' => $score, 
                'redirect' => route('dashboard')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Quiz Submit Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // =========================================================================
    // 6. HELPER: FORCE SUBMIT (SAAT WAKTU HABIS)
    // =========================================================================
    private function forceSubmit($attempt) {
        // Logika sama dengan submit, tapi tanpa request dari user
        $answers = QuizAttemptAnswer::where('quiz_attempt_id', $attempt->id)->get();
        $correctCount = $answers->where('is_correct', 1)->count();
        $totalQuestions = QuizQuestion::where('chapter_id', $attempt->chapter_id)->count();
        
        $score = ($totalQuestions > 0) ? round(($correctCount / $totalQuestions) * 100) : 0;

        $attempt->update([
            'score' => $score,
            'completed_at' => Carbon::now()
        ]);
        
        return redirect()->route('dashboard')
            ->with('info', 'Waktu habis! Jawaban tersimpan otomatis dikumpulkan. Skor Anda: ' . $score);
    }
}