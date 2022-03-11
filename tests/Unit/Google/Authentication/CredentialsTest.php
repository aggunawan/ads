<?php

namespace Tests\Unit\Google\Authentication;

use App\Google\Authentication\Credentials;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;

class CredentialsTest extends TestCase
{
    static public ?Credentials $credentials;

    public static function setUpBeforeClass(): void
    {
        self::$credentials = new Credentials(
            'foo','bar','baz','fooBar', 1
        );
        parent::setUpBeforeClass();
    }

    public static function tearDownAfterClass(): void
    {
        self::$credentials = null;
        parent::tearDownAfterClass();
    }

    #[ArrayShape([
        'client id' => "string[]",
        'client secret' => "string[]",
        'refresh token' => "string[]",
        'developer token' => "string[]",
        'login customer id' => "array"
    ])]
    public function getterProvider(): array
    {
        return [
          'client id' => ['foo', 'getClientId'],
          'client secret' => ['bar', 'getClientSecret'],
          'refresh token' =>['baz', 'getRefreshToken'],
          'developer token' =>['fooBar', 'getDeveloperToken'],
          'login customer id' =>[1, 'getLoginCustomerId'],
        ];
    }

    /**
     * @dataProvider getterProvider
     */
    public function testGetter($value, string $method)
    {
        self::assertSame($value, self::$credentials->{$method}());
    }
}
