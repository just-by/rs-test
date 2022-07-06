<?php

declare(strict_types=1);

namespace App\Presenter;

use App\ValueObject\StationTimeline;

use function array_filter;

final class TimelinePresenter
{
    /**
     * @return array<mixed>
     */
    public function transform(StationTimeline $timeline): array
    {
        $data = $timeline->toArray();

        return array_filter($data, static fn (array $day) => count($day['booked']) > 0);
    }
}
