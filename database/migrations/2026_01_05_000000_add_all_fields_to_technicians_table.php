<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('technicians', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nationality')->default('Ugandan');
            $table->string('id_number')->nullable();
            $table->string('phone_2')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->string('country')->default('Uganda');
            $table->string('region')->nullable();
            $table->string('district')->nullable();
            $table->string('sub_county')->nullable();
            $table->string('village')->nullable();
            $table->string('status')->default('Available');
            $table->string('employment_type')->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->decimal('daily_rate', 10, 2)->nullable();
            $table->text('training')->nullable();
            $table->text('languages')->nullable();
            $table->string('own_tools')->default('Yes');
            $table->string('has_vehicle')->default('No');
            $table->text('equipment_list')->nullable();
            $table->text('service_areas')->nullable();
            $table->integer('jobs_completed')->default(0);
            $table->decimal('rating', 3, 1)->default(5.0);
            $table->string('response_time')->default('2-4hrs');
            $table->text('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('technicians', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'middle_name', 'last_name', 'gender', 'date_of_birth',
                'nationality', 'id_number', 'phone_2', 'whatsapp', 'emergency_contact',
                'emergency_phone', 'country', 'region', 'district', 'sub_county',
                'village', 'status', 'employment_type', 'hourly_rate', 'daily_rate',
                'training', 'languages', 'own_tools', 'has_vehicle', 'equipment_list',
                'service_areas', 'jobs_completed', 'rating', 'response_time', 'notes'
            ]);
        });
    }
};
