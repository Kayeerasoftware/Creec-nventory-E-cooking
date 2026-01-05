<?php

namespace App\Observers;

use App\Models\Trainer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TrainerObserver
{
    public function created(Trainer $trainer): void
    {
        $existingUser = User::where('email', $trainer->email)->first();
        
        if (!$existingUser) {
            User::create([
                'name' => $trainer->name,
                'email' => $trainer->email,
                'password' => $trainer->password,
                'role' => 'trainer',
                'trainer_id' => $trainer->id,
            ]);
        }
    }

    public function updated(Trainer $trainer): void
    {
        $trainer->user?->update([
            'name' => $trainer->name,
            'email' => $trainer->email,
        ]);
    }

    public function deleted(Trainer $trainer): void
    {
        $trainer->user?->delete();
    }
}
