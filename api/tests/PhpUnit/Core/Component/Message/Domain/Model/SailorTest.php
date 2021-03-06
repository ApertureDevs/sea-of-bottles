<?php

namespace App\Tests\PhpUnit\Core\Component\Message\Domain\Model;

use App\Core\Component\Message\Domain\Exception\UndeletableSailorException;
use App\Tests\Factory\Message\SailorAggregateFactory;
use App\Tests\LockedClock;
use App\Tests\TestCase\AggregateTestCase;

/**
 * @covers \App\Core\Component\Message\Domain\Model\Sailor
 *
 * @internal
 */
class SailorTest extends AggregateTestCase
{
    public function testItShouldCreateASailor(): void
    {
        $sailor = SailorAggregateFactory::createSailor();

        self::assertSame('test@aperturedevs.com', $sailor->getEmail()->getAddress());
        self::assertSame('::1', $sailor->getCreateIp()->getAddress());
        self::assertInstanceOf(\DateTimeImmutable::class, $sailor->getCreateDate());
        self::assertIsString($sailor->getId());
        self::assertTrue($sailor->isActive());
    }

    public function testItShouldDeleteASailor(): void
    {
        $sailor = SailorAggregateFactory::createDeletedSailor();

        self::assertInstanceOf(\DateTimeImmutable::class, $sailor->getDeleteDate());
        self::assertTrue($sailor->isDelete());
        self::assertFalse($sailor->isActive());
        self::assertSame('::1', $sailor->getDeleteIp()->getAddress());
    }

    public function testItShouldThrowExceptionOnAlreadyDeletedASailor(): void
    {
        $sailor = SailorAggregateFactory::createDeletedSailor();

        self::expectException(UndeletableSailorException::class);

        $sailor->delete('::1', LockedClock::create(new \DateTimeImmutable('2021-01-01')));
    }
}
