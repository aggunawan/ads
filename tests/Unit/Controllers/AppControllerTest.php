<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Api\AppController;
use App\Http\Resources\Message;
use PHPUnit\Framework\TestCase;

/**
 * @testdox test index
 */
class AppControllerTest extends TestCase
{
    public function testIndex()
    {
        $message = (new AppController())->index();
        self::assertInstanceOf(Message::class, $message);
        self::assertSame('Ads API v0', $message->toArray(null)['message']);
    }
}
