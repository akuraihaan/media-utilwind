<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\UserLessonProgress;
use App\Models\UserActivityProgress;
use App\Models\QuizAttempt;

class CourseController extends Controller
{
    /**
     * =========================================================================
     * HELPER: Peta Status Kelulusan (Kunci Logika Sidebar & Akses)
     * =========================================================================
     */
    private function getChapterStatus($userId)
    {
        // 1. Ambil ID Lesson yang selesai
        $completedLessons = UserLessonProgress::where('user_id', $userId)
            ->where('completed', true)
            ->pluck('course_lesson_id')
            ->toArray();

        // 2. Ambil ID Activity yang selesai
        $completedActivities = UserActivityProgress::where('user_id', $userId)
            ->where('completed', true)
            ->pluck('course_activity_id')
            ->toArray();

        // 3. Ambil Kuis yang Selesai (PENTING UNTUK BUKA BAB 2)
        $completedQuizzes = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->pluck('chapter_id')
            ->map(fn($item) => (string)$item)
            ->toArray();

        // --- DEFINISI SYARAT KELULUSAN PER SUB-BAB ---
        
        // Helper cek kelengkapan
        $check = function($range, $actId) use ($completedLessons, $completedActivities) {
            $ids = is_array($range) ? $range : range($range[0], $range[1]);
            
            // Cek apakah semua lesson dalam range sudah selesai
            $lessonsDone = !empty($ids) && count(array_intersect($ids, $completedLessons)) == count($ids);
            
            // Cek apakah aktivitas coding sudah selesai
            $actDone = in_array($actId, $completedActivities);
            
            return $lessonsDone && $actDone;
        };

        // [BAB 1: PENDAHULUAN]
        $status['1.1'] = $check([1, 6], 1);
        $status['1.2'] = $check([7, 11], 2);
        $status['1.3'] = $check([12, 15], 3);
        $status['1.4'] = $check([16, 19], 4);
        $status['1.5'] = $check([20, 23], 5);
        $status['1.6'] = $check([24, 28], 6);
        $status['quiz_1'] = in_array('1', $completedQuizzes); // Syarat masuk Bab 2

        // [BAB 2: LAYOUTING]
        
        // 2.1 Flexbox (Lesson 29-32, Activity ID 7)
        $status['2.1'] = $check([29, 32], 7);

        // 2.2 Grid (Lesson 34-39, Activity ID 8)
        // Note: ID 33 dilewati karena itu ID lama/activity flexbox di DB Anda sebelumnya
        $status['2.2'] = $check([34, 40], 8); 
        // Lesson: 41-45, Activity: 9
        $status['2.3'] = $check([41, 45], 9);
        $status['quiz_2'] = in_array('2', $completedQuizzes);

        $status['3.1'] = $check([46, 51], 10);
        $status['3.2'] = $check([52, 55], 11);
        $status['3.3'] = $check([56, 59], 12);
        $status['3.4'] = $check([60, 63], 13);

        return $status;
    }

    /**
     * =========================================================================
     * HELPER: View Loader (Agar tidak copy-paste logic di setiap function)
     * =========================================================================
     */
    private function loadView($view, $currentKey, $lessonRange, $activityId, $requiredKey = null)
    {
        $userId = Auth::id();
        $course = Course::where('slug', 'tailwind-css')->firstOrFail();
        $statusMap = $this->getChapterStatus($userId);

        // 1. CEK PRASYARAT (Security Gate)
        // Jika prasyarat belum 'true', tendang user kembali
        if ($requiredKey && empty($statusMap[$requiredKey])) {
            // Redirect ke halaman sebelumnya yang relevan (misal dashboard atau bab sebelumnya)
            // Disini kita redirect ke dashboard saja agar aman
            return redirect()->route('dashboard')->with('error', 'Selesaikan materi sebelumnya untuk mengakses halaman ini!');
        }

        // 2. Hitung Progress Halaman Ini
        $targetIds = is_array($lessonRange) ? $lessonRange : range($lessonRange[0], $lessonRange[1]);
        
        $completedIds = UserLessonProgress::where('user_id', $userId)
            ->where('completed', true)
            ->pluck('course_lesson_id')
            ->toArray();

        $doneCount = count(array_intersect($targetIds, $completedIds));
        
        // Hitung persentase dasar dari materi bacaan
        $percent = count($targetIds) > 0 ? round(($doneCount / count($targetIds)) * 100) : 0;

        // Cek Activity Halaman Ini
        $actDone = UserActivityProgress::where('user_id', $userId)
            ->where('course_activity_id', $activityId)
            ->where('completed', true)
            ->exists();

        // Adjust Percent: 
        // - Jika materi bacaan 100% tapi Activity belum, set 90% (atau 99%)
        // - Jika materi bacaan 100% DAN Activity sudah, baru set 100%
        if ($percent == 100 && !$actDone) $percent = 90; 
        if ($percent >= 90 && $actDone) $percent = 100;
        // Kita ambil data Lessons dari DB agar judulnya dinamis sesuai yang kita input tadi
        $lessons = CourseLesson::whereIn('id', $targetIds)->orderBy('order')->get();
        // 3. Data Kuis untuk Sidebar
        $quizProgress = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->pluck('chapter_id')
            ->map(fn($item) => (string)$item)
            ->toArray();

        return view($view, [
            'course'              => $course,
            'lessons'             => $lessons,
            'progressPercent'     => $percent,
            'isCurrentCompleted'  => ($percent == 100),
            'completedLessonIds'  => $completedIds,
            'currentLessonIds'    => $targetIds,
            'activityCompleted'   => $actDone,
            'completedLessonsMap' => $statusMap, // Peta status untuk sidebar (checklist)
            'quizProgress'        => $quizProgress,
            
            // Variable legacy untuk kompatibilitas view lama
            'subbab1LessonIds'    => $targetIds, 
            'subbab12LessonIds'   => $targetIds, 
            'subbab13LessonIds'   => $targetIds, 
            'subbab14LessonIds'   => $targetIds, 
            'subbab15LessonIds'   => $targetIds, 
            'subbab16LessonIds'   => $targetIds, 


            // dst...
        ]);
    }

