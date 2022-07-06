<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderExtra;
use App\ValueObject\CampervanId;
use App\ValueObject\ExtraId;
use App\ValueObject\OrderId;
use App\ValueObject\StationId;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use PascalDeVink\ShortUuid\ShortUuid;

use function array_rand;
use function random_int;
use function shuffle;
use function sprintf;
use function strtolower;

final class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @return array<string>
     */
    public function getDependencies(): array
    {
        return [
            CampervanFixtures::class,
            StationFixtures::class,
            ExtraFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i <= 200; $i++) {
            $order = $this->generateOrder();

            $manager->persist($order);
            $this->addReference("$i-order", $order);
        }

        $manager->flush();
    }

    private function generateOrder(): Order
    {
        [$startDate, $endDate] = $this->getRandomPeriod();

        $startStation = $this->getRandomStation();
        $endStation   = $this->getRandomStation();
        $van          = $this->getRandomVan();

        $order = new Order(
            OrderId::fromString(ShortUuid::uuid4()),
            $startDate,
            $endDate,
            $startStation,
            $endStation,
            $van
        );

        foreach ($this->getRandomExtras($order) as $extra) {
            $order->addExtra($extra);
        }

        return $order;
    }

    /**
     * @return array<DateTimeImmutable>
     */
    private function getRandomPeriod(): array
    {
        $minDate = new DateTimeImmutable('-4 months');
        $maxDate = new DateTimeImmutable('+3 months');

        $randTimestamp = random_int($minDate->getTimestamp(), $maxDate->getTimestamp());
        $startDate     = DateTimeImmutable::createFromFormat('U', (string) $randTimestamp);

        $randDays = random_int(1, 60);
        $endDate  = $startDate->modify("+$randDays days");

        return [$startDate, $endDate];
    }

    private function getRandomStation(): StationId
    {
        $stations = StationFixtures::getStationData();

        $number    = random_int(1, 3);
        $randomKey = array_rand($stations);
        $location  = $stations[$randomKey];
        $id        = sprintf('%s-%d', strtolower($location), $number);

        return StationId::fromString($id);
    }

    private function getRandomVan(): CampervanId
    {
        $vans = CampervanFixtures::getCampervanData();

        $randomKey = array_rand($vans);
        $vanData   = $vans[$randomKey];
        $id        = $vanData[0];

        return CampervanId::fromString($id);
    }

    /**
     * @return array<OrderExtra>
     */
    private function getRandomExtras(Order $order): array
    {
        $extrasData = ExtraFixtures::getExtrasData();
        shuffle($extrasData);

        $orderExtras = [];
        $extraPieces = random_int(1, 3);
        for ($i = 0; $i < $extraPieces; $i++) {
            $extraId  = ExtraId::fromString($extrasData[$i][0]);
            $quantity = random_int(1, 10);

            $orderExtras[] = new OrderExtra(
                $order,
                $extraId,
                $quantity
            );
        }

        return $orderExtras;
    }
}
