<?php

namespace App\Tests\Test\Core\Component\Message\Domain\Model;

use App\Core\Component\Message\Domain\Exception\UnreceivableBottleException;
use App\Tests\Framework\Builder\Message\BottleAggregateBuilder;
use App\Tests\Framework\Builder\Message\SailorAggregateBuilder;
use App\Tests\Framework\LockedClock;
use App\Tests\Framework\TestCase\AggregateTestCase;

/**
 * @covers \App\Core\Component\Message\Domain\Model\Bottle
 * @group unit
 *
 * @internal
 */
class BottleTest extends AggregateTestCase
{
    public function testItShouldCreateABottle(): void
    {
        $bottle = BottleAggregateBuilder::new()->build();

        self::assertSame('This is a test message!', $bottle->getMessage()->getContent());
        self::assertSame('::1', $bottle->getCreateIp()->getAddress());
        self::assertInstanceOf(\DateTimeImmutable::class, $bottle->getCreateDate());
        self::assertIsString($bottle->getId());
    }

    public function testItShouldReceiveABottle(): void
    {
        $bottle = BottleAggregateBuilder::new()->wasReceived()->build();

        self::assertInstanceOf(\DateTimeImmutable::class, $bottle->getReceiveDate());
        self::assertIsString($bottle->getReceiver());
        self::assertTrue($bottle->isReceive());
    }

    public function testItShouldThrowExceptionOnAlreadyReceivedBottle(): void
    {
        $bottle = BottleAggregateBuilder::new()->wasReceived()->build();
        $sailor = SailorAggregateBuilder::new()->build();

        $this->expectException(UnreceivableBottleException::class);

        $bottle->receive($sailor, LockedClock::create(new \DateTimeImmutable('2021-01-01')));
    }
}
