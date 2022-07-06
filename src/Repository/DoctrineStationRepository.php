<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Station;
use App\Exception\StationNotFound;
use App\ValueObject\StationId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrineStationRepository extends ServiceEntityRepository implements StationRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Station::class);
    }

    public function getById(StationId $id): Station
    {
        $station = $this->find($id->asString());
        if ($station === null) {
            throw StationNotFound::byId($id);
        }

        return $station;
    }

    /**
     * @return array<Station>
     */
    public function findAll(): array
    {
        return parent::findAll();
    }
}
