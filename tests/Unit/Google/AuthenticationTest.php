<?php

namespace Tests\Unit\Google;

use App\Google\Authentication;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClient;
use PHPUnit\Framework\TestCase;

class AuthenticationTest extends TestCase
{
    public function testGetClient()
    {
        $credentials = $this->createMock(Authentication\Credentials::class);
        $credentials->expects(self::once())->method('getClientId')->willReturn('foo');
        $credentials->expects(self::once())->method('getClientSecret')->willReturn('bar');
        $credentials->expects(self::once())->method('getRefreshToken')->willReturn('baz');
        $credentials->expects(self::once())->method('getDeveloperToken')->willReturn('fooBar');
        $credentials->expects(self::once())->method('getLoginCustomerId')->willReturn(1);

        $authenticate = new Authentication($credentials);
        self::assertInstanceOf(GoogleAdsClient::class, $authenticate->getClient());
    }
}