    /**
     * =========================================================================
     * HALAMAN COURSE (Route Handlers)
     * =========================================================================
     */

    // 1.1 Pendahuluan (Selalu Terbuka)
    public function tailwind() {
        return $this->loadView('courses.htmldancss', '1.1', [1, 6], 1, null);
    }

    // 1.2 Konsep Dasar (Syarat: 1.1 Selesai)
    public function subbabTailwindCss() {
        return $this->loadView('courses.tailwindcss', '1.2', [7,11], 2, '1.1');
    }

    // 1.3 Latar Belakang (Syarat: 1.2 Selesai)
    public function background() {
        return $this->loadView('courses.latarbelakang', '1.3', [12, 15], 3, '1.2');
    }

    // 1.4 Implementasi (Syarat: 1.3 Selesai)
    public function implementation() {
        return $this->loadView('courses.implementasi', '1.4', [16, 19], 4, '1.3');
    }

    // 1.5 Keunggulan (Syarat: 1.4 Selesai)
    public function advantages() {
        return $this->loadView('courses.keunggulan', '1.5', [20, 23], 5, '1.4');
    }

    // 1.6 Instalasi (Syarat: 1.5 Selesai)
    public function installation() {
        return $this->loadView('courses.instalasi', '1.6', [24, 28], 6, '1.5');
    }

    /**
     * BAB 2.1 FLEXBOX
     * SYARAT: Kuis Bab 1 (quiz_1) Harus Selesai!
     */
    public function flexbox() {
        return $this->loadView('courses.flexbox', '2.1', [29, 32], 7, 'quiz_1');
    }

    /**
     * BAB 2.2 GRID LAYOUT
     * SYARAT: Bab 2.1 (Flexbox) Harus Selesai
     * Range ID: 34-39 (Materi)
     * Activity ID: 8 (Tantangan Grid)
     */
    public function grid() {
        return $this->loadView(
            'courses.grid',      // View Name
            '2.2',               // Current Key Status
            [34, 40],            // Lesson IDs Range 
            8,                   // Activity ID (Pastikan ini ID Grid Challenge di DB activity Anda)
            '2.1'                // Prasyarat: Flexbox selesai
        );
    }

    public function layoutMgmt() {
        return $this->loadView(
            'courses.layout-mgmt', // View Blade Baru
            '2.3',                 // Key Status
            [41, 45],              // Range Lesson ID (41,42,43,44,45)
            9,                     // Activity ID (Tantangan Layout Master)
            '2.2'                  // Prasyarat: Grid Selesai
        );
    }

    public function typography() {

        return $this->loadView(
            'courses.typography', // Nama Blade View
            '3.1',                // Key Status
            [46, 51],             // Range Lesson ID
            10,               // Activity ID
            'quiz_2'                 // Prasyarat: Quiz 2 selesai
        );
    }

    public function backgrounds() {

        return $this->loadView(
            'courses.background', // Nama Blade View
            '3.2',                // Key Status
            [52, 55],             // Range Lesson ID
            11,               // Activity ID
            '3.1'                 // Prasyarat: Layout Management selesai
        );
    }

    public function borders() {
            return $this->loadView(
                'courses.borders',    // File blade
                '3.3',                // Key Status Sidebar
                [55, 59],             // Range Lesson ID
                12,                   // Activity ID
                '3.2'                 // Prasyarat: Background Selesai
            );
    }

    // BAB 3.4: EFEK VISUAL
    public function effects() {
        return $this->loadView(
            'courses.effects',    // Nama file blade
            '3.4',                // ID Sidebar
            [60, 63],         // ID Lessons (DB)
            13,                   // ID Activity (DB)
            '3.3'                 // Prasyarat: Bab Borders selesai
        );
    }
    /**
     * [AJAX] Mark Lesson Complete
     */
    public function completeLesson(Request $request)
    {
        $data = $request->validate([
            'lesson_id' => ['required', 'integer', 'exists:course_lessons,id'],
        ]);

        UserLessonProgress::updateOrCreate(
            ['user_id' => Auth::id(), 'course_lesson_id' => $data['lesson_id']],
            ['completed' => true]
        );

        return response()->json(['status' => 'ok']);
    }
}