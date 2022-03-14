<?php

namespace App\Facebook;

use App\Facebook\Authentication\Credentials;
use FacebookAds\Api;

/**
 * Version 0.0
 */
class Authentication
{
    private Api $client;

    public function __construct(
        private readonly Credentials $credentials
    ) {
        $this->client = $this->init();
    }

    private function init(): Api
    {
        return Api::init(
            $this->credentials->getAppId(),
            $this->credentials->getAppSecret(),
            $this->credentials->getAccessToken()
        );
    }

    public function getClient(): Api
    {
        return $this->client;
    }
}
