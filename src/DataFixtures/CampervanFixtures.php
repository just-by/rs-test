<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Campervan;
use App\ValueObject\CampervanId;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class CampervanFixtures extends Fixture
{
    public const SURFER_SUITE = 'surfer_suite';
    public const BEACH_HOSTEL = 'beach_hostel';
    public const TRAVEL_HOME  = 'travel_home';
    public const CAMPER_CABIN = 'camper_cabin';

    public function load(ObjectManager $manager): void
    {
        foreach (self::getCampervanData() as [$id, $name]) {
            $van = new Campervan(CampervanId::fromString($id), $name);

            $manager->persist($van);
            $this->addReference($id, $van);
        }

        $manager->flush();
    }

    /**
     * @return array<array<string>>
     */
    public static function getCampervanData(): array
    {
        return [
            [self::SURFER_SUITE, 'Surfer Suite'],
            [self::BEACH_HOSTEL, 'Beach Hostel'],
            [self::TRAVEL_HOME, 'Travel Home'],
            [self::CAMPER_CABIN, 'Camper Cabin'],
        ];
    }
}
