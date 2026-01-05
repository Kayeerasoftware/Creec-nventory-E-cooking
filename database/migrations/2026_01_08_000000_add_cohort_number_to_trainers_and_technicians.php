<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainers', function (Blueprint $table) {
            $table->string('cohort_number')->nullable()->after('location');
        });

        Schema::table('technicians', function (Blueprint $table) {
            $table->string('cohort_number')->nullable()->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('trainers', function (Blueprint $table) {
            $table->dropColumn('cohort_number');
        });

        Schema::table('technicians', function (Blueprint $table) {
            $table->dropColumn('cohort_number');
        });
    }
};
