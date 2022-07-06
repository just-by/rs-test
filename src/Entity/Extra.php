<?php

declare(strict_types=1);

namespace App\Entity;

use App\ValueObject\ExtraId;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Extra
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(name: 'extra_id', type: 'string', length: 32)]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    public function __construct(ExtraId $id, string $name)
    {
        $this->id   = $id->asString();
        $this->name = $name;
    }

    public function getId(): ExtraId
    {
        return ExtraId::fromString($this->id);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
