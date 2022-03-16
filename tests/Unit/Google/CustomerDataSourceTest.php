<?php

namespace Tests\Unit\Google;

use App\Google\Authentication;
use App\Google\CustomerDataSource;
use App\Objects\Contracts\AccountInterface;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;

class CustomerDataSourceTest extends TestCase
{
    static public ?CustomerDataSource $customerDataSource;

    public function testFind()
    {
        self::assertInstanceOf(AccountInterface::class, self::$customerDataSource->find("foo"));
    }

    #[ArrayShape([
        'customers/123' => "array",
        'customer/123' => "array",
        '123/customers' => "array",
        '123/customer' => "array",
        'customer' => "array",
        'string int' => "array",
        'empty string' => "array"
    ])]
    public function isAccountCompatibleDataProvider(): array
    {
        return [
            'customers/123' => [true, 'customers/123'],
            'customer/123' => [false, 'customer/123'],
            '123/customers' => [false, '123/customers'],
            '123/customer' => [false, '123/customer'],
            'customer' => [false, 'customer'],
            'string int' => [false, '123'],
            'empty string' => [false, ''],
        ];
    }

    /**
     * @dataProvider isAccountCompatibleDataProvider
     */
    public function testIsAccountCompatible(bool $condition, string $accountId)
    {
        self::$customerDataSource = new CustomerDataSource(
            $this->createMock(Authentication::class)
        );

        self::assertSame($condition, self::$customerDataSource->isAccountCompatible($accountId));
    }
}
