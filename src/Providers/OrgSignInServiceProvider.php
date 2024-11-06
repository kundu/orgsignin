<?php

namespace Kundu\OrgSignIn\Providers;

use Illuminate\Support\ServiceProvider;
use Kundu\OrgSignIn\OrgSignIn;
use Kundu\OrgSignIn\Services\OrgSignInService;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Support\DeferrableProvider;

class OrgSignInServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/orgsignin.php',
            'orgsignin'
        );

        $this->app->singleton(OrgSignIn::class, function (Container $app) {
            return new OrgSignIn(
                $app->make(OrgSignInService::class)
            );
        });

        $this->app->alias(OrgSignIn::class, 'orgsignin');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/orgsignin.php' => config_path('orgsignin.php'),
            ], 'orgsignin-config');

            $this->publishes([
                __DIR__ . '/../../resources/views' => resource_path('views/vendor/orgsignin'),
            ], 'orgsignin-views');
        }

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'orgsignin');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }

    public function provides(): array
    {
        return [OrgSignIn::class, 'orgsignin'];
    }
} 