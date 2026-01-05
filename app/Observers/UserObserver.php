<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Trainer;
use App\Models\Technician;

class UserObserver
{
    public function created(User $user): void
    {
        if ($user->role === 'trainer' && !$user->trainer_id) {
            $trainer = Trainer::create([
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
                'specialty' => 'General Training',
                'experience' => 0,
                'phone' => '',
            ]);
            $user->update(['trainer_id' => $trainer->id]);
        }

        if ($user->role === 'technician' && !$user->technician_id) {
            $technician = Technician::create([
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
                'specialty' => 'General Repair',
                'experience' => 0,
                'phone' => '',
                'license' => '',
                'location' => '',
            ]);
            $user->update(['technician_id' => $technician->id]);
        }
    }

    public function updated(User $user): void
    {
        if ($user->role === 'trainer' && $user->trainer_id) {
            Trainer::where('id', $user->trainer_id)->update([
                'name' => $user->name,
                'email' => $user->email,
            ]);
        }

        if ($user->role === 'technician' && $user->technician_id) {
            Technician::where('id', $user->technician_id)->update([
                'name' => $user->name,
                'email' => $user->email,
            ]);
        }
    }

    public function deleted(User $user): void
    {
        if ($user->role === 'trainer' && $user->trainer_id) {
            Trainer::where('id', $user->trainer_id)->delete();
        }

        if ($user->role === 'technician' && $user->technician_id) {
            Technician::where('id', $user->technician_id)->delete();
        }
    }
}
