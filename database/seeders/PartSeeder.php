<?php

namespace Database\Seeders;

use App\Models\Part;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PartSeeder extends Seeder
{
    public function run(): void
    {
        $parts = [
            [
                'part_number' => 'EPC001',
                'name' => 'Pressure Valve',
                'appliance_id' => 1,
                'location' => 'Top Lid',
                'description' => 'Regulates pressure release during cooking to ensure safety',
                'availability' => false,
                'comments' => 'Ensure proper alignment to prevent leaks',
                'image_path' => '/images/pressure cooker/Steam Release Knob.png',
                'price' => 25000
            ],
            [
                'part_number' => 'EPC002',
                'name' => 'Silicone Gasket',
                'appliance_id' => 1,
                'location' => 'Lid',
                'description' => 'Silicone sealing ring for airtight pressure retention',
                'availability' => false,
                'comments' => 'Replace every 6-12 months due to wear',
                'image_path' => '/images/pressure cooker/Sealing Ring.png'
            ],
            [
                'part_number' => 'EPC003',
                'name' => 'Inner Pot',
                'appliance_id' => 1,
                'location' => 'Main Body',
                'description' => 'Stainless steel pot for even heat distribution',
                'availability' => false,
                'comments' => 'Check for scratches or dents to ensure performance',
                'image_path' => '/images/pressure cooker/Inner pot 1.jpg'
            ],
            [
                'part_number' => 'EPC004',
                'name' => 'Steam Rack',
                'appliance_id' => 1,
                'location' => 'Inside Pot',
                'description' => 'Metal rack for elevating food during steaming',
                'availability' => false,
                'comments' => 'Ensure proper fit to avoid wobbling',
                'image_path' => '/images/pressure cooker/Steam Rack.png'
            ],
            [
                'part_number' => 'EPC005',
                'name' => 'Float Valve',
                'appliance_id' => 1,
                'location' => 'Lid',
                'description' => 'Indicates pressure status and locks lid',
                'availability' => false,
                'comments' => 'Clean regularly to prevent blockages',
                'image_path' => '/images/pressure cooker/Float Valve.png'
            ],
            [
                'part_number' => 'EPC006',
                'name' => 'Control Panel',
                'appliance_id' => 1,
                'location' => 'Front Body',
                'description' => 'Electronic interface for setting cooking modes',
                'availability' => false,
                'comments' => 'Limited stock for older models; verify model number',
                'image_path' => '/images/pressure cooker/Control Panel.jpg'
            ],
            [
                'part_number' => 'EPC007',
                'name' => 'Heating Element',
                'appliance_id' => 1,
                'location' => 'Base',
                'description' => 'Provides heat for pressure cooking',
                'availability' => false,
                'comments' => 'Requires professional installation',
                'image_path' => '/images/pressure cooker/Hot plat.png'
            ],
            [
                'part_number' => 'EPC008',
                'name' => 'Lid Handle',
                'appliance_id' => 1,
                'location' => 'Lid',
                'description' => 'Ergonomic handle for safe lid operation',
                'availability' => false,
                'comments' => 'Check compatibility with lid size',
                'image_path' => '/images/pressure cooker/Lid Handle.png'
            ],
            [
                'part_number' => 'EPC009',
                'name' => 'Anti-Block Shield',
                'appliance_id' => 1,
                'location' => 'Lid',
                'description' => 'Prevents food particles from clogging valve',
                'availability' => false,
                'comments' => 'Clean after each use',
                'image_path' => '/images/pressure cooker/Anti-Block Shield.jpg'
            ],
            [
                'part_number' => 'EPC010',
                'name' => 'Power Cord',
                'appliance_id' => 1,
                'location' => 'Base',
                'description' => 'Detachable 220-240V power cord',
                'availability' => false,
                'comments' => 'Ensure voltage compatibility (220-240V)',
                'image_path' => '/images/pressure cooker/Power Cord.png'
            ],
            [
                'part_number' => 'EPC011',
                'name' => 'Condensation Collector',
                'appliance_id' => 1,
                'location' => 'Lid',
                'description' => 'Collects excess moisture during cooking',
                'availability' => false,
                'comments' => 'Empty and clean regularly',
                'image_path' => '/images/pressure cooker/Condensation Collector.jpg'
            ],
            [
                'part_number' => 'EPC012',
                'name' => 'Sealing Ring',
                'appliance_id' => 1,
                'location' => 'Lid',
                'description' => 'Silicone ring for pressure sealing',
                'availability' => false,
                'comments' => 'Replace annually for optimal performance',
                'image_path' => null
            ],
            [
                'part_number' => 'EPC013',
                'name' => 'Safety Valve',
                'appliance_id' => 1,
                'location' => 'Lid',
                'description' => 'Prevents overpressure incidents',
                'availability' => false,
                'comments' => 'Inspect for wear or damage',
                'image_path' => null
            ],
            [
                'part_number' => 'EPC014',
                'name' => 'Base Plate',
                'appliance_id' => 1,
                'location' => 'Base',
                'description' => 'Supports internal components',
                'availability' => false,
                'comments' => 'Limited availability; order in advance',
                'image_path' => null
            ],
            [
                'part_number' => 'EPC015',
                'name' => 'Display Screen',
                'appliance_id' => 1,
                'location' => 'Front Body',
                'description' => 'LED screen for cooking status',
                'availability' => false,
                'comments' => 'Fragile; handle with care',
                'image_path' => null
            ],
            [
                'part_number' => 'EPC016',
                'name' => 'Locking Mechanism',
                'appliance_id' => 1,
                'location' => 'Lid',
                'description' => 'Secures lid during pressure cooking',
                'availability' => false,
                'comments' => 'Ensure smooth operation to avoid jams',
                'image_path' => null
            ],
            [
                'part_number' => 'EPC017',
                'name' => 'Temperature Sensor',
                'appliance_id' => 1,
                'location' => 'Base',
                'description' => 'Monitors cooking temperature for safety',
                'availability' => false,
                'comments' => 'Requires calibration after replacement',
                'image_path' => null
            ],
            [
                'part_number' => 'EPC018',
                'name' => 'Pressure Sensor',
                'appliance_id' => 1,
                'location' => 'Internal',
                'description' => 'Monitors internal pressure levels',
                'availability' => false,
                'comments' => 'Specialized part; limited stock',
                'image_path' => null
            ],
            [
                'part_number' => 'EPC019',
                'name' => 'Steam Release Knob',
                'appliance_id' => 1,
                'location' => 'Lid',
                'description' => 'Manual steam release control',
                'availability' => false,
                'comments' => 'Check for smooth operation',
                'image_path' => null
            ],
            [
                'part_number' => 'EPC020',
                'name' => 'Inner Lid Seal',
                'appliance_id' => 1,
                'location' => 'Lid',
                'description' => 'Secondary seal for lid',
                'availability' => false,
                'comments' => 'Replace if cracked or deformed',
                'image_path' => null
            ],
            [
                'part_number' => 'EPC021',
                'name' => 'Control Knob',
                'appliance_id' => 1,
                'location' => 'Front Body',
                'description' => 'Adjusts cooking settings manually',
                'availability' => false,
                'comments' => 'Limited for digital models',
                'image_path' => null
            ],
            [
                'part_number' => 'EPC025',
                'name' => 'Timer Module',
                'appliance_id' => 1,
                'location' => 'Internal',
                'description' => 'Controls cooking duration',
                'availability' => false,
                'comments' => 'Limited availability; order early',
                'image_path' => null
            ],
            [
                'part_number' => 'EPC028',
                'name' => 'Handle Grip',
                'appliance_id' => 1,
                'location' => 'Lid',
                'description' => 'Heat-resistant grip for lid',
                'availability' => false,
                'comments' => 'Check for secure attachment',
                'image_path' => null
            ],
            [
                'part_number' => 'EPC029',
                'name' => 'Thermal Fuse',
                'appliance_id' => 1,
                'location' => 'Internal',
                'description' => 'Protects against overheating',
                'availability' => false,
                'comments' => 'Replace after tripping',
                'image_path' => null
            ],
            [
                'part_number' => 'AF001',
                'name' => 'Air Fryer Basket',
                'appliance_id' => 2,
                'location' => 'Main Body',
                'description' => 'Non-stick basket for air frying',
                'availability' => false,
                'comments' => 'Check coating for wear',
                'image_path' => null
            ],
            [
                'part_number' => 'AF002',
                'name' => 'Air Fryer Tray',
                'appliance_id' => 2,
                'location' => 'Basket',
                'description' => 'Perforated tray for air circulation',
                'availability' => false,
                'comments' => 'Ensure proper fit in basket',
                'image_path' => null
            ],
            [
                'part_number' => 'AF003',
                'name' => 'Heating Coil',
                'appliance_id' => 2,
                'location' => 'Top Body',
                'description' => 'Generates heat for air frying',
                'availability' => false,
                'comments' => 'Professional installation recommended',
                'image_path' => null
            ],
            [
                'part_number' => 'AF004',
                'name' => 'Fan Blade',
                'appliance_id' => 2,
                'location' => 'Top Body',
                'description' => 'Circulates hot air for even cooking',
                'availability' => false,
                'comments' => 'Check for balance to avoid noise',
                'image_path' => null
            ],
            [
                'part_number' => 'AF005',
                'name' => 'Control Knob',
                'appliance_id' => 2,
                'location' => 'Front Body',
                'description' => 'Adjusts temperature and time',
                'availability' => false,
                'comments' => 'Limited for touch-screen models',
                'image_path' => null
            ],
            [
                'part_number' => 'AF006',
                'name' => 'Silicone Liner',
                'appliance_id' => 2,
                'location' => 'Basket',
                'description' => 'Reusable non-stick liner for easy cleaning',
                'availability' => false,
                'comments' => 'Eco-friendly alternative to parchment',
                'image_path' => null
            ],
            [
                'part_number' => 'AF007',
                'name' => 'Temperature Sensor',
                'appliance_id' => 2,
                'location' => 'Internal',
                'description' => 'Monitors frying temperature',
                'availability' => false,
                'comments' => 'Requires calibration',
                'image_path' => null
            ],
            [
                'part_number' => 'AF008',
                'name' => 'Air Fryer Rack',
                'appliance_id' => 2,
                'location' => 'Basket',
                'description' => 'Multi-layer rack for extra capacity',
                'availability' => false,
                'comments' => 'Check size compatibility',
                'image_path' => null
            ],
            [
                'part_number' => 'AF009',
                'name' => 'Handle',
                'appliance_id' => 2,
                'location' => 'Basket',
                'description' => 'Ergonomic handle for basket removal',
                'availability' => false,
                'comments' => 'Ensure secure attachment',
                'image_path' => null
            ],
            [
                'part_number' => 'AF010',
                'name' => 'Filter',
                'appliance_id' => 2,
                'location' => 'Top Body',
                'description' => 'Reduces smoke and odors during frying',
                'availability' => false,
                'comments' => 'Replace every 3-6 months',
                'image_path' => null
            ],
            [
                'part_number' => 'AF011',
                'name' => 'Grill Pan',
                'appliance_id' => 2,
                'location' => 'Basket',
                'description' => 'Non-stick pan for grilling meats',
                'availability' => false,
                'comments' => 'Check coating integrity',
                'image_path' => null
            ],
            [
                'part_number' => 'AF012',
                'name' => 'Skewer Rack',
                'appliance_id' => 2,
                'location' => 'Basket',
                'description' => 'Metal rack for kebabs and skewers',
                'availability' => false,
                'comments' => 'Ensure proper fit in basket',
                'image_path' => null
            ],
            [
                'part_number' => 'AF013',
                'name' => 'Baking Tray',
                'appliance_id' => 2,
                'location' => 'Basket',
                'description' => 'Non-stick tray for baking cakes',
                'availability' => false,
                'comments' => 'Ensure even heat distribution',
                'image_path' => null
            ],
            [
                'part_number' => 'AF014',
                'name' => 'Air Inlet Cover',
                'appliance_id' => 2,
                'location' => 'Top Body',
                'description' => 'Protects fan and heater from debris',
                'availability' => false,
                'comments' => 'Clean regularly to maintain airflow',
                'image_path' => null
            ],
            [
                'part_number' => 'AF015',
                'name' => 'Power Button',
                'appliance_id' => 2,
                'location' => 'Front Body',
                'description' => 'Toggles power on/off',
                'availability' => false,
                'comments' => 'Check for responsiveness',
                'image_path' => null
            ],
            [
                'part_number' => 'AF016',
                'name' => 'Timer Module',
                'appliance_id' => 2,
                'location' => 'Internal',
                'description' => 'Controls frying duration',
                'availability' => false,
                'comments' => 'Limited availability; order early',
                'image_path' => null
            ],
            [
                'part_number' => 'AF017',
                'name' => 'Non-Stick Coating',
                'appliance_id' => 2,
                'location' => 'Basket',
                'description' => 'Replacement coating for basket',
                'availability' => false,
                'comments' => 'Apply professionally to ensure durability',
                'image_path' => null
            ],
            [
                'part_number' => 'AF018',
                'name' => 'Air Fryer Lid',
                'appliance_id' => 2,
                'location' => 'Top Body',
                'description' => 'Covers cooking chamber',
                'availability' => false,
                'comments' => 'Check hinge alignment',
                'image_path' => null
            ],
            [
                'part_number' => 'AF019',
                'name' => 'Fan Guard',
                'appliance_id' => 2,
                'location' => 'Top Body',
                'description' => 'Protects fan blade from debris',
                'availability' => false,
                'comments' => 'Clean regularly to maintain airflow',
                'image_path' => null
            ],
            [
                'part_number' => 'AF020',
                'name' => 'Temperature Control Knob',
                'appliance_id' => 2,
                'location' => 'Front Body',
                'description' => 'Adjusts frying temperature',
                'availability' => false,
                'comments' => 'Limited for digital models',
                'image_path' => null
            ],
            [
                'part_number' => 'AF022',
                'name' => 'Silicone Liner',
                'appliance_id' => 2,
                'location' => 'Basket',
                'description' => 'Reusable non-stick liner',
                'availability' => false,
                'comments' => 'Eco-friendly cleaning solution',
                'image_path' => null
            ],
            [
                'part_number' => 'AF024',
                'name' => 'Air Fryer Tray',
                'appliance_id' => 2,
                'location' => 'Basket',
                'description' => 'Perforated tray for air circulation',
                'availability' => false,
                'comments' => 'Ensure proper fit',
                'image_path' => null
            ],
            [
                'part_number' => 'IC005',
                'name' => 'Cooling Fan',
                'appliance_id' => 3,
                'location' => 'Base',
                'description' => 'Prevents overheating of internal components',
                'availability' => false,
                'comments' => 'Ensure proper airflow',
                'image_path' => null
            ],
            [
                'part_number' => 'IC006',
                'name' => 'Sensor Diode',
                'appliance_id' => 3,
                'location' => 'Internal',
                'description' => 'Detects pan presence for safety',
                'availability' => false,
                'comments' => 'Specialized part; limited stock',
                'image_path' => null
            ],
            [
                'part_number' => 'IC007',
                'name' => 'Bridge Rectifier',
                'appliance_id' => 3,
                'location' => 'Internal',
                'description' => 'Converts AC to DC for internal circuits',
                'availability' => false,
                'comments' => 'Requires technical expertise',
                'image_path' => null
            ],
            [
                'part_number' => 'IC008',
                'name' => 'Power Cord',
                'appliance_id' => 3,
                'location' => 'Base',
                'description' => 'Detachable 220-240V power cord',
                'availability' => false,
                'comments' => 'Check plug type for compatibility',
                'image_path' => null
            ],
            [
                'part_number' => 'IC009',
                'name' => 'Touch Panel',
                'appliance_id' => 3,
                'location' => 'Top Surface',
                'description' => 'Touch-sensitive control interface',
                'availability' => false,
                'comments' => 'Limited for older models; verify compatibility',
                'image_path' => null
            ],
            [
                'part_number' => 'IC010',
                'name' => 'Thermal Fuse',
                'appliance_id' => 3,
                'location' => 'Internal',
                'description' => 'Protects against overheating',
                'availability' => false,
                'comments' => 'Replace after tripping',
                'image_path' => null
            ],
            [
                'part_number' => 'IC011',
                'name' => 'Fan Motor',
                'appliance_id' => 3,
                'location' => 'Base',
                'description' => 'Drives cooling fan for heat dissipation',
                'availability' => false,
                'comments' => 'Ensure proper voltage (220-240V)',
                'image_path' => null
            ],
            [
                'part_number' => 'IC013',
                'name' => 'Base Cover',
                'appliance_id' => 3,
                'location' => 'Bottom',
                'description' => 'Protects internal components',
                'availability' => false,
                'comments' => 'Check for cracks or damage',
                'image_path' => null
            ],
            [
                'part_number' => 'IC014',
                'name' => 'IGBT Module',
                'appliance_id' => 3,
                'location' => 'Internal',
                'description' => 'Controls power output for induction',
                'availability' => false,
                'comments' => 'Specialized part; requires expertise',
                'image_path' => null
            ],
            [
                'part_number' => 'IC015',
                'name' => 'Microswitch',
                'appliance_id' => 3,
                'location' => 'Internal',
                'description' => 'Safety switch for operation',
                'availability' => false,
                'comments' => 'Check alignment for functionality',
                'image_path' => null
            ],
            [
                'part_number' => 'IC016',
                'name' => 'Display Module',
                'appliance_id' => 3,
                'location' => 'Front Body',
                'description' => 'Shows power and time settings',
                'availability' => false,
                'comments' => 'Fragile; handle with care',
                'image_path' => null
            ],
            [
                'part_number' => 'IC017',
                'name' => 'Base Feet',
                'appliance_id' => 3,
                'location' => 'Bottom',
                'description' => 'Non-slip feet for stability',
                'availability' => false,
                'comments' => 'Replace if worn or missing',
                'image_path' => null
            ],
            [
                'part_number' => 'IC018',
                'name' => 'Power Regulator',
                'appliance_id' => 3,
                'location' => 'Internal',
                'description' => 'Controls power output',
                'availability' => false,
                'comments' => 'Technical replacement needed',
                'image_path' => null
            ],
            [
                'part_number' => 'IC019',
                'name' => 'Temperature Sensor',
                'appliance_id' => 3,
                'location' => 'Internal',
                'description' => 'Monitors surface temperature',
                'availability' => false,
                'comments' => 'Requires calibration',
                'image_path' => null
            ],
            [
                'part_number' => 'IC033',
                'name' => 'Control Board',
                'appliance_id' => 3,
                'location' => 'Internal',
                'description' => 'Manages cooking settings',
                'availability' => false,
                'comments' => 'Limited availability; order early',
                'image_path' => null
            ],
            [
                'part_number' => 'IC038',
                'name' => 'Control Board',
                'appliance_id' => 3,
                'location' => 'Internal',
                'description' => 'Manages cooking settings',
                'availability' => false,
                'comments' => 'Limited availability; order early',
                'image_path' => null
            ],
            [
                'part_number' => 'IC039',
                'name' => 'Glass Plate',
                'appliance_id' => 3,
                'location' => 'Top Surface',
                'description' => 'Ceramic glass cooking surface',
                'availability' => false,
                'comments' => 'Check for cracks',
                'image_path' => null
            ],
            [
                'part_number' => 'IC040',
                'name' => 'Power Switch',
                'appliance_id' => 3,
                'location' => 'Front Body',
                'description' => 'On/off switch for power',
                'availability' => false,
                'comments' => 'Check for electrical faults',
                'image_path' => null
            ],
            [
                'part_number' => 'IC041',
                'name' => 'Induction Coil',
                'appliance_id' => 3,
                'location' => 'Base',
                'description' => 'Generates electromagnetic field',
                'availability' => false,
                'comments' => 'Professional replacement required',
                'image_path' => null
            ],
            [
                'part_number' => 'IC043',
                'name' => 'Control Knob',
                'appliance_id' => 3,
                'location' => 'Front Body',
                'description' => 'Adjusts power settings manually',
                'availability' => false,
                'comments' => 'Limited for touch-panel models',
                'image_path' => null
            ],
            [
                'part_number' => 'IC044',
                'name' => 'Glass Plate',
                'appliance_id' => 3,
                'location' => 'Top Surface',
                'description' => 'Ceramic glass cooking surface',
                'availability' => false,
                'comments' => 'Check for cracks',
                'image_path' => null
            ]
        ];

        $createdParts = [];
        foreach ($parts as $part) {
            $createdParts[] = Part::create($part);
        }

        // Attach brands to parts
        foreach ($createdParts as $part) {
            $part->brands()->attach([1, 2]); // Attach first two brands
        }
    }
}
