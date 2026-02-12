<?php

namespace App\Http\Controllers\Admin; // Perhatikan namespace ini

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

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
        
        // 5. DATA USER MANAGEMENT
        $users = User::orderByDesc('created_at')->limit(50)->get(); // Limit agar tidak berat

        return view('admin.dashboard', compact(
            'totalStudents', 'totalAttempts', 'globalAverage', 'remedialCount', 'totalLabsCompleted',
            'chartLabels', 'chartScores', 'questionStats', 'topStudents', 'recentActivities', 'users'
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
            'class_group' => $request->class_group,
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
     * UPDATE STUDENT PROFILE (FULL CRUD)
     */
    public function updateStudent(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // 1. Validasi Input
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,'.$id, // Ignore current user email
            'class_group'   => 'nullable|in:A1,A2,A3',
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
            // Hapus avatar lama jika bukan default (Opsional, sesuaikan path)
            // if ($user->avatar && file_exists(public_path('uploads/avatars/'.$user->avatar))) {
            //    unlink(public_path('uploads/avatars/'.$user->avatar));
            // }

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

    public function deleteUser($id) {
        if(auth()->id() == $id) return response()->json(['status' => 'error'], 403);
        User::findOrFail($id)->delete();
        return response()->json(['status' => 'success']);
    }

    /**
     * INSIGHT: STUDENT DETAIL
     */
    public function studentDetail($id)
    {
        $user = User::findOrFail($id);

        // A. LAB DATA
        $labHistories = DB::table('lab_histories')
            ->join('labs', 'lab_histories.lab_id', '=', 'labs.id')
            ->select('lab_histories.*', 'labs.title as lab_title')
            ->where('user_id', $id)
            ->orderByDesc('created_at')
            ->get();

        $labStats = [
            'total' => $labHistories->count(),
            'passed' => $labHistories->where('status', 'passed')->count(),
            'avg_score' => $labHistories->avg('final_score') ?? 0,
            'total_duration' => $labHistories->sum('duration_seconds'),
        ];

        // B. QUIZ DATA
        $quizAttempts = DB::table('quiz_attempts') 
            ->select('*') 
            ->where('user_id', $id)
            ->orderByDesc('created_at')
            ->get();

        $quizStats = [
            'total' => $quizAttempts->count(),
            'avg_score' => $quizAttempts->avg('score') ?? 0,
            'highest' => $quizAttempts->max('score') ?? 0,
        ];

        // C. CHART DATA
        $chartData = $labHistories->sortBy('created_at')->take(-10);
        $chartLabels = $chartData->map(fn($item) => Str::limit($item->lab_title, 12))->values();
        $chartScores = $chartData->pluck('final_score')->values();

        return view('admin.student_detail', compact(
            'user', 'labHistories', 'labStats', 'quizAttempts', 'quizStats', 'chartLabels', 'chartScores'
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
        $questions = \App\Models\QuizQuestion::with(['answers.attempt.user'])
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

        return view('admin.question_analytics', compact('questions'));
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
}