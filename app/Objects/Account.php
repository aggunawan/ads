<?php

namespace App\Objects;

use App\Objects\Contracts\AccountInterface;

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
