<?php

namespace Tests\Unit\Google;

use App\Exceptions\UnresolvedAdAccount;
use App\Google\Authentication;
use App\Google\CustomerDataSource;
use App\Objects\Contracts\AccountInterface;
use Google\Ads\GoogleAds\V10\Resources\Customer;
use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;

class CustomerDataSourceTest extends TestCase
{
    static public ?CustomerDataSource $customerDataSource;
    static public ?Authentication $authentication;

    protected function setUp(): void
    {
        parent::setUp();
        self::$authentication = $this->createMock(Authentication::class);
        self::$customerDataSource = new CustomerDataSource(self::$authentication);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        self::$authentication = null;
        self::$customerDataSource = null;
    }

    /**
     * @throws ValidationException
     * @throws ApiException
     * @throws UnresolvedAdAccount
     */
    public function testFind()
    {
        $customer = $this->createMock(Customer::class);

        self::$customerDataSource = $this->createPartialMock(CustomerDataSource::class, ['getCustomer']);
        self::$customerDataSource->expects(self::once())->method('getCustomer')->willReturn($customer);

        $result = self::$customerDataSource->find('customers/123');

        self::assertInstanceOf(AccountInterface::class, $result);
    }

    /**
     * @throws ValidationException
     * @throws ApiException
     */
    public function testFindWithNullCustomer()
    {
        $this->expectException(UnresolvedAdAccount::class);

        self::$customerDataSource = $this->createPartialMock(CustomerDataSource::class, ['getCustomer']);
        self::$customerDataSource->expects(self::once())->method('getCustomer')->willReturn(null);

        $result = self::$customerDataSource->find('customers/123');

        self::assertNull($result);
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
        self::assertSame($condition, self::$customerDataSource->isAccountCompatible($accountId));
    }
}
