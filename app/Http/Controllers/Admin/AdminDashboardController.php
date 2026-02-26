<?php

namespace App\Http\Controllers\Admin; // Perhatikan namespace ini

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserLessonProgress;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use Illuminate\Support\Str;
// use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\LabHistory;
use App\Models\Lab;
use App\Models\CourseLesson;
use App\Models\UserActivityProgress;
use App\Models\ClassGroup;

class AdminDashboardController extends Controller
{
    /**
     * DASHBOARD UTAMA
     */
    public function index()
    {
        // 1. DATA STATISTIK
        $totalStudents = User::where('role', 'student')->count();
        $totalAttempts = DB::table('quiz_attempts')->count();
        
        // Handle null value dengan benar
        $globalAverage = round(DB::table('quiz_attempts')->avg('score') ?? 0, 1);
        $remedialCount = DB::table('quiz_attempts')->where('score', '<', 70)->count();

        // [TAMBAHAN] Data Ringkasan Lab (Pastikan tabel 'lab_sessions' ada)
        // Gunakan try-catch atau pengecekan schema jika tabel ini opsional
        $totalLabsCompleted = 0;
        try {
            $totalLabsCompleted = DB::table('lab_sessions')->where('status', 'completed')->count();
        } catch (\Exception $e) {
            // Abaikan jika tabel belum ada
        }

        // 2. DATA CHART (7 Hari Terakhir)
        $chartDataRaw = DB::table('quiz_attempts')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('AVG(score) as avg_score'))
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartLabels = $chartDataRaw->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'));
        $chartScores = $chartDataRaw->pluck('avg_score')->map(fn($s) => round($s));

