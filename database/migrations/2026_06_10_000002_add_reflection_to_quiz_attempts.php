<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('quiz_attempts') && !Schema::hasColumn('quiz_attempts', 'reflection_note')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                $table->text('reflection_note')->nullable()->after('feedback_message');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('quiz_attempts') && Schema::hasColumn('quiz_attempts', 'reflection_note')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                $table->dropColumn('reflection_note');
            });
        }
    }
};
