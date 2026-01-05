<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trainer;
use Illuminate\Support\Facades\Hash;

class TrainerSeeder extends Seeder
{
    public function run(): void
    {
        $trainers = [
            [
                'name' => 'John Katende',
                'specialty' => 'EPC Training',
                'email' => 'john.katende@training.com',
                'password' => Hash::make('kayeera'),
                'phone' => '+256 701 234 567',
                'experience' => 8,
                'location' => 'Kampala',
                'trainings_count' => 12,
                'students_count' => 45,
                'sessions_count' => 48,
            ],
            [
                'name' => 'Sarah Nakimuli',
                'specialty' => 'Air Fryer Training',
                'email' => 'sarah.nakimuli@training.com',
                'password' => Hash::make('kayeera'),
                'phone' => '+256 702 345 678',
                'experience' => 6,
                'location' => 'Entebbe',
                'trainings_count' => 8,
                'students_count' => 32,
                'sessions_count' => 32,
            ],
            [
                'name' => 'David Ochieng',
                'specialty' => 'Induction Cooker Training',
                'email' => 'david.ochieng@training.com',
                'password' => Hash::make('kayeera'),
                'phone' => '+256 703 456 789',
                'experience' => 10,
                'location' => 'Jinja',
                'trainings_count' => 25,
                'students_count' => 78,
                'sessions_count' => 100,
            ],
            [
                'name' => 'Grace Ssemanda',
                'specialty' => 'General Appliance Training',
                'email' => 'grace.ssemanda@training.com',
                'password' => Hash::make('kayeera'),
                'phone' => '+256 704 567 890',
                'experience' => 5,
                'location' => 'Mbarara',
                'trainings_count' => 6,
                'students_count' => 28,
                'sessions_count' => 24,
            ],
            [
                'name' => 'Peter Mwesigwa',
                'specialty' => 'EPC Training',
                'email' => 'peter.mwesigwa@training.com',
                'password' => Hash::make('kayeera'),
                'phone' => '+256 705 678 901',
                'experience' => 7,
                'location' => 'Gulu',
                'trainings_count' => 15,
                'students_count' => 52,
                'sessions_count' => 60,
            ],
        ];

        foreach ($trainers as $trainer) {
            Trainer::updateOrCreate(
                ['email' => $trainer['email']],
                $trainer
            );
        }
    }
}
