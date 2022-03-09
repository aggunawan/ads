<?php

namespace Tests\Unit\DataSource;

use App\DataSource\AccountDataSource;
use App\Exceptions\UnresolvedAdAccount;
use App\Facebook\AdAccountDataSource;
use App\Google\CustomerDataSource;
use App\Objects\Account;
use App\TikTok\AdvertiserDataSource;
use Mockery;
use PHPUnit\Framework\TestCase;

class AccountDataSourceTest extends TestCase
{
    /**
     * @throws UnresolvedAdAccount
     */
    public function testFindGoogleCustomer()
    {
        $account = Mockery::mock(Account::class);

        $proxy = Mockery::mock(CustomerDataSource::class);
        $proxy->shouldReceive('find')->once()->andReturn($account);

        $dataSource = Mockery::mock(AccountDataSource::class)->makePartial();
        $dataSource->shouldAllowMockingProtectedMethods();
        $dataSource->shouldReceive('getCustomerDataSource')->once()->andReturn($proxy);
        $dataSource->shouldReceive('getFacebookDataSource')->never();
        $dataSource->shouldReceive('getTikTokDataSource')->never();

        if ($dataSource instanceof AccountDataSource) {
            self::assertSame($account, $dataSource->find('customers/foo'));
            Mockery::close();
        }
    }

    /**
     * @throws UnresolvedAdAccount
     */
    public function testFindFacebookAdAccount()
    {
        $account = Mockery::mock(Account::class);

        $proxy = Mockery::mock(AdAccountDataSource::class);
        $proxy->shouldReceive('find')->once()->andReturn($account);

        $dataSource = Mockery::mock(AccountDataSource::class)->makePartial();
        $dataSource->shouldAllowMockingProtectedMethods();
        $dataSource->shouldReceive('getCustomerDataSource')->never();
        $dataSource->shouldReceive('getFacebookDataSource')->once()->andReturn($proxy);
        $dataSource->shouldReceive('getTikTokDataSource')->never();

        if ($dataSource instanceof AccountDataSource) {
            self::assertSame($account, $dataSource->find('act_123'));
            Mockery::close();
        }
    }

    /**
     * @throws UnresolvedAdAccount
     */
    public function testFindWithTikTokAccount()
    {
        $account = Mockery::mock(Account::class);

        $proxy = Mockery::mock(AdvertiserDataSource::class);
        $proxy->shouldReceive('find')->once()->andReturn($account);

        $dataSource = Mockery::mock(AccountDataSource::class)->makePartial();
        $dataSource->shouldAllowMockingProtectedMethods();
        $dataSource->shouldReceive('getCustomerDataSource')->never();
        $dataSource->shouldReceive('getFacebookDataSource')->never();
        $dataSource->shouldReceive('getTikTokDataSource')->once()->andReturn($proxy);

        if ($dataSource instanceof AccountDataSource) {
            self::assertSame($account, $dataSource->find('123'));
            Mockery::close();
        }
    }

    public function testFindWithException()
    {
        $this->expectException(UnresolvedAdAccount::class);
        $account = Mockery::mock(Account::class);

        $proxy = Mockery::mock(AdvertiserDataSource::class);
        $proxy->shouldReceive('find')->once()->andReturnNull();

        $dataSource = Mockery::mock(AccountDataSource::class)->makePartial();
        $dataSource->shouldAllowMockingProtectedMethods();
        $dataSource->shouldReceive('getCustomerDataSource')->never();
        $dataSource->shouldReceive('getFacebookDataSource')->never();
        $dataSource->shouldReceive('getTikTokDataSource')->once()->andReturn($proxy);

        if ($dataSource instanceof AccountDataSource) {
            self::assertSame($account, $dataSource->find('123'));
            Mockery::close();
        }
    }
}
