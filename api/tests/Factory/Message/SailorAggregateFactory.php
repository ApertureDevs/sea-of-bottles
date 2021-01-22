<?php

namespace App\Tests\Factory\Message;

use App\Core\Component\Message\Domain\Aggregate\Sailor;

class SailorAggregateFactory
{
    public static function createSailor(): Sailor
    {
        return Sailor::create('test@aperturedevs.com');
    }

    public static function createDeletedSailor(): Sailor
    {
        $sailor = self::createSailor();
        $sailor->delete();

        return $sailor;
    }
}
