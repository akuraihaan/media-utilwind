<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\UserLessonProgress;
use App\Models\UserActivityProgress;
use App\Models\QuizAttempt;
use App\Models\LabHistory;
use App\Models\Lab;
use App\Models\LabSession;

class CourseController extends Controller
{
    /**
     * Helper: Menentukan Status Kunci/Buka Setiap Bab
     * Syarat Buka Bab Selanjutnya: Kuis Bab sebelumnya harus LULUS (Score >= 70).
     */
    private function getChapterStatus($userId) {
        $completedLessons = UserLessonProgress::where('user_id', $userId)->where('completed', true)->pluck('course_lesson_id')->toArray();
        $completedActivities = UserActivityProgress::where('user_id', $userId)->where('completed', true)->pluck('course_activity_id')->toArray();
        
        // Ambil ID Chapter yang Kuisnya LULUS (Score >= 70)
        $passedChapters = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->select('chapter_id', 'score')
            ->get()
            ->groupBy('chapter_id')
            ->map(fn($rows) => $rows->max('score')) // Ambil skor tertinggi
            ->filter(fn($score) => $score >= 70)   // Filter hanya yang lulus
            ->keys()
            ->map(fn($id) => (string)$id)
            ->toArray();

        // Helper Check Range Lesson
        $check = function($range, $actId) use ($completedLessons, $completedActivities) {
            if (in_array($actId, $completedActivities)) return true;
            $lastId = is_array($range) && count($range) == 2 ? $range[1] : (is_array($range) ? end($range) : $range);
            return in_array($lastId, $completedLessons);
        };

        // --- BAB 1 ---
        $status['1.1'] = $check([1, 6], 1);
        $status['1.2'] = $check([7, 11], 2);
        $status['1.3'] = $check([12, 15], 3);
        $status['1.4'] = $check([16, 19], 4);
        $status['1.5'] = $check([20, 23], 5);
        $status['1.6'] = $check([24, 28], 6);
        $status['quiz_1'] = in_array('1', $passedChapters); // Syarat Buka Bab 2

        // --- BAB 2 ---
        $status['2.1'] = $check([29, 33], 7);
        $status['2.2'] = $check([34, 40], 8);
        $status['2.3'] = $check([41, 45], 9);
        $status['quiz_2'] = in_array('2', $passedChapters); // Syarat Buka Bab 3

        // --- BAB 3 ---
        $status['3.1'] = $check([46, 51], 10);
        $status['3.2'] = $check([52, 55], 11);
        $status['3.3'] = $check([56, 59], 12);
        $status['3.4'] = $check([60, 65], 13);

        return $status;
    }

    /**
     * Main View Loader
     */
    private function loadView($view, $currentKey, $lessonRange, $activityId, $requiredKey = null)
    {
        $userId = Auth::id();
        $course = Course::where('slug', 'tailwind-css')->firstOrFail(); 
        $statusMap = $this->getChapterStatus($userId);

        if ($requiredKey && empty($statusMap[$requiredKey])) {
            return redirect()->route('dashboard')->with('error', 'Selesaikan materi/kuis sebelumnya untuk mengakses halaman ini!');
        }

        // Logic Progress Bar
        $targetIds = (is_array($lessonRange) && count($lessonRange) == 2) ? range($lessonRange[0], $lessonRange[1]) : $lessonRange;
        $completedIds = UserLessonProgress::where('user_id', $userId)->where('completed', true)->pluck('course_lesson_id')->toArray();
        $doneCount = count(array_intersect($targetIds, $completedIds));
        $percent = count($targetIds) > 0 ? round(($doneCount / count($targetIds)) * 100) : 0;
        
        $actDone = UserActivityProgress::where('user_id', $userId)->where('course_activity_id', $activityId)->where('completed', true)->exists();
        if ($actDone) $percent = 100;

        $lessons = CourseLesson::whereIn('id', $targetIds)->orderBy('order')->get();

        // -----------------------------------------------------------
        // 1. DATA KUIS (Resume & Score Logic)
        // -----------------------------------------------------------
        $allLabs = Lab::where('is_active', 1)->get();
        $labsByChapter = $allLabs->groupBy('chapter_id');
        // A. Skor Tertinggi (Hanya yang sudah submit)
        $quizScores = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('completed_at') 
            ->select('chapter_id', 'score')
            ->get()
            ->groupBy('chapter_id')
            ->map(fn($rows) => $rows->max('score')) 
            ->toArray();

        // B. Sesi Aktif (Untuk tombol RESUME)
        // Cari attempt yang completed_at masih NULL
        $activeQuizSessions = QuizAttempt::where('user_id', $userId)
            ->whereNull('completed_at') 
            ->pluck('id', 'chapter_id') 
            ->toArray();

        // -----------------------------------------------------------
        // 2. DATA LAB
        // -----------------------------------------------------------
        $completedLabs = LabHistory::where('user_id', $userId)
            ->join('labs', 'lab_histories.lab_id', '=', 'labs.id')
            ->select('labs.slug', 'lab_histories.final_score')
            ->get()
            ->groupBy('slug')
            ->map(fn($rows) => $rows->max('final_score'))
            ->toArray();

        $activeLabSessions = LabSession::where('user_id', $userId)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->with('lab')
            ->get()
            ->mapWithKeys(fn($s) => [$s->lab->slug => $s->id])
            ->toArray();

        return view($view, [
            'course' => $course, 
            'lessons' => $lessons, 
            'progressPercent' => $percent,
            'isCurrentCompleted' => ($percent == 100), 
            'completedLessonIds' => $completedIds,
            'currentLessonIds' => $targetIds, 
            'activityCompleted' => $actDone,
            'completedLessonsMap' => $statusMap, 
            
            // Lab Data
            'labsByChapter' => $labsByChapter,
            'completedLabs' => $completedLabs,
            'activeSessions' => $activeLabSessions,

            // Quiz Data
            'quizScores' => $quizScores,
            'activeQuizSessions' => $activeQuizSessions 
        ]);
    }

