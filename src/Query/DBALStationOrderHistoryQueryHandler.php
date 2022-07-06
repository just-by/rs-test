<?php

declare(strict_types=1);

namespace App\Query;

use App\Dto\OrderHistoryDto;
use App\ValueObject\ExtraId;
use App\ValueObject\StationId;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;

/**
 * Selects all extras movements for specified station
 * Returns rows in format:
 * date | extra_id | booked | returned
 */
final class DBALStationOrderHistoryQueryHandler implements StationOrderHistoryQueryHandler
{
    public function __construct(private Connection $connection)
    {
    }

    public function findByStation(StationId $station): iterable
    {
        $sql =
            '
        SELECT 
            (CASE WHEN start_station_id = :stationId THEN start_date ELSE end_date END) AS order_date,
            extra_id,
            SUM(CASE WHEN start_station_id = :stationId THEN quantity ELSE 0 END) AS booked,
            SUM(CASE WHEN start_station_id = :stationId THEN 0 ELSE quantity END) AS returned
        FROM rental_order o
        JOIN rental_order_extra oe USING (order_id)
        WHERE start_station_id = :stationId OR end_station_id = :stationId
        GROUP BY order_date, extra_id
        ORDER BY order_date
        ';

        $stmt = $this->connection->executeQuery($sql, ['stationId' => $station->asString()]);
        foreach ($stmt->iterateAssociative() as $item) {
            $dto = new OrderHistoryDto(
                DateTimeImmutable::createFromFormat('!Y-m-d', $item['order_date']),
                ExtraId::fromString($item['extra_id']),
                (int) $item['booked'],
                (int) $item['returned']
            );

            yield $dto;
        }
    }
}
