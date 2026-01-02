<?php

namespace Database\Seeders;

use App\Models\Appliance;
use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplianceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = Brand::all();

        $appliances = [
            [
                'name' => 'Electric Pressure Cooker',
                'color' => 'bg-primary',
                'icon' => 'fa-kitchen-set',
                'model' => 'EPC-5000',
                'power' => '1000W',
                'sku' => 'EPC-001',
                'status' => 'Available',
                'description' => '6-quart electric pressure cooker with multiple cooking functions'
            ],
            [
                'name' => 'Air Fryer',
                'color' => 'bg-warning',
                'icon' => 'fa-fan',
                'model' => 'AF-3000',
                'power' => '1500W',
                'sku' => 'AF-001',
                'status' => 'In Use',
                'description' => 'Digital air fryer with 5.8 quart capacity'
            ],
            [
                'name' => 'Induction Cooker',
                'color' => 'bg-danger',
                'icon' => 'fa-fire-burner',
                'model' => 'IC-2000',
                'power' => '2000W',
                'sku' => 'IC-001',
                'status' => 'Available',
                'description' => 'Portable induction cooktop with temperature control'
            ],
            [
                'name' => 'Rice Cooker',
                'color' => 'bg-success',
                'icon' => 'fa-bowl-food',
                'model' => 'RC-1000',
                'power' => '600W',
                'sku' => 'RC-001',
                'status' => 'Maintenance',
                'description' => 'Multi-functional rice cooker with fuzzy logic'
            ],
        ];

        foreach ($appliances as $appliance) {
            $appliance['brand_id'] = $brands->random()->id;
            Appliance::create($appliance);
        }
    }
}
