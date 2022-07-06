<?php

declare(strict_types=1);

namespace App\Exception;

use App\ValueObject\StationId;
use DomainException;

use function sprintf;

final class StationNotFound extends DomainException
{
    public static function byId(StationId $id): self
    {
        return new self(sprintf('No station with ID %s', $id->asString()));
    }
}
