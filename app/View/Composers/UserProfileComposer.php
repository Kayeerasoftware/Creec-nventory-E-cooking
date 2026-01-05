<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Technician;
use App\Models\Trainer;

class UserProfileComposer
{
    public function compose(View $view)
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            if (!$user->profile_picture) {
                if ($user->technician_id) {
                    $tech = Technician::find($user->technician_id);
                    $user->profile_picture = $tech ? ($tech->profile_photo ?? $tech->image) : null;
                } elseif ($user->trainer_id) {
                    $trainer = Trainer::find($user->trainer_id);
                    $user->profile_picture = $trainer ? $trainer->image : null;
                }
            }
        }
    }
}
