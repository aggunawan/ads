<?php

namespace App\TikTok;

use App\DataSource\Abstracts\BaseAccountDataSource;
use App\Objects\Account;
use App\Objects\Contracts\AccountInterface;
use JetBrains\PhpStorm\Pure;

class AdvertiserDataSource extends BaseAccountDataSource
{
    #[Pure]
    public function find(string $accountId): ?AccountInterface
    {
        return $accountId == '00' ? null : new Account($accountId, 'Foo');
    }
}
