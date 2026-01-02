<?php

namespace Database\Seeders;

use App\Models\Technician;
use Illuminate\Database\Seeder;

class TechnicianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $technicians = [
            [
                'name' => 'Michael Ssebunya',
                'specialty' => 'Refrigeration Systems',
                'email' => 'michael.ssebunya@tech.com',
                'phone' => '+256 770 123 45667',
                'license' => 'TL001234',
                'location' => 'Kampala Central',
                'experience' => 12,
                'rate' => 25000,
                'skills' => ['AC Repair', 'Refrigerator Maintenance', 'Cold Room Installation'],
                'certifications' => ['Certified HVAC Technician', 'Refrigeration License Class A'],
                'status' => 'Available',
            ],
            [
                'name' => 'Agnes Namutebi',
                'specialty' => 'Electronics Repair',
                'email' => 'agnes.namutebi@tech.com',
                'phone' => '+256 771 234 567',
                'license' => 'TL001235',
                'location' => 'Entebbe',
                'experience' => 8,
                'rate' => 20000,
                'skills' => ['TV Repair', 'Audio Systems', 'Microwave Servicing'],
                'certifications' => ['Electronics Technician Certificate', 'Appliance Repair License'],
                'status' => 'Available',
            ],
            [
                'name' => 'Joseph Kiggundu',
                'specialty' => 'Washing Machine Repair',
                'email' => 'joseph.kiggundu@tech.com',
                'phone' => '+256 772 345 678',
                'license' => 'TL001236',
                'location' => 'Jinja',
                'experience' => 10,
                'rate' => 22000,
                'skills' => ['Washing Machine Repair', 'Dryer Maintenance', 'Spin Motor Replacement'],
                'certifications' => ['Laundry Equipment Specialist', 'Appliance Technology Certificate'],
                'status' => 'Busy',
            ],
            [
                'name' => 'Rebecca Nakimuli',
                'specialty' => 'Kitchen Appliances',
                'email' => 'rebecca.nakimuli@tech.com',
                'phone' => '+256 773 456 789',
                'license' => 'TL001237',
                'location' => 'Mbarara',
                'experience' => 6,
                'rate' => 18000,
                'skills' => ['Microwave Repair', 'Oven Maintenance', 'Blender Servicing'],
                'certifications' => ['Kitchen Appliance Repair Certificate', 'Safety Compliance License'],
                'status' => 'Available',
            ],
            [
                'name' => 'Samuel Ochieng',
                'specialty' => 'Audio-Visual Equipment',
                'email' => 'samuel.ochieng@tech.com',
                'phone' => '+256 774 567 890',
                'license' => 'TL001238',
                'location' => 'Gulu',
                'experience' => 15,
                'rate' => 30000,
                'skills' => ['TV Repair', 'Sound System Installation', 'Home Theater Setup'],
                'certifications' => ['Audio-Visual Systems Expert', 'Electronics Engineering Diploma'],
                'status' => 'Available',
            ],
        ];

        foreach ($technicians as $technician) {
            Technician::updateOrCreate(
                ['email' => $technician['email']],
                $technician
            );
        }
    }
}
