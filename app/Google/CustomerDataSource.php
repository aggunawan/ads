<?php

namespace App\Google;

use App\DataSource\Abstracts\BaseAccountDataSource;
use App\Objects\Account;
use App\Objects\Contracts\AccountInterface;
use Google\Ads\GoogleAds\V10\Resources\Customer;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;

/**
 * Version 0.1
 */
class CustomerDataSource extends BaseAccountDataSource
{
    public function __construct(
        private readonly Authentication $authentication
    ) { }

    #[Pure]
    public function find(string $accountId): ?AccountInterface
    {
        return new Account($accountId, 'Foo');
    }

    #[Pure]
    public function isAccountCompatible(string $id): bool
    {
        return Str::startsWith($id, 'customers/');
    }
}
