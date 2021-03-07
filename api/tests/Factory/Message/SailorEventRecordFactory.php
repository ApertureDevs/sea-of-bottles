<?php

namespace App\Tests\Factory\Message;

use App\Core\Component\Message\Domain\Model\Sailor;
use App\Core\SharedKernel\Domain\Event\EventRecord;
use App\Core\SharedKernel\Domain\Event\Message\SailorCreated;
use App\Core\SharedKernel\Domain\Event\Message\SailorDeleted;

class SailorEventRecordFactory
{
    public static function createSailorCreatedEventRecord(): EventRecord
    {
        return EventRecord::create(
            'b65054ac-ec52-410b-ad2c-6896bb1a1efa',
            '9ec5aa9a-6b2d-4a92-a2b0-2a088955b477',
            0,
            self::createSailorCreatedEvent(),
            new \DateTimeImmutable('2020-01-01')
        );
    }

    public static function createSailorDeletedEventRecord(?Sailor $sailor = null): EventRecord
    {
        $aggregateId = null !== $sailor ? $sailor->getId() : '9ec5aa9a-6b2d-4a92-a2b0-2a088955b477';
        $playHead = null !== $sailor ? $sailor->getPlayHead() : 0;

        return EventRecord::create(
            '5d5720ed-d097-4c7b-a068-3f676f989bf8',
            $aggregateId,
            $playHead,
            self::createSailorDeletedEvent(),
            new \DateTimeImmutable('2020-01-02')
        );
    }

    private static function createSailorCreatedEvent(): SailorCreated
    {
        return SailorCreated::create(
            '9ec5aa9a-6b2d-4a92-a2b0-2a088955b477',
            'newsailor@aperturedevs.com',
            '::1',
            new \DateTimeImmutable('2020-01-01')
        );
    }

    private static function createSailorDeletedEvent(): SailorDeleted
    {
        return SailorDeleted::create(
            '::1',
            new \DateTimeImmutable('2020-01-02')
        );
    }
}
