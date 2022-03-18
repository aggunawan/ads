<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class AppTest extends TestCase
{
    public function testGet()
    {
        $this->get(route('api.index'))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'message' => 'Ads API v0'
                ]
            ]);
    }
}
