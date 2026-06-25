<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File; // 🔹 WAJIB DITAMBAHKAN
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserLessonProgress;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\QuizQuestion;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\LabHistory;
use App\Models\Lab;
use App\Models\CourseLesson;
use App\Models\UserActivityProgress;
use App\Models\ClassGroup;
use App\Support\LearningOutcomeAnalytics;

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

    private function filterColumns(string $table, array $attributes): array
    {
        return collect($attributes)
            ->filter(fn ($value, $column) => Schema::hasColumn($table, $column))
            ->all();
    }

    private function quizMediaPublicUrl(string $path): string
    {
        return '/uploads/' . ltrim(str_replace('\\', '/', $path), '/');
    }

    private function quizMediaPathFromUrl(?string $url): ?string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return null;
        }

        $path = parse_url($url, PHP_URL_PATH) ?: $url;
        $path = trim(str_replace('\\', '/', rawurldecode($path)));

        foreach (['/uploads/quiz-media/', 'uploads/quiz-media/'] as $needle) {
            if (Str::contains($path, $needle)) {
                return 'quiz-media/' . ltrim(Str::after($path, $needle), '/');
            }
        }

        foreach (['/storage/quiz-media/', 'storage/quiz-media/'] as $needle) {
            if (Str::contains($path, $needle)) {
                return 'quiz-media/' . ltrim(Str::after($path, $needle), '/');
            }
        }

        return Str::startsWith($path, 'quiz-media/') ? $path : null;
    }

    private function quizChapterLabel($chapterId): string
    {
        $chapterId = (int) $chapterId;

        return $chapterId === 99 ? 'Evaluasi' : 'Bab ' . $chapterId;
    }

    private function formatDurationShort($seconds): string
    {
        $seconds = max(0, (int) round((float) ($seconds ?? 0)));
        if ($seconds === 0) {
            return '0s';
        }

        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $remainingSeconds = $seconds % 60;
        $parts = [];

        if ($hours > 0) {
            $parts[] = $hours . 'j';
        }

        if ($minutes > 0) {
            $parts[] = $minutes . 'm';
        }

        if ($remainingSeconds > 0 || empty($parts)) {
            $parts[] = $remainingSeconds . 's';
        }

        return implode(' ', array_slice($parts, 0, 2));
    }

    private function normalizeQuizMediaUrl(?string $url, ?string $path = null): ?string
    {
        $url = trim((string) $url);
        $localPath = $this->quizMediaPathFromUrl($url) ?: ($path ?: null);

        if ($localPath) {
            return $this->quizMediaPublicUrl($localPath);
        }

        return $url !== '' ? $url : null;
    }

    private function deleteQuizMedia(?string $path): void
    {
        $path = trim((string) $path);

        if ($path !== '' && Str::startsWith(str_replace('\\', '/', $path), 'quiz-media/')) {
            Storage::disk('public')->delete($path);
        }
    }

    private function questionMediaAttributes(Request $request, array $validated, object|null $oldQuestion = null): array
    {
        $attributes = [
            'interaction_type' => $validated['interaction_type'] ?? 'multiple_choice',
            'interaction_prompt' => $validated['interaction_prompt'] ?? null,
            'media_caption' => $validated['media_caption'] ?? null,
        ];

        if (($validated['interaction_type'] ?? 'multiple_choice') !== 'image_context') {
            $this->deleteQuizMedia($oldQuestion?->media_path ?? null);

            $attributes['interaction_prompt'] = null;
            $attributes['media_caption'] = null;

            return $attributes + [
                'media_type' => null,
                'media_url' => null,
                'media_path' => null,
            ];
        }

        $mediaUrl = trim((string) ($validated['media_url'] ?? ''));

        if ($request->hasFile('media_file')) {
            $this->deleteQuizMedia($oldQuestion?->media_path ?? null);

            $path = $request->file('media_file')->store('quiz-media', 'public');

            return $attributes + [
                'media_type' => 'image',
                'media_url' => $this->quizMediaPublicUrl($path),
                'media_path' => $path,
            ];
        }

        if ($request->boolean('remove_media')) {
            $this->deleteQuizMedia($oldQuestion?->media_path ?? null);

            return $attributes + [
                'media_type' => null,
                'media_url' => null,
                'media_path' => null,
            ];
        }

        if ($mediaUrl !== '') {
            $mediaPath = $this->quizMediaPathFromUrl($mediaUrl);

            if (!empty($oldQuestion?->media_path) && (!$mediaPath || $mediaPath !== $oldQuestion->media_path)) {
                $this->deleteQuizMedia($oldQuestion->media_path);
            }

            return $attributes + [
                'media_type' => 'image',
                'media_url' => $this->normalizeQuizMediaUrl($mediaUrl),
                'media_path' => $mediaPath,
            ];
        }

        if ($oldQuestion && (!empty($oldQuestion->media_url) || !empty($oldQuestion->media_path))) {
            return $attributes + [
                'media_type' => $oldQuestion->media_type ?? 'image',
                'media_url' => $this->normalizeQuizMediaUrl($oldQuestion->media_url ?? null, $oldQuestion->media_path ?? null),
                'media_path' => $oldQuestion->media_path ?? null,
            ];
        }

        return $attributes + [
            'media_type' => null,
            'media_url' => null,
            'media_path' => null,
        ];
    }

    private function validateQuestionInteraction(Request $request, array $validated, object|null $oldQuestion = null): void
    {
        $type = $validated['interaction_type'] ?? 'multiple_choice';
        $mediaUrl = trim((string) ($validated['media_url'] ?? ''));
        $isRemovingMedia = $request->boolean('remove_media');
        $hasExistingMedia = !$isRemovingMedia && (!empty($oldQuestion?->media_url) || !empty($oldQuestion?->media_path));

        if ($type === 'image_context' && !$request->hasFile('media_file') && ($isRemovingMedia || $mediaUrl === '') && !$hasExistingMedia) {
            throw ValidationException::withMessages([
                'media_url' => 'Soal gambar wajib memiliki upload gambar atau URL gambar.',
            ]);
        }
    }

    private function syncQuestionOptions(int $questionId, array $validated): void
    {
        $optionKeys = ['option_a', 'option_b', 'option_c', 'option_d'];
        $existingOptions = DB::table('quiz_options')
            ->where('quiz_question_id', $questionId)
            ->orderBy('id')
            ->get()
            ->values();

        foreach ($optionKeys as $index => $key) {
            $payload = [
                'quiz_question_id' => $questionId,
                'option_text' => $validated[$key],
                'is_correct' => ($key === $validated['correct_answer']) ? 1 : 0,
                'updated_at' => now(),
            ];

            $existing = $existingOptions->get($index);
            if ($existing) {
                DB::table('quiz_options')->where('id', $existing->id)->update($payload);
                continue;
            }

            DB::table('quiz_options')->insert($payload + [
                'created_at' => now(),
            ]);
        }

        $extraOptionIds = $existingOptions->slice(count($optionKeys))->pluck('id');
        if ($extraOptionIds->isNotEmpty()) {
            DB::table('quiz_options')->whereIn('id', $extraOptionIds)->delete();
        }
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

        // 6. AKTIVITAS TERBARU
        $recentActivities = QuizAttempt::with('user')->latest()->take(5)->get();
        $availableClasses = ClassGroup::where('is_active', true)->orderBy('name', 'asc')->get();

        return view('admin.dashboard', compact(
            'totalStudents', 'totalAttempts', 'globalAverage', 'remedialCount', 'totalLabsCompleted',
            'chartLabels', 'chartScores', 'questionStats', 
            'totalAdmins', 'auditLogs', // Data Audit
            'recentActivities', 'availableClasses'
        ));
    }

    public function students()
    {
        $minimumQuizScore = 70;
        $totalLessons = CourseLesson::count();
        $totalLabs = Lab::where('is_active', 1)->count();
        $totalQuizChapters = DB::table('quiz_questions')->select('chapter_id')->distinct()->count('chapter_id');
        $totalItems = max(1, $totalLessons + $totalLabs + $totalQuizChapters);

        $studentsRaw = User::where('role', 'student')
            ->orderBy('class_group')
            ->orderBy('name')
            ->get();

        $studentIds = $studentsRaw->pluck('id');
        $quizAttempts = DB::table('quiz_attempts')
            ->whereIn('user_id', $studentIds)
            ->whereNotNull('completed_at')
            ->get();
        $labHistories = DB::table('lab_histories')
            ->whereIn('user_id', $studentIds)
            ->get();
        $lessonProgress = DB::table('user_lesson_progress')
            ->whereIn('user_id', $studentIds)
            ->where('completed', true)
            ->get();

        $students = $studentsRaw->map(function (User $student) use ($quizAttempts, $labHistories, $lessonProgress, $totalItems, $minimumQuizScore, $totalQuizChapters) {
            $userQuizzes = $quizAttempts->where('user_id', $student->id);
            $userLabs = $labHistories->where('user_id', $student->id);
            $completedLabs = $userLabs->where('status', 'passed')->unique('lab_id');
            $completedLessons = $lessonProgress->where('user_id', $student->id);

            $quizAttemptsCount = $userQuizzes->count();
            $avgScore = $quizAttemptsCount > 0 ? round($userQuizzes->avg('score'), 1) : 0;
            $bestScore = $quizAttemptsCount > 0 ? round($userQuizzes->max('score'), 1) : 0;
            $quizChapterScores = $userQuizzes
                ->groupBy('chapter_id')
                ->map(fn ($attempts) => round($attempts->max('score'), 1));
            $passedQuizChapters = $quizChapterScores->filter(fn ($score) => $score >= $minimumQuizScore)->count();
            $quizPassedAttempts = $userQuizzes->filter(fn ($attempt) => (float) ($attempt->score ?? 0) >= $minimumQuizScore)->count();
            $quizFailedAttempts = $userQuizzes->filter(fn ($attempt) => (float) ($attempt->score ?? 0) < $minimumQuizScore)->count();
            $labAttemptsCount = $userLabs->count();
            $labFailedAttempts = $userLabs->filter(fn ($attempt) => ($attempt->status ?? '') !== 'passed')->count();
            $quizChaptersTouched = $userQuizzes->pluck('chapter_id')->filter()->unique()->count();
            $learningCoveragePct = $totalQuizChapters > 0 ? min(100, round(($quizChaptersTouched / $totalQuizChapters) * 100)) : 0;
            $sortedStrongChapters = $quizChapterScores->sortDesc();
            $sortedWeakChapters = $quizChapterScores->sort();
            $strongestChapterId = $sortedStrongChapters->keys()->first();
            $weakestChapterId = $sortedWeakChapters->keys()->first();
            $strongestChapter = $strongestChapterId !== null
                ? $this->quizChapterLabel($strongestChapterId) . ' (' . $sortedStrongChapters->first() . ')'
                : 'Belum ada data';
            $weakestChapter = $weakestChapterId !== null
                ? $this->quizChapterLabel($weakestChapterId) . ' (' . $sortedWeakChapters->first() . ')'
                : 'Belum ada data';
            $avgQuizDurationSeconds = $quizAttemptsCount > 0 ? (int) round($userQuizzes->avg('time_spent_seconds') ?? 0) : 0;

            $activityCount = $quizAttemptsCount + $completedLabs->count() + $completedLessons->count();
            $progressPct = min(100, round((($completedLessons->count() + $completedLabs->count() + $passedQuizChapters) / $totalItems) * 100));

            if (empty($student->class_group)) {
                $statusLabel = 'Belum Masuk Kelas';
                $statusKey = 'unassigned';
                $statusTone = 'slate';
            } elseif ($quizAttemptsCount > 0 && $avgScore < $minimumQuizScore) {
                $statusLabel = 'Perlu Penguatan';
                $statusKey = 'attention';
                $statusTone = 'rose';
            } elseif ($progressPct >= 100) {
                $statusLabel = 'Tuntas';
                $statusKey = 'complete';
                $statusTone = 'emerald';
            } elseif ($activityCount > 0) {
                $statusLabel = 'Aktif Belajar';
                $statusKey = 'active';
                $statusTone = 'indigo';
            } else {
                $statusLabel = 'Belum Mulai';
                $statusKey = 'idle';
                $statusTone = 'amber';
            }

            $lastActivityAt = collect([
                $userQuizzes->max('completed_at'),
                $userLabs->max('created_at'),
                $completedLessons->max('updated_at'),
            ])
                ->filter()
                ->map(fn ($date) => Carbon::parse($date))
                ->sortDesc()
                ->first();

            $avatarUrl = !empty($student->avatar)
                ? (Str::startsWith($student->avatar, ['http://', 'https://'])
                    ? $student->avatar
                    : asset('uploads/' . $student->avatar) . '?v=' . time())
                : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=6366f1&color=fff&size=256';

            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'class_group' => $student->class_group,
                'institution' => $student->institution,
                'joined_at' => $student->created_at,
                'avatar_url' => $avatarUrl,
                'avg_score' => $avgScore,
                'best_score' => $bestScore,
                'quiz_attempts' => $quizAttemptsCount,
                'quiz_passed_attempts' => $quizPassedAttempts,
                'quiz_failed_attempts' => $quizFailedAttempts,
                'quizzes_passed' => $passedQuizChapters,
                'lessons_done' => $completedLessons->count(),
                'labs_done' => $completedLabs->count(),
                'lab_attempts' => $labAttemptsCount,
                'lab_failed_attempts' => $labFailedAttempts,
                'learning_coverage_pct' => $learningCoveragePct,
                'strongest_chapter' => $strongestChapter,
                'weakest_chapter' => $weakestChapter,
                'avg_quiz_duration_label' => $this->formatDurationShort($avgQuizDurationSeconds),
                'focus_lost_total' => (int) $userQuizzes->sum('focus_lost_count'),
                'flagged_total' => (int) $userQuizzes->sum('flagged_count'),
                'unanswered_total' => (int) $userQuizzes->sum('unanswered_count'),
                'progress_pct' => $progressPct,
                'status_label' => $statusLabel,
                'status_key' => $statusKey,
                'status_tone' => $statusTone,
                'last_activity_at' => $lastActivityAt,
                'activity_count' => $activityCount,
            ];
        })->values();

        $summary = [
            'total_students' => $students->count(),
            'with_class' => $students->filter(fn ($student) => !empty($student['class_group']))->count(),
            'active_students' => $students->where('activity_count', '>', 0)->count(),
            'need_attention' => $students->where('status_key', 'attention')->count(),
            'avg_progress' => $students->count() > 0 ? round($students->avg('progress_pct'), 1) : 0,
        ];

        $classSummaries = $students
            ->filter(fn ($student) => !empty($student['class_group']))
            ->groupBy('class_group')
            ->map(fn ($rows, $className) => [
                'name' => $className,
                'total' => $rows->count(),
                'active' => $rows->where('activity_count', '>', 0)->count(),
                'attention' => $rows->where('status_key', 'attention')->count(),
                'avg_progress' => $rows->count() > 0 ? round($rows->avg('progress_pct'), 1) : 0,
            ])
            ->sortBy('name')
            ->values();

        $availableClasses = ClassGroup::where('is_active', true)->orderBy('name')->get();

        return view('admin.students.directory', compact('students', 'summary', 'classSummaries', 'availableClasses'));
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
            $skipped = 0;
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($row) < 2 || empty($row[0]) || empty($row[1]) || !filter_var($row[1], FILTER_VALIDATE_EMAIL)) {
                    $skipped++;
                    continue;
                }

                User::updateOrCreate(
                    ['email' => trim($row[1])], 
                    [
                        'name' => trim($row[0]),
                        'class_group' => isset($row[2]) ? trim($row[2]) : null,
                        'institution' => isset($row[3]) ? trim($row[3]) : null,
                        'password' => Hash::make($row[4] ?? 'password123'),
                        'role' => 'student',
                        'email_verified_at' => now(),
                    ]
                );
                $count++;
            }
            DB::commit();

            // Rekam Audit Log (Batch Import)
            $this->logAudit('import_users_csv', 'System', 0, null, ['total_imported' => $count, 'total_skipped' => $skipped]);

            $message = "Import berhasil! {$count} data siswa diproses.";
            if ($skipped > 0) {
                $message .= " {$skipped} baris dilewati karena format tidak lengkap atau email tidak valid.";
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * UPDATE STUDENT PROFILE (FULL CRUD) - (Via Modal Halaman Detail)
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
        $beforeData = $user->only(['name', 'email', 'class_group', 'institution', 'phone', 'avatar']);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 🔹 LOGIKA UPLOAD GAMBAR KHUSUS HOSTING (Ke folder public/uploads)
        if ($request->hasFile('avatar')) {
            // Hapus gambar lama jika ada
            if (!empty($user->avatar) && File::exists(public_path('uploads/' . $user->avatar))) {
                File::delete(public_path('uploads/' . $user->avatar));
            }

            $file = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/profile-photos');

            // Otomatis buat folder jika belum ada di hosting
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            
            // Pindahkan file ke public/uploads/profile-photos
            $file->move($destinationPath, $filename);
            
            // Tambahkan path avatar ke model
            $user->avatar = 'profile-photos/' . $filename;
        }

        // Simpan field manual
        $user->name = $request->name;
        $user->email = $request->email;
        $user->class_group = $request->class_group;
        $user->institution = $request->institution;
        $user->study_program = $request->study_program;
        $user->phone = $request->phone;
        
        $user->save();

        // Rekam Audit Log (Update Profile)
        $afterData = $user->only(['name', 'email', 'class_group', 'institution', 'phone', 'avatar']);
        $this->logAudit('update_student_profile', 'User', $user->id, $beforeData, $afterData);

        return redirect()->back()->with('success', 'Profil siswa berhasil diperbarui!');
    }

    /**
     * CRUD USER METHODS (AJAX - Untuk DataTables)
     */
    public function updateUser(Request $request, $id) {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,student',
        ]);
        
        $beforeData = $user->only(['name', 'email', 'role']); 
        $user->update($validated);
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

        // Bersihkan file foto profil saat user dihapus
        if (!empty($user->avatar) && File::exists(public_path('uploads/' . $user->avatar))) {
            File::delete(public_path('uploads/' . $user->avatar));
        }

        $user->delete();

        // Rekam Audit Log (Delete)
        $this->logAudit('delete_user', 'User', $id, $beforeData, null);

        // Jika via AJAX Datatable vs Form biasa
        if (request()->expectsJson()) {
             return response()->json(['status' => 'success', 'message' => 'User berhasil dihapus']);
        }
        
        return redirect()->back()->with('success', 'Data siswa berhasil dihapus secara permanen!');
    }
    
    /**
     * STORE QUESTION
     */
    public function createQuestion()
    {
        return view('admin.quiz.create');
    }

    public function storeQuestion(Request $request)
    {
        return $this->storeQuestionAdmin($request);
    }

    public function storeQuestionAdmin(Request $request)
    {
        $validated = $request->validate([
            'question_text'  => 'required|string',
            'chapter_id'     => 'required|integer',
            'learning_objective_code' => 'nullable|string|max:40',
            'learning_objective_title' => 'nullable|string|max:255',
            'remediation_hint' => 'nullable|string|max:1000',
            'interaction_type' => 'nullable|in:multiple_choice,image_context',
            'interaction_prompt' => 'nullable|string|max:1000',
            'media_url' => 'nullable|string|max:1000',
            'media_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'media_caption' => 'nullable|string|max:255',
            'remove_media' => 'nullable|boolean',
            'option_a'       => 'required|string',
            'option_b'       => 'required|string',
            'option_c'       => 'required|string',
            'option_d'       => 'required|string',
            'correct_answer' => 'required|in:option_a,option_b,option_c,option_d',
        ]);

        $this->validateQuestionInteraction($request, $validated);

        DB::beginTransaction();
        try {
            $questionId = DB::table('quiz_questions')->insertGetId($this->filterColumns('quiz_questions', [
                'chapter_id'    => $validated['chapter_id'],
                'learning_objective_code' => $validated['learning_objective_code'] ?? null,
                'learning_objective_title' => $validated['learning_objective_title'] ?? null,
                'remediation_hint' => $validated['remediation_hint'] ?? null,
                ...$this->questionMediaAttributes($request, $validated),
                'question_text' => $validated['question_text'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]));

            $this->syncQuestionOptions($questionId, $validated);

            DB::commit();

            // Rekam Audit Log
            $this->logAudit('create_question', 'Question', $questionId, null, [
                'chapter_id' => $validated['chapter_id'],
                'learning_objective' => $validated['learning_objective_code'] ?? null,
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
            'learning_objective_code' => 'nullable|string|max:40',
            'learning_objective_title' => 'nullable|string|max:255',
            'remediation_hint' => 'nullable|string|max:1000',
            'interaction_type' => 'nullable|in:multiple_choice,image_context',
            'interaction_prompt' => 'nullable|string|max:1000',
            'media_url' => 'nullable|string|max:1000',
            'media_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'media_caption' => 'nullable|string|max:255',
            'remove_media' => 'nullable|boolean',
            'option_a'       => 'required|string',
            'option_b'       => 'required|string',
            'option_c'       => 'required|string',
            'option_d'       => 'required|string',
            'correct_answer' => 'required|in:option_a,option_b,option_c,option_d',
        ]);

        $oldQuestion = DB::table('quiz_questions')->where('id', $id)->first();
        if (!$oldQuestion) {
            return response()->json(['status' => 'error', 'message' => 'Soal tidak ditemukan'], 404);
        }

        $this->validateQuestionInteraction($request, $validated, $oldQuestion);

        DB::beginTransaction();
        try {
            
            DB::table('quiz_questions')->where('id', $id)->update($this->filterColumns('quiz_questions', [
                'chapter_id'    => $validated['chapter_id'],
                'learning_objective_code' => $validated['learning_objective_code'] ?? null,
                'learning_objective_title' => $validated['learning_objective_title'] ?? null,
                'remediation_hint' => $validated['remediation_hint'] ?? null,
                ...$this->questionMediaAttributes($request, $validated, $oldQuestion),
                'question_text' => $validated['question_text'],
                'updated_at'    => now(),
            ]));

            $this->syncQuestionOptions((int) $id, $validated);

            DB::commit();

            // Rekam Audit Log
            $this->logAudit('update_question', 'Question', $id, 
                ['old_text' => Str::limit($oldQuestion->question_text ?? '', 50)], 
                [
                    'new_text' => Str::limit($validated['question_text'], 50),
                    'learning_objective' => $validated['learning_objective_code'] ?? null,
                ]
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
        $minimumQuizScore = 70;
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
        $totalQuizzes = max(1, DB::table('quiz_questions')->select('chapter_id')->distinct()->count('chapter_id'));
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

        $quizAttempts = \App\Models\QuizAttempt::select(
                'id', 'user_id', 'chapter_id', 'score', 'time_spent_seconds',
                'answered_count', 'unanswered_count', 'flagged_count', 'focus_lost_count',
                'feedback_level', 'feedback_message', 'reflection_note', 'completed_at', 'created_at'
            )
            ->where('user_id', $id)
            ->whereNotNull('completed_at')
            ->latest('created_at')
            ->get();

        $quizOutcomeAnalyticsByAttempt = [];
        if ($quizAttempts->isNotEmpty()) {
            $attemptIds = $quizAttempts->pluck('id');
            $chapters = $quizAttempts->pluck('chapter_id')->unique()->values();
            $answersByAttempt = QuizAttemptAnswer::whereIn('quiz_attempt_id', $attemptIds)
                ->get()
                ->groupBy('quiz_attempt_id');
            $questionsByChapter = QuizQuestion::whereIn('chapter_id', $chapters)
                ->get()
                ->groupBy('chapter_id');

            foreach ($quizAttempts as $attempt) {
                $answersByQuestion = collect($answersByAttempt->get($attempt->id, collect()))
                    ->keyBy('quiz_question_id');
                $questionsForAttempt = collect($questionsByChapter->get($attempt->chapter_id, collect()));

                $quizOutcomeAnalyticsByAttempt[$attempt->id] = LearningOutcomeAnalytics::forQuizAttempt(
                    $questionsForAttempt,
                    $answersByQuestion,
                    $attempt
                );
            }
        }

        $quizzesCompleted = $quizAttempts->count();
        $quizAverage = $quizzesCompleted > 0 ? $quizAttempts->avg('score') : 0;

        $quizScoresMap = $quizAttempts->groupBy('chapter_id')
            ->mapWithKeys(fn ($attempts, $chapterId) => ['quiz_' . $chapterId => $attempts->max('score')])
            ->toArray();

        $chaptersPassed = count(array_filter($quizScoresMap, fn($s) => $s >= $minimumQuizScore));
        $quizStats = ['total' => $chaptersPassed, 'avg_score' => $quizAverage];

        $totalItemsEstimasi = $totalLessons + $totalLabs + $totalQuizzes;
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
            'labels' => $bestQuizScores->map(fn($q) => $this->quizChapterLabel($q->chapter_id))->toArray(),
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
            'name' => $item->chapter_id == 99 ? 'Evaluasi' : 'Evaluasi Bab ' . $item->chapter_id,
            'type' => 'quiz',
            'score' => $item->score,
            'date' => $item->created_at,
            'icon' => '📝'
        ]);

        $historyCombined = $mappedLabs->merge($mappedQuizzes)->sortByDesc('date')->take(10)->values();
        $outcomeRows = collect($quizOutcomeAnalyticsByAttempt)
            ->flatMap(fn ($analytics) => collect($analytics['outcomes'] ?? []))
            ->values();
        $strongestOutcome = $outcomeRows
            ->sortByDesc(fn ($row) => (float) data_get($row, 'mastery_percent', 0))
            ->first();
        $weakestOutcome = $outcomeRows
            ->sortBy(fn ($row) => (float) data_get($row, 'mastery_percent', 0))
            ->first();
        $chapterPerformance = $quizAttempts
            ->groupBy('chapter_id')
            ->map(function ($attempts, $chapterId) use ($minimumQuizScore) {
                $bestScore = round($attempts->max('score') ?? 0, 1);

                return [
                    'chapter_id' => (int) $chapterId,
                    'label' => $this->quizChapterLabel($chapterId),
                    'attempts' => $attempts->count(),
                    'best_score' => $bestScore,
                    'avg_score' => round($attempts->avg('score') ?? 0, 1),
                    'passed' => $bestScore >= $minimumQuizScore,
                ];
            })
            ->sortBy(fn ($row) => $row['chapter_id'] === 99 ? 99 : $row['chapter_id'])
            ->values();
        $quizAttemptCount = $quizAttempts->count();
        $labAttemptCount = $labHistories->count();
        $quizAvgDurationSeconds = $quizAttemptCount > 0 ? (int) round($quizAttempts->avg('time_spent_seconds') ?? 0) : 0;
        $labAvgDurationSeconds = $labAttemptCount > 0 ? (int) round($labHistories->avg('duration_seconds') ?? 0) : 0;
        $lastLearningActivity = collect([
            $quizAttempts->max('completed_at'),
            $labHistories->max('created_at'),
            $lessonProgress->max('updated_at'),
        ])
            ->filter()
            ->map(fn ($date) => Carbon::parse($date))
            ->sortDesc()
            ->first();
        $studentAnalyticsSummary = [
            'quiz_attempts' => $quizAttemptCount,
            'quiz_passed_attempts' => $quizAttempts->filter(fn ($attempt) => (float) ($attempt->score ?? 0) >= $minimumQuizScore)->count(),
            'quiz_failed_attempts' => $quizAttempts->filter(fn ($attempt) => (float) ($attempt->score ?? 0) < $minimumQuizScore)->count(),
            'quiz_best_score' => $quizAttemptCount > 0 ? round($quizAttempts->max('score') ?? 0, 1) : 0,
            'quiz_lowest_score' => $quizAttemptCount > 0 ? round($quizAttempts->min('score') ?? 0, 1) : 0,
            'quiz_avg_duration_label' => $this->formatDurationShort($quizAvgDurationSeconds),
            'quiz_flagged_total' => (int) $quizAttempts->sum('flagged_count'),
            'quiz_focus_lost_total' => (int) $quizAttempts->sum('focus_lost_count'),
            'quiz_unanswered_total' => (int) $quizAttempts->sum('unanswered_count'),
            'lab_attempts' => $labAttemptCount,
            'lab_passed_attempts' => $labHistories->where('status', 'passed')->count(),
            'lab_failed_attempts' => $labHistories->filter(fn ($history) => ($history->status ?? '') !== 'passed')->count(),
            'lab_best_score' => $labAttemptCount > 0 ? round($labHistories->max('final_score') ?? 0, 1) : 0,
            'lab_avg_duration_label' => $this->formatDurationShort($labAvgDurationSeconds),
            'coverage_pct' => $totalQuizzes > 0 ? min(100, round(($chapterPerformance->count() / $totalQuizzes) * 100)) : 0,
            'outcomes_count' => $outcomeRows->count(),
            'outcomes_need_review' => $outcomeRows->filter(fn ($row) => (float) data_get($row, 'mastery_percent', 0) < $minimumQuizScore)->count(),
            'strongest_outcome' => $strongestOutcome,
            'weakest_outcome' => $weakestOutcome,
            'last_activity_label' => $lastLearningActivity ? $lastLearningActivity->diffForHumans() : 'Belum ada aktivitas',
        ];
        $outcomeSummary = [
            'total' => $studentAnalyticsSummary['outcomes_count'],
            'need_review' => $studentAnalyticsSummary['outcomes_need_review'],
            'strongest' => $strongestOutcome,
            'weakest' => $weakestOutcome,
        ];
        $availableClasses = \App\Models\ClassGroup::where('is_active', true)->orderBy('name', 'asc')->get();

        return view('admin.student_detail', compact(
            'user', 'classGroup', 
            'unlockedBadges', 'allBadges', 'leaderboard', 
            'completedLessonIds', 'passedLabIds', 'quizScoresMap', 
            'labHistories', 'quizAttempts', 
            'lessonsCompleted', 'totalLessons', 'labsCompleted', 'totalLabs', 'totalQuizzes', 'quizzesCompleted', 'quizAverage', 'chaptersPassed',
            'labStats', 'quizStats', 'globalProgress', 
            'chartData', 'chartLabels', 'chartScores', 'historyCombined', 
            'availableClasses', 'quizOutcomeAnalyticsByAttempt', 'studentAnalyticsSummary',
            'chapterPerformance', 'outcomeSummary'
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

    private function splitQuizAnswerStudentCounts($answers): array
    {
        $latestAnswers = collect($answers)
            ->filter(fn ($answer) => $answer->attempt && $answer->attempt->completed_at && $answer->attempt->user_id)
            ->sortByDesc(fn ($answer) => $answer->attempt->completed_at?->timestamp ?? 0)
            ->unique(fn ($answer) => $answer->attempt->user_id)
            ->values();

        $correctCount = $latestAnswers->where('is_correct', 1)->count();
        $wrongCount = $latestAnswers->where('is_correct', 0)->count();
        $totalCount = $correctCount + $wrongCount;

        return [
            'correct_count' => $correctCount,
            'wrong_count' => $wrongCount,
            'total_count' => $totalCount,
            'correct_percent' => $totalCount > 0 ? round(($correctCount / $totalCount) * 100, 1) : 0,
            'wrong_percent' => $totalCount > 0 ? round(($wrongCount / $totalCount) * 100, 1) : 0,
        ];
    }

    public function questionAnalytics()
    {
        $questions = \App\Models\QuizQuestion::with(['answers.attempt', 'options'])
            ->get()
            ->map(function ($q) {
                $answerBreakdown = $this->splitQuizAnswerStudentCounts($q->answers);

                $q->total_attempts = $answerBreakdown['total_count'];
                $q->correct_count  = $answerBreakdown['correct_count'];
                $q->wrong_count    = $answerBreakdown['wrong_count'];

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

        return view('admin.question_analytics', compact(
            'questions',
            'studentStats',
            'recentActivities'
        ));
    }

    public function learningOutcomes()
    {
        $questions = QuizQuestion::with(['answers.attempt', 'options'])
            ->get()
            ->map(function ($question) {
                $answerBreakdown = $this->splitQuizAnswerStudentCounts($question->answers);

                $question->student_answer_breakdown = $answerBreakdown;
                $question->total_attempts = $answerBreakdown['total_count'];
                $question->correct_count = $answerBreakdown['correct_count'];
                $question->wrong_count = $answerBreakdown['wrong_count'];
                $question->accuracy = $question->total_attempts > 0
                    ? round(($question->correct_count / max(1, $question->total_attempts)) * 100, 1)
                    : 0;
                $question->outcome_meta = LearningOutcomeAnalytics::quizOutcomeMetadata($question);

                return $question;
            });

        $blueprints = collect(LearningOutcomeAnalytics::quizOutcomeBlueprints());
        $chapterMeta = [
            1 => ['label' => 'Bab 1', 'title' => 'Pendahuluan', 'description' => 'HTML, CSS, Tailwind CSS, CDN, instalasi.'],
            2 => ['label' => 'Bab 2', 'title' => 'Layouting', 'description' => 'Layout, flex, grid, dan breakpoint.'],
            3 => ['label' => 'Bab 3', 'title' => 'Styling', 'description' => 'Styling elemen web dengan Tailwind CSS.'],
            99 => ['label' => 'Evaluasi Akhir', 'title' => 'Rangkuman', 'description' => 'Ringkasan capaian lintas bab.'],
        ];

        $chapters = $blueprints
            ->map(function (array $objectives, int $chapterId) use ($questions, $chapterMeta) {
                $chapterQuestions = $questions->where('chapter_id', $chapterId);
                $objectiveRows = collect($objectives)->map(function (array $objective, int $index) use ($chapterQuestions, $chapterId) {
                    $objectiveQuestions = $chapterQuestions->filter(function ($question) use ($objective) {
                        return ($question->outcome_meta['code'] ?? '') === $objective['code'];
                    })->values();

                    $totalAnswers = $objectiveQuestions->sum('total_attempts');
                    $correctCount = $objectiveQuestions->sum('correct_count');
                    $wrongCount = $objectiveQuestions->sum('wrong_count');
                    $masteryPercent = $totalAnswers > 0 ? round(($correctCount / max(1, $totalAnswers)) * 100, 1) : 0;
                    $statusKey = $objectiveQuestions->count() === 0
                        ? 'empty'
                        : ($totalAnswers === 0 ? 'waiting' : ($masteryPercent < 70 ? 'attention' : 'stable'));
                    $statusLabel = [
                        'empty' => 'Belum Ada Soal',
                        'waiting' => 'Menunggu Data',
                        'attention' => 'Perlu Penguatan',
                        'stable' => 'Tercukupi',
                    ][$statusKey];

                    return [
                        'code' => $objective['code'],
                        'display_code' => ($chapterId === 99 ? 'Evaluasi Akhir' : 'Bab ' . $chapterId) . ' - TP' . ($index + 1),
                        'title' => $objective['title'],
                        'material' => $objective['material'],
                        'question_count' => $objectiveQuestions->count(),
                        'total_answers' => $totalAnswers,
                        'correct_count' => $correctCount,
                        'wrong_count' => $wrongCount,
                        'mastery_percent' => $masteryPercent,
                        'status_key' => $statusKey,
                        'status_label' => $statusLabel,
                        'needs_questions' => $objectiveQuestions->count() === 0,
                        'needs_attention' => $totalAnswers > 0 && $masteryPercent < 70,
                        'student_breakdown' => [
                            'correct_count' => $correctCount,
                            'wrong_count' => $wrongCount,
                            'total_count' => $totalAnswers,
                            'correct_percent' => $totalAnswers > 0 ? round(($correctCount / max(1, $totalAnswers)) * 100, 1) : 0,
                            'wrong_percent' => $totalAnswers > 0 ? round(($wrongCount / max(1, $totalAnswers)) * 100, 1) : 0,
                        ],
                        'activity_data' => sprintf(
                            '%s soal, %s jawaban terkumpul, %s benar, %s salah.',
                            $objectiveQuestions->count(),
                            $totalAnswers,
                            $correctCount,
                            $wrongCount
                        ),
                        'direction' => match ($statusKey) {
                            'empty' => 'Tambahkan soal yang mengukur ' . $objective['title'] . ' pada bank soal bab ini.',
                            'waiting' => 'Kumpulkan pengerjaan siswa terlebih dahulu agar capaian TP terbaca.',
                            'attention' => 'Arahkan siswa kembali ke ' . $objective['material'] . ' sebelum evaluasi berikutnya.',
                            default => 'Pertahankan penguatan singkat dan siapkan variasi soal pada ' . $objective['material'] . '.',
                        },
                        'questions' => $objectiveQuestions
                            ->sortBy('id')
                            ->map(fn ($question) => [
                                'id' => $question->id,
                                'text' => Str::limit(strip_tags((string) $question->question_text), 95),
                                'accuracy' => $question->accuracy,
                                'correct_count' => $question->correct_count,
                                'wrong_count' => $question->wrong_count,
                                'type' => [
                                    'multiple_choice' => 'Pilihan Ganda',
                                    'image_context' => 'Gambar',
                                ][$question->interaction_type ?? 'multiple_choice'] ?? 'Pilihan Ganda',
                                'has_media' => !empty($question->media_url),
                            ])
                            ->values()
                            ->all(),
                    ];
                })->values();

                return [
                    'id' => $chapterId,
                    'label' => $chapterMeta[$chapterId]['label'] ?? 'Bab ' . $chapterId,
                    'title' => $chapterMeta[$chapterId]['title'] ?? 'Materi Kuis',
                    'description' => $chapterMeta[$chapterId]['description'] ?? 'Tujuan pembelajaran pada bab ini.',
                    'question_count' => $chapterQuestions->count(),
                    'mapped_question_count' => $objectiveRows->sum('question_count'),
                    'objective_count' => $objectiveRows->count(),
                    'attention_count' => $objectiveRows->filter(fn ($row) => $row['needs_questions'] || $row['needs_attention'])->count(),
                    'average_mastery' => $objectiveRows->count() > 0 ? round($objectiveRows->avg('mastery_percent'), 1) : 0,
                    'empty_count' => $objectiveRows->where('status_key', 'empty')->count(),
                    'objectives' => $objectiveRows,
                    'bank_url' => route('admin.analytics.questions', ['chapter' => $chapterId]),
                ];
            })
            ->values();

        $totals = [
            'chapters' => $chapters->count(),
            'objectives' => $chapters->sum('objective_count'),
            'questions' => $questions->count(),
            'attention' => $chapters->sum('attention_count'),
        ];

        return view('admin.learning_outcomes', compact('chapters', 'totals'));
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
