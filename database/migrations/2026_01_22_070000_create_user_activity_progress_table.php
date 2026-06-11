<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_activity_progress')) {
            Schema::create('user_activity_progress', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->unsignedBigInteger('course_activity_id');
                $table->boolean('completed')->default(false);
                $table->unsignedInteger('score')->default(0);
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'course_activity_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_activity_progress');
    }
};
