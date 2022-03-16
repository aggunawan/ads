<?php

namespace App\Google;

use App\DataSource\Abstracts\BaseAccountDataSource;
use App\Exceptions\UnresolvedAdAccount;
use App\Objects\Account;
use App\Objects\Contracts\AccountInterface;
use Google\Ads\GoogleAds\V10\Resources\Customer;
use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;

/**
 * Version 0.1.1
 */
class CustomerDataSource extends BaseAccountDataSource
{
    public function __construct(
        private readonly Authentication $authentication
    ) { }

    /**
     * @throws ValidationException
     * @throws ApiException
     * @throws UnresolvedAdAccount
     */
    public function find(string $accountId): AccountInterface
    {
        $customer = $this->getCustomer($accountId);

        return ($customer instanceof Customer) ?
            new Account($accountId, $customer->getDescriptiveName()) :
            throw new UnresolvedAdAccount();
    }

    #[Pure]
    public function isAccountCompatible(string $id): bool
    {
        return Str::startsWith($id, 'customers/');
    }

    /**
     * @throws ApiException
     * @throws ValidationException
     */
    protected function getCustomer(string $accountId): ?Customer
    {
        $api = $this->authentication->getClient()->getGoogleAdsServiceClient();
        /**
         * @noinspection SqlDialectInspection
         * @noinspection SqlNoDataSourceInspection
         */
        return $api->search(
            $this->authentication->getCredentials()->getLoginCustomerId(),
            "SELECT customer.descriptive_name FROM customer WHERE customer.resource_name = '$accountId' LIMIT 1"
        )->getIterator()->current()->getCustomer();
    }
}
