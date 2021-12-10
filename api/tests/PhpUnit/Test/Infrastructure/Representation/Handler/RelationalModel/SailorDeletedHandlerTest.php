<?php

namespace App\Tests\PhpUnit\Test\Infrastructure\Representation\Handler\RelationalModel;

use App\Core\SharedKernel\Domain\Event\Aggregate;
use App\Core\SharedKernel\Port\EventDispatcherInterface;
use App\Infrastructure\Persistence\RelationalModel\Repository\SailorRepository;
use App\Infrastructure\Representation\Handler\RelationalModel\SailorDeletedHandler;
use App\Infrastructure\Representation\Model\RelationalModel\Sailor;
use App\Tests\Builder\Message\SailorAggregateBuilder;
use App\Tests\Builder\Message\SailorEventRecordFactory;
use App\Tests\PhpUnit\Framework\TestCase\EventHandlerTest;

/**
 * @covers \App\Infrastructure\Representation\Handler\RelationalModel\SailorDeletedHandler
 *
 * @internal
 */
class SailorDeletedHandlerTest extends EventHandlerTest
{
    public function testItShouldHandleEvent(): void
    {
        $sailor = SailorAggregateBuilder::new()->build();
        $this->persistAggregate($sailor);
        $record = SailorEventRecordFactory::createSailorDeletedEventRecord($sailor);
        /** @var SailorDeletedHandler $handler */
        $handler = $this->getEventHandler();

        $handler($record);

        self::assertTrue($handler->support($record));
        self::assertNull($this->getSailorProjection($sailor->getId()));
    }

    public function testItShouldSkipUnsupportedEvent(): void
    {
        $record = SailorEventRecordFactory::createSailorCreatedEventRecord();
        /** @var SailorDeletedHandler $handler */
        $handler = $this->getEventHandler();
        $sailorsCount = $this->getSailorsCount();

        $handler($record);

        self::assertFalse($handler->support($record));
        self::assertSame($sailorsCount, $this->getSailorsCount());
    }

    public function testItShouldThrowExceptionOnUnknownSailor(): void
    {
        $sailor = SailorAggregateBuilder::new()->build();
        $record = SailorEventRecordFactory::createSailorDeletedEventRecord($sailor);
        /** @var SailorDeletedHandler $handler */
        $handler = $this->getEventHandler();

        self::expectException(\RuntimeException::class);
        $handler($record);
    }

    protected function getEventHandlerClass(): string
    {
        return SailorDeletedHandler::class;
    }

    private function getSailorsCount(): int
    {
        return self::getContainer()->get(SailorRepository::class)->getSailorsCount();
    }

    private function getSailorProjection(string $sailorId): ?Sailor
    {
        return self::getContainer()->get(SailorRepository::class)->findById($sailorId);
    }

    private function persistAggregate(Aggregate $aggregate): void
    {
        $eventDispatcher = self::getContainer()->get(EventDispatcherInterface::class);
        $eventDispatcher->dispatch($aggregate->getUncommittedEventRecords());
    }
}
