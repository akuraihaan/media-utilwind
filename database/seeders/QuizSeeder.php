<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuizQuestion;
use App\Models\QuizOption;

class QuizSeeder extends Seeder
{
    public function run()
    {
        // === SOAL BAB 1 (10 Soal) ===
        $this->createQuestion('1', 'Apa kegunaan utama dari Tailwind CSS?', [
            ['text' => 'Framework UI Kit seperti Bootstrap', 'correct' => false],
            ['text' => 'Utility-first CSS framework', 'correct' => true],
            ['text' => 'Bahasa pemrograman backend', 'correct' => false],
            ['text' => 'Database management system', 'correct' => false],
        ]);

        $this->createQuestion('1', 'Perintah untuk menginisialisasi konfigurasi Tailwind adalah...', [
            ['text' => 'npm install tailwind', 'correct' => false],
            ['text' => 'npx tailwindcss init', 'correct' => true],
            ['text' => 'php artisan tailwind:install', 'correct' => false],
            ['text' => 'git init tailwind', 'correct' => false],
        ]);
        
        // ... Tambahkan 8 soal lagi untuk Bab 1 ...
        for($i=3; $i<=10; $i++) {
            $this->createQuestion('1', "Contoh Soal Dummy Bab 1 Nomor $i?", [
                ['text' => 'Jawaban A', 'correct' => true],
                ['text' => 'Jawaban B', 'correct' => false],
                ['text' => 'Jawaban C', 'correct' => false],
                ['text' => 'Jawaban D', 'correct' => false],
            ]);
        }

        // === SOAL BAB 2 (10 Soal) ===
        $this->createQuestion('2', 'Class untuk membuat container Flexbox adalah...', [
            ['text' => 'display: flex', 'correct' => false],
            ['text' => 'flex', 'correct' => true],
            ['text' => 'flex-container', 'correct' => false],
            ['text' => 'grid', 'correct' => false],
        ]);

        // ... Tambahkan 9 soal lagi untuk Bab 2 ...
        for($i=2; $i<=10; $i++) {
            $this->createQuestion('2', "Contoh Soal Dummy Bab 2 Nomor $i tentang Flexbox?", [
                ['text' => 'Benar', 'correct' => true],
                ['text' => 'Salah 1', 'correct' => false],
                ['text' => 'Salah 2', 'correct' => false],
                ['text' => 'Salah 3', 'correct' => false],
            ]);
        }
    }

    private function createQuestion($chapter, $text, $options)
    {
        $q = QuizQuestion::create([
            'chapter_id' => $chapter,
            'question_text' => $text
        ]);

        foreach ($options as $opt) {
            QuizOption::create([
                'quiz_question_id' => $q->id,
                'option_text' => $opt['text'],
                'is_correct' => $opt['correct']
            ]);
        }
    }
}