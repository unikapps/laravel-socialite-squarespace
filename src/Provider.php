<?php

namespace Unikapps\LaravelSocialiteSquarespace;

use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

/**
 * Socialite provider for Squarespace
 *
 * @see https://developers.squarespace.com/oauth
 */
class Provider extends AbstractProvider
{
    public const IDENTIFIER = 'SQUARESPACE';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state): string
    {
        return $this->buildAuthUrlFromBase(
            'https://login.squarespace.com/api/1/login/oauth/provider/authorize',
            $state
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl(): string
    {
        return 'https://login.squarespace.com/api/1/login/oauth/provider/tokens';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://api.squarespace.com/1.0/authorization/website', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'User-Agent' => 'Shown.io'
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user): User
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['id'],
            'name' => $user['title'],
            'email' => $user['siteId'] . '@squarespace.com', // Api does not return email
            'url' => $user['url'],
        ]);
    }

    /**
     * @param $code
     * @return string[]
     */
    protected function getTokenHeaders($code): array
    {
        return [
            'Authorization' => 'Basic ' . base64_encode(sprintf('%s:%s', $this->clientId, $this->clientSecret)),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];
    }

    /**
     * @param $state
     * @return array<string>
     */
    protected function getCodeFields($state = null): array
    {
        $fields = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
            'scope' => $this->formatScopes($this->getScopes(), $this->scopeSeparator),
            'access_type' => 'offline',
        ];

        if ($this->usesState()) {
            $fields['state'] = $state;
        }

        return $fields;
    }

    /**
     * @param $code
     * @return array<string>
     */
    protected function getTokenFields($code): array
    {
        return [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirectUrl
        ];
    }
}
