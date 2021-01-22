<?php

namespace App\Infrastructure\Event;

use App\Core\SharedKernel\Domain\Event\Aggregate;
use App\Core\SharedKernel\Domain\Event\EventRecords;

class AggregateFactory
{
    public function create(string $aggregateClassName, EventRecords $eventRecords): Aggregate
    {
        $aggregate = new $aggregateClassName();
        $aggregate->initializeState($eventRecords);

        return $aggregate;
    }
}
