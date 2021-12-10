<?php

namespace App\Tests\PhpUnit\Test\Infrastructure\Representation\Handler\RelationalModel;

use App\Core\SharedKernel\Domain\Event\Aggregate;
use App\Core\SharedKernel\Port\EventDispatcherInterface;
use App\Infrastructure\Persistence\RelationalModel\Repository\BottleRepository;
use App\Infrastructure\Representation\Handler\RelationalModel\BottleReceivedHandler;
use App\Infrastructure\Representation\Model\RelationalModel\Bottle;
use App\Infrastructure\Representation\Model\RelationalModel\Sailor;
use App\Tests\Builder\Message\BottleAggregateBuilder;
use App\Tests\Builder\Message\BottleEventRecordFactory;
use App\Tests\Builder\Message\SailorAggregateBuilder;
use App\Tests\PhpUnit\Framework\TestCase\EventHandlerTest;

/**
 * @covers \App\Infrastructure\Representation\Handler\RelationalModel\BottleReceivedHandler
 *
 * @internal
 */
class BottleReceivedHandlerTest extends EventHandlerTest
{
    public function testItShouldHandleEvent(): void
    {
        $bottle = BottleAggregateBuilder::new()->build();
        $this->persistAggregate($bottle);
        $sailor = SailorAggregateBuilder::new()->build();
        $this->persistAggregate($sailor);
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
        $this->persistAggregate($sailor);
        $record = BottleEventRecordFactory::createBottleReceivedEventRecord($bottle, $sailor);
        /** @var BottleReceivedHandler $handler */
        $handler = $this->getEventHandler();

        $this->expectException(\RuntimeException::class);
        $handler($record);
    }

    public function testItShouldThrowExceptionOnUnknownSailor(): void
    {
        $bottle = BottleAggregateBuilder::new()->build();
        $this->persistAggregate($bottle);
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

    private function persistAggregate(Aggregate $aggregate): void
    {
        $eventDispatcher = self::getContainer()->get(EventDispatcherInterface::class);
        $eventDispatcher->dispatch($aggregate->getUncommittedEventRecords());
    }
}
