<?php

namespace App\Google;

use App\DataSource\Abstracts\BaseAccountDataSource;
use App\Objects\Account;
use App\Objects\Contracts\AccountInterface;
use JetBrains\PhpStorm\Pure;

class CustomerDataSource extends BaseAccountDataSource
{
    #[Pure]
    public function find(string $accountId): ?AccountInterface
    {
        return new Account($accountId, 'Foo');
    }
}
