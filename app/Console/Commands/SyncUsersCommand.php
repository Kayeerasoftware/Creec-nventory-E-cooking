<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trainer;
use App\Models\Technician;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SyncUsersCommand extends Command
{
    protected $signature = 'users:sync';
    protected $description = 'Create user accounts for trainers and technicians';

    public function handle()
    {
        // Sync trainers
        $trainers = Trainer::all();
        foreach ($trainers as $trainer) {
            User::firstOrCreate(
                ['email' => $trainer->email],
                [
                    'name' => $trainer->name,
                    'password' => Hash::make('password123'), // Default password
                    'role' => 'trainer',
                ]
            );
            $this->info("Created user for trainer: {$trainer->email}");
        }

        // Sync technicians
        $technicians = Technician::all();
        foreach ($technicians as $technician) {
            User::firstOrCreate(
                ['email' => $technician->email],
                [
                    'name' => $technician->name,
                    'password' => Hash::make('password123'), // Default password
                    'role' => 'technician',
                ]
            );
            $this->info("Created user for technician: {$technician->email}");
        }

        $this->info('All users synced successfully!');
        $this->info('Default password for all: password123');
    }
}
