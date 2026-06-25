<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('quiz_questions')) {
            return;
        }

        Schema::table('quiz_questions', function (Blueprint $table) {
            if (!Schema::hasColumn('quiz_questions', 'interaction_type')) {
                $column = $table->string('interaction_type', 40)->default('multiple_choice');
                if (Schema::hasColumn('quiz_questions', 'remediation_hint')) {
                    $column->after('remediation_hint');
                } elseif (Schema::hasColumn('quiz_questions', 'question_text')) {
                    $column->after('question_text');
                }
            }

            if (!Schema::hasColumn('quiz_questions', 'interaction_prompt')) {
                $column = $table->text('interaction_prompt')->nullable();
                if (Schema::hasColumn('quiz_questions', 'interaction_type')) {
                    $column->after('interaction_type');
                }
            }

            if (!Schema::hasColumn('quiz_questions', 'media_type')) {
                $column = $table->string('media_type', 40)->nullable();
                if (Schema::hasColumn('quiz_questions', 'interaction_prompt')) {
                    $column->after('interaction_prompt');
                }
            }

            if (!Schema::hasColumn('quiz_questions', 'media_url')) {
                $column = $table->text('media_url')->nullable();
                if (Schema::hasColumn('quiz_questions', 'media_type')) {
                    $column->after('media_type');
                }
            }

            if (!Schema::hasColumn('quiz_questions', 'media_path')) {
                $column = $table->string('media_path')->nullable();
                if (Schema::hasColumn('quiz_questions', 'media_url')) {
                    $column->after('media_url');
                }
            }

            if (!Schema::hasColumn('quiz_questions', 'media_caption')) {
                $column = $table->string('media_caption')->nullable();
                if (Schema::hasColumn('quiz_questions', 'media_path')) {
                    $column->after('media_path');
                }
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('quiz_questions')) {
            return;
        }

        Schema::table('quiz_questions', function (Blueprint $table) {
            foreach (['media_caption', 'media_path', 'media_url', 'media_type', 'interaction_prompt', 'interaction_type'] as $column) {
                if (Schema::hasColumn('quiz_questions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
