<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Instant Pot',
            'Tefal',
            'Saachi',
            'Hisense',
            'Prestige',
            'Philips',
            'Ninja',
            'Hoffmans',
            'Midea',
            'SPJ',
            'Tatung',
            'Newmatic',
            'Ariete',
            'Electro Master',
            'Ramtons',
            'Mika'
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(['name' => $brand]);
        }
    }
}
