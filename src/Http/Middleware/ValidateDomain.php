<?php

namespace YourVendor\OrgSignIn\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class ValidateDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $emailDomain = explode('@', $user->{Config::get('orgsignin.email_column')})[1];
            
            if ($emailDomain !== Config::get('orgsignin.allowed_domain')) {
                Auth::logout();
                return Redirect::route('login')
                    ->withErrors(['email' => 'Your email domain is not authorized.']);
            }
        }

        return $next($request);
    }
} 