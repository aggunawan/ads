<?php

namespace Tests\Unit\Google;

use App\Google\CustomerDataSource;
use App\Objects\Contracts\AccountInterface;
use PHPUnit\Framework\TestCase;

class CustomerDataSourceTest extends TestCase
{
    static public ?CustomerDataSource $customerDataSource;

    public static function setUpBeforeClass(): void
    {
        self::$customerDataSource = new CustomerDataSource('bar');
        parent::setUpBeforeClass();
    }

    public static function tearDownAfterClass(): void
    {
        self::$customerDataSource = null;
        parent::tearDownAfterClass();
    }

    public function testFind()
    {
        self::assertInstanceOf(AccountInterface::class, self::$customerDataSource->find("foo"));
    }

    public function isAccountCompatibleDataProvider(): array
    {
        return [
            [true, 'customers/123'],
            [false, 'customer/123'],
            [false, '123/customers'],
            [false, '123/customer'],
            [false, 'customer'],
            [false, '123'],
            [false, ''],
        ];
    }

    /**
     * @dataProvider isAccountCompatibleDataProvider
     */
    public function testIsAccountCompatible(bool $condition, string $accountId)
    {
        self::assertSame($condition, self::$customerDataSource->isAccountCompatible($accountId));
    }
}
