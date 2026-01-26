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
            ->pluck('chapter_id') // Output: ['1', '2']
            ->map(fn($item) => (string)$item) // Pastikan string agar aman
            ->toArray();

        // --- DEFINISI SYARAT KELULUSAN PER SUB-BAB ---
        
        // Helper cek kelengkapan
        $check = function($range, $actId) use ($completedLessons, $completedActivities) {
            $ids = is_array($range) ? $range : range($range[0], $range[1]);
            $lessonsDone = !empty($ids) && count(array_intersect($ids, $completedLessons)) == count($ids);
            $actDone = in_array($actId, $completedActivities);
            return $lessonsDone && $actDone;
        };

        // Bab 1
        $status['1.1'] = $check([1, 5], 1);
        $status['1.2'] = $check([7, 11], 2);
        $status['1.3'] = $check([12, 15], 3);
        $status['1.4'] = $check([16, 19], 4);
        $status['1.5'] = $check([20, 23], 5);
        $status['1.6'] = $check([24, 28], 6);

        // Status Kuis Bab 1
        $status['quiz_1'] = in_array('1', $completedQuizzes);

        // Bab 2 (Syaratnya Kuis 1 Selesai + Materi Bab 2 sendiri selesai)
        $status['2.1'] = $check([29, 32], 7);

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
        // Jika ada prasyarat (misal '1.1') dan belum selesai, tendang user
        if ($requiredKey && empty($statusMap[$requiredKey])) {
            return redirect()->route('courses.htmldancss')->with('error', 'Selesaikan materi/evaluasi sebelumnya!');
        }

        // 2. Hitung Progress Halaman Ini
        $lessons = CourseLesson::where('course_id', $course->id)->orderBy('order')->get();
        $targetIds = is_array($lessonRange) ? $lessonRange : range($lessonRange[0], $lessonRange[1]);
        
        $completedIds = UserLessonProgress::where('user_id', $userId)
            ->where('completed', true)
            ->pluck('course_lesson_id')
            ->toArray();

        $doneCount = count(array_intersect($targetIds, $completedIds));
        $percent = count($targetIds) > 0 ? round(($doneCount / count($targetIds)) * 100) : 0;

        // Cek Activity Halaman Ini
        $actDone = UserActivityProgress::where('user_id', $userId)
            ->where('course_activity_id', $activityId)
            ->where('completed', true)
            ->exists();

        // Adjust Percent based on Activity
        if ($percent == 100 && !$actDone) $percent = 99;
        if ($percent >= 99 && $actDone) $percent = 100;

        // 3. Data Kuis untuk Sidebar
        $quizProgress = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->pluck('chapter_id')
            ->toArray();

        return view($view, [
            'course'              => $course,
            'progressPercent'     => $percent,
            'isCurrentCompleted'  => ($percent == 100),
            'completedLessonIds'  => $completedIds,
            'currentLessonIds'    => $targetIds,
            'activityCompleted'   => $actDone,
            'completedLessonsMap' => $statusMap, // Kirim map lengkap ke sidebar
            'quizProgress'        => $quizProgress, // Kirim status kuis ke sidebar
            // Kompatibilitas variabel lama jika di view masih dipakai
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
        return $this->loadView('courses.htmldancss', '1.1', [1, 5], 1, null);
    }

    // 1.2 Konsep Dasar (Syarat: 1.1 Selesai)
    public function subbabTailwindCss() {
        return $this->loadView('courses.tailwindcss', '1.2', [6, 10], 2, '1.1');
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
     * SYARAT UTAMA: Evaluasi Bab 1 Harus Selesai!
     */
    public function flexbox() {
        // Kita menggunakan key khusus 'quiz_1' yang kita buat di getChapterStatus
        // Jika quiz_1 false, maka akses ditolak.
        return $this->loadView('courses.flexbox', '2.1', [29, 32], 7, 'quiz_1');
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