<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Protect all web routes except the login route
        Route::middleware(['web', 'auth'])
            ->group(function () {
                Route::get('/login', function () {
                    return view('login');
                })->withoutMiddleware('auth');
                
                require base_path('routes/web.php');
            });
    }
}
