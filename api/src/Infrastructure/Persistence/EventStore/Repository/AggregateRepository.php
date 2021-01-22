<?php

namespace App\Infrastructure\Persistence\EventStore\Repository;

use App\Core\SharedKernel\Domain\Event\Aggregate;
use App\Infrastructure\Event\AggregateFactory;
use App\Infrastructure\Persistence\EventStore\EventStore;

abstract class AggregateRepository
{
    protected EventStore $eventStore;
    protected AggregateFactory $aggregateFactory;

    public function __construct(EventStore $eventStore, AggregateFactory $aggregateFactory)
    {
        $this->eventStore = $eventStore;
        $this->aggregateFactory = $aggregateFactory;
    }

    public function store(Aggregate $aggregate): void
    {
        $eventRecords = $aggregate->getUncommittedEventRecords();
        $this->eventStore->append($eventRecords);
    }

    /** @return Aggregate */
    abstract public function load(string $id);

    protected function generateFromEventStore(string $id, string $className): ?Aggregate
    {
        $eventRecords = $this->eventStore->loadAggregateEventRecords($id);

        if (0 === count($eventRecords)) {
            return null;
        }

        return $this->aggregateFactory->create($className, $eventRecords);
    }
}
