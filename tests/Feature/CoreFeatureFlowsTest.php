<?php

namespace Tests\Feature;

use App\Models\ClassGroup;
use App\Models\Course;
use App\Models\Lab;
use App\Models\LabSession;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CoreFeatureFlowsTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_register_login_and_logout_flow(): void
    {
        $this->post('/register', [
            'name' => 'Siswa Baru',
            'email' => 'siswa@example.test',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ])->assertRedirect('/login');

        $this->assertDatabaseHas('users', [
            'email' => 'siswa@example.test',
        ]);

        $this->post('/login', [
            'email' => 'siswa@example.test',
            'password' => 'secret123',
        ])->assertRedirect('/dashboard');

        $this->post('/logout')->assertRedirect('/');
    }

    public function test_student_can_join_active_class_with_token(): void
    {
        ClassGroup::create([
            'name' => 'PEMWEB JOIN',
            'major' => 'Informatika',
            'token' => 'ABC123',
            'is_active' => true,
        ]);

        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student)
            ->post('/student/join-class', ['token' => 'abc123'])
            ->assertRedirect();

        $this->assertSame('PEMWEB JOIN', $student->fresh()->class_group);
    }

    public function test_activity_lesson_and_sandbox_progress_can_be_saved(): void
    {
        $course = $this->seedLearningData();
        $student = $this->activeStudent();

        $activityId = DB::table('course_activities')->insertGetId([
            'course_id' => $course->id,
            'title' => 'Activity Smoke',
            'type' => 'activity',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($student)
            ->postJson('/activity/complete', [
                'activity_id' => $activityId,
                'score' => 90,
            ])
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->actingAs($student)
            ->postJson('/lesson/complete', ['lesson_id' => 5])
            ->assertOk()
            ->assertJson(['status' => 'ok']);

        $this->actingAs($student)
            ->postJson('/sandbox/complete')
            ->assertOk()
            ->assertJson(['status' => 'ok']);

        $this->assertDatabaseHas('user_activity_progress', [
            'user_id' => $student->id,
            'course_activity_id' => 99,
            'completed' => true,
        ]);
    }

    public function test_quiz_start_save_submit_and_result_flow(): void
    {
        $this->seedLearningData();
        $student = $this->activeStudent();
        [$questionId, $correctOptionId] = $this->seedQuizQuestion();

        $this->actingAs($student)
            ->post('/quiz/start', ['chapter_id' => 1])
            ->assertRedirect('/quiz/attempt/1');

        $attempt = QuizAttempt::where('user_id', $student->id)
            ->where('chapter_id', 1)
            ->whereNull('completed_at')
            ->firstOrFail();

        $this->actingAs($student)
            ->get('/quiz/attempt/1')
            ->assertOk();

        $this->actingAs($student)
            ->postJson('/quiz/save-progress', [
                'attempt_id' => $attempt->id,
                'question_id' => $questionId,
                'option_id' => $correctOptionId,
                'is_flagged' => false,
                'client_elapsed_seconds' => 12,
            ])
            ->assertOk()
            ->assertJson(['status' => 'success']);

        $this->actingAs($student)
            ->postJson('/quiz/submit', [
                'attempt_id' => $attempt->id,
                'time_spent' => 60,
                'focus_lost_count' => 0,
            ])
            ->assertOk()
            ->assertJson(['status' => 'success']);

        $this->actingAs($student)
            ->get('/quiz/result/' . $attempt->id)
            ->assertOk();
    }

    public function test_lab_start_check_and_end_flow(): void
    {
        $this->seedLearningData();
        $student = $this->activeStudent();

        $lab = Lab::create([
            'title' => 'Lab Smoke',
            'chapter_id' => 1,
            'slug' => 'lab-smoke',
            'description' => 'Lab flow test',
            'duration_minutes' => 30,
            'passing_grade' => 70,
            'is_active' => true,
        ]);

        $stepId = DB::table('lab_steps')->insertGetId([
            'lab_id' => $lab->id,
            'title' => 'Add text color',
            'instruction' => 'Use the required class',
            'initial_code' => '<p>Hello</p>',
            'validation_rules' => json_encode(['text-red-500']),
            'points' => 100,
            'order_index' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($student)
            ->get('/labs/start/' . $lab->id)
            ->assertRedirect('/labs/workspace/' . $lab->id);

        $session = LabSession::where('user_id', $student->id)
            ->where('lab_id', $lab->id)
            ->where('status', 'active')
            ->firstOrFail();

        $code = '<p class="text-red-500">Hello</p>';

        $this->actingAs($student)
            ->postJson('/labs/session/' . $session->id . '/check', [
                'step_id' => $stepId,
                'source_code' => $code,
            ])
            ->assertOk()
            ->assertJson(['status' => 'success']);

        $this->actingAs($student)
            ->postJson('/labs/session/' . $session->id . '/end', [
                'source_code' => $code,
            ])
            ->assertOk()
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('lab_histories', [
            'user_id' => $student->id,
            'lab_id' => $lab->id,
            'status' => 'passed',
            'final_score' => 100,
        ]);
    }

    public function test_admin_can_manage_class_records(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($admin)
            ->post('/admin/classes', [
                'name' => 'PEMWEB ADMIN',
                'major' => 'Informatika',
            ])
            ->assertRedirect();

        $class = ClassGroup::where('name', 'PEMWEB ADMIN')->firstOrFail();

        $this->actingAs($admin)
            ->put('/admin/classes/' . $class->id, [
                'name' => 'PEMWEB ADMIN UPDATED',
                'major' => 'Sistem Informasi',
                'is_active' => false,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('class_groups', [
            'id' => $class->id,
            'name' => 'PEMWEB ADMIN UPDATED',
            'is_active' => false,
        ]);

        $this->actingAs($admin)
            ->delete('/admin/classes/' . $class->id)
            ->assertRedirect();

        $this->assertDatabaseMissing('class_groups', [
            'id' => $class->id,
        ]);
    }

    private function activeStudent(): User
    {
        ClassGroup::firstOrCreate(
            ['name' => 'PEMWEB TEST'],
            [
                'major' => 'Informatika',
                'token' => 'TST123',
                'is_active' => true,
            ]
        );

        return User::factory()->create([
            'role' => 'student',
            'class_group' => 'PEMWEB TEST',
            'email_verified_at' => now(),
        ]);
    }

    private function seedLearningData(): Course
    {
        $course = Course::create([
            'slug' => 'tailwind-css',
            'title' => 'Tailwind CSS',
            'description' => 'Course feature flow test',
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

        return $course;
    }

    private function seedQuizQuestion(): array
    {
        $questionId = DB::table('quiz_questions')->insertGetId([
            'chapter_id' => 1,
            'question_text' => 'Utility class untuk flexbox?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $correctOptionId = DB::table('quiz_options')->insertGetId([
            'quiz_question_id' => $questionId,
            'option_text' => 'flex',
            'is_correct' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('quiz_options')->insert([
            'quiz_question_id' => $questionId,
            'option_text' => 'grid-cols-2',
            'is_correct' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [$questionId, $correctOptionId];
    }
}
