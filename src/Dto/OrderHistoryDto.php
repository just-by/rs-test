<?php

declare(strict_types=1);

namespace App\Dto;

use App\ValueObject\ExtraId;
use DateTimeImmutable;

final class OrderHistoryDto
{
    public function __construct(
        public readonly DateTimeImmutable $orderDate,
        public readonly ExtraId $extraId,
        public readonly int $booked,
        public readonly int $returned
    ) {
    }
}