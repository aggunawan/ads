<?php

namespace Tests\Unit\Objects;

use App\Objects\Account;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    public function testGetName()
    {
        self::assertSame('Foo', (new Account('1', 'Foo'))->getName());
        self::assertSame(null, (new Account('1'))->getName());
    }

    public function testGetId()
    {
        self::assertSame('foo', (new Account('foo'))->getId());
    }
}
