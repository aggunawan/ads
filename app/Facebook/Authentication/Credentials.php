<?php

namespace App\Facebook\Authentication;

class Credentials
{
    public function __construct(
        private readonly string $appId,
        private readonly string $appSecret,
        private readonly string $accessToken,
    ) { }

    public function getAppId(): string
    {
        return $this->appId;
    }

    public function getAppSecret(): string
    {
        return $this->appSecret;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }
}