    // --- Route Handlers ---
    public function tailwind() { return $this->loadView('courses.htmldancss', '1.1', [1, 6], 1, null); }
    public function subbabTailwindCss() { return $this->loadView('courses.tailwindcss', '1.2', [7, 11], 2, '1.1'); }
    public function background() { return $this->loadView('courses.latarbelakang', '1.3', [12, 15], 3, '1.2'); }
    public function implementation() { return $this->loadView('courses.implementasi', '1.4', [16, 19], 4, '1.3'); }
    public function advantages() { return $this->loadView('courses.keunggulan', '1.5', [20, 23], 5, '1.4'); }
    public function installation() { return $this->loadView('courses.instalasi', '1.6', [24, 28], 6, '1.5'); }
    public function flexbox() { return $this->loadView('courses.flexbox', '2.1', [29, 32], 7, 'quiz_1'); }
    public function grid() { return $this->loadView('courses.grid', '2.2', [34, 40], 8, '2.1'); }
    public function layoutMgmt() { return $this->loadView('courses.layout-mgmt', '2.3', [41, 45], 9, '2.2'); }
    public function typography() { return $this->loadView('courses.typography', '3.1', [46, 51], 10, 'quiz_2'); }
    public function backgrounds() { return $this->loadView('courses.background', '3.2', [52, 55], 11, '3.1'); }
    public function borders() { return $this->loadView('courses.borders', '3.3', [56, 59], 12, '3.2'); }
    public function effects() { return $this->loadView('courses.effects', '3.4', [60, 65], 13, '3.3'); }
    
    public function completeLesson(Request $request) {
       $data = $request->validate(['lesson_id' => 'required|integer|exists:course_lessons,id']);
        $user = Auth::user();

        // 1. Cek apakah slide ini sudah pernah diselesaikan sebelumnya
        $alreadyCompleted = UserLessonProgress::where('user_id', $user->id)
                                              ->where('course_lesson_id', $data['lesson_id'])
                                              ->where('completed', true)
                                              ->exists();

        // 2. Tandai slide sebagai selesai di database
        UserLessonProgress::updateOrCreate(
            ['user_id' => $user->id, 'course_lesson_id' => $data['lesson_id']], 
            ['completed' => 1]
        );

        // 3. LOGIKA GAMIFIKASI: Berikan XP hanya jika slide INI BARU PERTAMA KALI diselesaikan
        if (!$alreadyCompleted) {
            // Berikan 10 XP setiap kali membaca 1 halaman materi baru
            $user->increment('xp', 10);
            
            // Opsional: Anda bisa menambahkan logika pengecekan Badge disini jika ada badge spesifik
            // $this->checkBadges($user);
            
            // Kembalikan response JSON bahwa user dapat XP
            return response()->json([
                'status' => 'ok', 
                'gained_xp' => 10, 
                'total_xp' => $user->xp 
            ]);
        }

        // Jika sudah pernah baca (mengulang bacaan), tidak dapat XP lagi
        return response()->json(['status' => 'ok', 'gained_xp' => 0]);
    }

    // Di dalam class CourseController extends Controller

// Di dalam CourseController.php

public function showSyllabus()
{
    $userId = Auth::id();

    // 1. Status Materi & Kuis (Logika Lama)
    $statusMap = $this->getChapterStatus($userId);

    // 2. Status Lab (BARU: Ambil Lab yang sudah LULUS)
    // Mengambil daftar ID Lab yang statusnya 'passed' untuk user ini
    $passedLabsMap = \App\Models\LabHistory::where('user_id', $userId)
        ->where('status', 'passed')
        ->pluck('lab_id') // Misal: [1, 2] artinya Lab ID 1 dan 2 sudah lulus
        ->flip() // Ubah jadi key: [1 => true, 2 => true] agar mudah dicek di Blade
        ->toArray();

    // 3. Hitung Progress Global (Opsional, penyempurnaan)
    $totalSteps = 19; // 13 Materi + 3 Lab + 3 Kuis
    
    // Hitung item materi selesai
    $completedLessons = count(array_filter($statusMap, fn($val, $key) => $val === true && !str_contains($key, 'quiz'), ARRAY_FILTER_USE_BOTH));
    
    // Hitung lab selesai
    $completedLabs = count($passedLabsMap);
    
    // Hitung kuis selesai
    $completedQuizzes = count(array_filter($statusMap, fn($val, $key) => $val === true && str_contains($key, 'quiz'), ARRAY_FILTER_USE_BOTH));

    $totalCompleted = $completedLessons + $completedLabs + $completedQuizzes;
    $progressPercent = $totalSteps > 0 ? round(($totalCompleted / $totalSteps) * 100) : 0;

    return view('courses.curriculum', [
        'completedLessonsMap' => $statusMap,
        'passedLabsMap'       => $passedLabsMap, // Kirim data lab ke view
        'progressPercent'     => $progressPercent
    ]);
}
}