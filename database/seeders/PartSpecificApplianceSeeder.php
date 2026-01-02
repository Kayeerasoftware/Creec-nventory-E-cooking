<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Part;
use App\Models\SpecificAppliance;

class PartSpecificApplianceSeeder extends Seeder
{
    public function run(): void
    {
        $parts = Part::all();
        $specificAppliances = SpecificAppliance::all();

        foreach ($parts as $part) {
            // Get specific appliances that match the part's appliance type
            $compatibleAppliances = $specificAppliances->where('appliance_id', $part->appliance_id);
            
            // Attach 1-3 random compatible appliances to each part
            $randomAppliances = $compatibleAppliances->random(min(3, $compatibleAppliances->count()));
            
            foreach ($randomAppliances as $appliance) {
                DB::table('part_specific_appliances')->insert([
                    'part_id' => $part->id,
                    'specific_appliance_id' => $appliance->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
