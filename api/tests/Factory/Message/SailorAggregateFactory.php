<?php

namespace App\Tests\Factory\Message;

use App\Core\Component\Message\Domain\Model\Sailor;

class SailorAggregateFactory
{
    public static function createSailor(): Sailor
    {
        return Sailor::create('test@aperturedevs.com', '::1');
    }

    public static function createDeletedSailor(): Sailor
    {
        $sailor = self::createSailor();
        $sailor->delete('::1');

        return $sailor;
    }
}
