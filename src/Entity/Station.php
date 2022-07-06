<?php

declare(strict_types=1);

namespace App\Entity;

use App\ValueObject\StationId;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Station
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(name: 'station_id', type: 'string', length: 32)]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $location;

    public function __construct(StationId $id, string $location)
    {
        $this->id       = $id->asString();
        $this->location = $location;
    }

    public function getId(): StationId
    {
        return StationId::fromString($this->id);
    }

    public function getLocation(): string
    {
        return $this->location;
    }
}
