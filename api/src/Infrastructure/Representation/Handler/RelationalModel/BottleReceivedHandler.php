<?php

namespace App\Infrastructure\Representation\Handler\RelationalModel;

use App\Core\SharedKernel\Application\EventHandlerInterface;
use App\Core\SharedKernel\Domain\Event\EventRecord;
use App\Core\SharedKernel\Domain\Event\Message\BottleReceived;
use App\Infrastructure\Persistence\RelationalModel\Repository\BottleRepository;
use App\Infrastructure\Persistence\RelationalModel\Repository\SailorRepository;
use App\Infrastructure\Representation\Model\RelationalModel\Bottle;
use App\Infrastructure\Representation\Model\RelationalModel\Sailor;

class BottleReceivedHandler implements EventHandlerInterface
{
    private BottleRepository $bottleRepository;
    private SailorRepository $sailorRepository;

    public function __construct(BottleRepository $bottleRepository, SailorRepository $sailorRepository)
    {
        $this->bottleRepository = $bottleRepository;
        $this->sailorRepository = $sailorRepository;
    }

    public function __invoke(EventRecord $eventRecord): void
    {
        if (!$this->support($eventRecord)) {
            return;
        }

        $event = $eventRecord->getEvent();

        if (!$event instanceof BottleReceived) {
            throw new \RuntimeException('Unsupported event.');
        }

        $bottle = $this->bottleRepository->findById($eventRecord->getAggregateId());

        if (!$bottle instanceof Bottle) {
            throw new \RuntimeException("Bottle with id \"{$eventRecord->getAggregateId()}\" doesn't exist.");
        }

        $sailor = $this->sailorRepository->findById($event->getReceiverId());

        if (!$sailor instanceof Sailor) {
            throw new \RuntimeException("Sailor with id \"{$event->getReceiverId()}\" doesn't exist.");
        }

        $bottle->receive($sailor, $event->getReceiveDate());

        $this->bottleRepository->save($bottle);
    }

    public function support(EventRecord $eventRecord): bool
    {
        $event = $eventRecord->getEvent();

        return $event instanceof BottleReceived;
    }
}
