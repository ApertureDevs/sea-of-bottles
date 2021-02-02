<?php

namespace App\Infrastructure\Representation\Handler\RelationalModel;

use App\Core\SharedKernel\Application\EventHandlerInterface;
use App\Core\SharedKernel\Domain\Event\EventRecord;
use App\Core\SharedKernel\Domain\Event\Message\BottleCreated;
use App\Infrastructure\Persistence\RelationalModel\Repository\BottleRepository;
use App\Infrastructure\Representation\Model\RelationalModel\Bottle;

class BottleCreatedHandler implements EventHandlerInterface
{
    private BottleRepository $bottleRepository;

    public function __construct(BottleRepository $bottleRepository)
    {
        $this->bottleRepository = $bottleRepository;
    }

    public function __invoke(EventRecord $eventRecord): void
    {
        if (!$this->support($eventRecord)) {
            return;
        }

        $event = $eventRecord->getEvent();

        if (!$event instanceof BottleCreated) {
            throw new \RuntimeException('Unsupported event.');
        }

        $bottle = Bottle::create($event->getId(), $event->getMessage(), $event->getCreateDate());
        $this->bottleRepository->save($bottle);
    }

    public function support(EventRecord $eventRecord): bool
    {
        $event = $eventRecord->getEvent();

        return $event instanceof BottleCreated;
    }
}
