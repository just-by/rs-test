<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Station;
use App\Presenter\StationPresenter;
use App\Repository\StationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use function array_map;

/**
 * For demonstration purposes: list all stations with dashboard links
 */
final class HomeController
{
    public function __construct(
        private StationRepository $repository,
        private StationPresenter $presenter
    ) {
    }

    #[Route('/', name: 'station_list', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $stations = $this->repository->findAll();
        $result   = array_map(fn (Station $station) => $this->presenter->transform($station), $stations);

        return new JsonResponse(['stations' => $result]);
    }
}
