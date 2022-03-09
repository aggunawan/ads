<?php

namespace App\DataSource\Contracts;

use App\Objects\Contracts\AccountInterface;

interface AccountDataSourceInterface
{
    public function find(string $accountId): ?AccountInterface;
}
