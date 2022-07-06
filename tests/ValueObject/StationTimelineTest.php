<?php

declare(strict_types=1);

namespace App\Tests\ValueObject;

use App\Dto\OrderHistoryDto;
use App\ValueObject\ExtraId;
use App\ValueObject\StationTimeline;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
final class StationTimelineTest extends TestCase
{
    private StationTimeline $timeline;

    public function setUp(): void
    {
        $this->timeline = new StationTimeline();
    }

    public function testTrackOneDay(): void
    {
        $extraId = ExtraId::fromString('extra-1');

        $date = new DateTimeImmutable('2022-07-01');
        $dto  = new OrderHistoryDto($date, $extraId, 2, 5);

        $this->timeline->track($date, $dto);
        $this->timeline->aggregateAvailable($dto);

        self::assertEquals(
            ['2022-07-01' => ['booked' => ['extra-1' => 2], 'available' => ['extra-1' => 5]]],
            $this->timeline->toArray()
        );
    }

    public function testTrackMultipleDays(): void
    {
        $extraId = ExtraId::fromString('extra-1');

        $date = new DateTimeImmutable('2022-07-01');
        $dto  = new OrderHistoryDto($date, $extraId, 1, 5);

        $this->timeline->track($date, $dto);
        $this->timeline->aggregateAvailable($dto);

        $date = new DateTimeImmutable('2022-07-02');
        $dto  = new OrderHistoryDto($date, $extraId, 2, 2);

        $this->timeline->track($date, $dto);

        self::assertEquals(
            [
                '2022-07-01' => ['booked' => ['extra-1' => 1], 'available' => ['extra-1' => 5]],
                '2022-07-02' => ['booked' => ['extra-1' => 2], 'available' => ['extra-1' => 6]],
            ],
            $this->timeline->toArray()
        );
    }
}
