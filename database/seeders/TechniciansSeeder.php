<?php

namespace Database\Seeders;

use App\Models\Technician;
use Illuminate\Database\Seeder;

class TechniciansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $technicians = [
            [
                'name' => 'John Mukasa',
                'specialty' => 'Appliance Repair Technician',
                'email' => 'john.mukasa@creec.co.ug',
                'phone' => '+256 701 234 567',
                'license' => 'UG-ART-2024-001',
                'location' => 'Kampala',
                'experience' => 12,
                'rate' => 150000,
                'skills' => ['Refrigeration', 'Washing Machines', 'Microwaves', 'Dishwashers'],
                'certifications' => ['Certified Appliance Technician', 'EPA 608 Certification'],
                'image' => 'https://randomuser.me/api/portraits/men/1.jpg',
            ],
            [
                'name' => 'Sarah Nakayenga',
                'specialty' => 'Senior Appliance Technician',
                'email' => 'sarah.nakayenga@creec.co.ug',
                'phone' => '+256 702 345 678',
                'license' => 'UG-ART-2024-002',
                'location' => 'Kampala',
                'experience' => 8,
                'rate' => 120000,
                'skills' => ['Gas Stoves', 'Electric Cookers', 'HVAC Systems'],
                'certifications' => ['Gas Safety Certificate', 'Advanced Appliance Repair'],
                'image' => 'https://randomuser.me/api/portraits/women/2.jpg',
            ],
            [
                'name' => 'Robert Mwesigwa',
                'specialty' => 'Commercial Appliance Specialist',
                'email' => 'robert.mwesigwa@creec.co.ug',
                'phone' => '+256 703 456 789',
                'license' => 'UG-ART-2024-003',
                'location' => 'Mbarara',
                'experience' => 15,
                'rate' => 200000,
                'skills' => ['Industrial Refrigeration', 'Commercial Kitchen Equipment', 'Ice Machines'],
                'certifications' => ['Commercial Equipment Specialist', 'Refrigeration Engineering'],
                'image' => 'https://randomuser.me/api/portraits/men/3.jpg',
            ],
            [
                'name' => 'Grace Nansubuga',
                'specialty' => 'Appliance Technician',
                'email' => 'grace.nansubuga@creec.co.ug',
                'phone' => '+256 704 567 890',
                'license' => 'UG-ART-2024-004',
                'location' => 'Jinja',
                'experience' => 6,
                'rate' => 100000,
                'skills' => ['Small Appliances', 'Vacuum Cleaners', 'Coffee Machines'],
                'certifications' => ['Appliance Repair Certificate', 'Electrical Safety'],
                'image' => 'https://randomuser.me/api/portraits/women/4.jpg',
            ],
            [
                'name' => 'Michael Tumwebaze',
                'specialty' => 'HVAC and Appliance Technician',
                'email' => 'michael.tumwebaze@creec.co.ug',
                'phone' => '+256 705 678 901',
                'license' => 'UG-ART-2024-005',
                'location' => 'Kampala',
                'experience' => 10,
                'rate' => 180000,
                'skills' => ['Air Conditioners', 'Heaters', 'Heat Pumps', 'Central Cooling Systems'],
                'certifications' => ['HVAC Certification', 'EPA 608 Certification'],
                'image' => 'https://randomuser.me/api/portraits/men/5.jpg',
            ],
            [
                'name' => 'Anita Achieng',
                'specialty' => 'Washer and Dryer Specialist',
                'email' => 'anita.achieng@creec.co.ug',
                'phone' => '+256 706 789 012',
                'license' => 'UG-ART-2024-006',
                'location' => 'Entebbe',
                'experience' => 7,
                'rate' => 130000,
                'skills' => ['Washing Machines', 'Tumble Dryers', 'Washer-Dryer Combos'],
                'certifications' => ['Laundry Equipment Specialist'],
                'image' => 'https://randomuser.me/api/portraits/women/6.jpg',
            ],
            [
                'name' => 'David Ssekyanzi',
                'specialty' => 'Refrigeration Specialist',
                'email' => 'david.ssekyanzi@creec.co.ug',
                'phone' => '+256 707 890 123',
                'license' => 'UG-ART-2024-007',
                'location' => 'Kampala',
                'experience' => 14,
                'rate' => 170000,
                'skills' => ['Refrigerators', 'Freezers', 'Wine Coolers', 'Walk-in Coolers'],
                'certifications' => ['Refrigeration Certificate', 'Food Safety Compliance'],
                'image' => 'https://randomuser.me/api/portraits/men/7.jpg',
            ],
            [
                'name' => 'Phiona Namulanda',
                'specialty' => 'Appliance Technician',
                'email' => 'phiona.namulanda@creec.co.ug',
                'phone' => '+256 708 901 234',
                'license' => 'UG-ART-2024-008',
                'location' => 'Wakiso',
                'experience' => 5,
                'rate' => 90000,
                'skills' => ['Microwaves', 'Toasters', 'Blenders', 'Food Processors'],
                'certifications' => ['Small Appliance Repair'],
                'image' => 'https://randomuser.me/api/portraits/women/8.jpg',
            ],
            [
                'name' => 'James Odiambo',
                'specialty' => 'Kitchen Appliance Specialist',
                'email' => 'james.odiambo@creec.co.ug',
                'phone' => '+256 709 012 345',
                'license' => 'UG-ART-2024-009',
                'location' => 'Gulu',
                'experience' => 9,
                'rate' => 140000,
                'skills' => ['Ovens', 'Cooktops', 'Range Hoods', 'Dishwashers'],
                'certifications' => ['Kitchen Appliance Expert', 'Electrical Certification'],
                'image' => 'https://randomuser.me/api/portraits/men/9.jpg',
            ],
            [
                'name' => 'Ruth Katusiime',
                'specialty' => 'Senior Appliance Technician',
                'email' => 'ruth.katusiime@creec.co.ug',
                'phone' => '+256 710 123 456',
                'license' => 'UG-ART-2024-010',
                'location' => 'Kampala',
                'experience' => 11,
                'rate' => 160000,
                'skills' => ['All Appliance Types', 'Diagnostic Testing', 'Preventive Maintenance'],
                'certifications' => ['Master Appliance Technician', 'Technical Trainer'],
                'image' => 'https://randomuser.me/api/portraits/women/10.jpg',
            ],
        ];

        foreach ($technicians as $technician) {
            Technician::create($technician);
        }
    }
}
