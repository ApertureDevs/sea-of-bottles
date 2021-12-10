<?php

namespace App\Tests\PhpUnit\Test\Core\Component\Message\Domain\Model;

use App\Core\Component\Message\Domain\Exception\UndeletableSailorException;
use App\Tests\Builder\Message\SailorAggregateBuilder;
use App\Tests\PhpUnit\Framework\LockedClock;
use App\Tests\PhpUnit\Framework\TestCase\AggregateTestCase;

/**
 * @covers \App\Core\Component\Message\Domain\Model\Sailor
 *
 * @internal
 */
class SailorTest extends AggregateTestCase
{
    public function testItShouldCreateASailor(): void
    {
        $sailor = SailorAggregateBuilder::new()->build();

        self::assertSame('test@aperturedevs.com', $sailor->getEmail()->getAddress());
        self::assertSame('::1', $sailor->getCreateIp()->getAddress());
        self::assertInstanceOf(\DateTimeImmutable::class, $sailor->getCreateDate());
        self::assertIsString($sailor->getId());
        self::assertTrue($sailor->isActive());
    }

    public function testItShouldDeleteASailor(): void
    {
        $sailor = SailorAggregateBuilder::new()->wasDeleted()->build();

        self::assertInstanceOf(\DateTimeImmutable::class, $sailor->getDeleteDate());
        self::assertTrue($sailor->isDelete());
        self::assertFalse($sailor->isActive());
        self::assertSame('::1', $sailor->getDeleteIp()->getAddress());
    }

    public function testItShouldThrowExceptionOnAlreadyDeletedASailor(): void
    {
        $sailor = SailorAggregateBuilder::new()->wasDeleted()->build();

        $this->expectException(UndeletableSailorException::class);

        $sailor->delete('::1', LockedClock::create(new \DateTimeImmutable('2021-01-01')));
    }
}
