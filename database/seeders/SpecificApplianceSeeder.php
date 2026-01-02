<?php

namespace Database\Seeders;

use App\Models\SpecificAppliance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecificApplianceSeeder extends Seeder
{
    public function run(): void
    {
        $specificAppliances = [
            ['name' => 'Instant Pot Duo 6Qt', 'appliance_id' => 1, 'brand_id' => 1],
            ['name' => 'Instant Pot Pro 8Qt', 'appliance_id' => 1, 'brand_id' => 1],
            ['name' => 'Tefal Pressure Cooker', 'appliance_id' => 1, 'brand_id' => 2],
            ['name' => 'Tefal Secure Trendy', 'appliance_id' => 1, 'brand_id' => 2],
            ['name' => 'Saachi EPC-6.0', 'appliance_id' => 1, 'brand_id' => 3],
            ['name' => 'Saachi EPC-8.0', 'appliance_id' => 1, 'brand_id' => 3],
            ['name' => 'Hisense EPC', 'appliance_id' => 1, 'brand_id' => 4],
            ['name' => 'Hisense EPC 5L', 'appliance_id' => 1, 'brand_id' => 4],
            ['name' => 'Prestige EPC', 'appliance_id' => 1, 'brand_id' => 5],
            ['name' => 'Prestige Smartplus', 'appliance_id' => 1, 'brand_id' => 5],
            ['name' => 'Philips Viva Air Fryer', 'appliance_id' => 2, 'brand_id' => 6],
            ['name' => 'Philips Air Fryer XL', 'appliance_id' => 2, 'brand_id' => 6],
            ['name' => 'Ninja Air Fryer AF100', 'appliance_id' => 2, 'brand_id' => 7],
            ['name' => 'Ninja Foodi OP300', 'appliance_id' => 2, 'brand_id' => 7],
            ['name' => 'Tefal Easy Fry', 'appliance_id' => 2, 'brand_id' => 2],
            ['name' => 'Tefal ActiFry', 'appliance_id' => 2, 'brand_id' => 2],
            ['name' => 'Saachi Air Fryer', 'appliance_id' => 2, 'brand_id' => 3],
            ['name' => 'Saachi Air Fryer 5L', 'appliance_id' => 2, 'brand_id' => 3],
            ['name' => 'Hisense Air Fryer', 'appliance_id' => 2, 'brand_id' => 4],
            ['name' => 'Hisense Air Fryer 4L', 'appliance_id' => 2, 'brand_id' => 4],
            ['name' => 'Hoffmans Air Fryer 5L', 'appliance_id' => 2, 'brand_id' => 8],
            ['name' => 'Hoffmans Air Fryer 7L', 'appliance_id' => 2, 'brand_id' => 8],
            ['name' => 'Midea Air Fryer 5L', 'appliance_id' => 2, 'brand_id' => 9],
            ['name' => 'Midea Air Fryer 7L', 'appliance_id' => 2, 'brand_id' => 9],
            ['name' => 'Philips Induction Cooker', 'appliance_id' => 3, 'brand_id' => 6],
            ['name' => 'Philips HD4938', 'appliance_id' => 3, 'brand_id' => 6],
            ['name' => 'Tefal Induction Cooker', 'appliance_id' => 3, 'brand_id' => 2],
            ['name' => 'Tefal IH2018', 'appliance_id' => 3, 'brand_id' => 2],
            ['name' => 'Saachi Induction Cooker', 'appliance_id' => 3, 'brand_id' => 3],
            ['name' => 'Saachi IC-2000', 'appliance_id' => 3, 'brand_id' => 3],
            ['name' => 'Hisense Induction Cooker', 'appliance_id' => 3, 'brand_id' => 4],
            ['name' => 'Hisense IC-1800', 'appliance_id' => 3, 'brand_id' => 4],
            ['name' => 'SPJ Induction Cooker', 'appliance_id' => 3, 'brand_id' => 10],
            ['name' => 'SPJ IC-2000', 'appliance_id' => 3, 'brand_id' => 10],
            ['name' => 'Tatung Induction Cooker', 'appliance_id' => 3, 'brand_id' => 11],
            ['name' => 'Tatung IC-1800', 'appliance_id' => 3, 'brand_id' => 11],
            ['name' => 'Newmatic Induction Cooker', 'appliance_id' => 3, 'brand_id' => 12],
            ['name' => 'Newmatic IC-2000', 'appliance_id' => 3, 'brand_id' => 12],
            ['name' => 'Ariete Induction Cooker', 'appliance_id' => 3, 'brand_id' => 13],
            ['name' => 'Ariete IC-1800', 'appliance_id' => 3, 'brand_id' => 13],
            ['name' => 'Electro Master Induction Cooker', 'appliance_id' => 3, 'brand_id' => 14],
            ['name' => 'Electro Master IC-2000', 'appliance_id' => 3, 'brand_id' => 14],
            ['name' => 'Ramtons Induction Cooker', 'appliance_id' => 3, 'brand_id' => 15],
            ['name' => 'Ramtons IC-2000', 'appliance_id' => 3, 'brand_id' => 15],
            ['name' => 'Mika Induction Cooker', 'appliance_id' => 3, 'brand_id' => 16],
            ['name' => 'Mika IC-1800', 'appliance_id' => 3, 'brand_id' => 16],
        ];

        foreach ($specificAppliances as $sa) {
            SpecificAppliance::create($sa);
        }
    }
}
