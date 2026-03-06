<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Riwayat Pengerjaan Kuis (Header)
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siswa
            $table->integer('chapter_id'); // Bab berapa
            $table->integer('score')->default(0); // Nilai akhir (0-100)
            
            // Waktu pengerjaan (Untuk durasi dan deadline)
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable(); // Jika null, berarti belum submit
            
            // Opsional: Snapshot durasi dalam detik (untuk analisis performa)
            $table->integer('time_spent_seconds')->default(0); 
            
            $table->timestamps();
        });

        // 2. Tabel Detail Jawaban Per Soal (Detail)
        // Tabel ini PENTING untuk fitur "Analisis Butir Soal"
        Schema::create('quiz_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')->constrained('quiz_attempts')->onDelete('cascade');
            
            // Relasi ke soal
            $table->foreignId('quiz_question_id')->constrained('quiz_questions')->onDelete('cascade');
            
            // Jawaban yang dipilih siswa
            $table->foreignId('quiz_option_id')->constrained('quiz_options')->onDelete('cascade');
            
            // Snapshot kebenaran (1 = Benar, 0 = Salah)
            // Disimpan agar jika soal diedit nanti, riwayat siswa tidak rusak
            $table->boolean('is_correct')->default(false);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempt_answers');
        Schema::dropIfExists('quiz_attempts');
    }
};