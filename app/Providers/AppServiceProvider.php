<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Services\BookingService;
        
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    $this->app->singleton('bookingservice', function ($app) {
        return new BookingService();
    });
              
    $this->app->singleton('eventservice', function ($app) {
        return new EventService();
    });
       
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
