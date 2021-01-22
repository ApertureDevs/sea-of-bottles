<?php

namespace App\Infrastructure\Representation\Projector\RelationalModel\Handler;

use App\Core\SharedKernel\Application\EventHandlerInterface;
use App\Core\SharedKernel\Domain\Event\EventRecord;
use App\Core\SharedKernel\Domain\Event\Message\BottleCreated;
use App\Infrastructure\Persistence\RelationalModel\Repository\BottleRepository;
use App\Infrastructure\Representation\Projector\RelationalModel\Model\Bottle;

class BottleCreatedHandler implements EventHandlerInterface
{
    private BottleRepository $bottleRepository;

    public function __construct(BottleRepository $bottleRepository)
    {
        $this->bottleRepository = $bottleRepository;
    }

    public function __invoke(EventRecord $eventRecord): void
    {
        $event = $eventRecord->getEvent();

        if (!$event instanceof BottleCreated) {
            return;
        }

        $bottle = Bottle::create($event->getId(), $event->getMessage(), $event->getCreateDate());
        $this->bottleRepository->save($bottle);
    }
}
