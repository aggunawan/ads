<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: "Message",
    title: "Message",
    properties: [
        new Property(
            property: "message",
            type: "string"
        )
    ]
)]
class Message extends JsonResource
{
    #[Pure]
    public function __construct(
        private readonly string $message
    )
    {
        parent::__construct(null);
    }

    #[ArrayShape([
        'message' => "string"
    ])]
    public function toArray($request): array
    {
        return [
            'message' => $this->message
        ];
    }
}
