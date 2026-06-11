<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('quiz_attempts')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                if (!Schema::hasColumn('quiz_attempts', 'started_at')) {
                    $table->timestamp('started_at')->nullable()->after('chapter_id');
                }

                if (!Schema::hasColumn('quiz_attempts', 'answered_count')) {
                    $table->unsignedSmallInteger('answered_count')->default(0)->after('time_spent_seconds');
                }

                if (!Schema::hasColumn('quiz_attempts', 'unanswered_count')) {
                    $table->unsignedSmallInteger('unanswered_count')->default(0)->after('answered_count');
                }

                if (!Schema::hasColumn('quiz_attempts', 'flagged_count')) {
                    $table->unsignedSmallInteger('flagged_count')->default(0)->after('unanswered_count');
                }

                if (!Schema::hasColumn('quiz_attempts', 'focus_lost_count')) {
                    $table->unsignedSmallInteger('focus_lost_count')->default(0)->after('flagged_count');
                }

                if (!Schema::hasColumn('quiz_attempts', 'feedback_level')) {
                    $table->string('feedback_level', 30)->nullable()->after('focus_lost_count');
                }

                if (!Schema::hasColumn('quiz_attempts', 'feedback_message')) {
                    $table->text('feedback_message')->nullable()->after('feedback_level');
                }
            });
        }

        if (Schema::hasTable('quiz_attempt_answers')) {
            Schema::table('quiz_attempt_answers', function (Blueprint $table) {
                if (!Schema::hasColumn('quiz_attempt_answers', 'answer_change_count')) {
                    $table->unsignedSmallInteger('answer_change_count')->default(0)->after('is_correct');
                }

                if (!Schema::hasColumn('quiz_attempt_answers', 'client_elapsed_seconds')) {
                    $table->unsignedInteger('client_elapsed_seconds')->nullable()->after('answer_change_count');
                }

                if (!Schema::hasColumn('quiz_attempt_answers', 'first_answered_at')) {
                    $table->timestamp('first_answered_at')->nullable()->after('client_elapsed_seconds');
                }

                if (!Schema::hasColumn('quiz_attempt_answers', 'last_answered_at')) {
                    $table->timestamp('last_answered_at')->nullable()->after('first_answered_at');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('quiz_attempt_answers')) {
            Schema::table('quiz_attempt_answers', function (Blueprint $table) {
                $columns = [
                    'answer_change_count',
                    'client_elapsed_seconds',
                    'first_answered_at',
                    'last_answered_at',
                ];

                foreach ($columns as $column) {
                    if (Schema::hasColumn('quiz_attempt_answers', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('quiz_attempts')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                $columns = [
                    'answered_count',
                    'unanswered_count',
                    'flagged_count',
                    'focus_lost_count',
                    'feedback_level',
                    'feedback_message',
                ];

                foreach ($columns as $column) {
                    if (Schema::hasColumn('quiz_attempts', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
