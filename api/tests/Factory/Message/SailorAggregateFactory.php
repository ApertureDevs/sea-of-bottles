<?php

namespace App\Tests\Factory\Message;

use App\Core\Component\Message\Domain\Model\Sailor;
use App\Core\SharedKernel\Port\ClockInterface;
use App\Tests\LockedClock;

class SailorAggregateFactory
{
    public static function createSailor(ClockInterface $clock = null): Sailor
    {
        if (null === $clock) {
            $clock = LockedClock::create(new \DateTimeImmutable('2021-01-01'));
        }

        return Sailor::create('test@aperturedevs.com', '::1', $clock);
    }

    public static function createDeletedSailor(ClockInterface $clock = null): Sailor
    {
        if (null === $clock) {
            $clock = LockedClock::create(new \DateTimeImmutable('2021-01-01'));
        }

        $sailor = self::createSailor($clock);
        $sailor->delete('::1', $clock);

        return $sailor;
    }
}
