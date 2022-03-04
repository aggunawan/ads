<?php

namespace App\Objects\Contracts;

interface AccountInterface
{
    public function getId(): string;
    public function getName(): ?string;
}
