<?php

namespace App\Objects;

use App\Objects\Contracts\AccountInterface;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'AdAccount',
    properties: [
        new Property(
            property: 'id',
            description: "Ad Account ID",
            type: 'string'
        ),
        new Property(
            property: 'name',
            description: "Ad Account Name",
            type: 'string'
        ),
    ]
)]
class Account implements AccountInterface
{
    public function __construct(
        private readonly string $id,
        private readonly ?string $name = null,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
