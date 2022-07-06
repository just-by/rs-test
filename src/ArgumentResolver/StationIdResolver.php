<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use App\Exception\StationNotFound;
use App\Repository\StationRepository;
use App\ValueObject\StationId;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class StationIdResolver implements ArgumentValueResolverInterface
{
    public function __construct(private StationRepository $repository)
    {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === StationId::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $value     = (string) $request->attributes->get('stationId');
        $stationId = StationId::fromString($value);

        $this->ensureStationExists($stationId);

        yield $stationId;
    }

    private function ensureStationExists(StationId $stationId): void
    {
        try {
            $this->repository->getById($stationId);
        } catch (StationNotFound $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }
}