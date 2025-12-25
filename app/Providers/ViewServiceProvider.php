<?php

namespace App\Providers;

use App\Models\HrdPerson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share HRD person data with navigation
        View::composer('layouts.navigation', function ($view) {
            $hrdPerson = null;
            
            if (Auth::check() && Auth::user()->idcard) {
                $hrdPerson = HrdPerson::findByIdCard(Auth::user()->idcard);
            }
            
            $view->with('navHrdPerson', $hrdPerson);
        });
    }
}
