<?php

namespace Tests\App\Core\Component\Message\Domain\Aggregate;

use App\Core\Component\Message\Domain\Exception\UnreceivableBottleException;
use App\Tests\Factory\Message\BottleAggregateFactory;
use App\Tests\Factory\Message\SailorAggregateFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Core\Component\Message\Domain\Aggregate\Bottle
 *
 * @internal
 */
class BottleTest extends TestCase
{
    public function testItShouldCreateABottle(): void
    {
        $bottle = BottleAggregateFactory::createBottle();

        self::assertSame('This is a test message!', $bottle->getMessage()->getContent());
        self::assertInstanceOf(\DateTimeImmutable::class, $bottle->getCreateDate());
        self::assertIsString($bottle->getId());
    }

    public function testItShouldReceiveABottle(): void
    {
        $bottle = BottleAggregateFactory::createReceivedBottle();

        self::assertInstanceOf(\DateTimeImmutable::class, $bottle->getReceiveDate());
        self::assertIsString($bottle->getReceiver());
        self::assertTrue($bottle->isReceive());
    }

    public function testItShouldThrowExceptionOnAlreadyReceivedBottle(): void
    {
        $bottle = BottleAggregateFactory::createReceivedBottle();
        $sailor = SailorAggregateFactory::createSailor();

        self::expectException(UnreceivableBottleException::class);

        $bottle->receive($sailor);
    }
}
