<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(){
    if (!Schema::hasTable('quiz_attempts')) {
      Schema::create('quiz_attempts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->integer('chapter_id');
        $table->timestamp('started_at')->nullable();
        $table->integer('score')->default(0);
        $table->integer('time_spent_seconds')->default(0);
        $table->timestamp('completed_at')->nullable();
        $table->timestamps();
      });
    }
  }
  public function down(){ Schema::dropIfExists('quiz_attempts'); }
};
