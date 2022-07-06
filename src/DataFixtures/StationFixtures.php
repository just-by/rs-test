<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Station;
use App\ValueObject\StationId;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use function sprintf;
use function strtolower;

final class StationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (self::getStationData() as $location) {
            for ($i = 1; $i <= 3; $i++) {
                $id      = sprintf('%s-%d', strtolower($location), $i);
                $station = new Station(StationId::fromString($id), $location);

                $manager->persist($station);
                $this->addReference($id, $station);
            }
        }

        $manager->flush();
    }

    /**
     * @return array<array<string>>
     */
    public static function getStationData(): array
    {
        return [
            'Munich',
            'Paris',
            'Madrid',
            'Porto',
        ];
    }
}
