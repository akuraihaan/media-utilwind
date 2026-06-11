<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoClassUsersSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $now = now();

            $this->removePreviousDemoCohort();
            $this->ensureBaseClasses($now);

            foreach ($this->fullProgressDemoAccounts() as $account) {
                $user = $this->upsertStudent($account, $now, 4000);
                $this->clearProgress($user->id);
                $this->seedFullProgress($user->id, $now);
            }

            foreach ($this->regularAccounts() as $account) {
                $user = $this->upsertStudent($account, $now, 0);
                $this->clearProgress($user->id);
                $this->seedFailedProgress($user->id, $now);
            }
        });
    }

    private function removePreviousDemoCohort(): void
    {
        $emails = DB::table('users')
            ->where(function ($query) {
                $query
                    ->whereIn('email', $this->generatedEmails())
                    ->orWhere('email', 'demo@example.com')
                    ->orWhere('email', 'full.progress@example.com')
                    ->orWhere('email', 'like', 'demo+%@example.com')
                    ->orWhere('email', 'like', 'demo2026-%@example.com')
                    ->orWhere('email', 'like', 'demo2027-%@example.com')
                    ->orWhere('email', 'like', 'pemweb2026-%@example.com')
                    ->orWhere('email', 'like', 'pemweb2027-%@example.com');
            })
            ->pluck('email');

        foreach ($emails as $email) {
            $user = DB::table('users')->where('email', $email)->first();

            if ($user) {
                $this->clearProgress((int) $user->id);
                DB::table('users')->where('id', $user->id)->delete();
            }
        }

        DB::table('class_groups')->where('name', 'like', 'DEMO KELAS %')->delete();
    }

    private function ensureBaseClasses($now): void
    {
        $classes = [
            ['name' => 'PEMWEB 2026', 'major' => 'Pemrograman Web 2026', 'token' => '3BEJBE'],
            ['name' => 'PEMWEB 2027', 'major' => 'Pemrograman Web 2027', 'token' => 'HHGVYW'],
        ];

        foreach ($classes as $class) {
            DB::table('class_groups')->updateOrInsert(
                ['name' => $class['name']],
                [
                    'major' => $class['major'],
                    'token' => $class['token'],
                    'is_active' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }

    private function upsertStudent(array $account, $now, int $xp): User
    {
        return User::updateOrCreate(
            ['email' => $account['email']],
            [
                'name' => $account['name'],
                'class_group' => $account['class_group'],
                'xp' => $xp,
                'institution' => 'SMK Negeri 1 Banjarmasin',
                'study_program' => 'Pemrograman Web',
                'phone' => $account['phone'],
                'email_verified_at' => $now,
                'password' => Hash::make('password123'),
                'role' => 'student',
                'updated_at' => $now,
            ]
        );
    }

    private function clearProgress(int $userId): void
    {
        $attemptIds = DB::table('quiz_attempts')->where('user_id', $userId)->pluck('id');

        if ($attemptIds->isNotEmpty()) {
            DB::table('quiz_attempt_answers')->whereIn('quiz_attempt_id', $attemptIds)->delete();
        }

        DB::table('quiz_attempts')->where('user_id', $userId)->delete();
        DB::table('lab_sessions')->where('user_id', $userId)->delete();
        DB::table('lab_histories')->where('user_id', $userId)->delete();
        DB::table('user_lesson_progress')->where('user_id', $userId)->delete();
        DB::table('user_activity_progress')->where('user_id', $userId)->delete();
        DB::table('course_progress')->where('user_id', $userId)->delete();
        DB::table('user_course_progress')->where('user_id', $userId)->delete();
        DB::table('user_badges')->where('user_id', $userId)->delete();
    }

    private function seedFullProgress(int $userId, $now): void
    {
        $this->seedLessonProgress($userId, $now);
        $this->seedActivityProgress($userId, $now);
        $this->seedQuizProgress($userId, $now);
        $this->seedLabProgress($userId, $now);
        $this->seedCourseProgress($userId, $now);
        $this->seedBadges($userId, $now);
    }

    private function seedLessonProgress(int $userId, $now): void
    {
        $rows = DB::table('course_lessons')
            ->orderBy('id')
            ->pluck('id')
            ->map(fn ($lessonId) => [
                'user_id' => $userId,
                'course_lesson_id' => $lessonId,
                'completed' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->all();

        if ($rows) {
            DB::table('user_lesson_progress')->insert($rows);
        }
    }

    private function seedActivityProgress(int $userId, $now): void
    {
        $rows = DB::table('course_activities')
            ->orderBy('id')
            ->pluck('id')
            ->map(fn ($activityId) => [
                'user_id' => $userId,
                'course_activity_id' => $activityId,
                'completed' => 1,
                'score' => 100,
                'completed_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->all();

        if ($rows) {
            DB::table('user_activity_progress')->insert($rows);
        }
    }

    private function seedQuizProgress(int $userId, $now): void
    {
        $chapters = DB::table('quiz_questions')
            ->select('chapter_id')
            ->distinct()
            ->orderByRaw('CAST(chapter_id AS UNSIGNED)')
            ->pluck('chapter_id');

        foreach ($chapters as $chapterId) {
            $questions = DB::table('quiz_questions')
                ->where('chapter_id', $chapterId)
                ->orderBy('id')
                ->get();

            $attemptId = DB::table('quiz_attempts')->insertGetId([
                'user_id' => $userId,
                'chapter_id' => $chapterId,
                'started_at' => $now->copy()->subMinutes(12),
                'score' => 100,
                'time_spent_seconds' => 720,
                'answered_count' => $questions->count(),
                'unanswered_count' => 0,
                'flagged_count' => 0,
                'focus_lost_count' => 0,
                'feedback_level' => 'excellent',
                'feedback_message' => 'Seluruh jawaban benar.',
                'reflection_note' => 'Saya sudah memahami materi dan siap melanjutkan.',
                'completed_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $answers = [];

            foreach ($questions as $question) {
                $correctOption = DB::table('quiz_options')
                    ->where('quiz_question_id', $question->id)
                    ->where('is_correct', 1)
                    ->first();

                if (! $correctOption) {
                    continue;
                }

                $answers[] = [
                    'quiz_attempt_id' => $attemptId,
                    'quiz_question_id' => $question->id,
                    'quiz_option_id' => $correctOption->id,
                    'is_correct' => 1,
                    'answer_change_count' => 0,
                    'client_elapsed_seconds' => 15,
                    'first_answered_at' => $now,
                    'last_answered_at' => $now,
                    'is_flagged' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if ($answers) {
                DB::table('quiz_attempt_answers')->insert($answers);
            }
        }
    }

    private function seedLabProgress(int $userId, $now): void
    {
        $labs = DB::table('labs')->where('is_active', 1)->orderBy('chapter_id')->get();

        foreach ($labs as $lab) {
            $stepIds = DB::table('lab_steps')
                ->where('lab_id', $lab->id)
                ->orderBy('order_index')
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->values()
                ->all();

            DB::table('lab_histories')->insert([
                'user_id' => $userId,
                'lab_id' => $lab->id,
                'final_score' => 100,
                'status' => 'passed',
                'duration_seconds' => 1800,
                'last_code_snapshot' => '<!-- Semua task lab selesai -->',
                'source_code' => '<!-- Semua task lab selesai -->',
                'completed_steps' => json_encode($stepIds),
                'completed_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    private function seedCourseProgress(int $userId, $now): void
    {
        $lessonIds = DB::table('course_lessons')->orderBy('id')->pluck('id')->map(fn ($id) => (int) $id)->values();

        DB::table('course_progress')->insert([
            'user_id' => $userId,
            'course' => 'tailwind-css',
            'progress' => 100,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('user_course_progress')->insert([
            'user_id' => $userId,
            'course_slug' => 'tailwind-css',
            'completed_lessons' => json_encode($lessonIds),
            'progress_percent' => 100,
            'current_level' => 'expert',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function seedBadges(int $userId, $now): void
    {
        $rows = DB::table('badges')
            ->orderBy('id')
            ->pluck('id')
            ->map(fn ($badgeId) => [
                'user_id' => $userId,
                'badge_id' => $badgeId,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->all();

        if ($rows) {
            DB::table('user_badges')->insert($rows);
        }
    }

    private function seedFailedProgress(int $userId, $now): void
    {
        $this->seedFailedQuizProgress($userId, $now);
        $this->seedFailedLabProgress($userId, $now);
    }

    private function seedFailedQuizProgress(int $userId, $now): void
    {
        $chapterScores = [
            1 => 45,
            2 => 55,
            3 => 60,
            99 => 50,
        ];

        foreach ($chapterScores as $chapterId => $score) {
            $questions = DB::table('quiz_questions')
                ->where('chapter_id', (string) $chapterId)
                ->orderBy('id')
                ->get();

            if ($questions->isEmpty()) {
                continue;
            }

            $attemptId = DB::table('quiz_attempts')->insertGetId([
                'user_id' => $userId,
                'chapter_id' => $chapterId,
                'started_at' => $now->copy()->subMinutes(10),
                'score' => $score,
                'time_spent_seconds' => 600,
                'answered_count' => $questions->count(),
                'unanswered_count' => 0,
                'flagged_count' => 0,
                'focus_lost_count' => 1,
                'feedback_level' => 'remedial',
                'feedback_message' => 'Nilai belum mencapai KKM. Siswa perlu mengulang materi dan evaluasi.',
                'reflection_note' => 'Saya perlu mempelajari ulang materi sebelum mencoba lagi.',
                'completed_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $answers = [];
            foreach ($questions as $index => $question) {
                $option = DB::table('quiz_options')
                    ->where('quiz_question_id', $question->id)
                    ->where('is_correct', $index % 2 === 0 ? 0 : 1)
                    ->orderBy('id')
                    ->first();

                if (! $option) {
                    $option = DB::table('quiz_options')
                        ->where('quiz_question_id', $question->id)
                        ->orderBy('id')
                        ->first();
                }

                if (! $option) {
                    continue;
                }

                $answers[] = [
                    'quiz_attempt_id' => $attemptId,
                    'quiz_question_id' => $question->id,
                    'quiz_option_id' => $option->id,
                    'is_correct' => (int) $option->is_correct,
                    'answer_change_count' => 1,
                    'client_elapsed_seconds' => 12,
                    'first_answered_at' => $now,
                    'last_answered_at' => $now,
                    'is_flagged' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if ($answers) {
                DB::table('quiz_attempt_answers')->insert($answers);
            }
        }
    }

    private function seedFailedLabProgress(int $userId, $now): void
    {
        $labScores = [35, 45, 55, 60];
        $labs = DB::table('labs')->where('is_active', 1)->orderBy('chapter_id')->get();

        foreach ($labs as $index => $lab) {
            DB::table('lab_histories')->insert([
                'user_id' => $userId,
                'lab_id' => $lab->id,
                'final_score' => $labScores[$index] ?? 50,
                'status' => 'failed',
                'duration_seconds' => 1200,
                'last_code_snapshot' => '<!-- Belum memenuhi seluruh validation rules lab -->',
                'source_code' => '<!-- Belum memenuhi seluruh validation rules lab -->',
                'completed_steps' => json_encode([]),
                'completed_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    private function fullProgressDemoAccounts(): array
    {
        return [
            ['name' => 'Nabila Putri', 'email' => 'nabila.putri26@gmail.com', 'class_group' => 'PEMWEB 2026', 'phone' => '081260260001'],
            ['name' => 'Rizal Fadli', 'email' => 'rizal.fadli26@gmail.com', 'class_group' => 'PEMWEB 2026', 'phone' => '081260260002'],
            ['name' => 'Siti Aisyah', 'email' => 'siti.aisyah26@gmail.com', 'class_group' => 'PEMWEB 2026', 'phone' => '081260260003'],
            ['name' => 'Ahmad Farhan', 'email' => 'ahmad.farhan27@gmail.com', 'class_group' => 'PEMWEB 2027', 'phone' => '081270270001'],
            ['name' => 'Dewi Kartika', 'email' => 'dewi.kartika27@gmail.com', 'class_group' => 'PEMWEB 2027', 'phone' => '081270270002'],
            ['name' => 'Raka Prasetyo', 'email' => 'raka.prasetyo27@gmail.com', 'class_group' => 'PEMWEB 2027', 'phone' => '081270270003'],
        ];
    }

    private function regularAccounts(): array
    {
        $names2026 = [
            'Aulia Rahman', 'Bagas Saputra', 'Citra Lestari', 'Dimas Pratama', 'Elsa Maharani',
            'Fikri Ramadhan', 'Gita Permata', 'Hafiz Nugroho', 'Intan Safitri', 'Jihan Maulana',
        ];

        $names2027 = [
            'Kirana Putri', 'Lutfi Hakim', 'Maya Anggraini', 'Naufal Ardiansyah', 'Olivia Zahra',
            'Putra Wijaya', 'Qori Fadillah', 'Rizky Amelia', 'Salsa Nabila', 'Tegar Mahendra',
        ];

        $accounts = [];

        foreach ($names2026 as $index => $name) {
            $number = $index + 1;
            $accounts[] = [
                'name' => $name,
                'email' => $this->emailFromName($name, '26'),
                'class_group' => 'PEMWEB 2026',
                'phone' => sprintf('0826202600%02d', $number),
            ];
        }

        foreach ($names2027 as $index => $name) {
            $number = $index + 1;
            $accounts[] = [
                'name' => $name,
                'email' => $this->emailFromName($name, '27'),
                'class_group' => 'PEMWEB 2027',
                'phone' => sprintf('0827202700%02d', $number),
            ];
        }

        return $accounts;
    }

    private function generatedEmails(): array
    {
        return collect($this->fullProgressDemoAccounts())
            ->merge($this->regularAccounts())
            ->pluck('email')
            ->all();
    }

    private function emailFromName(string $name, string $suffix): string
    {
        $localPart = strtolower(str_replace(' ', '.', $name));

        return "{$localPart}{$suffix}@gmail.com";
    }
}
