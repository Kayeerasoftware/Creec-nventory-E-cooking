<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ComprehensiveTechniciansSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('technicians')->truncate();
        
        $faker = Faker::create();
        
        $specialties = [
            'Refrigeration Systems' => ['AC Repair', 'Refrigerator Maintenance', 'Cold Room Installation', 'Compressor Repair'],
            'Electronics Repair' => ['TV Repair', 'Audio Systems', 'Microwave Servicing', 'PCB Diagnostics'],
            'Washing Machine Repair' => ['Washing Machine Repair', 'Dryer Maintenance', 'Spin Motor Replacement', 'Drum Repair'],
            'Kitchen Appliances' => ['Microwave Repair', 'Oven Maintenance', 'Blender Servicing', 'Food Processor Repair'],
            'Audio-Visual Equipment' => ['TV Repair', 'Sound System Installation', 'Home Theater Setup', 'Projector Maintenance'],
            'Air Conditioning' => ['AC Installation', 'AC Repair', 'Ventilation Systems', 'HVAC Maintenance'],
            'Microwave & Oven Repair' => ['Microwave Repair', 'Oven Maintenance', 'Heating Element Replacement', 'Control Panel Repair'],
            'Dishwasher Repair' => ['Dishwasher Repair', 'Pump Replacement', 'Spray Arm Maintenance', 'Drain System Repair'],
            'Small Appliances' => ['Small Appliance Repair', 'Component Level Repair', 'Motor Replacement', 'Electrical Repair'],
            'Industrial Equipment' => ['Industrial Equipment Maintenance', 'Heavy Machinery Repair', 'Conveyor Systems', 'Factory Equipment']
        ];

        $certifications = [
            'Refrigeration Systems' => ['Certified HVAC Technician', 'Refrigeration License Class A', 'EPA Certification'],
            'Electronics Repair' => ['Electronics Technician Certificate', 'Appliance Repair License', 'IPC Certification'],
            'Washing Machine Repair' => ['Laundry Equipment Specialist', 'Appliance Technology Certificate', 'Motor Repair Certification'],
            'Kitchen Appliances' => ['Kitchen Appliance Repair Certificate', 'Safety Compliance License', 'Gas Appliance Certification'],
            'Audio-Visual Equipment' => ['Audio-Visual Systems Expert', 'Electronics Engineering Diploma', 'CEDIA Certification'],
            'Air Conditioning' => ['HVAC Certification', 'EPA Section 608 Certified', 'NATE Certification'],
            'Microwave & Oven Repair' => ['Appliance Repair Technician Certificate', 'Electrical Safety Certified', 'Microwave Specialist'],
            'Dishwasher Repair' => ['Dishwasher Specialist Certification', 'Plumbing License', 'Water System Certification'],
            'Small Appliances' => ['Small Appliance Repair Certificate', 'Electrical Safety Certification', 'Component Level Repair'],
            'Industrial Equipment' => ['Industrial Equipment Certification', 'Mechanical Engineering Degree', 'PLC Programming']
        ];

        $locations = ['Kampala Central', 'Entebbe', 'Jinja', 'Mbarara', 'Gulu', 'Mbale', 'Masaka', 'Lira', 'Fort Portal', 'Arua'];
        $regions = ['Central', 'Eastern', 'Western', 'Northern'];
        $districts = ['Kampala', 'Wakiso', 'Mukono', 'Jinja', 'Mbale', 'Mbarara', 'Gulu', 'Lira', 'Masaka', 'Arua'];
        $statuses = ['Available', 'Available', 'Available', 'Busy', 'Available', 'Available'];
        $employmentTypes = ['Full-Time', 'Part-Time', 'Contract', 'Freelance'];
        $languages = ['English, Luganda', 'English, Swahili', 'English, Luganda, Swahili', 'English, Runyankole', 'English, Acholi'];

        for ($i = 0; $i < 20; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $specialty = $faker->randomElement(array_keys($specialties));
            $location = $faker->randomElement($locations);
            $experience = $faker->numberBetween(2, 20);
            $hourlyRate = $faker->numberBetween(15000, 35000);
            
            DB::table('technicians')->insert([
                'first_name' => $firstName,
                'middle_name' => $faker->optional(0.3)->firstName,
                'last_name' => $lastName,
                'name' => $firstName . ' ' . $lastName,
                'gender' => $faker->randomElement(['Male', 'Female']),
                'date_of_birth' => $faker->date('Y-m-d', '-30 years'),
                'nationality' => 'Ugandan',
                'id_number' => 'CM' . $faker->numerify('##########'),
                'email' => strtolower($firstName . '.' . $lastName . $i) . '@tech.ug',
                'phone' => '+256 7' . $faker->numerify('## ### ###'),
                'phone_2' => $faker->optional(0.5)->numerify('+256 7## ### ###'),
                'whatsapp' => '+256 7' . $faker->numerify('## ### ###'),
                'emergency_contact' => $faker->name,
                'emergency_phone' => $faker->numerify('+256 7## ### ###'),
                'country' => 'Uganda',
                'region' => $faker->randomElement($regions),
                'district' => $faker->randomElement($districts),
                'sub_county' => $faker->city,
                'village' => $faker->streetName,
                'location' => $location,
                'specialty' => $specialty,
                'license' => 'TL' . $faker->numerify('######'),
                'experience' => $experience,
                'rate' => $hourlyRate,
                'hourly_rate' => $hourlyRate,
                'daily_rate' => $hourlyRate * 8,
                'status' => $faker->randomElement($statuses),
                'employment_type' => $faker->randomElement($employmentTypes),
                'skills' => json_encode($specialties[$specialty]),
                'certifications' => json_encode($certifications[$specialty]),
                'training' => $faker->randomElement([
                    'Advanced Technical Training',
                    'Manufacturer Certified Training',
                    'On-the-job Training',
                    'Vocational Technical Training',
                    'Apprenticeship Program'
                ]),
                'languages' => $faker->randomElement($languages),
                'own_tools' => $faker->randomElement(['Yes', 'No', 'Partial']),
                'has_vehicle' => $faker->randomElement(['Yes', 'No']),
                'equipment_list' => $faker->randomElement([
                    'Multimeter, Oscilloscope, Soldering Station',
                    'Power Tools, Hand Tools, Testing Equipment',
                    'Diagnostic Tools, Repair Kit, Safety Equipment',
                    'Specialized Tools, Meters, Gauges'
                ]),
                'service_areas' => $location . ', ' . $faker->randomElement($districts) . ', Surrounding Areas',
                'jobs_completed' => $faker->numberBetween(20, 200),
                'rating' => $faker->randomFloat(1, 4.0, 5.0),
                'response_time' => $faker->randomElement(['1-2hrs', '2-4hrs', '4-6hrs', 'Same Day']),
                'notes' => $faker->optional(0.7)->sentence(15),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
