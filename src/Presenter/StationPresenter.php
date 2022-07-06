<?php

declare(strict_types=1);

namespace App\Presenter;

use App\Entity\Station;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class StationPresenter
{
    public function __construct(private UrlGeneratorInterface $router)
    {
    }

    /**
     * @return array<mixed>
     */
    public function transform(Station $station): array
    {
        return [
            'id'        => $station->getId()->asString(),
            'location'  => $station->getLocation(),
            'dashboard' => $this->router->generate(
                'station_dashboard',
                ['stationId' => $station->getId()->asString()],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
        ];
    }
}
