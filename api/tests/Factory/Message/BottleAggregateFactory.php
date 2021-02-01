<?php

namespace App\Tests\Factory\Message;

use App\Core\Component\Message\Domain\Model\Bottle;

class BottleAggregateFactory
{
    public static function createBottle(): Bottle
    {
        return Bottle::create('This is a test message!');
    }

    public static function createReceivedBottle(): Bottle
    {
        $bottle = self::createBottle();
        $bottle->receive(SailorAggregateFactory::createSailor());

        return $bottle;
    }
}
