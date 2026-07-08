<?php

namespace Tests\Feature;

use App\Models\ClassGroup;
use App\Models\Course;
use App\Models\Lab;
use App\Models\LabHistory;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NavigationSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_learning_navigation_pages_do_not_error(): void
    {
        $this->seedLearningData();

        $student = User::factory()->create([
            'role' => 'student',
            'class_group' => 'PEMWEB TEST',
        ]);

        QuizAttempt::create([
            'user_id' => $student->id,
            'chapter_id' => 1,
            'score' => 80,
            'started_at' => now()->subMinutes(10),
            'completed_at' => now(),
        ]);

        $this->actingAs($student);

        foreach ([
            '/dashboard',
            '/learning-path',
            '/courses/html-css',
            '/courses/layout-basics',
        ] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_learning_path_is_public_but_material_requires_class_access(): void
    {
        $this->seedLearningData();

        $this->get('/learning-path')
            ->assertOk()
            ->assertSeeText('Silabus')
            ->assertSeeText('Silabus Publik');

        $this->get('/courses/html-css')
            ->assertRedirect('/login');

        $studentWithoutClass = User::factory()->create([
            'role' => 'student',
            'class_group' => null,
        ]);

        $this->actingAs($studentWithoutClass)
            ->get('/courses/html-css')
            ->assertRedirect('/dashboard')
            ->assertSessionHas('learning_access_error');
    }

    public function test_quiz_result_page_does_not_error(): void
    {
        $this->seedLearningData();

        $student = User::factory()->create([
            'role' => 'student',
            'class_group' => 'PEMWEB TEST',
        ]);

        $questionId = DB::table('quiz_questions')->insertGetId([
            'chapter_id' => 1,
            'question_text' => 'Utility class untuk flexbox?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $optionId = DB::table('quiz_options')->insertGetId([
            'quiz_question_id' => $questionId,
            'option_text' => 'flex',
            'is_correct' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $attempt = QuizAttempt::create([
            'user_id' => $student->id,
            'chapter_id' => 1,
            'score' => 100,
            'started_at' => now()->subMinutes(5),
            'completed_at' => now(),
        ]);

        DB::table('quiz_attempt_answers')->insert([
            'quiz_attempt_id' => $attempt->id,
            'quiz_question_id' => $questionId,
            'quiz_option_id' => $optionId,
            'is_correct' => true,
            'is_flagged' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($student)
            ->get('/quiz/result/' . $attempt->id)
            ->assertOk()
            ->assertSeeText('Ringkasan materi')
            ->assertSeeText('Prioritas belajar');
    }

    public function test_lab_result_page_does_not_error(): void
    {
        $this->seedLearningData();

        $student = User::factory()->create([
            'role' => 'student',
            'class_group' => 'PEMWEB TEST',
        ]);

        $lab = Lab::create([
            'title' => 'Lab Smoke',
            'chapter_id' => 1,
            'slug' => 'lab-smoke',
            'description' => 'Lab result smoke test',
            'duration_minutes' => 30,
            'passing_grade' => 70,
            'is_active' => true,
        ]);

        $stepId = DB::table('lab_steps')->insertGetId([
            'lab_id' => $lab->id,
            'title' => 'Gunakan warna teks',
            'instruction' => 'Tambahkan class text-red-500.',
            'initial_code' => '<p>Hello</p>',
            'validation_rules' => json_encode(['text-red-500']),
            'points' => 100,
            'order_index' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $history = LabHistory::create([
            'user_id' => $student->id,
            'lab_id' => $lab->id,
            'last_code_snapshot' => '<p class="text-red-500">Hello</p>',
            'source_code' => '<p class="text-red-500">Hello</p>',
            'status' => 'passed',
            'final_score' => 100,
            'duration_seconds' => 180,
            'completed_steps' => json_encode([$stepId]),
            'completed_at' => now(),
        ]);

        $this->actingAs($student)
            ->get('/labs/result/' . $history->id)
            ->assertOk()
            ->assertSeeText('Hasil akhir')
            ->assertSeeText('Ringkasan materi')
            ->assertSeeText('Prioritas perbaikan')
            ->assertSee(route('lab.workspace.history', ['historyId' => $history->id]));

        $this->actingAs($student)
            ->get('/labs/workspace/history/' . $history->id)
            ->assertOk()
            ->assertSeeText('MODE TINJAU')
            ->assertSee('text-red-500');

        $this->actingAs($student)
            ->get('/dashboard')
            ->assertOk()
            ->assertSeeText('Tinjauan Praktik')
            ->assertSeeText('Buka Detail Praktik')
            ->assertSeeText('Praktik: Lab Smoke');

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get('/admin/labs/results/' . $history->id)
            ->assertOk()
            ->assertSeeText('Tinjauan Hasil Lab Siswa')
            ->assertSeeText('Bagian yang Perlu Dicek')
            ->assertDontSeeText('Analisis Tersembunyi')
            ->assertDontSeeText('Rincian Cuplikan Kode')
            ->assertSeeText('Cuplikan Kode Akhir');
    }

    public function test_admin_dashboard_does_not_error(): void
    {
        $this->seedLearningData();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk();
    }

    public function test_admin_primary_sidebar_navigation_links_are_reachable(): void
    {
        $this->seedLearningData();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        $student = User::factory()->create([
            'role' => 'student',
            'class_group' => 'PEMWEB TEST',
        ]);
        QuizAttempt::create([
            'user_id' => $student->id,
            'chapter_id' => 1,
            'score' => 88,
            'time_spent_seconds' => 320,
            'focus_lost_count' => 1,
            'started_at' => now()->subMinutes(8),
            'completed_at' => now(),
        ]);

        $this->actingAs($admin);

        $routes = [
            'admin.dashboard',
            'admin.guide',
            'admin.analytics.questions',
            'admin.lab.analytics',
            'admin.labs.index',
            'admin.questions.create',
            'admin.students.index',
            'admin.classes.index',
        ];

        foreach ($routes as $routeName) {
            $this->get(route($routeName))
                ->assertOk();
        }

        $this->get(route('admin.learning-outcomes.index'))
            ->assertRedirect(route('admin.analytics.questions'));

        $this->get(route('admin.quiz.student.analytics', $student->id))
            ->assertOk()
            ->assertSeeText('Ringkasan Kuis Siswa')
            ->assertSeeText('Log Pengerjaan Kuis');
    }

    public function test_admin_lab_analytics_is_lab_only_and_lists_all_students(): void
    {
        $this->seedLearningData();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $lab = Lab::create([
            'title' => 'Lab Analitik',
            'chapter_id' => 1,
            'slug' => 'lab-analitik',
            'description' => 'Lab analytics smoke test',
            'duration_minutes' => 30,
            'passing_grade' => 70,
            'is_active' => true,
        ]);

        $students = collect([
            User::factory()->create(['role' => 'student', 'name' => 'Siswa Nilai Tinggi', 'class_group' => 'KELAS A']),
            User::factory()->create(['role' => 'student', 'name' => 'Siswa Perlu Bimbingan', 'class_group' => 'KELAS B']),
        ]);

        ClassGroup::create([
            'name' => 'KELAS C',
            'major' => 'Rekayasa Perangkat Lunak',
            'token' => 'KELASC1',
            'is_active' => true,
        ]);

        User::factory()->create([
            'role' => 'student',
            'name' => 'Siswa Baru Praktik',
            'class_group' => 'KELAS C',
        ]);

        foreach ([95, 45] as $index => $score) {
            LabHistory::create([
                'user_id' => $students[$index]->id,
                'lab_id' => $lab->id,
                'last_code_snapshot' => '<div class="p-4">Lab</div>',
                'source_code' => '<div class="p-4">Lab</div>',
                'status' => $score >= 70 ? 'passed' : 'failed',
                'final_score' => $score,
                'duration_seconds' => 180 + ($index * 60),
                'completed_steps' => json_encode([]),
                'completed_at' => now(),
            ]);
        }

        $this->actingAs($admin)
            ->get('/admin/analytics/lab')
            ->assertOk()
            ->assertSeeText('Ringkasan Per Kelas')
            ->assertSeeText('KELAS A')
            ->assertSeeText('KELAS B')
            ->assertSeeText('Rata-rata Skor per Lab')
            ->assertSeeText('Rincian Percobaan Lab')
            ->assertSeeText('Siswa Nilai Tinggi')
            ->assertSeeText('Siswa Perlu Bimbingan')
            ->assertDontSeeText('Grafik Perkembangan Nilai Kuis dan Lab')
            ->assertDontSeeText('Nilai Kuis');

        $this->actingAs($admin)
            ->get('/admin/analytics/lab?class_group=KELAS%20A')
            ->assertOk()
            ->assertSeeText('Siswa Nilai Tinggi')
            ->assertDontSeeText('Siswa Perlu Bimbingan');

        $this->actingAs($admin)
            ->get('/admin/analytics/lab?class_group=KELAS%20C')
            ->assertOk()
            ->assertSeeText('KELAS C')
            ->assertSeeText('Belum ada percobaan lab pada filter ini.')
            ->assertDontSeeText('Siswa Nilai Tinggi')
            ->assertDontSeeText('Siswa Perlu Bimbingan');
    }

    public function test_admin_question_analytics_accepts_period_and_class_filters(): void
    {
        $this->seedLearningData();

        $admin = User::factory()->create(['role' => 'admin']);

        ClassGroup::create([
            'name' => 'KELAS SOAL',
            'major' => 'Rekayasa Perangkat Lunak',
            'token' => 'SOALCLS1',
            'is_active' => true,
        ]);

        $student = User::factory()->create([
            'role' => 'student',
            'name' => 'Siswa Analitik Soal',
            'class_group' => 'KELAS SOAL',
        ]);

        $questionId = DB::table('quiz_questions')->insertGetId([
            'chapter_id' => 1,
            'question_text' => 'Properti CSS untuk layout flex?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $optionId = DB::table('quiz_options')->insertGetId([
            'quiz_question_id' => $questionId,
            'option_text' => 'display: flex',
            'is_correct' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $attempt = QuizAttempt::create([
            'user_id' => $student->id,
            'chapter_id' => 1,
            'score' => 100,
            'started_at' => now()->subMinutes(5),
            'completed_at' => now(),
        ]);

        DB::table('quiz_attempt_answers')->insert([
            'quiz_attempt_id' => $attempt->id,
            'quiz_question_id' => $questionId,
            'quiz_option_id' => $optionId,
            'is_correct' => true,
            'is_flagged' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/analytics/questions?class_group=KELAS%20SOAL&period=30d')
            ->assertOk()
            ->assertSeeText('Filter Data')
            ->assertSeeText('30 hari terakhir')
            ->assertSeeText('KELAS SOAL');
    }

    public function test_admin_lab_configuration_uses_indonesian_labels(): void
    {
        $this->seedLearningData();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        Lab::create([
            'title' => 'Lab Konfigurasi',
            'chapter_id' => 1,
            'slug' => 'lab-konfigurasi',
            'description' => 'Lab configuration smoke test',
            'duration_minutes' => 45,
            'passing_grade' => 75,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get('/admin/labs')
            ->assertOk()
            ->assertSeeText('Direktori Lab')
            ->assertSeeText('Informasi Modul')
            ->assertSeeText('Nilai Lulus')
            ->assertDontSeeText('Lab Directory')
            ->assertDontSeeText('Module Info');
    }

    private function seedLearningData(): void
    {
        ClassGroup::create([
            'name' => 'PEMWEB TEST',
            'major' => 'Informatika',
            'token' => 'TEST2026',
            'is_active' => true,
        ]);

        $course = Course::create([
            'slug' => 'tailwind-css',
            'title' => 'Tailwind CSS',
            'description' => 'Course smoke test',
        ]);

        for ($id = 1; $id <= 65; $id++) {
            DB::table('course_lessons')->insert([
                'id' => $id,
                'course_id' => $course->id,
                'title' => 'Lesson ' . $id,
                'order' => $id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
