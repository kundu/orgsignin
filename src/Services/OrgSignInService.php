<?php

namespace Kundu\OrgSignIn\Services;

use Exception;
use Google\Client as GoogleClient;
use Google\Service\Oauth2 as GoogleOauth2;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class OrgSignInService
{
    protected GoogleClient $client;
    protected string $userModel;

    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->client->setClientId(config('orgsignin.client_id'));
        $this->client->setClientSecret(config('orgsignin.client_secret'));
        $this->client->setRedirectUri(config('orgsignin.redirect_uri'));
        $this->client->addScope('email');
        $this->client->addScope('profile');
        
        // Get the user model from config
        $this->userModel = config('orgsignin.user_model', \App\Models\User::class);
    }

    /**
     * Get the authentication URL
     * 
     * @return string
     */
    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    /**
     * Handle the callback from Google
     * 
     * @param string $authCode
     * @return array
     */
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

    /**
     * Validate the domain of the email
     * 
     * @param string $email
     * @return bool
     */
    protected function validateDomain(string $email): bool
    {
        $domain = explode('@', $email)[1];
        $allowedDomains = explode(',', config('orgsignin.allowed_domain'));
        return in_array($domain, $allowedDomains, true);
    }

    /**
     * Validate the email verification status of the user
     * 
     * @param Model $user
     * @return bool
     */
    protected function validateEmailVerification(Model $user): bool
    {
        if (!config('orgsignin.check_verified')) {
            return true;
        }

        return !is_null($user->email_verified_at);
    }

    /**
     * Find the user by email
     * 
     * @param string $email
     * @return Model|null
     */
    protected function findUser(string $email): ?Model
    {
        $emailColumn = config('orgsignin.email_column', 'email');
        return $this->userModel::where($emailColumn, $email)->first();
    }
} 