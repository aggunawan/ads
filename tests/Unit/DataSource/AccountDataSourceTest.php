<?php

namespace Tests\Unit\DataSource;

use App\DataSource\Abstracts\BaseAccountDataSource;
use App\DataSource\AccountDataSource;
use App\Exceptions\UnresolvedAdAccount;
use App\Objects\Account;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class AccountDataSourceTest extends TestCase
{
    private ?AccountDataSource $dataSource;

    protected function setUp(): void
    {
        $this->dataSource = new AccountDataSource('foo');
        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->dataSource = null;
        parent::tearDown();
    }

    public function testIsAccountCompatible()
    {
        self::assertFalse($this->dataSource->isAccountCompatible('foo'));
    }

    public function testSetDataSources()
    {
        $props = new ReflectionProperty(AccountDataSource::class, 'dataSources');

        self::assertEmpty($props->getValue($this->dataSource));

        $source = $this->createMock(BaseAccountDataSource::class);
        $this->dataSource->setDataSources($source);

        self::assertCount(1, $props->getValue($this->dataSource));
        self::assertSame($source, $props->getValue($this->dataSource)[0]);
    }

    public function testFind()
    {
        $source = $this->createMock(BaseAccountDataSource::class);
        $source->expects($this->once())
            ->method('isAccountCompatible')
            ->with('foo')
            ->willReturn(true);

        $account = $this->createStub(Account::class);
        $source->expects($this->once())
            ->method('find')
            ->with('foo')
            ->willReturn($account);

        $this->dataSource->setDataSources($source);
        try {
            self::assertSame($account, $this->dataSource->find('foo'));
        } catch (UnresolvedAdAccount) {
            $this->fail();
        }
    }

    public function findRaiseUnresolvedAdAccountProvider(): array
    {
        $mock = $this->createMock(BaseAccountDataSource::class);
        $mock->expects($this->once())
            ->method('isAccountCompatible')
            ->with('foo')
            ->willReturn(false);

        return [
            'null data source' => [null, 'foo'],
            'false return from data source' => [$mock, 'foo'],
        ];
    }

    /**
     * @dataProvider findRaiseUnresolvedAdAccountProvider
     */
    public function testFindRaiseUnresolvedAdAccount(?BaseAccountDataSource $dataSource, string $accountId)
    {
        $this->expectException(UnresolvedAdAccount::class);

        if ($dataSource) $this->dataSource->setDataSources($dataSource);
        $this->dataSource->find($accountId);
    }
}
