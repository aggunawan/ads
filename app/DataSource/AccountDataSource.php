<?php

namespace App\DataSource;

use App\DataSource\Abstracts\BaseAccountDataSource;
use App\DataSource\Contracts\AccountDataSourceInterface;
use App\Exceptions\AmbiguousAdAccountProvider;
use App\Exceptions\UnresolvedAdAccount;
use App\Objects\Account;
use App\Objects\Contracts\AccountInterface;

/**
 * @version 0.1.1
 */
class AccountDataSource extends BaseAccountDataSource implements AccountDataSourceInterface
{
    private array $dataSources = [];

    public function isAccountCompatible(string $id): bool
    {
        return false;
    }

    public function setDataSources(BaseAccountDataSource $dataSources): void
    {
        $this->dataSources[] = $dataSources;
    }

    /**
     * @throws UnresolvedAdAccount
     * @throws AmbiguousAdAccountProvider
     */
    public function find(string $accountId): AccountInterface
    {
        $dataSource = $this->getCompatibleDataSource($accountId);

        if ($dataSource instanceof BaseAccountDataSource) {
            $account = $dataSource->find($accountId);
            if ($account instanceof Account) return $account;
        }

        throw new UnresolvedAdAccount();
    }

    /**
     * @throws AmbiguousAdAccountProvider
     */
    private function getCompatibleDataSource(string $accountId): ?BaseAccountDataSource
    {
        $compatibleClasses = [];

        foreach ($this->dataSources as $dataSource) {
            if ($dataSource instanceof BaseAccountDataSource) {
                if ($dataSource->isAccountCompatible($accountId)) $compatibleClasses[] = $dataSource;
            }
        }

        return match (true) {
            count($compatibleClasses) >= 2 => throw new AmbiguousAdAccountProvider(
                "Multiple compatible data source provided."
            ),
            count($compatibleClasses) == 1 => $compatibleClasses[0],
            default => null
        };
    }
}
