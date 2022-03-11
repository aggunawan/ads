<?php

namespace App\DataSource;

use App\DataSource\Abstracts\BaseAccountDataSource;
use App\DataSource\Contracts\AccountDataSourceInterface;
use App\Exceptions\UnresolvedAdAccount;
use App\Objects\Account;
use App\Objects\Contracts\AccountInterface;

/**
 * @version 0.1
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

    private function getCompatibleDataSource(string $accountId): ?BaseAccountDataSource
    {
        foreach ($this->dataSources as $dataSource) {
            if ($dataSource instanceof BaseAccountDataSource) {
                if ($dataSource->isAccountCompatible($accountId)) return $dataSource;
            }
        }

        return null;
    }
}
