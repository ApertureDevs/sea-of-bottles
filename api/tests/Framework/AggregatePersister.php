<?php

namespace App\Tests\Framework;

use App\Core\Component\Message\Domain\Model\Bottle;
use App\Core\Component\Message\Domain\Model\Sailor;
use App\Core\Component\Message\Port\BottleStoreInterface;
use App\Core\Component\Message\Port\SailorStoreInterface;
use App\Core\SharedKernel\Domain\Event\Aggregate;
use App\Core\SharedKernel\Port\EventDispatcherInterface;

class AggregatePersister
{
    private EventDispatcherInterface $eventDispatcher;

    private BottleStoreInterface $bottleStore;

    private SailorStoreInterface $sailorStore;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        BottleStoreInterface $bottleStore,
        SailorStoreInterface $sailorStore,
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->bottleStore = $bottleStore;
        $this->sailorStore = $sailorStore;
    }

    public function persist(Aggregate $aggregate): void
    {
        $eventRecords = $aggregate->getUncommittedEventRecords();

        if ($aggregate instanceof Bottle) {
            $this->bottleStore->store($aggregate);
        }

        if ($aggregate instanceof Sailor) {
            $this->sailorStore->store($aggregate);
        }

        $this->eventDispatcher->dispatch($eventRecords);
    }
}
