<?php

namespace App\DataSource;

use App\DataSource\Abstracts\BaseAccountDataSource;
use App\DataSource\Contracts\AccountDataSourceInterface;
use App\Exceptions\UnresolvedAdAccount;
use App\Facebook\AdAccountDataSource;
use App\Google\CustomerDataSource;
use App\Objects\Contracts\AccountInterface;
use App\TikTok\AdvertiserDataSource;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;

class AccountDataSource extends BaseAccountDataSource implements AccountDataSourceInterface
{
    private CustomerDataSource $customerDataSource;
    private AdAccountDataSource $facebookDataSource;
    private AdvertiserDataSource $tikTokDataSource;

    #[Pure]
    public function __construct(string $accessToken)
    {
        parent::__construct($accessToken);
        $this->customerDataSource = new CustomerDataSource($accessToken);
        $this->facebookDataSource = new AdAccountDataSource($accessToken);
        $this->tikTokDataSource = new AdvertiserDataSource($accessToken);
    }

    /**
     * @throws UnresolvedAdAccount
     */
    public function find(string $accountId): AccountInterface
    {
        if (Str::startsWith($accountId, 'customers/')) return $this->getCustomerDataSource()->find($accountId);
        if (Str::startsWith($accountId, 'act_')) return $this->getFacebookDataSource()->find($accountId);

        $account = $this->getTikTokDataSource()->find($accountId);
        if ($account instanceof AccountInterface) return $account;

        throw new UnresolvedAdAccount();
    }

    protected function getCustomerDataSource(): CustomerDataSource
    {
        return $this->customerDataSource;
    }

    protected function getFacebookDataSource(): AdAccountDataSource
    {
        return $this->facebookDataSource;
    }

    protected function getTikTokDataSource(): AdvertiserDataSource
    {
        return $this->tikTokDataSource;
    }
}
