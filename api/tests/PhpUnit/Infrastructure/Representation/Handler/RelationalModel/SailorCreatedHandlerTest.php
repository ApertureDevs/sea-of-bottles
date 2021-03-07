<?php

namespace App\Tests\PhpUnit\Infrastructure\Representation\Handler\RelationalModel;

use App\Infrastructure\Persistence\RelationalModel\Repository\SailorRepository;
use App\Infrastructure\Representation\Handler\RelationalModel\SailorCreatedHandler;
use App\Infrastructure\Representation\Model\RelationalModel\Sailor;
use App\Tests\Factory\Message\SailorEventRecordFactory;
use App\Tests\TestCase\EventHandlerTest;

/**
 * @covers \App\Infrastructure\Representation\Handler\RelationalModel\SailorCreatedHandler
 *
 * @internal
 */
class SailorCreatedHandlerTest extends EventHandlerTest
{
    public function testItShouldHandleEvent(): void
    {
        $record = SailorEventRecordFactory::createSailorCreatedEventRecord();
        /** @var SailorCreatedHandler $handler */
        $handler = $this->getEventHandler();

        $handler($record);

        $sailorProjection = $this->getSailorProjection($record->getAggregateId());
        self::assertTrue($handler->support($record));
        self::assertSame('9ec5aa9a-6b2d-4a92-a2b0-2a088955b477', $sailorProjection->getId());
        self::assertSame('::1', $sailorProjection->getCreateIp());
        self::assertSame('newsailor@aperturedevs.com', $sailorProjection->getEmail());
        self::assertEquals(new \DateTimeImmutable('2020-01-01'), $sailorProjection->getCreateDate());
    }

    public function testItShouldSkipUnsupportedEvent(): void
    {
        $record = SailorEventRecordFactory::createSailorDeletedEventRecord();
        /** @var SailorCreatedHandler $handler */
        $handler = $this->getEventHandler();
        $sailorsCount = $this->getSailorsCount();

        $handler($record);

        self::assertFalse($handler->support($record));
        self::assertSame($sailorsCount, $this->getSailorsCount());
    }

    protected function getEventHandlerClass(): string
    {
        return SailorCreatedHandler::class;
    }

    private function getSailorProjection(string $sailorId): ?Sailor
    {
        $repository = $this->getContainer()->get(SailorRepository::class);

        return $repository->findById($sailorId);
    }

    private function getSailorsCount(): int
    {
        $repository = $this->getContainer()->get(SailorRepository::class);

        return $repository->getSailorsCount();
    }
}
