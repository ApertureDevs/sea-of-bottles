<?php

namespace App\Tests\Factory\Message;

use App\Core\Component\Message\Domain\Model\Bottle;
use App\Core\Component\Message\Domain\Model\Sailor;
use App\Core\SharedKernel\Domain\Event\EventRecord;
use App\Core\SharedKernel\Domain\Event\Message\BottleCreated;
use App\Core\SharedKernel\Domain\Event\Message\BottleReceived;

class BottleEventRecordFactory
{
    public static function createBottleCreatedEventRecord(): EventRecord
    {
        return EventRecord::create(
            'e21433f6-ebac-4ab1-839b-b614f7e09ad6',
            'dfee8af6-2fda-43e5-bfd7-7ecc38671dea',
            0,
            self::createBottleCreatedEvent(),
            new \DateTimeImmutable('2020-01-01')
        );
    }

    public static function createBottleReceivedEventRecord(?Bottle $bottle = null, ?Sailor $receiver = null): EventRecord
    {
        $aggregateId = null !== $bottle ? $bottle->getId() : 'dfee8af6-2fda-43e5-bfd7-7ecc38671dea';
        $playHead = null !== $bottle ? $bottle->getPlayHead() : 0;

        return EventRecord::create(
            'f3aaf85e-2817-4958-b199-8a5e4c32902a',
            $aggregateId,
            $playHead,
            self::createBottleReceivedEvent($receiver),
            new \DateTimeImmutable('2020-01-01')
        );
    }

    private static function createBottleCreatedEvent(): BottleCreated
    {
        return BottleCreated::create(
            'dfee8af6-2fda-43e5-bfd7-7ecc38671dea',
            'Test message!',
            '::1',
            new \DateTimeImmutable('2020-01-01')
        );
    }

    private static function createBottleReceivedEvent(?Sailor $receiver = null): BottleReceived
    {
        $receiverId = null !== $receiver ? $receiver->getId() : 'dfee8af6-2fda-43e5-bfd7-7ecc38671dea';

        return BottleReceived::create(
            $receiverId,
            new \DateTimeImmutable('2020-01-01')
        );
    }
}
