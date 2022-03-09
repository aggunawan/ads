<?php

namespace Tests\Unit\Facebook;

use App\Facebook\AdAccountDataSource;
use App\Objects\Contracts\AccountInterface;
use PHPUnit\Framework\TestCase;

class AdAccountDataSourceTest extends TestCase
{
    public function testFind()
    {
        $accountDataSource = new AdAccountDataSource('foo');
        self::assertInstanceOf(AccountInterface::class, $accountDataSource->find('foo'));
    }
}
