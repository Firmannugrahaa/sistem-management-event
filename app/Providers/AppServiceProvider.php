<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\CompanySetting;

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
        View::composer('*', function ($view) {
            $companySettings = cache()->remember('company_settings', 3600, function () { // Cache for 1 hour
                return \App\Models\CompanySetting::first();
            });
            $view->with('companySettings', $companySettings);
        });
    }
}
