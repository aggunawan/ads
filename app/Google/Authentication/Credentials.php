<?php

namespace App\Google\Authentication;

class Credentials
{
    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $refreshToken,
        private readonly string $developerToken,
        private readonly int $loginCustomerId,
    ) { }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getDeveloperToken(): string
    {
        return $this->developerToken;
    }

    public function getLoginCustomerId(): int
    {
        return $this->loginCustomerId;
    }
}
