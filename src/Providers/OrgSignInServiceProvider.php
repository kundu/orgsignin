<?php

namespace Kundu\OrgSignIn\Providers;

use Illuminate\Support\ServiceProvider;
use Kundu\OrgSignIn\OrgSignIn;
use Kundu\OrgSignIn\Services\OrgSignInService;
use Illuminate\Support\Facades\Route;

class OrgSignInServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/orgsignin.php',
            'orgsignin'
        );

        $this->app->singleton('orgsignin', function ($app) {
            return new OrgSignIn(
                $app->make(OrgSignInService::class)
            );
        });
    }

    public function boot(): void
    {
        // Register routes
        $this->registerRoutes();

        // Register views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'orgsignin');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/orgsignin.php' => config_path('orgsignin.php'),
            ], 'orgsignin-config');

            $this->publishes([
                __DIR__ . '/../../resources/views' => resource_path('views/vendor/orgsignin'),
            ], 'orgsignin-views');
        }
    }

    protected function registerRoutes(): void
    {
        Route::group([
            'middleware' => ['web'],
            'namespace' => 'Kundu\OrgSignIn\Http\Controllers',
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        });
    }
} 