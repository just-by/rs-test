<?php

declare(strict_types=1);

namespace App\ValueObject;

final class StationId
{
    private function __construct(private readonly string $id)
    {
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public function asString(): string
    {
        return $this->id;
    }
}
