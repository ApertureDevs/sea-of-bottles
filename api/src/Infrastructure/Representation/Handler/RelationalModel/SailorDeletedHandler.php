<?php

namespace App\Infrastructure\Representation\Handler\RelationalModel;

use App\Core\SharedKernel\Application\EventHandlerInterface;
use App\Core\SharedKernel\Domain\Event\EventRecord;
use App\Core\SharedKernel\Domain\Event\Message\SailorDeleted;
use App\Infrastructure\Persistence\RelationalModel\Repository\SailorRepository;

class SailorDeletedHandler implements EventHandlerInterface
{
    private SailorRepository $sailorRepository;

    public function __construct(SailorRepository $sailorRepository)
    {
        $this->sailorRepository = $sailorRepository;
    }

    public function __invoke(EventRecord $eventRecord): void
    {
        if (!$this->support($eventRecord)) {
            return;
        }

        $event = $eventRecord->getEvent();

        if (!$event instanceof SailorDeleted) {
            throw new \RuntimeException('Unsupported event.');
        }

        $sailor = $this->sailorRepository->findById($eventRecord->getAggregateId());

        if (null === $sailor) {
            throw new \RuntimeException("Sailor with id \"{$eventRecord->getAggregateId()}\" doesn't exist.");
        }

        $this->sailorRepository->remove($sailor);
    }

    public function support(EventRecord $eventRecord): bool
    {
        $event = $eventRecord->getEvent();

        return $event instanceof SailorDeleted;
    }
}
