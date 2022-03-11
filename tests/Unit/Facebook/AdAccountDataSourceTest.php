<?php

namespace Tests\Unit\Facebook;

use App\Facebook\AdAccountDataSource;
use App\Objects\Contracts\AccountInterface;
use PHPUnit\Framework\TestCase;

class AdAccountDataSourceTest extends TestCase
{
    static public ?AdAccountDataSource $adAccountDataSource;

    public static function setUpBeforeClass(): void
    {
        self::$adAccountDataSource = new AdAccountDataSource('bar');
        parent::setUpBeforeClass();
    }

    public static function tearDownAfterClass(): void
    {
        self::$adAccountDataSource = null;
        parent::tearDownAfterClass();
    }

    public function testFind()
    {
        self::assertInstanceOf(AccountInterface::class, self::$adAccountDataSource->find('foo'));
    }

    public function isAccountCompatibleDataProvider(): array
    {
        return [
            [true, 'act_123'],
            [false, 'act123'],
            [false, '123_act'],
            [false, '123act'],
            [false, '123'],
            [false, 'act'],
            [false, ''],
        ];
    }

    /**
     * @dataProvider isAccountCompatibleDataProvider
     */
    public function testIsAccountCompatible(bool $condition, string $accountId)
    {
        self::assertSame($condition, self::$adAccountDataSource->isAccountCompatible($accountId));
    }
}
