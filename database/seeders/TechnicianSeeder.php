<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Technician;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Hash;

class TechnicianSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = base_path('Technicians.xlsx');
        
        if (!file_exists($filePath)) {
            $this->command->warn('Technicians.xlsx not found. Skipping technician seeding.');
            return;
        }
        
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        
        $currentVenue = '';
        $currentTrainingDates = '';
        $cohortNumber = 0;
        
        foreach ($worksheet->getRowIterator(2) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $cells = [];
            
            foreach ($cellIterator as $cell) {
                $cells[] = $cell->getValue();
            }
            
            if (isset($cells[0]) && str_contains(strtolower($cells[0] ?? ''), 'training dates')) {
                $currentTrainingDates = $cells[0];
                $cohortNumber++;
                continue;
            }
            
            if (isset($cells[7]) && str_contains(strtolower($cells[7] ?? ''), 'venue')) {
                $currentVenue = str_replace(['Venue:', 'Venue'], '', $cells[7]);
                $currentVenue = trim($currentVenue);
                continue;
            }
            
            if ($cells[0] === 'ID' || empty($cells[1])) {
                continue;
            }
            
            $name = trim($cells[1] ?? '');
            if (empty($name)) continue;
            
            $nameParts = explode(' ', strtolower($name));
            $emailName = implode('', array_filter($nameParts));
            $email = $emailName . $cohortNumber . '@creec.com';
            
            $phone = $cells[5] ?? '';
            $phone = preg_replace('/[^0-9]/', '', $phone);
            if (str_starts_with($phone, '0')) {
                $phone = '+256' . substr($phone, 1);
            } elseif (!str_starts_with($phone, '+')) {
                $phone = '+256' . $phone;
            }
            
            Technician::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'phone' => $phone,
                    'place_of_work' => $cells[4] ?? null,
                    'gender' => $cells[2] ?? null,
                    'age' => $cells[3] ?? null,
                    'venue' => $currentVenue ?: null,
                    'training_dates' => $currentTrainingDates ?: null,
                    'cohort_number' => $cohortNumber,
                    'specialty' => 'E-cooking',
                    'license' => 'N/A',
                    'location' => $currentVenue ?: 'Uganda',
                    'password' => Hash::make('password'),
                ]
            );
        }
    }
}
