<?php

namespace App\Infrastructure\Representation\Handler\RelationalModel;

use App\Core\SharedKernel\Application\EventHandlerInterface;
use App\Core\SharedKernel\Domain\Event\EventRecord;
use App\Core\SharedKernel\Domain\Event\Message\SailorCreated;
use App\Infrastructure\Persistence\RelationalModel\Repository\SailorRepository;
use App\Infrastructure\Representation\Model\RelationalModel\Sailor;

class SailorCreatedHandler implements EventHandlerInterface
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

        if (!$event instanceof SailorCreated) {
            throw new \RuntimeException('Unsupported event.');
        }

        $sailor = Sailor::create($event->getId(), $event->getEmail(), $event->getCreateDate());
        $this->sailorRepository->save($sailor);
    }

    public function support(EventRecord $eventRecord): bool
    {
        $event = $eventRecord->getEvent();

        return $event instanceof SailorCreated;
    }
}
