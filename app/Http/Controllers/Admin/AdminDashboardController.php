<?php

namespace App\Http\Controllers\Admin;

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
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\LabHistory;
use App\Models\Lab;
use App\Models\CourseLesson;
use App\Models\UserActivityProgress;
use App\Models\ClassGroup;

class AdminDashboardController extends Controller
{
    /**
     * HELPER METHOD: Perekam Audit Log Otomatis
     * Digunakan untuk merekam seluruh aksi CRUD ke tabel `admin_audit_logs`
     */
    private function logAudit($action, $targetType, $targetId, $before = null, $after = null)
    {
        DB::table('admin_audit_logs')->insert([
            'admin_id'    => auth()->id() ?? 1, // Fallback ID jika Auth kosong saat testing
            'action'      => $action,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'before'      => $before ? json_encode($before) : null,
            'after'       => $after ? json_encode($after) : null,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    /**
     * DASHBOARD UTAMA
     */
    public function index()
    {
        // 1. DATA STATISTIK SISWA & KUIS
        $totalStudents = User::where('role', 'student')->count();
        $totalAttempts = DB::table('quiz_attempts')->count();
        $globalAverage = round(DB::table('quiz_attempts')->avg('score') ?? 0, 1);
        $remedialCount = DB::table('quiz_attempts')->where('score', '<', 70)->count();

        // 2. DATA PENYELESAIAN LAB
        $totalLabsCompleted = 0;
        try {
            $totalLabsCompleted = DB::table('lab_sessions')->where('status', 'completed')->count();
        } catch (\Exception $e) {
            // Abaikan jika tabel opsional
        }

        // 3. DATA CHART TREN (7 Hari Terakhir)
        $chartDataRaw = DB::table('quiz_attempts')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('AVG(score) as avg_score'))
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartLabels = $chartDataRaw->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'));
        $chartScores = $chartDataRaw->pluck('avg_score')->map(fn($s) => round($s));

        // 4. ANALISIS SOAL (Top 10)
        $questionStats = collect([]);
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
        } catch (\Exception $e) {}

        // 5. DATA ADMIN & AUDIT LOGS (Menggantikan Leaderboard)
        $totalAdmins = User::where('role', 'admin')->count(); // Hanya menghitung role 'admin'
        
        $auditLogs = collect();
        try {
            $auditLogs = DB::table('admin_audit_logs')
                ->join('users', 'admin_audit_logs.admin_id', '=', 'users.id')
                ->select(
                    'admin_audit_logs.id', 
                    'admin_audit_logs.action', 
                    'admin_audit_logs.target_type', 
                    'admin_audit_logs.target_id', 
                    'admin_audit_logs.before', 
                    'admin_audit_logs.after', 
                    'admin_audit_logs.created_at',
                    'users.name as admin_name'
                )
                ->orderByDesc('admin_audit_logs.created_at')
                ->limit(15) // Limit agar query dashboard ringan
                ->get()
                ->map(function($log) {
                    // Merapikan format tampilan untuk UI Blade
                    $log->action_label = ucwords(str_replace('_', ' ', $log->action));
                    $log->before_formatted = $log->before ? json_encode(json_decode($log->before), JSON_PRETTY_PRINT) : null;
                    $log->after_formatted = $log->after ? json_encode(json_decode($log->after), JSON_PRETTY_PRINT) : null;
                    return $log;
                });
        } catch (\Exception $e) {}

        // 6. AKTIVITAS TERBARU & DAFTAR SISWA (Direktori)
        $recentActivities = QuizAttempt::with('user')->latest()->take(5)->get();
        $availableClasses = ClassGroup::where('is_active', true)->orderBy('name', 'asc')->get();
        $users = User::orderByDesc('created_at')->limit(50)->get(); 

        return view('admin.dashboard', compact(
            'totalStudents', 'totalAttempts', 'globalAverage', 'remedialCount', 'totalLabsCompleted',
            'chartLabels', 'chartScores', 'questionStats', 
            'totalAdmins', 'auditLogs', // Data Audit
            'recentActivities', 'users', 'availableClasses'
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
            'class_group' => 'nullable|exists:class_groups,name',
            'institution' => 'nullable|string|max:100',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'class_group' => $request->class_group,
            'institution' => $request->institution,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        // Rekam Audit Log (Create)
        $this->logAudit('create_user', 'User', $user->id, null, $user->only(['name', 'email', 'class_group', 'role']));

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
            $count = 0;
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                User::updateOrCreate(
                    ['email' => $row[1]], 
                    [
                        'name' => $row[0],
                        'class_group' => $row[2] ?? null,
                        'institution' => $row[3] ?? null,
                        'password' => Hash::make($row[4] ?? 'password123'),
                        'role' => 'student',
                        'email_verified_at' => now(),
                    ]
                );
                $count++;
            }
            DB::commit();

            // Rekam Audit Log (Batch Import)
            $this->logAudit('import_users_csv', 'System', 0, null, ['total_imported' => $count]);

            return redirect()->back()->with('success', 'Import berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * UPDATE STUDENT PROFILE (FULL CRUD)
     */
    public function updateStudent(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,'.$id,
            'class_group'   => 'nullable|string|max:255',
            'institution'   => 'nullable|string|max:255',
            'study_program' => 'nullable|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'password'      => 'nullable|string|min:6',
            'avatar'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Tangkap data lama
        $beforeData = $user->only(['name', 'email', 'class_group', 'institution', 'phone']);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']); 
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar && \Illuminate\Support\Facades\Storage::exists('public/' . $user->avatar)) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $user->avatar);
            }
            $path = $request->file('avatar')->store('profile-photos', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        // Rekam Audit Log (Update Profile)
        $afterData = $user->fresh()->only(['name', 'email', 'class_group', 'institution', 'phone']);
        $this->logAudit('update_student_profile', 'User', $user->id, $beforeData, $afterData);

        return redirect()->back()->with('success', 'Profil siswa berhasil diperbarui!');
    }

    /**
     * CRUD USER METHODS (AJAX)
     */
    public function updateUser(Request $request, $id) {
        $user = User::findOrFail($id);
        
        $beforeData = $user->only(['name', 'email', 'role']); 
        $user->update($request->only('name', 'email', 'role'));
        $afterData = $user->fresh()->only(['name', 'email', 'role']);
        
        // Rekam Audit Log
        $this->logAudit('update_user_role', 'User', $user->id, $beforeData, $afterData);

        return response()->json(['status' => 'success']);
    }

    public function resetPassword(Request $request, $id) {
        $request->validate(['password' => 'required|string|min:8']);
        $user = User::findOrFail($id);
        $user->update(['password' => Hash::make($request->password)]);

        // Rekam Audit Log
        $this->logAudit('reset_user_password', 'User', $user->id, ['email' => $user->email], ['status' => 'Password changed manually']);

        return response()->json(['status' => 'success', 'message' => 'Password reset successful']);
    }

    public function deleteUser($id) 
    {
        if(auth()->id() == $id) {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak: Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user = User::findOrFail($id);
        $beforeData = $user->only(['name', 'email', 'role', 'class_group']);

        $user->delete();

        // Rekam Audit Log (Delete)
        $this->logAudit('delete_user', 'User', $id, $beforeData, null);

        return redirect()->route('admin.dashboard')->with('success', 'Data siswa berhasil dihapus secara permanen!');
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

            // Rekam Audit Log
            $this->logAudit('create_question', 'Question', $questionId, null, [
                'chapter_id' => $validated['chapter_id'],
                'question' => Str::limit($validated['question_text'], 50)
            ]);

            return response()->json(['status' => 'success', 'message' => 'Soal berhasil disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

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
            $oldQuestion = DB::table('quiz_questions')->where('id', $id)->first();
            
            DB::table('quiz_questions')->where('id', $id)->update([
                'chapter_id'    => $validated['chapter_id'],
                'question_text' => $validated['question_text'],
                'updated_at'    => now(),
            ]);

            DB::table('quiz_options')->where('quiz_question_id', $id)->delete();

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

            // Rekam Audit Log
            $this->logAudit('update_question', 'Question', $id, 
                ['old_text' => Str::limit($oldQuestion->question_text ?? '', 50)], 
                ['new_text' => Str::limit($validated['question_text'], 50)]
            );

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
            $oldQuestion = DB::table('quiz_questions')->where('id', $id)->first();

            DB::table('quiz_options')->where('quiz_question_id', $id)->delete();
            DB::table('quiz_questions')->where('id', $id)->delete();

            DB::commit();

            // Rekam Audit Log
            $this->logAudit('delete_question', 'Question', $id, 
                ['question_text' => Str::limit($oldQuestion->question_text ?? '', 50)], 
                null
            );

            return response()->json(['status' => 'success', 'message' => 'Soal berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
        }
    }

    // =========================================================================
    // EXPORT & REPORTING METHODS (Keep as original)
    // =========================================================================
    
    public function exportPdf()
    {
        $students = \App\Models\User::where('role', 'student')
            ->orderBy('class_group')
            ->orderBy('name')
            ->get();
        return view('admin.exports.students_print', compact('students'));
    }

    public function exportStudentPdf($id)
    {
        $user = User::findOrFail($id);
        
        $labHistories = DB::table('lab_histories')
            ->join('labs', 'lab_histories.lab_id', '=', 'labs.id')
            ->where('lab_histories.user_id', $id)
            ->select('labs.title', 'lab_histories.lab_id', 'lab_histories.status', 'lab_histories.final_score', 'lab_histories.duration_seconds', 'lab_histories.created_at')
            ->orderByDesc('lab_histories.created_at')
            ->get();
            
        $quizAttempts = DB::table('quiz_attempts')->where('user_id', $id)->orderByDesc('created_at')->get();
        $completedLessonIds = DB::table('user_lesson_progress')->where('user_id', $id)->where('completed', true)->pluck('course_lesson_id')->toArray();
        $passedLabIds = $labHistories->where('status', 'passed')->pluck('lab_id')->unique()->toArray();
        
        $quizScoresMap = $quizAttempts->groupBy('chapter_id')->mapWithKeys(function ($attempts, $chapterId) {
            return ['quiz_' . $chapterId => $attempts->max('score')];
        })->toArray();

        $totalItemsEstimasi = 73;
        $countLessons = count($completedLessonIds);
        $countLabs = count($passedLabIds);
        $countQuizzes = count(array_filter($quizScoresMap, fn($s) => $s >= 70));
        
        $totalDone = $countLessons + $countLabs + $countQuizzes;
        $globalProgress = ($totalItemsEstimasi > 0) ? round(($totalDone / $totalItemsEstimasi) * 100) : 0;
        $globalProgress = min($globalProgress, 100);

        return view('admin.exports.student_detail_print', compact('user', 'labHistories', 'quizAttempts', 'completedLessonIds', 'passedLabIds', 'quizScoresMap', 'globalProgress'));
    }

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
            fputcsv($file, ['Nama Lengkap', 'Email', 'Kelas', 'Institusi', 'Terdaftar']);

            foreach ($users as $user) {
                fputcsv($file, [$user->name, $user->email, $user->class_group ?? '-', $user->institution ?? '-', $user->created_at->format('Y-m-d')]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportStudentCsv($id)
    {
        $user = User::findOrFail($id);
        $labHistories = DB::table('lab_histories')
            ->join('labs', 'lab_histories.lab_id', '=', 'labs.id')
            ->where('lab_histories.user_id', $id)
            ->select('labs.title', 'lab_histories.status', 'lab_histories.final_score', 'lab_histories.duration_seconds', 'lab_histories.created_at')
            ->orderByDesc('lab_histories.created_at')
            ->get();
            
        $quizAttempts = DB::table('quiz_attempts')->where('user_id', $id)->orderByDesc('created_at')->get();

        $response = new StreamedResponse(function() use ($user, $labHistories, $quizAttempts) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['--- PROFIL SISWA ---']);
            fputcsv($handle, ['Nama Lengkap', $user->name]);
            fputcsv($handle, ['Email', $user->email]);
            fputcsv($handle, ['Kelas', $user->class_group ?? 'Tidak ada data']);
            fputcsv($handle, ['Institusi', $user->institution ?? 'Tidak ada data']);
            fputcsv($handle, []); 
            
            fputcsv($handle, ['--- RIWAYAT PRAKTIKUM LAB ---']);
            fputcsv($handle, ['Nama Modul', 'Status', 'Skor Akhir', 'Durasi (Detik)', 'Tanggal Dikerjakan']);
            
            if ($labHistories->isEmpty()) {
                fputcsv($handle, ['Belum ada riwayat pengerjaan lab']);
            } else {
                foreach ($labHistories as $lab) {
                    fputcsv($handle, [$lab->title, strtoupper($lab->status), $lab->final_score, $lab->duration_seconds, $lab->created_at]);
                }
            }
            fputcsv($handle, []); 

            fputcsv($handle, ['--- RIWAYAT EVALUASI TEORI (KUIS) ---']);
            fputcsv($handle, ['Bab Evaluasi', 'Status', 'Skor', 'Tanggal Dikerjakan']);
            
            if ($quizAttempts->isEmpty()) {
                fputcsv($handle, ['Belum ada riwayat pengerjaan kuis']);
            } else {
                foreach ($quizAttempts as $quiz) {
                    $status = $quiz->score >= 70 ? 'LULUS' : 'GAGAL';
                    $babName = $quiz->chapter_id == 99 ? 'Evaluasi Akhir' : 'Bab ' . $quiz->chapter_id;
                    fputcsv($handle, [$babName, $status, $quiz->score, $quiz->created_at]);
                }
            }
            fclose($handle);
        });

        $fileName = 'Laporan_Siswa_' . Str::slug($user->name) . '_' . date('Ymd_His') . '.csv';
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$fileName.'"');
        return $response;
    }

    public function studentDetail($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $classGroup = null;
        if (!empty($user->class_group)) {
            $classGroup = \App\Models\ClassGroup::where('name', $user->class_group)->first();
        }

        $unlockedBadges = \Illuminate\Support\Facades\DB::table('user_badges')->where('user_id', $user->id)->pluck('badge_id')->toArray();
        $allBadges = \Illuminate\Support\Facades\DB::table('badges')->get();

        $leaderboard = [];
        if (!empty($user->class_group)) {
            $leaderboard = \App\Models\User::where('class_group', $user->class_group)
                               ->where('role', 'student')
                               ->orderByDesc('xp')
                               ->take(5)
                               ->get();
        }

        $totalLessons = \App\Models\CourseLesson::count();
        $lessonProgress = \App\Models\UserLessonProgress::where('user_id', $id)->where('completed', true)->get();
        $completedLessonIds = $lessonProgress->pluck('course_lesson_id')->toArray(); 
        $lessonsCompleted = count($completedLessonIds);

        $totalLabs = \App\Models\Lab::where('is_active', 1)->count(); 
        $labHistories = \App\Models\LabHistory::with(['lab' => function($q) { $q->select('id', 'title'); }])
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

        $chaptersPassed = count(array_filter($quizScoresMap, fn($s) => $s >= 70));
        $quizStats = ['total' => $chaptersPassed, 'avg_score' => $quizAverage];

        $totalItemsEstimasi = $totalLessons + $totalLabs + 4; 
        $totalDone = $lessonsCompleted + $labsCompleted + $chaptersPassed;
        $globalProgress = ($totalItemsEstimasi > 0) ? round(($totalDone / $totalItemsEstimasi) * 100) : 0;
        $globalProgress = min($globalProgress, 100);

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

        $labChartRaw = $labHistories->where('status', 'passed')->take(10)->reverse()->values();
        $chartLabels = $labChartRaw->map(fn($h) => $h->lab->title ?? 'Lab #'.$h->lab_id)->toArray();
        $chartScores = $labChartRaw->pluck('final_score')->toArray();

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
        $availableClasses = \App\Models\ClassGroup::where('is_active', true)->orderBy('name', 'asc')->get();

        return view('admin.student_detail', compact(
            'user', 'classGroup', 
            'unlockedBadges', 'allBadges', 'leaderboard', 
            'completedLessonIds', 'passedLabIds', 'quizScoresMap', 
            'labHistories', 'quizAttempts', 
            'lessonsCompleted', 'totalLessons', 'labsCompleted', 'totalLabs', 'quizzesCompleted', 'quizAverage', 'chaptersPassed', 
            'labStats', 'quizStats', 'globalProgress', 
            'chartData', 'chartLabels', 'chartScores', 'historyCombined', 
            'availableClasses'
        ));
    }

    public function labAnalytics(Request $request) 
    {
        $labId = $request->get('labId'); 

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
        $chartLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $chartData = [0, 0, 0, 0, 0, 0, 0]; 

        return view('admin.lab_analytics', compact(
            'labs', 'totalAttempts', 'passedCount', 'failedCount', 'completionRate', 
            'avgScore', 'avgDuration', 'userPerformance', 'labsList', 'labId', 'chartLabels', 'chartData'
        ));
    }

     public function questionAnalytics() 
    {
        $questions = \App\Models\QuizQuestion::with(['answers.attempt.user', 'options'])
            ->get()
            ->map(function ($q) {
                $answers = $q->answers;

                $q->list_correct = $answers->where('is_correct', 1)->map(fn($a) => $a->attempt && $a->attempt->user ? $a->attempt->user->name : 'Unknown')->values()->toArray();
                $q->list_wrong = $answers->where('is_correct', 0)->map(fn($a) => $a->attempt && $a->attempt->user ? $a->attempt->user->name : 'Unknown')->values()->toArray();

                $q->total_attempts = $answers->count();
                $q->correct_count  = count($q->list_correct);
                $q->wrong_count    = count($q->list_wrong);

                $q->accuracy = $q->total_attempts > 0 ? round(($q->correct_count / $q->total_attempts) * 100) : 0;

                if ($q->accuracy >= 80) $q->status = 'Mudah';
                elseif ($q->accuracy >= 50) $q->status = 'Sedang';
                else $q->status = 'Sulit';

                return $q;
            });

        $studentStats = DB::table('quiz_attempts')
            ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
            ->select(
                'users.name', 
                'users.email',
                'users.class_group',
                DB::raw('COUNT(quiz_attempts.id) as total_attempts'),
                DB::raw('ROUND(AVG(quiz_attempts.score), 1) as avg_score'),
                DB::raw('MAX(quiz_attempts.score) as highest_score'),
                DB::raw('ROUND(AVG(TIMESTAMPDIFF(MINUTE, quiz_attempts.created_at, quiz_attempts.updated_at)), 0) as avg_time')
            )
            ->whereNotNull('completed_at')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.class_group')
            ->orderByDesc('avg_score')
            ->limit(10) 
            ->get();

        $recentActivities = \App\Models\QuizAttempt::with('user')
            ->whereNotNull('completed_at')
            ->latest('completed_at')
            ->take(6)
            ->get();
        
        return view('admin.question_analytics', compact('questions', 'studentStats', 'recentActivities'));
    }

    public function quizAnalytics(Request $request)
    {
        $chapterId = $request->get('chapterId'); 
        $query = DB::table('quiz_attempts')->whereNotNull('completed_at');
        if ($chapterId) $query->where('chapter_id', $chapterId);

        $totalAttempts = $query->count();
        $avgScore      = round($query->avg('score') ?? 0, 1);
        $highestScore  = $query->max('score') ?? 0;
        $lowestScore   = $query->min('score') ?? 0;
        
        $passedCount = (clone $query)->where('score', '>=', 70)->count();
        $failedCount = (clone $query)->where('score', '<', 70)->count();
        $passRate    = $totalAttempts > 0 ? round(($passedCount / $totalAttempts) * 100) : 0;

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

        $weeklyTrend = DB::table('quiz_attempts')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('AVG(score) as avg_score'))
            ->where('created_at', '>=', now()->subDays(7))
            ->when($chapterId, function($q) use ($chapterId) { return $q->where('chapter_id', $chapterId); })
            ->groupBy('date')->orderBy('date', 'asc')->get();

        $chartLabels = $weeklyTrend->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'));
        $chartData   = $weeklyTrend->pluck('avg_score')->map(fn($s) => round($s));

        $chaptersList = DB::table('quiz_questions')->select('chapter_id')->distinct()->orderBy('chapter_id')->pluck('chapter_id');

        return view('admin.quiz_analytics_dashboard', compact(
            'totalAttempts', 'avgScore', 'highestScore', 'lowestScore', 
            'passedCount', 'failedCount', 'passRate',
            'chaptersData', 'topStudents', 'chartLabels', 'chartData', 'chaptersList', 'chapterId'
        ));
    }
}