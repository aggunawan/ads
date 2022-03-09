<?php

namespace Tests\Unit\Google;

use App\Google\CustomerDataSource;
use App\Objects\Contracts\AccountInterface;
use PHPUnit\Framework\TestCase;

class CustomerDataSourceTest extends TestCase
{
    public function testFind()
    {
        $customerDataSource = new CustomerDataSource('bar');
        self::assertInstanceOf(AccountInterface::class, $customerDataSource->find("foo"));
    }
}
