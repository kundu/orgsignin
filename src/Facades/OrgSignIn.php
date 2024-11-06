<?php

namespace Kundu\OrgSignIn\Facades;

use Illuminate\Support\Facades\Facade;
use Kundu\OrgSignIn\OrgSignIn as OrgSignInClass;

/**
 * @method static string getAuthUrl()
 * @method static array handleCallback(string $authCode)
 * 
 * @see \Kundu\OrgSignIn\OrgSignIn
 */
class OrgSignIn extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'orgsignin';
    }
} 