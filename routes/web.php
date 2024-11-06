<?php

use Illuminate\Support\Facades\Route;
use YourVendor\OrgSignIn\Http\Controllers\OrgSignInController;
use YourVendor\OrgSignIn\Http\Middleware\ValidateDomain;

Route::middleware(['web', ValidateDomain::class])->group(function () {
    Route::get('auth/google', [OrgSignInController::class, 'redirect'])
        ->name('orgsignin.redirect');
    Route::get('auth/google/callback', [OrgSignInController::class, 'callback'])
        ->name('orgsignin.callback');
}); 