<?php

use Illuminate\Support\Facades\Route;
use Kundu\OrgSignIn\Http\Controllers\OrgSignInController;
use Kundu\OrgSignIn\Http\Middleware\ValidateDomain;

Route::middleware(['web'])->group(function () {
    Route::get('auth/google', [OrgSignInController::class, 'redirect'])
        ->name('orgsignin.redirect');
    Route::get('auth/google/callback', [OrgSignInController::class, 'callback'])
        ->name('orgsignin.callback');
});

Route::middleware(['auth', ValidateDomain::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
    // Other protected routes...
}); 