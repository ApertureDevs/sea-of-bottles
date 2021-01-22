<?php

namespace App\Infrastructure\Representation\Projector\RelationalModel\Handler;

use App\Core\SharedKernel\Application\EventHandlerInterface;
use App\Core\SharedKernel\Domain\Event\EventRecord;
use App\Core\SharedKernel\Domain\Event\Message\SailorCreated;
use App\Infrastructure\Persistence\RelationalModel\Repository\SailorRepository;
use App\Infrastructure\Representation\Projector\RelationalModel\Model\Sailor;

class SailorCreatedHandler implements EventHandlerInterface
{
    private SailorRepository $sailorRepository;

    public function __construct(SailorRepository $sailorRepository)
    {
        $this->sailorRepository = $sailorRepository;
    }

    public function __invoke(EventRecord $eventRecord): void
    {
        $event = $eventRecord->getEvent();

        if (!$event instanceof SailorCreated) {
            return;
        }

        $sailor = Sailor::create($event->getId(), $event->getEmail(), $event->getCreateDate());
        $this->sailorRepository->save($sailor);
    }
}
