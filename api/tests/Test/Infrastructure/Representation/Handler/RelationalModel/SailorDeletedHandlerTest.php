<?php

namespace App\Tests\Test\Infrastructure\Representation\Handler\RelationalModel;

use App\Infrastructure\Persistence\RelationalModel\Repository\SailorRepository;
use App\Infrastructure\Representation\Handler\RelationalModel\SailorDeletedHandler;
use App\Infrastructure\Representation\Model\RelationalModel\Sailor;
use App\Tests\Framework\AggregatePersister;
use App\Tests\Framework\Builder\Message\SailorAggregateBuilder;
use App\Tests\Framework\Builder\Message\SailorEventRecordFactory;
use App\Tests\Framework\TestCase\EventHandlerTest;

/**
 * @covers \App\Infrastructure\Representation\Handler\RelationalModel\SailorDeletedHandler
 * @group integration
 *
 * @internal
 */
class SailorDeletedHandlerTest extends EventHandlerTest
{
    private AggregatePersister $persister;

    protected function setUp(): void
    {
        parent::setUp();

        $this->persister = self::getContainer()->get(AggregatePersister::class);
    }

    public function testItShouldHandleEvent(): void
    {
        $sailor = SailorAggregateBuilder::new()->build();
        $this->persister->persist($sailor);
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

        $this->expectException(\RuntimeException::class);
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
}
