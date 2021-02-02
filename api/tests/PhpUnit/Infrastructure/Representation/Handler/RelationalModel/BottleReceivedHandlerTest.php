<?php

namespace App\Tests\PhpUnit\Infrastructure\Representation\Handler\RelationalModel;

use App\Core\SharedKernel\Domain\Event\Aggregate;
use App\Core\SharedKernel\Port\EventDispatcherInterface;
use App\Infrastructure\Persistence\RelationalModel\Repository\BottleRepository;
use App\Infrastructure\Representation\Handler\RelationalModel\BottleReceivedHandler;
use App\Infrastructure\Representation\Model\RelationalModel\Bottle;
use App\Infrastructure\Representation\Model\RelationalModel\Sailor;
use App\Tests\Factory\Message\BottleAggregateFactory;
use App\Tests\Factory\Message\BottleEventRecordFactory;
use App\Tests\Factory\Message\SailorAggregateFactory;
use App\Tests\TestCase\EventHandlerTest;

/**
 * @covers \App\Infrastructure\Representation\Handler\RelationalModel\BottleReceivedHandler
 *
 * @internal
 */
class BottleReceivedHandlerTest extends EventHandlerTest
{
    public function testItShouldHandleEvent(): void
    {
        $bottle = BottleAggregateFactory::createBottle();
        $this->persistAggregate($bottle);
        $sailor = SailorAggregateFactory::createSailor();
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
        $bottle = BottleAggregateFactory::createBottle();
        $sailor = SailorAggregateFactory::createSailor();
        $this->persistAggregate($sailor);
        $record = BottleEventRecordFactory::createBottleReceivedEventRecord($bottle, $sailor);
        /** @var BottleReceivedHandler $handler */
        $handler = $this->getEventHandler();

        self::expectException(\RuntimeException::class);
        $handler($record);
    }

    public function testItShouldThrowExceptionOnUnknownSailor(): void
    {
        $bottle = BottleAggregateFactory::createBottle();
        $this->persistAggregate($bottle);
        $sailor = SailorAggregateFactory::createSailor();
        $record = BottleEventRecordFactory::createBottleReceivedEventRecord($bottle, $sailor);
        /** @var BottleReceivedHandler $handler */
        $handler = $this->getEventHandler();

        self::expectException(\RuntimeException::class);
        $handler($record);
    }

    protected function getEventHandlerClass(): string
    {
        return BottleReceivedHandler::class;
    }

    private function getBottleProjection(string $bottleId): ?Bottle
    {
        $repository = $this->getContainer()->get(BottleRepository::class);

        return $repository->findById($bottleId);
    }

    private function getBottlesCount(): int
    {
        $repository = $this->getContainer()->get(BottleRepository::class);

        return $repository->getRemainingBottlesCount();
    }

    private function persistAggregate(Aggregate $aggregate): void
    {
        $eventDispatcher = $this->getContainer()->get(EventDispatcherInterface::class);
        $eventDispatcher->dispatch($aggregate->getUncommittedEventRecords());
    }
}
