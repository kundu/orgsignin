<?php

namespace YourVendor\OrgSignIn\Facades;

use Illuminate\Support\Facades\Facade;
use YourVendor\OrgSignIn\OrgSignIn as OrgSignInClass;

/**
 * @method static string getAuthUrl()
 * @method static array handleCallback(string $authCode)
 * 
 * @see \YourVendor\OrgSignIn\OrgSignIn
 */
class OrgSignIn extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'orgsignin';
    }
} 