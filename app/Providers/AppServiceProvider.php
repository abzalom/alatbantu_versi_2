<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use App\Observers\OpdTagBidangObserver;
use Illuminate\Support\ServiceProvider;
use App\Models\Tagging\Nomenklatur\OpdTagBidang;

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
        date_default_timezone_set(config('app.timezone')); // Ambil dari config/app.php
        Carbon::setLocale('id'); // Set bahasa Indonesia untuk Carbon
        // URL::forceScheme('https');
        Paginator::useBootstrapFive();
        Paginator::useBootstrapFour();
        OpdTagBidang::observe(OpdTagBidangObserver::class);
    }
}
