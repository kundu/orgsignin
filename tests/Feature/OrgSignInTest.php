<?php

namespace YourVendor\OrgSignIn\Tests\Feature;

use YourVendor\OrgSignIn\Tests\TestCase;
use YourVendor\OrgSignIn\Facades\OrgSignIn;
use Illuminate\Support\Facades\DB;
use Mockery;
use Google\Client as GoogleClient;
use Google\Service\Oauth2\Userinfo;

class OrgSignInTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        DB::table('users')->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_it_validates_domain(): void
    {
        $result = $this->handleGoogleCallback('invalid@wrongdomain.com');

        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid email domain', $result['message']);
    }

    public function test_it_validates_existing_user(): void
    {
        $result = $this->handleGoogleCallback('nonexistent@example.com');

        $this->assertFalse($result['success']);
        $this->assertEquals('Email not registered in the system', $result['message']);
    }

    public function test_it_validates_email_verification(): void
    {
        DB::table('users')->insert([
            'name' => 'Unverified User',
            'email' => 'unverified@example.com',
            'email_verified_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $result = $this->handleGoogleCallback('unverified@example.com');

        $this->assertFalse($result['success']);
        $this->assertEquals('Email not verified', $result['message']);
    }

    public function test_successful_authentication(): void
    {
        $result = $this->handleGoogleCallback('test@example.com');

        $this->assertTrue($result['success']);
        $this->assertEquals('/home', $result['redirect']);
    }

    protected function handleGoogleCallback(string $email): array
    {
        $userInfo = new Userinfo();
        $userInfo->setEmail($email);
        $userInfo->setName('Test User');

        $googleClient = Mockery::mock(GoogleClient::class);
        $googleClient->shouldReceive('fetchAccessTokenWithAuthCode')
            ->andReturn(['access_token' => 'test-token']);
        $googleClient->shouldReceive('setAccessToken')
            ->with(['access_token' => 'test-token']);

        $oauth2 = Mockery::mock('Google\Service\Oauth2');
        $oauth2->userinfo = Mockery::mock();
        $oauth2->userinfo->shouldReceive('get')
            ->andReturn($userInfo);

        app()->instance(GoogleClient::class, $googleClient);

        return OrgSignIn::handleCallback('test-auth-code');
    }
} 