<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Bank Soal (Master Data)
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->integer('chapter_id'); // Digunakan di QuizController: where('chapter_id', $chapterId)
            $table->text('question_text');
            $table->timestamps();
        });

        // 2. Opsi Jawaban (Master Data)
        Schema::create('quiz_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_question_id')->constrained('quiz_questions')->onDelete('cascade');
            $table->text('option_text');
            $table->boolean('is_correct')->default(false); // Digunakan di saveProgress: $option->is_correct
            $table->timestamps();
        });

        // 3. Riwayat Sesi Kuis (Transactional)
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('chapter_id'); // Grouping sesi per materi
            $table->integer('score')->default(0); // Digunakan di intro: max('score')
            $table->integer('time_spent_seconds')->default(0); // Digunakan di submit
            $table->timestamp('completed_at')->nullable(); // Penting: Nullable untuk cek "sesi gantung"
            $table->timestamps(); // created_at dipakai untuk hitung timer
        });

        // 4. Jawaban Detail User per Soal (Transactional)
        Schema::create('quiz_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')->constrained('quiz_attempts')->onDelete('cascade');
            $table->foreignId('quiz_question_id')->constrained('quiz_questions')->onDelete('cascade');
            
            // Nullable karena user bisa simpan status "Ragu-ragu" tanpa memilih jawaban
            $table->foreignId('quiz_option_id')->nullable()->constrained('quiz_options')->onDelete('cascade'); 
            
            $table->boolean('is_correct')->default(false); // Snapshot status jawaban saat saveProgress
            $table->boolean('is_flagged')->default(false); // Untuk fitur "Ragu-ragu"
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quiz_attempt_answers');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_options');
        Schema::dropIfExists('quiz_questions');
    }
};