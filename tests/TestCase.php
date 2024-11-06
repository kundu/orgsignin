<?php

namespace Kundu\OrgSignIn\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Kundu\OrgSignIn\Providers\OrgSignInServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->setUpDatabase();
    }

    protected function getPackageProviders($app): array
    {
        return [
            OrgSignInServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        Config::set('orgsignin.allowed_domain', 'example.com');
        Config::set('orgsignin.client_id', 'test-client-id');
        Config::set('orgsignin.client_secret', 'test-client-secret');
        Config::set('orgsignin.redirect_uri', 'http://localhost/auth/google/callback');
    }

    protected function setUpDatabase(): void
    {
        Schema::create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });
    }
} 