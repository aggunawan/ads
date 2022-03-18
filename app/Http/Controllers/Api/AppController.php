<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Message;
use OpenApi\Attributes\Examples;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

class AppController extends Controller
{
    #[Get(
        path: "/api",
        summary: "Get version of API.",
        tags: ["App"],
        responses: [
            new Response(
                response: 200,
                description: "The version of API.",
                content: new JsonContent(
                    examples: [
                        new Examples(
                            example: "response",
                            summary: "Default",
                            value: [
                                "data" => [
                                    "message" => "Ads API v0"
                                ]
                            ]
                        )
                    ],
                    properties: [
                        new Property(
                            property: "data",
                            ref: "#/components/schemas/Message",
                        )
                    ]
                ),
            )
        ]
    )]
    public function index(): Message
    {
        return new Message('Ads API v0');
    }
}
