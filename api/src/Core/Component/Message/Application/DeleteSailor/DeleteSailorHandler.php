<?php

namespace App\Core\Component\Message\Application\DeleteSailor;

use App\Core\Component\Message\Domain\Aggregate\Sailor;
use App\Core\Component\Message\Port\SailorStoreInterface;
use App\Core\SharedKernel\Application\CommandHandlerInterface;
use App\Core\SharedKernel\Domain\Exception\ResourceNotFoundException;
use App\Core\SharedKernel\Port\EventDispatcherInterface;

class DeleteSailorHandler implements CommandHandlerInterface
{
    private SailorStoreInterface $sailorStore;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(SailorStoreInterface $sailorStore, EventDispatcherInterface $eventDispatcher)
    {
        $this->sailorStore = $sailorStore;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(DeleteSailorCommand $command): DeleteSailorResponse
    {
        $id = $this->sailorStore->findIdWithEmailAndNotDeleted($command->email);

        if (null === $id) {
            throw ResourceNotFoundException::createResourceNotFoundWithPropertyException(Sailor::class, 'email', $command->email);
        }

        $sailor = $this->sailorStore->load($id);

        if (null === $sailor) {
            throw new \RuntimeException('Sailor cannot be null. The previous query returned an invalid id.');
        }

        $sailor->delete();
        $this->sailorStore->store($sailor);
        $this->eventDispatcher->dispatch($sailor->getUncommittedEventRecords());

        return DeleteSailorResponse::createFromSailor($sailor);
    }
}
