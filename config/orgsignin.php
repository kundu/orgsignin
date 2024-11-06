<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google OAuth Configuration
    |--------------------------------------------------------------------------
    */
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect_uri' => env('APP_URL') . '/auth/google/callback',

    /*
    |--------------------------------------------------------------------------
    | Domain Configuration
    |--------------------------------------------------------------------------
    */
    'allowed_domain' => env('ORG_SIGNIN_ALLOWED_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Database Configuration
    |--------------------------------------------------------------------------
    */
    'user_table' => env('ORG_SIGNIN_USER_TABLE', 'users'),
    'email_column' => env('ORG_SIGNIN_EMAIL_COLUMN', 'email'),
    'check_verified' => env('ORG_SIGNIN_CHECK_VERIFIED', true),

    /*
    |--------------------------------------------------------------------------
    | Redirect Configuration
    |--------------------------------------------------------------------------
    */
    'redirect_route' => env('ORG_SIGNIN_REDIRECT_ROUTE', '/home'),

    /*
    |--------------------------------------------------------------------------
    | View Configuration
    |--------------------------------------------------------------------------
    */
    'views' => [
        'button' => 'orgsignin::components.signin-button',
        'error' => 'orgsignin::errors.domain-error',
    ],
]; 