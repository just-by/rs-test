<?php

declare(strict_types=1);

namespace App\ValueObject;

use function max;

final class AvailableExtras
{
    /** @var array<string, int> */
    private array $extras = [];

    public function aggregate(ExtraId $extraId, int $booked, int $returned): void
    {
        $id = $extraId->asString();
        if (! isset($this->extras[$id])) {
            $this->extras[$id] = 0;
        }

        $this->extras[$id] = $this->extras[$id] + $returned - $booked;
        $this->extras[$id] = max($this->extras[$id], 0);
    }

    public function getAvailable(ExtraId $extraId): int
    {
        return $this->extras[$extraId->asString()] ?? 0;
    }
}
