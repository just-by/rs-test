<?php

declare(strict_types=1);

namespace App\Controller;

use App\Presenter\TimelinePresenter;
use App\Service\StationDemandTimelineService;
use App\ValueObject\StationId;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class StationDashboardController
{
    public function __construct(
        private StationDemandTimelineService $service,
        private TimelinePresenter $presenter
    ) {
    }

    #[Route('/station/{stationId}/dashboard', name: 'station_dashboard', methods: ['GET'])]
    public function __invoke(StationId $id): JsonResponse
    {
        $now = new DateTimeImmutable();

        $timeline = $this->service->timelineForStation($id, $now);

        return new JsonResponse(['timeline' => $this->presenter->transform($timeline)]);
    }
}
