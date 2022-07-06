<?php

declare(strict_types=1);

namespace App\Entity;

use App\ValueObject\ExtraId;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'rental_order_extra')]
#[ORM\Entity]
class OrderExtra
{
    #[ORM\Id]
    #[ORM\Column(name: 'order_id', type: 'string', length: 64)]
    private string $orderId;

    #[ORM\ManyToOne(targetEntity: Order::class)]
    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'order_id', nullable: false)]
    private Order $order;

    #[ORM\Id]
    #[ORM\Column(name: 'extra_id', type: 'string', length: 32)]
    private string $extraId;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    public function __construct(
        Order $order,
        ExtraId $extraId,
        int $quantity,
    ) {
        $this->order    = $order;
        $this->orderId  = $order->getId();
        $this->extraId  = $extraId->asString();
        $this->quantity = $quantity;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getExtraId(): ExtraId
    {
        return ExtraId::fromString($this->extraId);
    }
}
