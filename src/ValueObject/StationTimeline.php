<?php

declare(strict_types=1);

namespace App\ValueObject;

use App\Dto\OrderHistoryDto;
use DateTimeImmutable;

final class StationTimeline
{
    /** @var array<string, array<string, mixed>> */
    private array $timeline = [];

    private AvailableExtras $availableExtras;

    public function __construct()
    {
        $this->availableExtras = new AvailableExtras();
    }

    public function aggregateAvailable(OrderHistoryDto $historyDto): void
    {
        $this->availableExtras->aggregate($historyDto->extraId, $historyDto->booked, $historyDto->returned);
    }

    public function track(
        DateTimeImmutable $date,
        OrderHistoryDto $historyDto
    ): void {
        $dateKey = $this->key($date);
        $this->initForDate($dateKey);

        $this->booked($dateKey, $historyDto);
    }

    private function key(DateTimeImmutable $date): string
    {
        return $date->format('Y-m-d');
    }

    private function initForDate(string $dateKey): void
    {
        if (isset($this->timeline[$dateKey])) {
            return;
        }

        $this->timeline[$dateKey] = [
            'booked'    => [],
            'available' => [],
        ];
    }

    private function booked(string $dateKey, OrderHistoryDto $historyDto): void
    {
        if ($historyDto->booked === 0) {
            return;
        }

        $id = $historyDto->extraId->asString();
        $this->timeline[$dateKey]['booked'][$id] = $historyDto->booked;

        $available = $this->availableExtras->getAvailable($historyDto->extraId);
        $this->timeline[$dateKey]['available'][$id] = $available + $historyDto->returned;
    }

    public function toArray(): array
    {
        return $this->timeline;
    }
}
