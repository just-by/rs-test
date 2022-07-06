<?php

declare(strict_types=1);

namespace App\Entity;

use App\ValueObject\CampervanId;
use App\ValueObject\OrderId;
use App\ValueObject\StationId;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'rental_order')]
#[ORM\Entity]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(name: 'order_id', type: 'string', length: 64)]
    private string $id;

    #[ORM\Column(type: 'date_immutable')]
    private DateTimeImmutable $startDate;

    #[ORM\Column(type: 'date_immutable')]
    private DateTimeImmutable $endDate;

    #[ORM\Column(name: 'start_station_id', type: 'string', length: 32)]
    private string $startStationId;

    #[ORM\Column(name: 'end_station_id', type: 'string', length: 32)]
    private string $endStationId;

    #[ORM\Column(name: 'campervan_id', type: 'string', length: 32)]
    private string $campervanId;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderExtra::class, cascade: ['persist'])]
    private Collection $extras;

    public function __construct(
        OrderId $id,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate,
        StationId $startStation,
        StationId $endStation,
        CampervanId $campervan
    ) {
        $this->id             = $id->asString();
        $this->startDate      = $startDate;
        $this->endDate        = $endDate;
        $this->startStationId = $startStation->asString();
        $this->endStationId   = $endStation->asString();
        $this->campervanId    = $campervan->asString();

        $this->extras = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

    public function getStartStationId(): StationId
    {
        return StationId::fromString($this->startStationId);
    }

    public function getEndStationId(): StationId
    {
        return StationId::fromString($this->endStationId);
    }

    public function getCampervanId(): CampervanId
    {
        return CampervanId::fromString($this->campervanId);
    }

    public function addExtra(OrderExtra $extra): void
    {
        $this->extras->add($extra);
    }

    /**
     * @return array<OrderExtra>
     */
    public function getExtras(): array
    {
        return $this->extras->toArray();
    }
}
