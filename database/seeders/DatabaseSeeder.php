<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ComprehensiveUserSeeder::class,
            BrandSeeder::class,
            TrainerSeeder::class,
            TechnicianSeeder::class,
            ApplianceSeeder::class,
            SpecificApplianceSeeder::class,
            PartSeeder::class,
            PartSpecificApplianceSeeder::class,
        ]);
    }
}
