<?php

declare(strict_types=1);

namespace App\Tests\Query;

use App\Dto\OrderHistoryDto;
use App\Entity\Order;
use App\Entity\OrderExtra;
use App\Query\DBALStationOrderHistoryQueryHandler;
use App\ValueObject\CampervanId;
use App\ValueObject\ExtraId;
use App\ValueObject\OrderId;
use App\ValueObject\StationId;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PascalDeVink\ShortUuid\ShortUuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @group integration
 */
final class DBALStationOrderHistoryQueryHandlerTest extends KernelTestCase
{
    private ?EntityManagerInterface $em;

    private DBALStationOrderHistoryQueryHandler $handler;

    public function setUp(): void
    {
        $this->em = self::getContainer()->get('doctrine')->getManager();

        $this->handler = self::getContainer()->get(DBALStationOrderHistoryQueryHandler::class);

        $this->prepareOrders();
    }

    private function prepareOrders(): void
    {
        foreach (self::getOrdersData() as $data) {
            $order = new Order(
                OrderId::fromString(ShortUuid::uuid4()),
                new DateTimeImmutable($data[0]),
                new DateTimeImmutable($data[1]),
                StationId::fromString($data[2]),
                StationId::fromString($data[3]),
                CampervanId::fromString('test')
            );

            foreach ($data[4] as $extraKey => $quantity) {
                $orderExtra = new OrderExtra($order, ExtraId::fromString($extraKey), $quantity);
                $order->addExtra($orderExtra);
            }

            $this->em->persist($order);
        }

        $this->em->flush();
    }

    private static function getOrdersData(): array
    {
        return [
            ['2022-07-05', '2022-08-01', 'station-0', 'station-1', ['extra-1' => 5, 'extra-2' => 2]],
            ['2022-07-05', '2022-07-22', 'station-0', 'station-3', ['extra-2' => 1, 'extra-3' => 4]],
            ['2022-06-04', '2022-07-09', 'station-2', 'station-0', ['extra-1' => 7, 'extra-2' => 1]],
            ['2022-07-09', '2022-07-12', 'station-2', 'station-3', ['extra-1' => 1, 'extra-2' => 4]],
            ['2022-07-09', '2022-07-19', 'station-0', 'station-3', ['extra-1' => 2, 'extra-2' => 3]],
        ];
    }

    public function testFindByStation(): void
    {
        $stationId = StationId::fromString('station-0');
        $history   = $this->handler->findByStation($stationId);

        $expected = [
            new OrderHistoryDto(
                DateTimeImmutable::createFromFormat('!Y-m-d', '2022-07-05'),
                ExtraId::fromString('extra-1'),
                booked: 5,
                returned: 0
            ),
            new OrderHistoryDto(
                DateTimeImmutable::createFromFormat('!Y-m-d', '2022-07-05'),
                ExtraId::fromString('extra-2'),
                booked: 3,
                returned: 0
            ),
            new OrderHistoryDto(
                DateTimeImmutable::createFromFormat('!Y-m-d', '2022-07-05'),
                ExtraId::fromString('extra-3'),
                booked: 4,
                returned: 0
            ),
            new OrderHistoryDto(
                DateTimeImmutable::createFromFormat('!Y-m-d', '2022-07-09'),
                ExtraId::fromString('extra-1'),
                booked: 2,
                returned: 7
            ),
            new OrderHistoryDto(
                DateTimeImmutable::createFromFormat('!Y-m-d', '2022-07-09'),
                ExtraId::fromString('extra-2'),
                booked: 3,
                returned: 1
            ),
        ];

        foreach ($history as $index => $actual) {
            self::assertEquals($expected[$index], $actual);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}
