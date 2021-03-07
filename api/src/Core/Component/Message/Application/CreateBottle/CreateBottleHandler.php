<?php

namespace App\Core\Component\Message\Application\CreateBottle;

use App\Core\Component\Message\Domain\Model\Bottle;
use App\Core\Component\Message\Port\BottleStoreInterface;
use App\Core\SharedKernel\Application\CommandHandlerInterface;
use App\Core\SharedKernel\Port\EventDispatcherInterface;

class CreateBottleHandler implements CommandHandlerInterface
{
    private BottleStoreInterface $bottleStore;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(BottleStoreInterface $bottleStore, EventDispatcherInterface $eventDispatcher)
    {
        $this->bottleStore = $bottleStore;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(CreateBottleCommand $command): CreateBottleResponse
    {
        $bottle = Bottle::create($command->message, $command->createIp);
        $eventRecords = $bottle->getUncommittedEventRecords();
        $this->bottleStore->store($bottle);
        $this->eventDispatcher->dispatch($eventRecords);

        return CreateBottleResponse::createFromBottle($bottle);
    }
}
