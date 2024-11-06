<?php

namespace YourVendor\OrgSignIn\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use YourVendor\OrgSignIn\Facades\OrgSignIn;

class OrgSignInController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Redirect::away(OrgSignIn::getAuthUrl());
    }

    public function callback(Request $request): RedirectResponse
    {
        if ($request->has('error')) {
            return Redirect::route('login')
                ->withErrors(['google' => 'Google authentication failed']);
        }

        $result = OrgSignIn::handleCallback($request->get('code'));

        if (!$result['success']) {
            return Redirect::route('login')
                ->withErrors(['google' => $result['message']]);
        }

        return Redirect::to($result['redirect']);
    }
} 