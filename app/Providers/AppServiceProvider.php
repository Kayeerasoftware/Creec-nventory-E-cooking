<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\Trainer;
use App\Models\Technician;
use App\Observers\UserObserver;
use App\Observers\TrainerObserver;
use App\Observers\TechnicianObserver;
use App\View\Composers\UserProfileComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Trainer::observe(TrainerObserver::class);
        Technician::observe(TechnicianObserver::class);
        
        View::composer('*', UserProfileComposer::class);
    }
}