        // 3. ANALISIS SOAL (Top 10 - Butuh Tabel quiz_questions & quiz_attempt_answers)
        $questionStats = collect([]); // Default empty collection
        try {
            $questionStats = DB::table('quiz_questions as q')
                ->leftJoin('quiz_attempt_answers as a', 'q.id', '=', 'a.quiz_question_id')
                ->select(
                    'q.id', 'q.question_text', 'q.chapter_id',
                    DB::raw('count(a.id) as total_answers'),
                    DB::raw('sum(case when a.is_correct = 1 then 1 else 0 end) as correct_count')
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
        } catch (\Exception $e) {
            // Handle error query jika tabel belum lengkap
        }

        // 4. LEADERBOARD & RECENT
        $topStudents = User::where('role', 'student')
            ->join('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
            ->select('users.id', 'users.name', 'users.email', DB::raw('AVG(quiz_attempts.score) as avg_score'))
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('avg_score')
            ->limit(5)
            ->get();

        $recentActivities = QuizAttempt::with('user')->latest()->take(5)->get();
        $availableClasses = ClassGroup::where('is_active', true)->orderBy('name', 'asc')->get();
        
        // 5. DATA USER MANAGEMENT
        $users = User::orderByDesc('created_at')->limit(50)->get(); // Limit agar tidak berat

        return view('admin.dashboard', compact(
            'totalStudents', 'totalAttempts', 'globalAverage', 'remedialCount', 'totalLabsCompleted',
            'chartLabels', 'chartScores', 'questionStats', 'topStudents', 'recentActivities', 'users',
'availableClasses'
            
        ));

        
    }

    /**
     * FITUR 1: TAMBAH USER MANUAL
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'class_group' => 'required|in:A1,A2,A3',
            'institution' => 'nullable|string|max:100',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            // HAPUS INI:
// 'class_group' => 'required|in:A1,A2,A3',

// GANTI JADI INI:
'class_group' => 'nullable|exists:class_groups,name',
            'institution' => $request->institution,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Siswa berhasil ditambahkan!');
    }

    /**
     * FITUR 2: IMPORT CSV
     */
    public function importUsers(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');
        fgetcsv($handle); // Skip header

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                // Format CSV: Name, Email, Class, Institution, Password
                User::updateOrCreate(
                    ['email' => $row[1]], 
                    [
                        'name' => $row[0],
                        'class_group' => $row[2] ?? null, // Ambil data kelas dari kolom ke-3 CSV
                        'institution' => $row[3] ?? null,
                        'password' => Hash::make($row[4] ?? 'password123'),
                        'role' => 'student',
                        'email_verified_at' => now(), // Otomatis verified agar bisa login
                    ]
                );
            }
            DB::commit();
            return redirect()->back()->with('success', 'Import berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

   /**
     * EXPORT PDF
     */
   // Ganti method exportPdf yang lama dengan ini
    public function exportPdf()
    {
        // Ambil data siswa (sama seperti sebelumnya)
        $students = \App\Models\User::where('role', 'student')
            ->orderBy('class_group')
            ->orderBy('name')
            ->get();

        // Return view biasa (Browser yang akan convert ke PDF)
        return view('admin.exports.students_print', compact('students'));
    }

    /**
     * EXPORT PDF SPESIFIK SISWA (MENGGUNAKAN METODE VIEW NATIVE)
     */
    public function exportStudentPdf($id)
    {
        $user = User::findOrFail($id);
        
        // 1. Data Riwayat Lab
        $labHistories = DB::table('lab_histories')
            ->join('labs', 'lab_histories.lab_id', '=', 'labs.id')
            ->where('lab_histories.user_id', $id)
            ->select('labs.title', 'lab_histories.lab_id', 'lab_histories.status', 'lab_histories.final_score', 'lab_histories.duration_seconds', 'lab_histories.created_at')
            ->orderByDesc('lab_histories.created_at')
            ->get();
            
        // 2. Data Riwayat Kuis
        $quizAttempts = DB::table('quiz_attempts')
            ->where('user_id', $id)
            ->orderByDesc('created_at')
            ->get();

        // 3. Data Progress Materi (Lesson)
        $completedLessonIds = DB::table('user_lesson_progress')
            ->where('user_id', $id)
            ->where('completed', true)
            ->pluck('course_lesson_id')
            ->toArray();

        // 4. Kalkulasi Map Kelulusan untuk Tracker
        $passedLabIds = $labHistories->where('status', 'passed')->pluck('lab_id')->unique()->toArray();
        
        $quizScoresMap = $quizAttempts->groupBy('chapter_id')
            ->mapWithKeys(function ($attempts, $chapterId) {
                return ['quiz_' . $chapterId => $attempts->max('score')];
            })
            ->toArray();

        // 5. Kalkulasi Global Progress
        $totalItemsEstimasi = 73; // Sesuai dengan estimasi di halaman detail Anda
        $countLessons = count($completedLessonIds);
        $countLabs = count($passedLabIds);
        $countQuizzes = count(array_filter($quizScoresMap, fn($s) => $s >= 70));
        
        $totalDone = $countLessons + $countLabs + $countQuizzes;
        $globalProgress = ($totalItemsEstimasi > 0) ? round(($totalDone / $totalItemsEstimasi) * 100) : 0;
        $globalProgress = min($globalProgress, 100);

        return view('admin.exports.student_detail_print', compact(
            'user', 'labHistories', 'quizAttempts', 
            'completedLessonIds', 'passedLabIds', 'quizScoresMap', 'globalProgress'
        ));
    }
    /**
     * EXPORT CSV (Update agar lebih rapi)
     */
    public function exportUsers()
    {
        $users = User::where('role', 'student')->get();
        $csvFileName = 'data_siswa_'.date('Y-m-d').'.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nama Lengkap', 'Email', 'Kelas', 'Institusi', 'Terdaftar']); // Header Bahasa Indonesia

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->name, 
                    $user->email, 
                    $user->class_group ?? '-', 
                    $user->institution ?? '-', 
                    $user->created_at->format('Y-m-d')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

      /**
     * EXPORT CSV SPESIFIK SISWA (DARI DETAIL)
     */
    public function exportStudentCsv($id)
    {
        $user = User::findOrFail($id);
        
        // Ambil Data Lab
        $labHistories = DB::table('lab_histories')
            ->join('labs', 'lab_histories.lab_id', '=', 'labs.id')
            ->where('lab_histories.user_id', $id)
            ->select('labs.title', 'lab_histories.status', 'lab_histories.final_score', 'lab_histories.duration_seconds', 'lab_histories.created_at')
            ->orderByDesc('lab_histories.created_at')
            ->get();
            
        // Ambil Data Kuis
        $quizAttempts = DB::table('quiz_attempts')
            ->where('user_id', $id)
            ->orderByDesc('created_at')
            ->get();

        $response = new StreamedResponse(function() use ($user, $labHistories, $quizAttempts) {
            $handle = fopen('php://output', 'w');
            
            // 1. Tulis Profil Siswa
            fputcsv($handle, ['--- PROFIL SISWA ---']);
            fputcsv($handle, ['Nama Lengkap', $user->name]);
            fputcsv($handle, ['Email', $user->email]);
            fputcsv($handle, ['Kelas', $user->class_group ?? 'Tidak ada data']);
            fputcsv($handle, ['Institusi', $user->institution ?? 'Tidak ada data']);
            fputcsv($handle, []); // Baris Kosong Pemisah
            
            // 2. Tulis Riwayat Lab
            fputcsv($handle, ['--- RIWAYAT PRAKTIKUM LAB ---']);
            fputcsv($handle, ['Nama Modul', 'Status', 'Skor Akhir', 'Durasi (Detik)', 'Tanggal Dikerjakan']);
            
            if ($labHistories->isEmpty()) {
                fputcsv($handle, ['Belum ada riwayat pengerjaan lab']);
            } else {
                foreach ($labHistories as $lab) {
                    fputcsv($handle, [
                        $lab->title, 
                        strtoupper($lab->status), 
                        $lab->final_score, 
                        $lab->duration_seconds, 
                        $lab->created_at
                    ]);
                }
            }
            fputcsv($handle, []); // Baris Kosong Pemisah

            // 3. Tulis Riwayat Kuis
            fputcsv($handle, ['--- RIWAYAT EVALUASI TEORI (KUIS) ---']);
            fputcsv($handle, ['Bab Evaluasi', 'Status', 'Skor', 'Tanggal Dikerjakan']);
            
            if ($quizAttempts->isEmpty()) {
                fputcsv($handle, ['Belum ada riwayat pengerjaan kuis']);
            } else {
                foreach ($quizAttempts as $quiz) {
                    $status = $quiz->score >= 70 ? 'LULUS' : 'GAGAL';
                    $babName = $quiz->chapter_id == 99 ? 'Evaluasi Akhir' : 'Bab ' . $quiz->chapter_id;
                    fputcsv($handle, [
                        $babName, 
                        $status, 
                        $quiz->score, 
                        $quiz->created_at
                    ]);
                }
            }
            fclose($handle);
        });

        // Set Header untuk Download File
        $fileName = 'Laporan_Siswa_' . Str::slug($user->name) . '_' . date('Ymd_His') . '.csv';
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$fileName.'"');

        return $response;
    }
/**
     * UPDATE STUDENT PROFILE (FULL CRUD)
     */
    public function updateStudent(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // 1. Validasi Input
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,'.$id, // Ignore current user email
            // HAPUS INI:
// 'class_group' => 'nullable|in:A1,A2,A3',

// GANTI JADI INI:
// PASTIKAN BEGINI (Jangan ada in:A1,A2,A3)
'class_group' => 'nullable|string|max:255',
            'institution'   => 'nullable|string|max:255',
            'study_program' => 'nullable|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'password'      => 'nullable|string|min:6', // Nullable: hanya update jika diisi
            'avatar'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        // 2. Handle Password (Jika diisi)
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']); // Jangan update jika kosong
        }

