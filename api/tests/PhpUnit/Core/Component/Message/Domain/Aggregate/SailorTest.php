<?php

namespace Tests\App\Core\Component\Message\Domain\Aggregate;

use App\Core\Component\Message\Domain\Exception\UndeletableSailorException;
use App\Tests\Factory\Message\SailorAggregateFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Core\Component\Message\Domain\Aggregate\Sailor
 *
 * @internal
 */
class SailorTest extends TestCase
{
    public function testItShouldCreateASailor(): void
    {
        $sailor = SailorAggregateFactory::createSailor();

        self::assertSame('test@aperturedevs.com', $sailor->getEmail()->getAddress());
        self::assertInstanceOf(\DateTimeImmutable::class, $sailor->getCreateDate());
        self::assertIsString($sailor->getId());
    }

    public function testItShouldDeleteASailor(): void
    {
        $sailor = SailorAggregateFactory::createDeletedSailor();

        self::assertInstanceOf(\DateTimeImmutable::class, $sailor->getDeleteDate());
        self::assertTrue($sailor->isDelete());
    }

    public function testItShouldThrowExceptionOnAlreadyDeletedASailor(): void
    {
        $sailor = SailorAggregateFactory::createDeletedSailor();

        self::expectException(UndeletableSailorException::class);

        $sailor->delete();
    }
}
