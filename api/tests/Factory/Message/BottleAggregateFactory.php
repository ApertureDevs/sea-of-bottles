<?php

namespace App\Tests\Factory\Message;

use App\Core\Component\Message\Domain\Model\Bottle;
use App\Core\SharedKernel\Port\ClockInterface;
use App\Tests\LockedClock;

class BottleAggregateFactory
{
    public static function createBottle(ClockInterface $clock = null): Bottle
    {
        if (null === $clock) {
            $clock = LockedClock::create(new \DateTimeImmutable('2021-01-01'));
        }

        return Bottle::create('This is a test message!', '::1', $clock);
    }

    public static function createReceivedBottle(ClockInterface $clock = null): Bottle
    {
        if (null === $clock) {
            $clock = LockedClock::create(new \DateTimeImmutable('2021-01-01'));
        }

        $bottle = self::createBottle($clock);
        $bottle->receive(SailorAggregateFactory::createSailor(), $clock);

        return $bottle;
    }
}
