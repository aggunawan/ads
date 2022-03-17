<?php

namespace Tests\Unit\Facebook;

use App\Exceptions\UnresolvedAdAccount;
use App\Facebook\AdAccountDataSource;
use App\Facebook\Authentication;
use App\Objects\Contracts\AccountInterface;
use ArgumentCountError;
use FacebookAds\Api;
use FacebookAds\Http\Response;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * @testdox  "act_123" is compatible account id
 * @testdox "act123" is incompatible account id
 * @testdox "123_act" is incompatible account id
 * @testdox "123" is incompatible account id
 * @testdox "act" is incompatible account id
 * @testdox "" is incompatible account id
 *
 * @testdox find return Account Object
 * @testdox find thrown Unresolved Account
 *
 * @testdox construct without Facebook API Instance and don't pass Authentication
 * @testdox construct with Facebook API Instance and don't pass Authentication
 * @testdox construct without Facebook API Instance and pass Authentication
 * @testdox construct with Facebook API Instance and pass Authentication
 */
class AdAccountDataSourceTest extends TestCase
{
    static public ?AdAccountDataSource $adAccountDataSource;

    public function setUp(): void
    {
        self::$adAccountDataSource = new AdAccountDataSource($this->createMock(Authentication::class));
        parent::setUpBeforeClass();
    }

    public function tearDown(): void
    {
        self::$adAccountDataSource = null;
        $ref = new ReflectionProperty(Api::class, 'instance');
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        $ref->setValue(Api::instance(), null);
        parent::tearDownAfterClass();
    }

    /**
     * @throws UnresolvedAdAccount
     */
    public function testFind()
    {
        $res = $this->createMock(Response::class);
        $res->expects(self::once())->method('getContent')->willReturn(["id" => "act_123", "name" => "Foo"]);

        $api = $this->createMock(Api::class);
        $api->expects(self::once())->method('call')->willReturn($res);
        Api::setInstance($api);

        self::$adAccountDataSource = new AdAccountDataSource();
        $account = self::$adAccountDataSource->find('act_123');
        self::assertInstanceOf(AccountInterface::class, $account);
        self::assertSame("Foo", $account->getName());
    }

    public function testFindThrownUnresolvedAccount()
    {
        $this->expectException(UnresolvedAdAccount::class);
        self::assertInstanceOf(AccountInterface::class, self::$adAccountDataSource->find('act_123'));
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

    public function testConstructWithoutApiInstanceAndParam()
    {
        $this->expectException(ArgumentCountError::class);
        $this->expectExceptionMessage('Uninitialized Facebook API. Pass App\Facebook\Authentication object to constructor of App\Facebook\AdAccountDataSource');
        new AdAccountDataSource();
    }

    public function testConstructWithApiButDoNotPassParam()
    {
        self::assertNull(Api::instance());
        Api::setInstance($this->createMock(Api::class));
        self::assertInstanceOf(AdAccountDataSource::class, new AdAccountDataSource());
    }

    public function testConstructWithoutApiButPassParam()
    {
        self::assertNull(Api::instance());
        self::assertInstanceOf(AdAccountDataSource::class, new AdAccountDataSource(
            $this->createMock(Authentication::class)
        ));
    }

    public function testConstructWithApiAndPassParam()
    {
        self::assertNull(Api::instance());
        Api::setInstance($this->createMock(Api::class));
        self::assertInstanceOf(AdAccountDataSource::class, new AdAccountDataSource(
            $this->createMock(Authentication::class)
        ));
    }
}
