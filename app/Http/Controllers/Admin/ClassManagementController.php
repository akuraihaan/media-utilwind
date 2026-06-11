<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassGroup;
use App\Models\CourseLesson;
use App\Models\Lab;
use Illuminate\Http\Request;
use App\Models\User; // <--- WAJIB TAMBAHKAN INI
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClassManagementController extends Controller
{
    public function index()
    {
        // 1. Ambil data kelas beserta relasi siswanya
        $classesRaw = \App\Models\ClassGroup::with('students')->orderBy('name', 'asc')->get();
        $minimumQuizScore = 70;
        $totalLessons = CourseLesson::count();
        $totalLabs = Lab::where('is_active', 1)->count();
        $totalQuizChapters = DB::table('quiz_questions')->select('chapter_id')->distinct()->pluck('chapter_id')->count();
        $totalItemsEstimasi = max(1, $totalLessons + $totalLabs + $totalQuizChapters);

        // 2. Mapping data untuk menghitung Analitik Super Detail per Kelas & per Siswa
        $classes = $classesRaw->map(function($class) use ($totalItemsEstimasi, $minimumQuizScore) {
            $students = $class->students;
            $studentIds = $students->pluck('id');
            
            // Tarik semua data log aktivitas siswa di kelas ini secara massal (Optimalisasi Query)
            $quizAttempts = DB::table('quiz_attempts')->whereIn('user_id', $studentIds)->get();
            $labHistories = DB::table('lab_histories')->whereIn('user_id', $studentIds)->where('status', 'passed')->get();
            $lessonProgress = DB::table('user_lesson_progress')->whereIn('user_id', $studentIds)->where('completed', true)->get();

            // Susun Detail Tiap Siswa
            $studentsList = $students->map(function($s) use ($quizAttempts, $labHistories, $lessonProgress, $totalItemsEstimasi, $minimumQuizScore) {
                // A. Kuis
                $userQuizzes = $quizAttempts->where('user_id', $s->id);
                $quizAttemptsCount = $userQuizzes->count();
                $avgScore = $quizAttemptsCount > 0 ? $userQuizzes->avg('score') : 0;
                $passedQuizzesCount = $userQuizzes
                    ->groupBy('chapter_id')
                    ->map(fn($att) => $att->max('score'))
                    ->filter(fn($score) => $score >= $minimumQuizScore)
                    ->count();
                $effectivePassedQuizzesCount = $avgScore >= $minimumQuizScore ? $passedQuizzesCount : 0;

                // B. Lab & Materi
                $userLabsCount = $labHistories->where('user_id', $s->id)->unique('lab_id')->count();
                $userLessonsCount = $lessonProgress->where('user_id', $s->id)->count();

                // C. Kalkulasi Global Progress
                $totalDone = $userLessonsCount + $userLabsCount + $effectivePassedQuizzesCount;
                $progressPct = ($totalItemsEstimasi > 0) ? round(($totalDone / $totalItemsEstimasi) * 100) : 0;
                $progressPct = min($progressPct, 100); // Max 100%
                $hasAnyProgress = ($userLessonsCount + $userLabsCount + $passedQuizzesCount + $quizAttemptsCount) > 0;

                if ($quizAttemptsCount > 0 && $avgScore < $minimumQuizScore) {
                    $statusLabel = 'Remedial Kuis';
                    $statusTone = 'red';
                } elseif ($progressPct >= 100) {
                    $statusLabel = 'Tuntas';
                    $statusTone = 'emerald';
                } elseif ($hasAnyProgress) {
                    $statusLabel = 'Dalam Proses';
                    $statusTone = 'amber';
                } else {
                    $statusLabel = 'Belum Mulai';
                    $statusTone = 'slate';
                }

                return [
                    'id' => $s->id,
                    'name' => $s->name,
                    'email' => $s->email,
                    'avg_score' => round($avgScore, 1),
                    'quiz_attempts' => $quizAttemptsCount,
                    'lessons_done' => $userLessonsCount,
                    'labs_done' => $userLabsCount,
                    'quizzes_passed' => $effectivePassedQuizzesCount,
                    'progress_pct' => $progressPct,
                    'status_label' => $statusLabel,
                    'status_tone' => $statusTone,
                ];
            })->sortByDesc('progress_pct')->values()->toArray(); // Diurutkan dari Progress Tertinggi

            // Total Aggregate Kelas
            $avgQuiz = $quizAttempts->avg('score') ?? 0;
            $totalLabPasses = $labHistories->count();
            
            return [
                'id' => $class->id,
                'name' => $class->name,
                'major' => $class->major,
                'token' => $class->token,
                'is_active' => $class->is_active,
                'students_count' => $studentIds->count(),
                'avg_quiz' => round($avgQuiz, 1),
                'lab_passes' => $totalLabPasses,
                'students_list' => $studentsList,
            ];
        })->toArray();

        $totalClasses = count($classes);
        $totalActive = collect($classes)->where('is_active', 1)->count();
        $totalStudents = collect($classes)->sum('students_count');

        return view('admin.class_management', compact('classes', 'totalClasses', 'totalActive', 'totalStudents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:class_groups,name|max:255',
            'major' => 'nullable|string|max:255',
        ]);

        ClassGroup::create([
            'name' => $request->name,
            'major' => $request->major,
            'token' => $this->generateUniqueToken(),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Kelas berhasil ditambahkan beserta Token barunya!');
    }

 public function update(Request $request, $id)
    {
        $class = \App\Models\ClassGroup::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:class_groups,name,'.$id,
            'major' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        // Begitu kode ini dieksekusi, Model ClassGroup akan otomatis
        // menjalankan fungsi booted() di atas dan meng-update tabel users!
        $class->update([
            'name' => trim($request->name),
            'major' => $request->major,
            'is_active' => $request->is_active,
        ]);

        return redirect()->back()->with('success', 'Data kelas berhasil diperbarui dan disinkronkan!');
    }

    public function destroy($id)
    {
        $class = \App\Models\ClassGroup::findOrFail($id);
        
        // Begitu kode delete() dieksekusi, user yang ada di kelas ini 
        // otomatis akan di-NULL-kan class_group-nya
        $class->delete();

        return redirect()->back()->with('success', 'Kelas berhasil dihapus!');
    }

    public function regenerateToken($id)
    {
        $class = ClassGroup::findOrFail($id);
        $class->update([
            'token' => $this->generateUniqueToken()
        ]);

        return redirect()->back()->with('success', 'Token keamanan berhasil diperbarui!');
    }

    // Private helper untuk generate token 6 karakter alphanumeric kapital
    private function generateUniqueToken()
    {
        do {
            $token = strtoupper(Str::random(6));
        } while (ClassGroup::where('token', $token)->exists());
        
        return $token;
    }
}
