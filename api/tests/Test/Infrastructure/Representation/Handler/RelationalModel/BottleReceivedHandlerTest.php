<?php

namespace App\Tests\Test\Infrastructure\Representation\Handler\RelationalModel;

use App\Infrastructure\Persistence\RelationalModel\Repository\BottleRepository;
use App\Infrastructure\Representation\Handler\RelationalModel\BottleReceivedHandler;
use App\Infrastructure\Representation\Model\RelationalModel\Bottle;
use App\Infrastructure\Representation\Model\RelationalModel\Sailor;
use App\Tests\Framework\AggregatePersister;
use App\Tests\Framework\Builder\Message\BottleAggregateBuilder;
use App\Tests\Framework\Builder\Message\BottleEventRecordFactory;
use App\Tests\Framework\Builder\Message\SailorAggregateBuilder;
use App\Tests\Framework\TestCase\EventHandlerTest;

/**
 * @covers \App\Infrastructure\Representation\Handler\RelationalModel\BottleReceivedHandler
 * @group integration
 *
 * @internal
 */
class BottleReceivedHandlerTest extends EventHandlerTest
{
    private AggregatePersister $persister;

    protected function setUp(): void
    {
        parent::setUp();

        $this->persister = self::getContainer()->get(AggregatePersister::class);
    }

    public function testItShouldHandleEvent(): void
    {
        $bottle = BottleAggregateBuilder::new()->build();
        $this->persister->persist($bottle);
        $sailor = SailorAggregateBuilder::new()->build();
        $this->persister->persist($sailor);
        $record = BottleEventRecordFactory::createBottleReceivedEventRecord($bottle, $sailor);
        /** @var BottleReceivedHandler $handler */
        $handler = $this->getEventHandler();

        $handler($record);

        $bottleProjection = $this->getBottleProjection($record->getAggregateId());
        self::assertTrue($handler->support($record));
        self::assertInstanceOf(Sailor::class, $bottleProjection->getReceiver());
        self::assertEquals(new \DateTimeImmutable('2020-01-01'), $bottleProjection->getReceiveDate());
    }

    public function testItShouldSkipUnsupportedEvent(): void
    {
        $record = BottleEventRecordFactory::createBottleCreatedEventRecord();
        /** @var BottleReceivedHandler $handler */
        $handler = $this->getEventHandler();
        $bottlesCount = $this->getBottlesCount();

        $handler($record);

        self::assertFalse($handler->support($record));
        self::assertSame($bottlesCount, $this->getBottlesCount());
    }

    public function testItShouldThrowExceptionOnUnknownBottle(): void
    {
        $bottle = BottleAggregateBuilder::new()->build();
        $sailor = SailorAggregateBuilder::new()->build();
        $this->persister->persist($sailor);
        $record = BottleEventRecordFactory::createBottleReceivedEventRecord($bottle, $sailor);
        /** @var BottleReceivedHandler $handler */
        $handler = $this->getEventHandler();

        $this->expectException(\RuntimeException::class);
        $handler($record);
    }

    public function testItShouldThrowExceptionOnUnknownSailor(): void
    {
        $bottle = BottleAggregateBuilder::new()->build();
        $this->persister->persist($bottle);
        $sailor = SailorAggregateBuilder::new()->build();
        $record = BottleEventRecordFactory::createBottleReceivedEventRecord($bottle, $sailor);
        /** @var BottleReceivedHandler $handler */
        $handler = $this->getEventHandler();

        $this->expectException(\RuntimeException::class);
        $handler($record);
    }

    protected function getEventHandlerClass(): string
    {
        return BottleReceivedHandler::class;
    }

    private function getBottleProjection(string $bottleId): ?Bottle
    {
        return self::getContainer()->get(BottleRepository::class)->findById($bottleId);
    }

    private function getBottlesCount(): int
    {
        return self::getContainer()->get(BottleRepository::class)->getRemainingBottlesCount();
    }
}
