<?php

namespace App\Infrastructure\Representation\Projector\RelationalModel\Handler;

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
        $event = $eventRecord->getEvent();

        if (!$event instanceof SailorDeleted) {
            return;
        }

        $sailor = $this->sailorRepository->getEntity($eventRecord->getAggregateId());

        if (null === $sailor) {
            throw new \RuntimeException("Sailor with id \"{$eventRecord->getAggregateId()}\" doesn't exist.");
        }

        $this->sailorRepository->remove($sailor);
    }
}
