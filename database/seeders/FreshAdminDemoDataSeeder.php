<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class FreshAdminDemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->resetStudentAndProgressData();
        $this->call(AdminUsersSeeder::class);

        $now = now();

        $this->seedClassGroups($now);
        $this->seedNaturalImageQuestions($now);
        $this->seedNaturalImageLabTask($now);
        $this->seedFiveStudents($now);
    }

    private function resetStudentAndProgressData(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ([
            'quiz_attempt_answers',
            'quiz_attempts',
            'lab_sessions',
            'lab_histories',
            'user_lesson_progress',
            'user_activity_progress',
            'course_progress',
            'user_course_progress',
            'user_badges',
        ] as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        if (Schema::hasTable('users')) {
            DB::table('users')
                ->where(fn ($query) => $query->where('role', '<>', 'admin')->orWhereNull('role'))
                ->delete();
        }

        if (Schema::hasTable('class_groups')) {
            DB::table('class_groups')->delete();
        }

        Schema::enableForeignKeyConstraints();
    }

    private function seedClassGroups($now): void
    {
        if (!Schema::hasTable('class_groups')) {
            return;
        }

        foreach ([
            ['name' => 'KELAS ANALITIK A', 'major' => 'Pemrograman Web - Kelompok A', 'token' => 'ANA5A1'],
            ['name' => 'KELAS ANALITIK B', 'major' => 'Pemrograman Web - Kelompok B', 'token' => 'ANA5B2'],
        ] as $class) {
            DB::table('class_groups')->insert($this->filterColumns('class_groups', [
                ...$class,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }

    private function seedNaturalImageQuestions($now): void
    {
        if (!Schema::hasTable('quiz_questions') || !Schema::hasTable('quiz_options')) {
            return;
        }

        $oldQuestionIds = DB::table('quiz_questions')
            ->where('media_path', 'like', 'quiz-media/natural-quiz-%')
            ->pluck('id');

        if ($oldQuestionIds->isNotEmpty()) {
            DB::table('quiz_options')->whereIn('quiz_question_id', $oldQuestionIds)->delete();
            DB::table('quiz_questions')->whereIn('id', $oldQuestionIds)->delete();
        }

        foreach ($this->naturalQuestions() as $question) {
            $questionId = DB::table('quiz_questions')->insertGetId($this->filterColumns('quiz_questions', [
                'chapter_id' => $question['chapter_id'],
                'learning_objective_code' => $question['tp_code'],
                'learning_objective_title' => $question['tp_title'],
                'remediation_hint' => $question['remediation_hint'],
                'interaction_type' => 'image_context',
                'interaction_prompt' => 'Amati gambar natural tanpa teks, lalu pilih jawaban yang paling tepat.',
                'media_type' => 'image',
                'media_url' => '/uploads/' . $question['media_path'],
                'media_path' => $question['media_path'],
                'media_caption' => null,
                'question_text' => $question['question_text'],
                'created_at' => $now,
                'updated_at' => $now,
            ]));

            foreach ($question['options'] as $key => $optionText) {
                DB::table('quiz_options')->insert($this->filterColumns('quiz_options', [
                    'quiz_question_id' => $questionId,
                    'option_text' => $optionText,
                    'is_correct' => $key === $question['correct'] ? 1 : 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }
    }

    private function seedNaturalImageLabTask($now): void
    {
        if (!Schema::hasTable('labs') || !Schema::hasTable('lab_steps')) {
            return;
        }

        if (DB::table('labs')->count() === 0) {
            $this->call(FullCourseLabsSeeder::class);
        }

        $lab = DB::table('labs')
            ->where('slug', 'lab-03-styling-tipografi-komponen')
            ->first() ?: DB::table('labs')->where('is_active', 1)->orderBy('id')->first();

        if (!$lab) {
            return;
        }

        DB::table('lab_steps')
            ->where('lab_id', $lab->id)
            ->where('title', 'Task Baru: Kartu Gambar Natural Responsif')
            ->delete();

        $nextOrder = ((int) DB::table('lab_steps')->where('lab_id', $lab->id)->max('order_index')) + 1;

        DB::table('lab_steps')->insert($this->filterColumns('lab_steps', [
            'lab_id' => $lab->id,
            'title' => 'Task Baru: Kartu Gambar Natural Responsif',
            'learning_objective_code' => 'TP2',
            'learning_objective_title' => 'Menerapkan class Tailwind CSS untuk mengatur tampilan elemen web.',
            'remediation_hint' => 'Gunakan elemen img, container overflow-hidden, aspect-video, object-cover, dan rounded-xl agar gambar rapi tanpa teks tambahan.',
            'instruction' => 'Buat kartu gambar natural tanpa teks. Gunakan elemen <img>, bungkus gambar dalam container overflow-hidden, lalu atur gambar dengan aspect-video, object-cover, dan rounded-xl.',
            'initial_code' => <<<'HTML'
<article class="bg-white p-4 rounded-xl shadow-md">
    <div class="">
        <img src="/uploads/quiz-media/natural-quiz-02.png" alt="Gambar natural tanpa teks" class="">
    </div>
</article>
HTML,
            'validation_rules' => json_encode(['<img', 'overflow-hidden', 'aspect-video', 'object-cover', 'rounded-xl']),
            'points' => 20,
            'order_index' => $nextOrder,
            'created_at' => $now,
            'updated_at' => $now,
        ]));
    }

    private function seedFiveStudents($now): void
    {
        $students = $this->studentProfiles();

        foreach ($students as $index => $profile) {
            $userId = DB::table('users')->insertGetId($this->filterColumns('users', [
                'name' => $profile['name'],
                'email' => $profile['email'],
                'class_group' => $profile['class_group'],
                'xp' => $profile['xp'],
                'institution' => 'SMK Negeri 1 Banjarmasin',
                'study_program' => 'Pemrograman Web',
                'phone' => $profile['phone'],
                'email_verified_at' => $now,
                'password' => Hash::make('password123'),
                'role' => 'student',
                'created_at' => $now,
                'updated_at' => $now,
            ]));

            $this->seedCourseProgress($userId, $profile, $now);
            $this->seedLessonProgress($userId, $profile['progress'], $now);
            $this->seedActivityProgress($userId, $profile, $now);
            $this->seedQuizAttempts($userId, $profile, $index, $now);
            $this->seedLabHistories($userId, $profile, $index, $now);
            $this->seedBadges($userId, $profile['progress'], $now);
        }
    }

    private function seedCourseProgress(int $userId, array $profile, $now): void
    {
        if (Schema::hasTable('course_progress')) {
            DB::table('course_progress')->insert($this->filterColumns('course_progress', [
                'user_id' => $userId,
                'course' => 'tailwind-css',
                'progress' => $profile['progress'],
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        if (Schema::hasTable('user_course_progress')) {
            $lessonIds = DB::table('course_lessons')->orderBy('id')->pluck('id')->map(fn ($id) => (int) $id)->values();
            $completedCount = $this->countByPercent($lessonIds->count(), $profile['progress']);

            DB::table('user_course_progress')->insert($this->filterColumns('user_course_progress', [
                'user_id' => $userId,
                'course_slug' => 'tailwind-css',
                'completed_lessons' => json_encode($lessonIds->take($completedCount)->values()->all()),
                'progress_percent' => $profile['progress'],
                'current_level' => $profile['level'],
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }

    private function seedLessonProgress(int $userId, int $progress, $now): void
    {
        if (!Schema::hasTable('user_lesson_progress') || !Schema::hasTable('course_lessons')) {
            return;
        }

        $lessonIds = DB::table('course_lessons')->orderBy('id')->pluck('id')->map(fn ($id) => (int) $id)->values();
        $completedCount = $this->countByPercent($lessonIds->count(), $progress);
        $rows = $lessonIds->take($completedCount)->map(fn ($lessonId) => $this->filterColumns('user_lesson_progress', [
            'user_id' => $userId,
            'course_lesson_id' => $lessonId,
            'completed' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]))->all();

        if ($rows) {
            DB::table('user_lesson_progress')->insert($rows);
        }
    }

    private function seedActivityProgress(int $userId, array $profile, $now): void
    {
        if (!Schema::hasTable('user_activity_progress') || !Schema::hasTable('course_activities')) {
            return;
        }

        $activityIds = DB::table('course_activities')->orderBy('id')->pluck('id')->map(fn ($id) => (int) $id)->values();
        $completedCount = $this->countByPercent($activityIds->count(), $profile['progress']);
        $rows = $activityIds->take($completedCount)->map(fn ($activityId, $index) => $this->filterColumns('user_activity_progress', [
            'user_id' => $userId,
            'course_activity_id' => $activityId,
            'completed' => 1,
            'score' => min(100, max(55, $profile['quiz_score'] + ($index % 3))),
            'completed_at' => $now->copy()->subDays(max(0, 5 - $index)),
            'created_at' => $now,
            'updated_at' => $now,
        ]))->all();

        if ($rows) {
            DB::table('user_activity_progress')->insert($rows);
        }
    }

    private function seedQuizAttempts(int $userId, array $profile, int $studentIndex, $now): void
    {
        if (!Schema::hasTable('quiz_attempts') || !Schema::hasTable('quiz_attempt_answers')) {
            return;
        }

        $chapters = DB::table('quiz_questions')
            ->select('chapter_id')
            ->distinct()
            ->orderByRaw('CAST(chapter_id AS UNSIGNED)')
            ->pluck('chapter_id');

        foreach ($chapters as $chapterIndex => $chapterId) {
            $questions = DB::table('quiz_questions')->where('chapter_id', $chapterId)->orderBy('id')->get();

            if ($questions->isEmpty()) {
                continue;
            }

            $score = min(100, max(20, $profile['quiz_score'] + ($chapterIndex * 2) - 3));
            $correctTarget = $this->countByPercent($questions->count(), $score);
            $completedAt = $now->copy()->subDays(4 - $studentIndex)->subMinutes($chapterIndex * 17);

            $attemptId = DB::table('quiz_attempts')->insertGetId($this->filterColumns('quiz_attempts', [
                'user_id' => $userId,
                'chapter_id' => $chapterId,
                'started_at' => $completedAt->copy()->subMinutes(11),
                'score' => $score,
                'time_spent_seconds' => 540 + ($studentIndex * 55) + ($chapterIndex * 30),
                'answered_count' => $questions->count(),
                'unanswered_count' => 0,
                'flagged_count' => $score < 70 ? 1 : 0,
                'focus_lost_count' => $score < 60 ? 2 : ($score < 75 ? 1 : 0),
                'feedback_level' => $score >= 85 ? 'excellent' : ($score >= 70 ? 'good' : 'remedial'),
                'feedback_message' => $score >= 70 ? 'Capaian kuis sudah baik pada data demo terbaru.' : 'Perlu penguatan materi berdasarkan data demo terbaru.',
                'reflection_note' => $score >= 70 ? 'Saya mulai memahami pola utility class.' : 'Saya perlu membaca ulang materi dan mencoba latihan lagi.',
                'completed_at' => $completedAt,
                'created_at' => $completedAt,
                'updated_at' => $completedAt,
            ]));

            $answers = [];
            foreach ($questions as $questionIndex => $question) {
                $isCorrect = $questionIndex < $correctTarget;
                $option = DB::table('quiz_options')
                    ->where('quiz_question_id', $question->id)
                    ->where('is_correct', $isCorrect ? 1 : 0)
                    ->orderBy('id')
                    ->first()
                    ?: DB::table('quiz_options')->where('quiz_question_id', $question->id)->orderBy('id')->first();

                if (!$option) {
                    continue;
                }

                $answers[] = $this->filterColumns('quiz_attempt_answers', [
                    'quiz_attempt_id' => $attemptId,
                    'quiz_question_id' => $question->id,
                    'quiz_option_id' => $option->id,
                    'is_correct' => (int) $option->is_correct,
                    'answer_change_count' => $score < 75 ? 1 : 0,
                    'client_elapsed_seconds' => 12 + (($questionIndex + $studentIndex) % 9),
                    'first_answered_at' => $completedAt->copy()->subMinutes(5),
                    'last_answered_at' => $completedAt->copy()->subMinutes(2),
                    'is_flagged' => $score < 60 && $questionIndex === $questions->count() - 1 ? 1 : 0,
                    'created_at' => $completedAt,
                    'updated_at' => $completedAt,
                ]);
            }

            if ($answers) {
                DB::table('quiz_attempt_answers')->insert($answers);
            }
        }
    }

    private function seedLabHistories(int $userId, array $profile, int $studentIndex, $now): void
    {
        if (!Schema::hasTable('labs') || !Schema::hasTable('lab_steps') || !Schema::hasTable('lab_histories')) {
            return;
        }

        $labs = DB::table('labs')->where('is_active', 1)->orderBy('chapter_id')->orderBy('id')->get();

        foreach ($labs as $labIndex => $lab) {
            $steps = DB::table('lab_steps')->where('lab_id', $lab->id)->orderBy('order_index')->get();
            $score = min(100, max(20, $profile['lab_score'] + ($labIndex * 3) - 4));
            $completedCount = $this->countByPercent($steps->count(), $score);
            $completedSteps = $steps->take($completedCount)->pluck('id')->map(fn ($id) => (int) $id)->values()->all();
            $completedAt = $now->copy()->subDays(3 - $studentIndex)->subMinutes($labIndex * 23);

            DB::table('lab_histories')->insert($this->filterColumns('lab_histories', [
                'user_id' => $userId,
                'lab_id' => $lab->id,
                'final_score' => $score,
                'status' => $score >= (int) ($lab->passing_grade ?? 70) ? 'passed' : 'failed',
                'duration_seconds' => 860 + ($studentIndex * 170) + ($labIndex * 95),
                'last_code_snapshot' => $this->codeSnapshot($score),
                'source_code' => $this->codeSnapshot($score),
                'completed_steps' => json_encode($completedSteps),
                'save_count' => 2 + $studentIndex + $labIndex,
                'validation_attempt_count' => 1 + $studentIndex + ($score < 70 ? 2 : 0),
                'code_change_count' => 4 + ($studentIndex * 2) + $labIndex,
                'completed_at' => $completedAt,
                'created_at' => $completedAt,
                'updated_at' => $completedAt,
            ]));
        }
    }

    private function seedBadges(int $userId, int $progress, $now): void
    {
        if (!Schema::hasTable('badges') || !Schema::hasTable('user_badges')) {
            return;
        }

        $badgeLimit = match (true) {
            $progress >= 100 => 5,
            $progress >= 80 => 4,
            $progress >= 60 => 3,
            $progress >= 40 => 2,
            $progress >= 20 => 1,
            default => 0,
        };

        $rows = DB::table('badges')
            ->orderBy('id')
            ->take($badgeLimit)
            ->pluck('id')
            ->map(fn ($badgeId) => $this->filterColumns('user_badges', [
                'user_id' => $userId,
                'badge_id' => $badgeId,
                'created_at' => $now,
                'updated_at' => $now,
            ]))->all();

        if ($rows) {
            DB::table('user_badges')->insert($rows);
        }
    }

    private function naturalQuestions(): array
    {
        return [
            [
                'chapter_id' => 1,
                'tp_code' => 'TP1',
                'tp_title' => 'Menjelaskan fungsi HTML dan CSS dalam halaman web.',
                'media_path' => 'quiz-media/natural-quiz-01.png',
                'question_text' => 'Gambar menunjukkan sebuah layar kosong di ruang belajar. Struktur HTML semantik yang paling tepat untuk area konten utama pada halaman tersebut adalah...',
                'options' => [
                    'A' => '<main>',
                    'B' => '<meta>',
                    'C' => '<script>',
                    'D' => '<style>',
                ],
                'correct' => 'A',
                'remediation_hint' => 'Ingat kembali elemen semantik untuk konten utama halaman.',
            ],
            [
                'chapter_id' => 2,
                'tp_code' => 'TP3',
                'tp_title' => 'Mengatur layout responsif sederhana menggunakan breakpoint.',
                'media_path' => 'quiz-media/natural-quiz-02.png',
                'question_text' => 'Gambar menampilkan tiga ukuran perangkat. Kombinasi class yang paling tepat untuk membuat layout berubah dari 1 kolom, 2 kolom, lalu 3 kolom adalah...',
                'options' => [
                    'A' => 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
                    'B' => 'flex flex-row justify-center',
                    'C' => 'hidden md:block lg:hidden',
                    'D' => 'text-left md:text-center lg:text-right',
                ],
                'correct' => 'A',
                'remediation_hint' => 'Tinjau kembali prefix breakpoint md dan lg untuk layout responsif.',
            ],
            [
                'chapter_id' => 2,
                'tp_code' => 'TP2',
                'tp_title' => 'Menerapkan class flex dan grid untuk mengatur layout.',
                'media_path' => 'quiz-media/natural-quiz-03.png',
                'question_text' => 'Gambar memperlihatkan tiga kartu kosong yang tersusun sejajar. Class Tailwind yang paling sesuai untuk membuat pola tersebut adalah...',
                'options' => [
                    'A' => 'grid grid-cols-3 gap-4',
                    'B' => 'font-bold leading-7',
                    'C' => 'rounded-xl shadow-md',
                    'D' => 'bg-green-100 text-green-700',
                ],
                'correct' => 'A',
                'remediation_hint' => 'Pelajari kembali penggunaan grid, jumlah kolom, dan jarak antar elemen.',
            ],
            [
                'chapter_id' => 3,
                'tp_code' => 'TP2',
                'tp_title' => 'Menerapkan class Tailwind CSS untuk mengatur tampilan elemen web.',
                'media_path' => 'quiz-media/natural-quiz-04.png',
                'question_text' => 'Gambar memperlihatkan blok berwarna dengan ruang antar elemen yang jelas. Utility yang digunakan untuk mengatur jarak antar item dalam container adalah...',
                'options' => [
                    'A' => 'gap-4',
                    'B' => 'font-mono',
                    'C' => 'text-white',
                    'D' => 'border-none',
                ],
                'correct' => 'A',
                'remediation_hint' => 'Tinjau ulang utility gap untuk memberi jarak antar item dalam flex atau grid.',
            ],
            [
                'chapter_id' => 3,
                'tp_code' => 'TP2',
                'tp_title' => 'Menerapkan class Tailwind CSS untuk mengatur tampilan elemen web.',
                'media_path' => 'quiz-media/natural-quiz-05.png',
                'question_text' => 'Gambar menunjukkan kumpulan warna dan material netral. Agar sebuah kartu tampil rapi dengan sudut melengkung dan bayangan halus, kombinasi class yang paling tepat adalah...',
                'options' => [
                    'A' => 'bg-white rounded-xl shadow-md',
                    'B' => 'grid-cols-3 flex-1 col-span-2',
                    'C' => 'font-mono leading-7 text-center',
                    'D' => 'hidden md:block lg:hidden',
                ],
                'correct' => 'A',
                'remediation_hint' => 'Pelajari kembali kombinasi utility untuk kartu visual: latar, radius, dan shadow.',
            ],
        ];
    }

    private function studentProfiles(): array
    {
        return [
            ['name' => 'Alya Pramesti', 'email' => 'alya.demo01@utilwind.test', 'class_group' => 'KELAS ANALITIK A', 'phone' => '081230000001', 'progress' => 20, 'level' => 'beginner', 'xp' => 180, 'quiz_score' => 48, 'lab_score' => 42],
            ['name' => 'Bagus Wicaksono', 'email' => 'bagus.demo02@utilwind.test', 'class_group' => 'KELAS ANALITIK A', 'phone' => '081230000002', 'progress' => 40, 'level' => 'beginner', 'xp' => 620, 'quiz_score' => 63, 'lab_score' => 58],
            ['name' => 'Citra Lestari', 'email' => 'citra.demo03@utilwind.test', 'class_group' => 'KELAS ANALITIK A', 'phone' => '081230000003', 'progress' => 60, 'level' => 'intermediate', 'xp' => 1420, 'quiz_score' => 74, 'lab_score' => 72],
            ['name' => 'Dimas Pratama', 'email' => 'dimas.demo04@utilwind.test', 'class_group' => 'KELAS ANALITIK B', 'phone' => '081230000004', 'progress' => 80, 'level' => 'advanced', 'xp' => 2860, 'quiz_score' => 86, 'lab_score' => 84],
            ['name' => 'Nabila Zahra', 'email' => 'nabila.demo05@utilwind.test', 'class_group' => 'KELAS ANALITIK B', 'phone' => '081230000005', 'progress' => 100, 'level' => 'expert', 'xp' => 4200, 'quiz_score' => 96, 'lab_score' => 98],
        ];
    }

    private function countByPercent(int $total, int|float $percent): int
    {
        if ($total <= 0) {
            return 0;
        }

        return min($total, max(0, (int) round($total * ($percent / 100))));
    }

    private function codeSnapshot(int $score): string
    {
        if ($score >= 70) {
            return <<<'HTML'
<article class="bg-white p-4 rounded-xl shadow-md">
    <div class="overflow-hidden rounded-xl">
        <img src="/uploads/quiz-media/natural-quiz-02.png" alt="Gambar natural tanpa teks" class="aspect-video object-cover rounded-xl">
    </div>
</article>
HTML;
        }

        return <<<'HTML'
<article class="bg-white p-4">
    <img src="/uploads/quiz-media/natural-quiz-02.png" alt="Gambar natural tanpa teks">
</article>
HTML;
    }

    private function filterColumns(string $table, array $attributes): array
    {
        return collect($attributes)
            ->filter(fn ($value, $column) => Schema::hasColumn($table, $column))
            ->all();
    }
}
