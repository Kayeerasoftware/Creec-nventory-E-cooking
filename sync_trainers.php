<?php

use App\Models\Trainer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$trainers = Trainer::all();

foreach ($trainers as $trainer) {
    User::updateOrCreate(
        ['email' => $trainer->email],
        [
            'name' => $trainer->name,
            'email' => $trainer->email,
            'password' => Hash::make('kayeera'),
            'role' => 'trainer',
            'trainer_id' => $trainer->id,
        ]
    );
}

echo "Synced {$trainers->count()} trainers to users table\n";
