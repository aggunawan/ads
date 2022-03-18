<?php

namespace Tests\Unit\Resources;

use App\Http\Resources\Message;
use PHPUnit\Framework\TestCase;

/**
 * @testdox test to array
 */
class MessageTest extends TestCase
{
    public function toArrayProvider(): array
    {
        return [
            [['message' => 'foo'], 'foo'],
            [['message' => 'baz'], 'baz'],
            [['message' => 'fooBar'], 'fooBar'],
        ];
    }

    /**
     * @dataProvider toArrayProvider
     */
    public function testToArray(array $arr, string $message)
    {
        self::assertSame($arr, (new Message($message))->toArray(null));
    }
}
