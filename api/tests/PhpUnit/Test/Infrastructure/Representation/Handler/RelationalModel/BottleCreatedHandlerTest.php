<?php

namespace App\Tests\PhpUnit\Test\Infrastructure\Representation\Handler\RelationalModel;

use App\Infrastructure\Persistence\RelationalModel\Repository\BottleRepository;
use App\Infrastructure\Representation\Handler\RelationalModel\BottleCreatedHandler;
use App\Infrastructure\Representation\Model\RelationalModel\Bottle;
use App\Tests\Builder\Message\BottleEventRecordFactory;
use App\Tests\PhpUnit\Framework\TestCase\EventHandlerTest;

/**
 * @covers \App\Infrastructure\Representation\Handler\RelationalModel\BottleCreatedHandler
 *
 * @internal
 */
class BottleCreatedHandlerTest extends EventHandlerTest
{
    public function testItShouldHandleEvent(): void
    {
        $record = BottleEventRecordFactory::createBottleCreatedEventRecord();
        /** @var BottleCreatedHandler $handler */
        $handler = $this->getEventHandler();

        $handler($record);

        $bottleProjection = $this->getBottleProjection($record->getAggregateId());
        self::assertTrue($handler->support($record));
        self::assertSame('dfee8af6-2fda-43e5-bfd7-7ecc38671dea', $bottleProjection->getId());
        self::assertSame('::1', $bottleProjection->getCreateIp());
        self::assertSame('Test message!', $bottleProjection->getMessage());
        self::assertEquals(new \DateTimeImmutable('2020-01-01'), $bottleProjection->getCreateDate());
    }

    public function testItShouldSkipUnsupportedEvent(): void
    {
        $record = BottleEventRecordFactory::createBottleReceivedEventRecord();
        /** @var BottleCreatedHandler $handler */
        $handler = $this->getEventHandler();
        $bottlesCount = $this->getBottlesCount();

        $handler($record);

        self::assertFalse($handler->support($record));
        self::assertSame($bottlesCount, $this->getBottlesCount());
    }

    protected function getEventHandlerClass(): string
    {
        return BottleCreatedHandler::class;
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
