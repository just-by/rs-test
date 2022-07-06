<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Extra;
use App\ValueObject\ExtraId;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class ExtraFixtures extends Fixture
{
    public const TOILET = 'portable_toilet';
    public const SHEET  = 'bed_sheet';
    public const BAG    = 'sleeping_bag';
    public const TABLE  = 'table';
    public const CHAIR  = 'chair';

    public function load(ObjectManager $manager): void
    {
        foreach (self::getExtrasData() as [$id, $name]) {
            $extra = new Extra(ExtraId::fromString($id), $name);

            $manager->persist($extra);
            $this->addReference($id, $extra);
        }

        $manager->flush();
    }

    /**
     * @return array<array<string>>
     */
    public static function getExtrasData(): array
    {
        return [
            [self::TOILET, 'Portable toilet'],
            [self::SHEET, 'Bed sheet'],
            [self::BAG, 'Sleeping bag'],
            [self::TABLE, 'Camping table'],
            [self::CHAIR, 'Camping chair'],
        ];
    }
}
