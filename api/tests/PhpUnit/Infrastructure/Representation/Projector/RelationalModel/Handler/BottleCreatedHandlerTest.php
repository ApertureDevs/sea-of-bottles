<?php

namespace Tests\App\Infrastructure\Representation\Projector\RelationalModel\Handler;

use App\Core\SharedKernel\Domain\Event\EventRecord;
use App\Core\SharedKernel\Domain\Event\Message\BottleCreated;
use App\Core\SharedKernel\Domain\Event\Message\SailorCreated;
use App\Infrastructure\Persistence\RelationalModel\Repository\BottleRepository;
use App\Infrastructure\Representation\Projector\RelationalModel\Handler\BottleCreatedHandler;
use App\Infrastructure\Representation\Projector\RelationalModel\Model\Bottle;
use App\Tests\Factory\Message\BottleAggregateFactory;
use App\Tests\Factory\Message\SailorAggregateFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

/**
 * @covers \App\Infrastructure\Representation\Projector\RelationalModel\Handler\BottleCreatedHandler
 *
 * @internal
 */
class BottleCreatedHandlerTest extends TestCase
{
    public function testItShouldCreateBottleProjection(): void
    {
        $record = $this->generateBottleCreatedEventRecord();
        $event = $record->getEvent();

        if (!$event instanceof BottleCreated) {
            throw new \RuntimeException(sprintf('Generated event is not an instance of "%s".', BottleCreated::class));
        }

        $expectedModel = Bottle::create($event->getId(), $event->getMessage(), $event->getCreateDate());
        $bottleRepository = $this->prophesize(BottleRepository::class);
        $bottleRepository->save(Argument::exact($expectedModel))->shouldBeCalled();
        $handler = new BottleCreatedHandler($bottleRepository->reveal());
        $handler($record);
    }

    public function testItShouldSkipUnsupportedEvent(): void
    {
        $record = $this->generateSailorCreatedEventRecord();
        $event = $record->getEvent();

        if (!$event instanceof SailorCreated) {
            throw new \RuntimeException(sprintf('Generated event is not an instance of "%s".', SailorCreated::class));
        }

        $bottleRepository = $this->prophesize(BottleRepository::class);
        $bottleRepository->save(Argument::any())->shouldNotBeCalled();
        $handler = new BottleCreatedHandler($bottleRepository->reveal());
        $handler($record);
    }

    private function generateBottleCreatedEventRecord(): EventRecord
    {
        $bottle = BottleAggregateFactory::createBottle();
        $records = $bottle->getUncommittedEventRecords();

        foreach ($records as $record) {
            if ($record->getEvent() instanceof BottleCreated) {
                return $record;
            }
        }

        throw new \RuntimeException(sprintf('No instance of "%s" was generated.', BottleCreated::class));
    }

    private function generateSailorCreatedEventRecord(): EventRecord
    {
        $bottle = SailorAggregateFactory::createSailor();
        $records = $bottle->getUncommittedEventRecords();

        foreach ($records as $record) {
            if ($record->getEvent() instanceof SailorCreated) {
                return $record;
            }
        }

        throw new \RuntimeException(sprintf('No instance of "%s" was generated.', SailorCreated::class));
    }
}
