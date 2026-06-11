<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('labs')) {
            Schema::create('labs', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->unsignedInteger('chapter_id')->nullable();
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->unsignedInteger('duration_minutes')->default(60);
                $table->unsignedInteger('passing_grade')->default(70);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('lab_steps')) {
            Schema::create('lab_steps', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lab_id')->constrained('labs')->cascadeOnDelete();
                $table->string('title');
                $table->text('instruction')->nullable();
                $table->longText('initial_code')->nullable();
                $table->json('validation_rules')->nullable();
                $table->unsignedInteger('points')->default(0);
                $table->unsignedInteger('order_index')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('lab_sessions')) {
            Schema::create('lab_sessions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('lab_id')->constrained('labs')->cascadeOnDelete();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->unsignedInteger('current_score')->default(0);
                $table->longText('current_code')->nullable();
                $table->string('status')->default('active');
                $table->json('completed_steps')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('lab_histories')) {
            Schema::create('lab_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('lab_id')->constrained('labs')->cascadeOnDelete();
                $table->unsignedInteger('final_score')->default(0);
                $table->string('status')->default('completed');
                $table->unsignedInteger('duration_seconds')->default(0);
                $table->longText('last_code_snapshot')->nullable();
                $table->longText('source_code')->nullable();
                $table->json('completed_steps')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_histories');
        Schema::dropIfExists('lab_sessions');
        Schema::dropIfExists('lab_steps');
        Schema::dropIfExists('labs');
    }
};
