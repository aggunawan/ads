<?php

namespace App\DataSource\Abstracts;

use App\DataSource\Contracts\AccountDataSourceInterface;

abstract class BaseAccountDataSource implements AccountDataSourceInterface
{
    public function __construct(
        private readonly string $accessToken
    ) {}

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    abstract public function isAccountCompatible(string $id): bool;
}
