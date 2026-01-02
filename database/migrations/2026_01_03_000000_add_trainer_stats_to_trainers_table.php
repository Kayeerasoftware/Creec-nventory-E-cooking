<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainers', function (Blueprint $table) {
            if (!Schema::hasColumn('trainers', 'trainings_count')) {
                $table->integer('trainings_count')->default(0);
            }
            if (!Schema::hasColumn('trainers', 'students_count')) {
                $table->integer('students_count')->default(0);
            }
            if (!Schema::hasColumn('trainers', 'sessions_count')) {
                $table->integer('sessions_count')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('trainers', function (Blueprint $table) {
            $table->dropColumn(['trainings_count', 'students_count', 'sessions_count']);
        });
    }
};
