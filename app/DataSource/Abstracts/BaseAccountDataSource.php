<?php

namespace App\DataSource\Abstracts;

use App\DataSource\Contracts\AccountDataSourceInterface;

/**
 * Version 0.1
 */
abstract class BaseAccountDataSource implements AccountDataSourceInterface
{
    abstract public function isAccountCompatible(string $id): bool;
}
