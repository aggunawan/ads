<?php

namespace App\Google;

use App\Google\Authentication\Credentials;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClientBuilder;
use Google\Auth\Credentials\UserRefreshCredentials;

/**
 * @version 0.1.1
 */
class Authentication
{
    private GoogleAdsClient $client;

    public function __construct(
        private readonly Credentials $credentials
    )
    {
        $this->client = $this->buildClient();
    }

    public function getClient(): GoogleAdsClient
    {
        return $this->client;
    }

    public function getCredentials(): Credentials
    {
        return $this->credentials;
    }

    private function getOauthToken(): UserRefreshCredentials
    {
        return (new OAuth2TokenBuilder())
            ->withClientId($this->credentials->getClientId())
            ->withClientSecret($this->credentials->getClientSecret())
            ->withRefreshToken($this->credentials->getRefreshToken())
            ->build();
    }

    private function buildClient(): GoogleAdsClient
    {
        return (new GoogleAdsClientBuilder())
            ->withOAuth2Credential($this->getOauthToken())
            ->withDeveloperToken($this->credentials->getDeveloperToken())
            ->withLoginCustomerId($this->credentials->getLoginCustomerId())
            ->build();
    }
}
