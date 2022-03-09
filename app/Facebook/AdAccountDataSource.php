<?php

namespace App\Facebook;

use App\DataSource\Abstracts\BaseAccountDataSource;
use App\Objects\Account;
use App\Objects\Contracts\AccountInterface;
use JetBrains\PhpStorm\Pure;

class AdAccountDataSource extends BaseAccountDataSource
{
    #[Pure]
    public function find(string $accountId): ?AccountInterface
    {
        return new Account($accountId, 'Foo');
    }
}
