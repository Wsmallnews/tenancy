<?php

namespace App\Services;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;

/**
 * Custom Socialite SSO Provider
 * Implements OAuth 2.0 authorization code flow against NHGRC SSO service
 */
class SsoProvider extends AbstractProvider
{
    protected $scopes = ['openid', 'profile'];
    protected $scopeSeparator = ' ';

    protected function getBaseUrl(): string
    {
        return config('services.sso.base_url', 'http://10.0.0.40:8091');
    }

    protected function getAuthUrl($state): string
    {
        return $this->buildAuthUrlFromBase(
            $this->getBaseUrl() . '/oauth/authorize',
            $state
        );
    }

    protected function getTokenUrl(): string
    {
        return $this->getBaseUrl() . '/oauth/token';
    }

    protected function getUserByToken($token): array
    {
        $response = $this->getHttpClient()->get(
            $this->getBaseUrl() . '/oauth/userinfo',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]
        );

        return json_decode((string) $response->getBody(), true);
    }

    protected function mapUserToObject(array $user): User
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['sub'] ?? null,
            'name' => $user['username'] ?? null,
            'email' => $user['email'] ?? null,
        ]);
    }
}
