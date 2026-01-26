<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabel Soal
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->string('chapter_id'); // Misal: "1", "2"
            $table->text('question_text');
            $table->timestamps();
        });

        // 2. Tabel Pilihan Jawaban (A, B, C, D)
        Schema::create('quiz_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_question_id')->constrained()->onDelete('cascade');
            $table->string('option_text');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });

        // 3. Tabel Riwayat Pengerjaan (Header)
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('chapter_id');
            $table->integer('score')->default(0);
            $table->integer('time_spent_seconds')->default(0);
            $table->timestamp('completed_at')->useCurrent();
            $table->timestamps();
        });

        // 4. Tabel Detail Jawaban Siswa (Untuk Analisis Guru)
        Schema::create('quiz_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')->constrained('quiz_attempts')->onDelete('cascade');
            $table->foreignId('quiz_question_id')->constrained('quiz_questions')->onDelete('cascade');
            $table->foreignId('quiz_option_id')->constrained('quiz_options')->onDelete('cascade');
            $table->boolean('is_correct'); // Snapshot status benar/salah saat itu
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