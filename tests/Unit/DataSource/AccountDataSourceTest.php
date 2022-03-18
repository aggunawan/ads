<?php

namespace Tests\Unit\DataSource;

use App\DataSource\Abstracts\BaseAccountDataSource;
use App\DataSource\AccountDataSource;
use App\Exceptions\AmbiguousAdAccountProvider;
use App\Exceptions\UnresolvedAdAccount;
use App\Objects\Account;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * @testdox isAccountCompatible always return false
 * @testdox add data source
 * @test find with 1 compatible data source
 * @test find with 0 compatible data source
 * @test find with 2 compatible data source thrown AmbiguousAdAccountProvider
 * @test find with > 2 compatible data source thrown AmbiguousAdAccountProvider
 */
class AccountDataSourceTest extends TestCase
{
    private ?AccountDataSource $dataSource;

    protected function setUp(): void
    {
        $this->dataSource = new AccountDataSource();
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
        } catch (UnresolvedAdAccount|AmbiguousAdAccountProvider) {
            $this->fail();
        }
    }

    #[ArrayShape([
        'null data source' => "array",
        'false return from data source' => "array"
    ])]
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
        try {
            $this->dataSource->find($accountId);
        } catch (AmbiguousAdAccountProvider) {
            $this->fail();
        }
    }

    public function multipleCompatibleDataSourceProvider(): array
    {
        return [
            [2],
            [3],
        ];
    }

    /**
     * @dataProvider multipleCompatibleDataSourceProvider
     */
    public function testMultipleCompatibleDataSource(int $dataSources)
    {
        $this->expectException(AmbiguousAdAccountProvider::class);
        $this->expectExceptionMessage('Multiple compatible data source provided.');

        for ($i = 0; $i < $dataSources; $i++) {
            $source = $this->createMock(BaseAccountDataSource::class);
            $source->expects($this->once())
                ->method('isAccountCompatible')
                ->with('foo')
                ->willReturn(true);
            $this->dataSource->setDataSources($source);
        }

        $prop = new ReflectionProperty(AccountDataSource::class, 'dataSources');
        self::assertCount($dataSources, $prop->getValue($this->dataSource));

        try {
            $this->dataSource->find('foo');
        } catch (UnresolvedAdAccount) {
            $this->fail();
        }
    }
}
