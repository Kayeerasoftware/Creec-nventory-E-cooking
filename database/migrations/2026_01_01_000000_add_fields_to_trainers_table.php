<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainers', function (Blueprint $table) {
            $table->string('whatsapp')->nullable()->after('phone');
            $table->decimal('hourly_rate', 10, 2)->nullable()->after('experience');
            $table->string('license_number')->nullable()->after('hourly_rate');
            $table->string('country')->default('Uganda')->after('license_number');
            $table->string('region')->nullable()->after('country');
            $table->string('district')->nullable()->after('region');
            $table->string('village')->nullable()->after('district');
            $table->string('sub_county')->nullable()->after('village');
            $table->text('skills')->nullable()->after('qualifications');
            $table->text('languages')->nullable()->after('skills');
            $table->text('certifications')->nullable()->after('languages');
            $table->text('notes')->nullable()->after('certifications');
            $table->string('status')->default('Active')->after('notes');
            $table->integer('trainings_count')->default(0)->after('status');
            $table->integer('students_count')->default(0)->after('trainings_count');
            $table->decimal('rating', 3, 1)->default(5.0)->after('students_count');
        });
    }

    public function down(): void
    {
        Schema::table('trainers', function (Blueprint $table) {
            $table->dropColumn(['whatsapp', 'country', 'region', 'district', 'village', 'sub_county', 'hourly_rate', 'license_number', 'skills', 'languages', 'certifications', 'notes', 'status', 'trainings_count', 'students_count', 'rating']);
        });
    }
};
