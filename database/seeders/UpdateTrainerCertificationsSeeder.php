<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateTrainerCertificationsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('trainers')->where('email', 'john.katende@training.com')->update([
            'certifications' => 'Certified EPC Technician, Advanced Training Certificate',
            'qualifications' => 'Bachelor of Engineering, Certified Trainer',
        ]);

        DB::table('trainers')->where('email', 'sarah.nakimuli@training.com')->update([
            'certifications' => 'Air Fryer Specialist, Safety Compliance Certified',
            'qualifications' => 'Diploma in Electrical Engineering, Trainer Certification',
        ]);

        DB::table('trainers')->where('email', 'david.ochieng@training.com')->update([
            'certifications' => 'Induction Technology Expert, Master Trainer',
            'qualifications' => 'Bachelor of Science, Advanced Trainer',
        ]);

        DB::table('trainers')->where('email', 'grace.ssemanda@training.com')->update([
            'certifications' => 'General Appliance Trainer, Customer Service Excellence',
            'qualifications' => 'Certificate in Appliance Technology',
        ]);

        DB::table('trainers')->where('email', 'peter.mwesigwa@training.com')->update([
            'certifications' => 'EPC Master Technician, Advanced Trainer Certificate',
            'qualifications' => 'Diploma in Electrical Engineering, Trainer Certification',
        ]);
    }
}
