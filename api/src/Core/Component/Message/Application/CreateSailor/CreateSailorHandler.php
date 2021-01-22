<?php

namespace App\Core\Component\Message\Application\CreateSailor;

use App\Core\Component\Message\Domain\Aggregate\Sailor;
use App\Core\Component\Message\Domain\Exception\UncreatableSailorException;
use App\Core\Component\Message\Port\SailorStoreInterface;
use App\Core\SharedKernel\Application\CommandHandlerInterface;
use App\Core\SharedKernel\Port\EventDispatcherInterface;

class CreateSailorHandler implements CommandHandlerInterface
{
    private SailorStoreInterface $sailorStore;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(SailorStoreInterface $sailorStore, EventDispatcherInterface $eventDispatcher)
    {
        $this->sailorStore = $sailorStore;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(CreateSailorCommand $command): CreateSailorResponse
    {
        $id = $this->sailorStore->findIdWithEmailAndNotDeleted($command->email);

        if (null !== $id) {
            throw UncreatableSailorException::createAlreadyCreatedException($command->email);
        }

        $sailor = Sailor::create($command->email);
        $this->sailorStore->store($sailor);
        $this->eventDispatcher->dispatch($sailor->getUncommittedEventRecords());

        return CreateSailorResponse::createFromSailor($sailor);
    }
}
