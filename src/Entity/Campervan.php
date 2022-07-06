<?php

declare(strict_types=1);

namespace App\Entity;

use App\ValueObject\CampervanId;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Campervan
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(name: 'campervan_id', type: 'string', length: 32)]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    public function __construct(CampervanId $id, string $name)
    {
        $this->id   = $id->asString();
        $this->name = $name;
    }

    public function getId(): CampervanId
    {
        return CampervanId::fromString($this->id);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
