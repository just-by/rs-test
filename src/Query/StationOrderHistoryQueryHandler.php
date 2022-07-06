<?php

declare(strict_types=1);

namespace App\Query;

use App\Dto\OrderHistoryDto;
use App\ValueObject\StationId;

interface StationOrderHistoryQueryHandler
{
    /**
     * @return iterable<OrderHistoryDto>
     */
    public function findByStation(StationId $station): iterable;
}
