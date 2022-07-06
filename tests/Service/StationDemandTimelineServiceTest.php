<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Dto\OrderHistoryDto;
use App\Query\StationOrderHistoryQueryHandler;
use App\Service\StationDemandTimelineService;
use App\ValueObject\ExtraId;
use App\ValueObject\StationId;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function array_map;

/**
 * @group unit
 */
final class StationDemandTimelineServiceTest extends TestCase
{
    private StationDemandTimelineService $service;

    private MockObject $handler;

    public function setUp(): void
    {
        $this->handler = $this->createMock(StationOrderHistoryQueryHandler::class);

        $this->service = new StationDemandTimelineService($this->handler);
    }

    public function testTimelineWithoutData(): void
    {
        $this->handler->method('findByStation')
            ->willReturn([]);

        $date = DateTimeImmutable::createFromFormat('!Y-m-d', '2022-07-01');

        $timeline = $this->service->timelineForStation(StationId::fromString('test'), $date);

        self::assertEmpty($timeline->toArray());
    }

    public function testTimeline(): void
    {
        $this->handler->method('findByStation')
            ->willReturn(self::getOrderHistory());

        $date = DateTimeImmutable::createFromFormat('!Y-m-d', '2022-07-03');

        $timeline = $this->service->timelineForStation(StationId::fromString('test'), $date);

        self::assertEquals(
            [
                '2022-07-03' => ['booked' => ['extra-2' => 2, 'extra-3' => 2], 'available' => ['extra-2' => 1, 'extra-3' => 4]],
                '2022-07-04' => ['booked' => ['extra-1' => 1], 'available' => ['extra-1' => 2]],
                '2022-07-06' => ['booked' => [], 'available' => []],
            ],
            $timeline->toArray()
        );
    }

    /**
     * @return array<OrderHistoryDto>
     */
    private static function getOrderHistory(): array
    {
        $data = [
            ['2022-07-01', 'extra-2', 2, 3],
            ['2022-07-01', 'extra-1', 2, 0],
            ['2022-07-02', 'extra-3', 0, 4],
            ['2022-07-02', 'extra-1', 3, 0],
            ['2022-07-03', 'extra-3', 2, 0],
            ['2022-07-03', 'extra-2', 2, 0],
            ['2022-07-04', 'extra-2', 0, 2],
            ['2022-07-04', 'extra-1', 1, 2],
            ['2022-07-06', 'extra-2', 0, 2],
        ];

        return array_map(
            static fn ($row) => new OrderHistoryDto(
                DateTimeImmutable::createFromFormat('!Y-m-d', $row[0]),
                ExtraId::fromString($row[1]),
                $row[2],
                $row[3]
            ),
            $data
        );
    }
}
