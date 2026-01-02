<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateTrainerStatisticsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('trainers')->where('email', 'john.katende@training.com')->update([
            'trainings_count' => 12,
            'students_count' => 45,
            'sessions_count' => 48,
        ]);

        DB::table('trainers')->where('email', 'sarah.nakimuli@training.com')->update([
            'trainings_count' => 8,
            'students_count' => 32,
            'sessions_count' => 32,
        ]);

        DB::table('trainers')->where('email', 'david.ochieng@training.com')->update([
            'trainings_count' => 25,
            'students_count' => 78,
            'sessions_count' => 100,
        ]);

        DB::table('trainers')->where('email', 'grace.ssemanda@training.com')->update([
            'trainings_count' => 6,
            'students_count' => 28,
            'sessions_count' => 24,
        ]);

        DB::table('trainers')->where('email', 'peter.mwesigwa@training.com')->update([
            'trainings_count' => 15,
            'students_count' => 52,
            'sessions_count' => 60,
        ]);
    }
}