        // 3. Handle Avatar Upload
        if ($request->hasFile('avatar')) {
     

            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/avatars'), $filename);
            $validated['avatar'] = $filename;
        }

        // 4. Update Database
        $user->update($validated);

        return redirect()->back()->with('success', 'Profil siswa berhasil diperbarui!');
    }
    /**
     * CRUD USER METHODS
     */
    public function updateUser(Request $request, $id) {
        User::findOrFail($id)->update($request->only('name', 'email', 'role'));
        return response()->json(['status' => 'success']);
    }

    public function resetPassword(Request $request, $id) {
        $request->validate(['password' => 'required|string|min:8']);
        $user = User::findOrFail($id);
        $user->update(['password' => Hash::make($request->password)]);
        return response()->json(['status' => 'success', 'message' => 'Password reset successful']);
    }


    public function deleteUser($id) 
    {
        // Mencegah admin menghapus akunnya sendiri secara tidak sengaja
        if(auth()->id() == $id) {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak: Anda tidak dapat menghapus akun Anda sendiri!');
        }

        // Cari data siswa dan hapus dari database
        $user = User::findOrFail($id);
        $user->delete();

        // Kembalikan ke halaman dashboard dengan pesan sukses
        return redirect()->route('admin.dashboard')->with('success', 'Data siswa berhasil dihapus secara permanen!');
    }
    

