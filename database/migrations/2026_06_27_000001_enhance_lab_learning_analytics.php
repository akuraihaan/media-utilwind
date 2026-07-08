<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['lab_sessions', 'lab_histories'] as $tableName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'save_count')) {
                    $table->unsignedInteger('save_count')->default(0)->after('completed_steps');
                }

                if (!Schema::hasColumn($tableName, 'validation_attempt_count')) {
                    $table->unsignedInteger('validation_attempt_count')->default(0)->after('save_count');
                }

                if (!Schema::hasColumn($tableName, 'code_change_count')) {
                    $table->unsignedInteger('code_change_count')->default(0)->after('validation_attempt_count');
                }
            });
        }
    }

    public function down(): void
    {
        foreach (['lab_sessions', 'lab_histories'] as $tableName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                foreach (['code_change_count', 'validation_attempt_count', 'save_count'] as $column) {
                    if (Schema::hasColumn($tableName, $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
