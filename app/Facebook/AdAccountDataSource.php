<?php

namespace App\Facebook;

use App\DataSource\Abstracts\BaseAccountDataSource;
use App\Exceptions\UnresolvedAdAccount;
use App\Objects\Account;
use App\Objects\Contracts\AccountInterface;
use ArgumentCountError;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdAccountFields;
use Illuminate\Support\Str;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;

class AdAccountDataSource extends BaseAccountDataSource
{
    public function __construct(
        private readonly ?Authentication $authentication = null
    ) {
        if (is_null(Api::instance()) and is_null($this->authentication)) throw new ArgumentCountError(
            'Uninitialized Facebook API. Pass ' . Authentication::class . ' object to constructor of ' . __CLASS__
        );
    }

    public function find(string $accountId): ?AccountInterface
    {
        return new Account($accountId, 'Foo');
    }

    #[Pure]
    public function isAccountCompatible(string $id): bool
    {
        return Str::startsWith($id, 'act_');
    }
}