public function studentDetail($id)
    {
        // 1. DATA USER & KELAS (GAMIFIKASI INCLUDED)
        // Ambil data utuh agar accessor model (XP progress, Level Title) bisa berjalan
        $user = \App\Models\User::findOrFail($id);
        
        // Ambil Data Kelas (Untuk mengecek Token Kelas & Status)
        $classGroup = null;
        if (!empty($user->class_group)) {
            $classGroup = \App\Models\ClassGroup::where('name', $user->class_group)->first();
        }

        // =====================================================================
        // 2. DATA GAMIFIKASI (BADGES & LEADERBOARD)
        // =====================================================================
        $unlockedBadges = \Illuminate\Support\Facades\DB::table('user_badges')
                            ->where('user_id', $user->id)
                            ->pluck('badge_id')
                            ->toArray();
        $allBadges = \Illuminate\Support\Facades\DB::table('badges')->get();

        $leaderboard = [];
        if (!empty($user->class_group)) {
            // Leaderboard spesifik di kelas siswa ini
            $leaderboard = \App\Models\User::where('class_group', $user->class_group)
                               ->where('role', 'student')
                               ->orderByDesc('xp')
                               ->take(5)
                               ->get();
        }

        // =====================================================================
        // 3. DATA PROGRESS MATERI (LESSON)
        // =====================================================================
        $totalLessons = \App\Models\CourseLesson::count();
        $lessonProgress = \App\Models\UserLessonProgress::where('user_id', $id)
                            ->where('completed', true)
                            ->get();
                            
        $completedLessonIds = $lessonProgress->pluck('course_lesson_id')->toArray(); 
        $lessonsCompleted = count($completedLessonIds);

        // =====================================================================
        // 4. DATA LAB & PRAKTIK
        // =====================================================================
        $totalLabs = \App\Models\Lab::where('is_active', 1)->count(); 
        $labHistories = \App\Models\LabHistory::with(['lab' => function($q) {
                $q->select('id', 'title');
            }])
            ->select('id', 'user_id', 'lab_id', 'status', 'final_score', 'duration_seconds', 'last_code_snapshot', 'created_at')
            ->where('user_id', $id)
            ->latest('created_at')
            ->get();

        $passedLabIds = $labHistories->where('status', 'passed')->pluck('lab_id')->unique()->toArray();
        $labsCompleted = count($passedLabIds);

        $labStats = [
            'total' => $labsCompleted,
            'avg_score' => $labsCompleted > 0 ? round($labHistories->where('status', 'passed')->avg('final_score'), 1) : 0
        ];

        // =====================================================================
        // 5. DATA QUIZ & EVALUASI
        // =====================================================================
        $quizAttempts = \App\Models\QuizAttempt::select('id', 'user_id', 'chapter_id', 'score', 'time_spent_seconds', 'created_at')
            ->where('user_id', $id)
            ->whereNotNull('completed_at')
            ->latest('created_at')
            ->get();

        $quizzesCompleted = $quizAttempts->count();
        $quizAverage = $quizzesCompleted > 0 ? $quizAttempts->avg('score') : 0;

        $quizScoresMap = $quizAttempts->groupBy('chapter_id')
            ->mapWithKeys(fn ($attempts, $chapterId) => ['quiz_' . $chapterId => $attempts->max('score')])
            ->toArray();

        // Hitung bab yang lulus (Score >= 70)
        $chaptersPassed = count(array_filter($quizScoresMap, fn($s) => $s >= 70));

        $quizStats = [
            'total' => $chaptersPassed, // Jumlah evaluasi lulus
            'avg_score' => $quizAverage
        ];

        // =====================================================================
        // 6. KALKULASI GLOBAL PROGRESS
        // =====================================================================
        // Asumsi Total = Materi + Lab (Misal 4) + Kuis (Misal 4)
        $totalItemsEstimasi = $totalLessons + $totalLabs + 4; 
        $totalDone = $lessonsCompleted + $labsCompleted + $chaptersPassed;
        
        $globalProgress = ($totalItemsEstimasi > 0) ? round(($totalDone / $totalItemsEstimasi) * 100) : 0;
        $globalProgress = min($globalProgress, 100);

        // =====================================================================
        // 7. CHART DATA (KUIS & LABS)
        // =====================================================================
        // Chart Kuis
        $bestQuizScores = \App\Models\QuizAttempt::where('user_id', $id)
            ->whereNotNull('completed_at')
            ->select('chapter_id', \Illuminate\Support\Facades\DB::raw('MAX(score) as max_score'))
            ->groupBy('chapter_id')
            ->orderBy('chapter_id')
            ->get();

        $chartData = [
            'labels' => $bestQuizScores->map(fn($q) => $q->chapter_id == 99 ? 'Final' : 'Bab ' . $q->chapter_id)->toArray(),
            'scores' => $bestQuizScores->pluck('max_score')->toArray(),
        ];

        // Chart Labs
        $labChartRaw = $labHistories->where('status', 'passed')->take(10)->reverse()->values();
        $chartLabels = $labChartRaw->map(fn($h) => $h->lab->title ?? 'Lab #'.$h->lab_id)->toArray();
        $chartScores = $labChartRaw->pluck('final_score')->toArray();

        // =====================================================================
        // 8. DATA RIWAYAT GABUNGAN (UNTUK TABEL LOG)
        // =====================================================================
        $mappedLabs = $labHistories->take(15)->map(fn ($item) => [
            'id' => 'lab-' . $item->id,
            'name' => $item->lab->title ?? 'Lab #'.$item->lab_id,
            'type' => 'lab',
            'score' => $item->final_score,
            'date' => $item->created_at,
            'icon' => '💻'
        ]);

        $mappedQuizzes = $quizAttempts->take(15)->map(fn ($item) => [
            'id' => 'quiz-' . $item->id,
            'name' => $item->chapter_id == 99 ? 'Final Evaluation' : 'Evaluasi Bab ' . $item->chapter_id,
            'type' => 'quiz',
            'score' => $item->score,
            'date' => $item->created_at,
            'icon' => '📝'
        ]);

        $historyCombined = $mappedLabs->merge($mappedQuizzes)->sortByDesc('date')->take(10)->values();

        // 9. DAFTAR KELAS UNTUK MODAL EDIT
        $availableClasses = \App\Models\ClassGroup::where('is_active', true)->orderBy('name', 'asc')->get();

        return view('admin.student_detail', compact(
            'user', 'classGroup', // User & Token Class
            'unlockedBadges', 'allBadges', 'leaderboard', // Gamifikasi
            'completedLessonIds', 'passedLabIds', 'quizScoresMap', // Peta ID
            'labHistories', 'quizAttempts', // Data Raw Table
            'lessonsCompleted', 'totalLessons', 'labsCompleted', 'totalLabs', 'quizzesCompleted', 'quizAverage', 'chaptersPassed', // Basic Stats
            'labStats', 'quizStats', 'globalProgress', // Advanced Stats
            'chartData', 'chartLabels', 'chartScores', 'historyCombined', // Visual Stats
            'availableClasses'
        ));
    }
    /**
     * INSIGHT: LAB ANALYTICS
     */
    public function labAnalytics(Request $request) 
    {
        $labId = $request->get('labId'); // Ambil filter ID jika ada

        $labs = DB::table('labs')
            ->leftJoin('lab_sessions', 'labs.id', '=', 'lab_sessions.lab_id')
            ->select(
                'labs.id', 'labs.title',
                DB::raw('count(lab_sessions.id) as total_participants'),
                DB::raw('sum(case when lab_sessions.status = "completed" then 1 else 0 end) as completed_count')
            )
            ->groupBy('labs.id', 'labs.title')
            ->orderByDesc('total_participants')
            ->get()
            ->map(function($l) {
                $l->completion_rate = $l->total_participants > 0 ? round(($l->completed_count / $l->total_participants) * 100) : 0;
                return $l;
            });

        // Data Tambahan untuk View Blade 'admin.lab_analytics'
        $totalAttempts = DB::table('lab_histories')->count();
        $passedCount = DB::table('lab_histories')->where('status', 'passed')->count();
        $failedCount = DB::table('lab_histories')->where('status', 'failed')->count();
        $completionRate = $totalAttempts > 0 ? round(($passedCount/$totalAttempts)*100) : 0;
        $avgScore = round(DB::table('lab_histories')->avg('final_score') ?? 0);
        $avgDuration = gmdate("i:s", DB::table('lab_histories')->avg('duration_seconds') ?? 0);
        
        $userPerformance = DB::table('lab_histories')
            ->join('users', 'lab_histories.user_id', '=', 'users.id')
            ->select('users.id as student_id', 'users.name', 'users.email', 
                DB::raw('count(*) as total_tries'), 
                DB::raw('max(final_score) as best_score'),
                DB::raw('avg(duration_seconds) as avg_time'),
                DB::raw('max(created_at) as last_attempt')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('best_score')
            ->limit(10)
            ->get();

        $labsList = DB::table('labs')->select('id', 'title')->get();
        
        // Chart Data Dummy (Sesuaikan query jika perlu)
        $chartLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $chartData = [0, 0, 0, 0, 0, 0, 0]; 

        return view('admin.lab_analytics', compact(
            'labs', 'totalAttempts', 'passedCount', 'failedCount', 'completionRate', 
            'avgScore', 'avgDuration', 'userPerformance', 'labsList', 'labId', 'chartLabels', 'chartData'
        ));
    }

    /**
     * INSIGHT: QUESTION ANALYTICS
     */
     public function questionAnalytics() 
    {
        $questions = \App\Models\QuizQuestion::with(['answers.attempt.user', 'options'])
            ->get()
            ->map(function ($q) {
                $answers = $q->answers;

                $q->list_correct = $answers->where('is_correct', 1)
                    ->map(function ($a) {
                        return $a->attempt && $a->attempt->user 
                            ? $a->attempt->user->name 
                            : 'User Tidak Dikenal';
                    })
                    ->values()
                    ->toArray();

                $q->list_wrong = $answers->where('is_correct', 0)
                    ->map(function ($a) {
                        return $a->attempt && $a->attempt->user 
                            ? $a->attempt->user->name 
                            : 'User Tidak Dikenal';
                    })
                    ->values()
                    ->toArray();

                $q->total_attempts = $answers->count();
                $q->correct_count  = count($q->list_correct);
                $q->wrong_count    = count($q->list_wrong);

                if ($q->total_attempts > 0) {
                    $q->accuracy = round(($q->correct_count / $q->total_attempts) * 100);
                } else {
                    $q->accuracy = 0;
                }

                if ($q->accuracy >= 80) {
                    $q->status = 'Mudah';
                } elseif ($q->accuracy >= 50) {
                    $q->status = 'Sedang';
                } else {
                    $q->status = 'Sulit';
                }

                return $q;
            });
            // [TAMBAHAN BARU] Data Analisis Siswa untuk Bagian Bawah
    $studentStats = DB::table('quiz_attempts')
        ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
        ->select(
            'users.name', 
            'users.email',
            'users.class_group',
            DB::raw('COUNT(quiz_attempts.id) as total_attempts'),
            DB::raw('ROUND(AVG(quiz_attempts.score), 1) as avg_score'),
            DB::raw('MAX(quiz_attempts.score) as highest_score'),
            // Estimasi durasi (menit) dari created_at ke updated_at
            DB::raw('ROUND(AVG(TIMESTAMPDIFF(MINUTE, quiz_attempts.created_at, quiz_attempts.updated_at)), 0) as avg_time')
        )
        ->whereNotNull('completed_at')
        ->groupBy('users.id', 'users.name', 'users.email', 'users.class_group')
        ->orderByDesc('avg_score')
        ->limit(10) // Top 10 Siswa
        ->get();

    // [TAMBAHAN BARU] Aktivitas Terbaru
    $recentActivities = \App\Models\QuizAttempt::with('user')
        ->whereNotNull('completed_at')
        ->latest('completed_at')
        ->take(6)
        ->get();
        

        return view('admin.question_analytics', compact('questions', 'studentStats', 'recentActivities'));
    }

    /**
     * STORE QUESTION
     */
    public function storeQuestion(Request $request)
    {
        $validated = $request->validate([
            'question_text'  => 'required|string',
            'chapter_id'     => 'required|integer',
            'option_a'       => 'required|string',
            'option_b'       => 'required|string',
            'option_c'       => 'required|string',
            'option_d'       => 'required|string',
            'correct_answer' => 'required|in:option_a,option_b,option_c,option_d',
        ]);

        DB::beginTransaction();
        try {
            $questionId = DB::table('quiz_questions')->insertGetId([
                'chapter_id'    => $validated['chapter_id'],
                'question_text' => $validated['question_text'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            $optionsData = [
                'option_a' => $validated['option_a'],
                'option_b' => $validated['option_b'],
                'option_c' => $validated['option_c'],
                'option_d' => $validated['option_d'],
            ];

            foreach ($optionsData as $key => $text) {
                DB::table('quiz_options')->insert([
                    'quiz_question_id' => $questionId,
                    'option_text'      => $text,
                    'is_correct'       => ($key === $validated['correct_answer']) ? 1 : 0,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Soal berhasil disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    /**
     * =========================================================================
     * TAMBAHAN: CRUD KUIS (UPDATE & DELETE)
     * =========================================================================
     */

    /**
     * UPDATE QUESTION (EDIT)
     */
    public function updateQuestion(Request $request, $id)
    {
        $validated = $request->validate([
            'question_text'  => 'required|string',
            'chapter_id'     => 'required|integer',
            'option_a'       => 'required|string',
            'option_b'       => 'required|string',
            'option_c'       => 'required|string',
            'option_d'       => 'required|string',
            'correct_answer' => 'required|in:option_a,option_b,option_c,option_d',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update Pertanyaan Utama
            DB::table('quiz_questions')->where('id', $id)->update([
                'chapter_id'    => $validated['chapter_id'],
                'question_text' => $validated['question_text'],
                'updated_at'    => now(),
            ]);

            // 2. Hapus Opsi Lama (Cara paling aman untuk update opsi)
            DB::table('quiz_options')->where('quiz_question_id', $id)->delete();

            // 3. Masukkan Opsi Baru
            $optionsData = [
                'option_a' => $validated['option_a'],
                'option_b' => $validated['option_b'],
                'option_c' => $validated['option_c'],
                'option_d' => $validated['option_d'],
            ];

            foreach ($optionsData as $key => $text) {
                DB::table('quiz_options')->insert([
                    'quiz_question_id' => $id,
                    'option_text'      => $text,
                    'is_correct'       => ($key === $validated['correct_answer']) ? 1 : 0,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Soal berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * DELETE QUESTION (HAPUS)
     */
    public function destroyQuestion($id)
    {
        DB::beginTransaction();
        try {
            // Hapus Opsi Terkait dulu (Cascade manual jika di DB tidak diset)
            DB::table('quiz_options')->where('quiz_question_id', $id)->delete();
            
            // Hapus Soal
            DB::table('quiz_questions')->where('id', $id)->delete();

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Soal berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
        }
    }

    /**
     * =========================================================================
     * TAMBAHAN: QUIZ ANALYTICS (LENGKAP SEPERTI LABS)
     * =========================================================================
     */

    public function quizAnalytics(Request $request)
    {
        $chapterId = $request->get('chapterId'); // Filter per Bab jika ada

        // 1. STATISTIK UTAMA
        $query = DB::table('quiz_attempts')->whereNotNull('completed_at');
        if ($chapterId) $query->where('chapter_id', $chapterId);

        $totalAttempts = $query->count();
        $avgScore      = round($query->avg('score') ?? 0, 1);
        $highestScore  = $query->max('score') ?? 0;
        $lowestScore   = $query->min('score') ?? 0;
        
        // Menghitung Lulus/Gagal (Asumsi KKM 70)
        $passedCount = (clone $query)->where('score', '>=', 70)->count();
        $failedCount = (clone $query)->where('score', '<', 70)->count();
        $passRate    = $totalAttempts > 0 ? round(($passedCount / $totalAttempts) * 100) : 0;

        // 2. ANALISIS PER BAB (TABEL RINGKASAN)
        $chaptersData = DB::table('quiz_attempts')
            ->select(
                'chapter_id',
                DB::raw('count(*) as total_participants'),
                DB::raw('avg(score) as avg_score'),
                DB::raw('max(score) as max_score'),
                DB::raw('sum(case when score >= 70 then 1 else 0 end) as passed_count')
            )
            ->whereNotNull('completed_at')
            ->groupBy('chapter_id')
            ->orderBy('chapter_id')
            ->get()
            ->map(function($c) {
                $c->pass_rate = $c->total_participants > 0 ? round(($c->passed_count / $c->total_participants) * 100) : 0;
                return $c;
            });

        // 3. LEADERBOARD SISWA (TOP 10)
        $topStudents = DB::table('quiz_attempts')
            ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
            ->select(
                'users.id', 'users.name', 'users.email', 'users.class_group',
                DB::raw('avg(quiz_attempts.score) as avg_score'),
                DB::raw('count(quiz_attempts.id) as total_quizzes')
            )
            ->whereNotNull('completed_at')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.class_group')
            ->orderByDesc('avg_score')
            ->limit(10)
            ->get();

        // 4. CHART DATA (Tren Skor Mingguan)
        $weeklyTrend = DB::table('quiz_attempts')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('AVG(score) as avg_score'))
            ->where('created_at', '>=', now()->subDays(7))
            ->when($chapterId, function($q) use ($chapterId) { return $q->where('chapter_id', $chapterId); })
            ->groupBy('date')->orderBy('date', 'asc')->get();

        $chartLabels = $weeklyTrend->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'));
        $chartData   = $weeklyTrend->pluck('avg_score')->map(fn($s) => round($s));

        // 5. LIST CHAPTER UNTUK FILTER DROPDOWN
        $chaptersList = DB::table('quiz_questions')->select('chapter_id')->distinct()->orderBy('chapter_id')->pluck('chapter_id');

        return view('admin.quiz_analytics_dashboard', compact(
            'totalAttempts', 'avgScore', 'highestScore', 'lowestScore', 
            'passedCount', 'failedCount', 'passRate',
            'chaptersData', 'topStudents', 'chartLabels', 'chartData', 'chaptersList', 'chapterId'
        ));
    }

    
}