<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Station;
use App\Exception\StationNotFound;
use App\ValueObject\StationId;

interface StationRepository
{
    /**
     * @throws StationNotFound
     */
    public function getById(StationId $id): Station;

    /**
     * @return array<Station>
     */
    public function findAll(): array;
}
