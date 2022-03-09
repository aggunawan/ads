<?php

namespace Tests\Unit\TikTok;

use App\Objects\Contracts\AccountInterface;
use App\TikTok\AdvertiserDataSource;
use PHPUnit\Framework\TestCase;

class AdvertiserDataSourceTest extends TestCase
{
    public function testFind()
    {
        $tikTokDataSource = new AdvertiserDataSource('foo');
        self::assertInstanceOf(AccountInterface::class, $tikTokDataSource->find('foo'));
    }

    public function testFindNullResult()
    {
        $tikTokDataSource = new AdvertiserDataSource('foo');
        self::assertNull($tikTokDataSource->find('00'));
    }
}
