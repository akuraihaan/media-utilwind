<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('quiz_questions')) {
            Schema::table('quiz_questions', function (Blueprint $table) {
                if (!Schema::hasColumn('quiz_questions', 'learning_objective_code')) {
                    $table->string('learning_objective_code', 40)->nullable()->after('chapter_id');
                }

                if (!Schema::hasColumn('quiz_questions', 'learning_objective_title')) {
                    $table->string('learning_objective_title')->nullable()->after('learning_objective_code');
                }

                if (!Schema::hasColumn('quiz_questions', 'remediation_hint')) {
                    $table->text('remediation_hint')->nullable()->after('learning_objective_title');
                }
            });
        }

        if (Schema::hasTable('lab_steps')) {
            Schema::table('lab_steps', function (Blueprint $table) {
                if (!Schema::hasColumn('lab_steps', 'learning_objective_code')) {
                    $table->string('learning_objective_code', 40)->nullable()->after('title');
                }

                if (!Schema::hasColumn('lab_steps', 'learning_objective_title')) {
                    $table->string('learning_objective_title')->nullable()->after('learning_objective_code');
                }

                if (!Schema::hasColumn('lab_steps', 'remediation_hint')) {
                    $table->text('remediation_hint')->nullable()->after('learning_objective_title');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('lab_steps')) {
            Schema::table('lab_steps', function (Blueprint $table) {
                foreach (['remediation_hint', 'learning_objective_title', 'learning_objective_code'] as $column) {
                    if (Schema::hasColumn('lab_steps', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('quiz_questions')) {
            Schema::table('quiz_questions', function (Blueprint $table) {
                foreach (['remediation_hint', 'learning_objective_title', 'learning_objective_code'] as $column) {
                    if (Schema::hasColumn('quiz_questions', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
