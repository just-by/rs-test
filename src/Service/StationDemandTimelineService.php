<?php

declare(strict_types=1);

namespace App\Service;

use App\Query\StationOrderHistoryQueryHandler;
use App\ValueObject\StationId;
use App\ValueObject\StationTimeline;
use DateTimeImmutable;

final class StationDemandTimelineService
{
    public function __construct(private StationOrderHistoryQueryHandler $handler)
    {
    }

    public function timelineForStation(StationId $stationId, DateTimeImmutable $fromDate): StationTimeline
    {
        $history = $this->handler->findByStation($stationId);

        $timeline = new StationTimeline();
        foreach ($history as $item) {
            if ($item->orderDate >= $fromDate) {
                $timeline->track($item->orderDate, $item);
            }

            $timeline->aggregateAvailable($item);
        }

        return $timeline;
    }
}
