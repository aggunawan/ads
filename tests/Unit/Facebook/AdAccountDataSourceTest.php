<?php

namespace Tests\Unit\Facebook;

use App\Facebook\AdAccountDataSource;
use App\Objects\Contracts\AccountInterface;
use JetBrains\PhpStorm\ArrayShape;
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

    #[ArrayShape([
        'act_123' => "array",
        'act123' => "array",
        '123_act' => "array",
        '123act' => "array",
        'string int' => "array",
        'act' => "array",
        'empty string' => "array"
    ])]
    public function isAccountCompatibleDataProvider(): array
    {
        return [
            'act_123' => [true, 'act_123'],
            'act123' => [false, 'act123'],
            '123_act' => [false, '123_act'],
            '123act' => [false, '123act'],
            'string int' => [false, '123'],
            'act' => [false, 'act'],
            'empty string' => [false, ''],
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
