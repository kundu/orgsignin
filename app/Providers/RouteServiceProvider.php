<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix(config('jetstream.path', 'jet-stream'))
                ->middleware(config('jetstream.middleware', ['web']))
                ->group(base_path('vendor/kundu/orgsignin/routes/web.php'));
        });
    }

    protected function configureRateLimiting(): void
    {
        // Configure the rate limiters for the application.
    }
} 