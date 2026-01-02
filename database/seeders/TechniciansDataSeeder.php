<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TechniciansDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        $specialties = [
            'Refrigeration Systems' => ['AC Repair, Refrigerator Maintenance, Cold Room Installation', 'Certified HVAC Technician, Refrigeration License Class A'],
            'Electronics Repair' => ['TV Repair, Audio Systems, Microwave Servicing', 'Electronics Technician Certificate, Appliance Repair License'],
            'Washing Machine Repair' => ['Washing Machine Repair, Dryer Maintenance, Spin Motor Replacement', 'Laundry Equipment Specialist, Appliance Technology Certificate'],
            'Kitchen Appliances' => ['Microwave Repair, Oven Maintenance, Blender Servicing', 'Kitchen Appliance Repair Certificate, Safety Compliance License'],
            'Audio-Visual Equipment' => ['TV Repair, Sound System Installation, Home Theater Setup', 'Audio-Visual Systems Expert, Electronics Engineering Diploma']
        ];

        $locations = ['Kampala Central', 'Entebbe', 'Jinja', 'Mbarara', 'Gulu'];
        $regions = ['Central', 'Eastern', 'Western', 'Northern'];
        $statuses = ['Available', 'Available', 'Available', 'Busy', 'Available'];

        $technicians = [
            ['first' => 'Michael', 'last' => 'Ssebunya', 'specialty' => 'Refrigeration Systems', 'location' => 'Kampala Central', 'exp' => 12, 'rate' => 25000],
            ['first' => 'Agnes', 'last' => 'Namutebi', 'specialty' => 'Electronics Repair', 'location' => 'Entebbe', 'exp' => 8, 'rate' => 20000],
            ['first' => 'Joseph', 'last' => 'Kiggundu', 'specialty' => 'Washing Machine Repair', 'location' => 'Jinja', 'exp' => 10, 'rate' => 22000],
            ['first' => 'Rebecca', 'last' => 'Nakimuli', 'specialty' => 'Kitchen Appliances', 'location' => 'Mbarara', 'exp' => 6, 'rate' => 18000],
            ['first' => 'Samuel', 'last' => 'Ochieng', 'specialty' => 'Audio-Visual Equipment', 'location' => 'Gulu', 'exp' => 15, 'rate' => 30000]
        ];

        foreach ($technicians as $index => $tech) {
            $specialty = $tech['specialty'];
            $skills = $specialties[$specialty][0];
            $certs = $specialties[$specialty][1];
            $initials = strtoupper(substr($tech['first'], 0, 1) . substr($tech['last'], 0, 1));
            
            DB::table('technicians')->where('id', $index + 1)->update([
                'first_name' => $tech['first'],
                'middle_name' => $faker->optional()->firstName,
                'last_name' => $tech['last'],
                'name' => $tech['first'] . ' ' . $tech['last'],
                'gender' => $faker->randomElement(['Male', 'Female']),
                'date_of_birth' => $faker->date('Y-m-d', '-25 years'),
                'nationality' => 'Ugandan',
                'id_number' => 'CM' . $faker->numerify('##########'),
                'email' => strtolower($tech['first'] . '.' . $tech['last']) . '@tech.com',
                'phone' => '+256 77' . $faker->numerify('# ### ###'),
                'phone_2' => $faker->optional()->numerify('+256 78# ### ###'),
                'whatsapp' => '+256 77' . $faker->numerify('# ### ###'),
                'emergency_contact' => $faker->name,
                'emergency_phone' => $faker->numerify('+256 77# ### ###'),
                'country' => 'Uganda',
                'region' => $faker->randomElement($regions),
                'district' => $tech['location'],
                'sub_county' => $faker->city,
                'village' => $faker->streetName,
                'location' => $tech['location'],
                'specialty' => $specialty,
                'license' => 'TL00123' . ($index + 4),
                'experience' => $tech['exp'],
                'rate' => $tech['rate'],
                'hourly_rate' => $tech['rate'],
                'daily_rate' => $tech['rate'] * 8,
                'status' => $statuses[$index],
                'employment_type' => 'Full-Time',
                'skills' => json_encode(explode(', ', $skills)),
                'certifications' => json_encode(explode(', ', $certs)),
                'training' => 'Advanced Technical Training',
                'languages' => 'English, Luganda',
                'own_tools' => 'Yes',
                'has_vehicle' => $faker->randomElement(['Yes', 'No']),
                'equipment_list' => 'Multimeter, Oscilloscope, Soldering Station',
                'service_areas' => $tech['location'] . ', Surrounding Areas',
                'jobs_completed' => $faker->numberBetween(50, 150),
                'rating' => $faker->randomFloat(1, 4.5, 5.0),
                'response_time' => $faker->randomElement(['1-2hrs', '2-4hrs', '4-6hrs']),
                'notes' => 'Experienced technician with excellent customer service skills. Committed to providing quality repair services.'
            ]);
        }
    }
}
