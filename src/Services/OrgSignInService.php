<?php

namespace Kundu\OrgSignIn\Services;

use Exception;
use Google\Client as GoogleClient;
use Google\Service\Oauth2 as GoogleOauth2;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Query\Builder;
use stdClass;

class OrgSignInService
{
    protected GoogleClient $client;

    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->client->setClientId(config('orgsignin.client_id'));
        $this->client->setClientSecret(config('orgsignin.client_secret'));
        $this->client->setRedirectUri(config('orgsignin.redirect_uri'));
        $this->client->addScope('email');
        $this->client->addScope('profile');
    }

    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    public function handleCallback(string $authCode): array
    {
        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($authCode);
            $this->client->setAccessToken($token);

            $oauth2 = new GoogleOauth2($this->client);
            $userInfo = $oauth2->userinfo->get();

            if (!$this->validateDomain($userInfo->email)) {
                throw new Exception('Invalid email domain');
            }

            $user = $this->findUser($userInfo->email);
            
            if (!$user) {
                throw new Exception('Email not registered in the system');
            }

            if (!$this->validateEmailVerification($user)) {
                throw new Exception('Email not verified');
            }

            Auth::login($user);

            return [
                'success' => true,
                'redirect' => config('orgsignin.redirect_route'),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    protected function validateDomain(string $email): bool
    {
        $domain = explode('@', $email)[1];
        return $domain === config('orgsignin.allowed_domain');
    }

    protected function validateEmailVerification(stdClass $user): bool
    {
        if (!config('orgsignin.check_verified')) {
            return true;
        }

        return !is_null($user->email_verified_at);
    }

    protected function findUser(string $email): ?stdClass
    {
        return DB::table(config('orgsignin.user_table'))
            ->where(config('orgsignin.email_column'), $email)
            ->first();
    }
} 