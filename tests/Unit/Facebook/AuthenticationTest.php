<?php

namespace Tests\Unit\Facebook;

use App\Facebook\Authentication;
use FacebookAds\Api;
use PHPUnit\Framework\TestCase;

class AuthenticationTest extends TestCase
{
    public function testGetClient()
    {
        $cred = $this->createMock(Authentication\Credentials::class);
        $cred->expects(self::once())->method('getAppId')->willReturn('foo');
        $cred->expects(self::once())->method('getAppSecret')->willReturn('bar');
        $cred->expects(self::once())->method('getAccessToken')->willReturn('fooBar');

        $auth = new Authentication($cred);
        self::assertInstanceOf(Api::class, $auth->getClient());
    }
}
