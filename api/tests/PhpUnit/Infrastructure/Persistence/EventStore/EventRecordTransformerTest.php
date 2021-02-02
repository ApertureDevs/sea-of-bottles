<?php

namespace App\Tests\PhpUnit\Infrastructure\Persistence\EventStore;

use App\Core\Component\Message\Domain\Model\Bottle;
use App\Core\SharedKernel\Domain\Event\Event;
use App\Core\SharedKernel\Domain\Event\EventRecord;
use App\Infrastructure\Persistence\EventStore\EventMap;
use App\Infrastructure\Persistence\EventStore\EventRecordTransformer;
use App\Infrastructure\Persistence\EventStore\StorableEventRecord;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @covers \App\Infrastructure\Persistence\EventStore\EventRecordTransformer
 *
 * @internal
 */
class EventRecordTransformerTest extends KernelTestCase
{
    public function testItShouldConvertIntoStorableEventRecord(): void
    {
        $eventRecord = $this->generateDomainEventRecord();
        $transformer = $this->generateEventRecordTransformer();

        $storableEventRecord = $transformer->convertIntoStorableEventRecord($eventRecord);

        self::assertSame($eventRecord->getId(), $storableEventRecord->getId());
        self::assertSame($eventRecord->getAggregateId(), $storableEventRecord->getAggregateId());
        self::assertSame($eventRecord->getPlayHead(), $storableEventRecord->getPlayHead());
        self::assertSame($eventRecord->getRecordDate(), $storableEventRecord->getRecordDate());
        self::assertSame('bottle_created', $storableEventRecord->getEventType());
        self::assertSame('message', $storableEventRecord->getContext());
        self::assertIsString($storableEventRecord->getEvent());
    }

    public function testItShouldConvertIntoDomainEventRecord(): void
    {
        $storableEventRecord = StorableEventRecord::createFromEventRecord($this->generateDomainEventRecord(), '{"id":"36341903-9a96-4094-9a15-a9e70031047d","message":"Hello World!","create_date":"2020-01-01"}', 'bottle_created', 'message');
        $transformer = $this->generateEventRecordTransformer();

        $eventRecord = $transformer->convertIntoDomainEventRecord($storableEventRecord);

        self::assertSame($storableEventRecord->getId(), $eventRecord->getId());
        self::assertSame($storableEventRecord->getAggregateId(), $eventRecord->getAggregateId());
        self::assertSame($storableEventRecord->getPlayHead(), $eventRecord->getPlayHead());
        self::assertSame($storableEventRecord->getRecordDate(), $eventRecord->getRecordDate());
        self::assertInstanceOf(Event::class, $eventRecord->getEvent());
    }

    private function generateEventRecordTransformer(): EventRecordTransformer
    {
        self::bootKernel();
        $container = self::$container;
        $serializer = $container->get(SerializerInterface::class);
        $eventMap = new EventMap();

        return new EventRecordTransformer($serializer, $eventMap);
    }

    private function generateDomainEventRecord(): EventRecord
    {
        $bottle = Bottle::create('Hello World!');
        $eventRecords = $bottle->getUncommittedEventRecords();

        foreach ($eventRecords->getIterator() as $eventRecord) {
            return $eventRecord;
        }

        throw new \RuntimeException('No event record was generated.');
    }
}
