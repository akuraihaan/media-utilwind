<?php

namespace Tests\Feature;

use App\Models\ClassGroup;
use App\Models\Course;
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
            ->assertOk();
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
