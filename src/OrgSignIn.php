<?php

namespace Kundu\OrgSignIn;

use Kundu\OrgSignIn\Services\OrgSignInService;

class OrgSignIn
{
    public function __construct(
        protected OrgSignInService $service
    ) {}

    public function getAuthUrl(): string
    {
        return $this->service->getAuthUrl();
    }

    public function handleCallback(string $authCode): array
    {
        return $this->service->handleCallback($authCode);
    }
} 