<?php

declare(strict_types=1);

namespace App\Tests\ValueObject;

use App\ValueObject\AvailableExtras;
use App\ValueObject\ExtraId;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
final class AvailableExtrasTest extends TestCase
{
    private AvailableExtras $extras;

    public function setUp(): void
    {
        $this->extras = new AvailableExtras();
    }

    public function testSimpleAdd(): void
    {
        $id = ExtraId::fromString('test');
        $this->extras->aggregate($id, 0, 5);

        self::assertEquals(5, $this->extras->getAvailable($id));
    }

    public function testBookOnEmpty(): void
    {
        $id = ExtraId::fromString('test');
        $this->extras->aggregate($id, 5, 0);

        self::assertEquals(0, $this->extras->getAvailable($id));
    }

    public function testComplexCase(): void
    {
        $id1 = ExtraId::fromString('test-1');
        $id2 = ExtraId::fromString('test-2');

        $this->extras->aggregate($id1, 2, 5);
        $this->extras->aggregate($id1, 0, 1);

        $this->extras->aggregate($id2, 8, 5);

        self::assertEquals(4, $this->extras->getAvailable($id1));
        self::assertEquals(0, $this->extras->getAvailable($id2));
    }
}
