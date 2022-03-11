<?php

namespace Tests\Unit\Facebook\Authentication;

use App\Facebook\Authentication\Credentials;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;

class CredentialsTest extends TestCase
{
    static private ?Credentials $credentials;

    public static function setUpBeforeClass(): void
    {
        self::$credentials = new Credentials('foo', 'bar', 'baz');
        parent::setUpBeforeClass();
    }

    public static function tearDownAfterClass(): void
    {
        self::$credentials = null;
        parent::tearDownAfterClass();
    }

    #[ArrayShape([
        'getAppId' => "string[]",
        'getAppSecret' => "string[]",
        'getAccessToken' => "string[]"
    ])]
    public function getterProvider(): array
    {
        return [
            'getAppId' => ['foo', 'getAppId'],
            'getAppSecret' => ['bar', 'getAppSecret'],
            'getAccessToken' => ['baz', 'getAccessToken'],
        ];
    }

    /**
     * @dataProvider getterProvider
     */
    public function testGetter(string $value, string $method)
    {
        self::assertSame($value, self::$credentials->{$method}());
    }
}
