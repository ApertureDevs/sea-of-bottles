<?php

namespace App\Tests\PhpUnit\Core\Component\Message\Domain\Model;

use App\Core\Component\Message\Domain\Exception\UnreceivableBottleException;
use App\Tests\Factory\Message\BottleAggregateFactory;
use App\Tests\Factory\Message\SailorAggregateFactory;
use App\Tests\LockedClock;
use App\Tests\TestCase\AggregateTestCase;

/**
 * @covers \App\Core\Component\Message\Domain\Model\Bottle
 *
 * @internal
 */
class BottleTest extends AggregateTestCase
{
    public function testItShouldCreateABottle(): void
    {
        $bottle = BottleAggregateFactory::createBottle();

        self::assertSame('This is a test message!', $bottle->getMessage()->getContent());
        self::assertSame('::1', $bottle->getCreateIp()->getAddress());
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

        $bottle->receive($sailor, LockedClock::create(new \DateTimeImmutable('2021-01-01')));
    }
}
