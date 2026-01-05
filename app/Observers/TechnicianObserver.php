<?php

namespace App\Observers;

use App\Models\Technician;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TechnicianObserver
{
    public function created(Technician $technician): void
    {
        $existingUser = User::where('email', $technician->email)->first();
        
        if (!$existingUser) {
            User::create([
                'name' => $technician->name,
                'email' => $technician->email,
                'password' => $technician->password,
                'role' => 'technician',
                'technician_id' => $technician->id,
            ]);
        }
    }

    public function updated(Technician $technician): void
    {
        $technician->user?->update([
            'name' => $technician->name,
            'email' => $technician->email,
        ]);
    }

    public function deleted(Technician $technician): void
    {
        $technician->user?->delete();
    }
}
