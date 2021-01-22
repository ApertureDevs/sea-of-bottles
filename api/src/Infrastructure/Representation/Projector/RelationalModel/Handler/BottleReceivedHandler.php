<?php

namespace App\Infrastructure\Representation\Projector\RelationalModel\Handler;

use App\Core\SharedKernel\Application\EventHandlerInterface;
use App\Core\SharedKernel\Domain\Event\EventRecord;
use App\Core\SharedKernel\Domain\Event\Message\BottleReceived;
use App\Infrastructure\Persistence\RelationalModel\Repository\BottleRepository;
use App\Infrastructure\Persistence\RelationalModel\Repository\SailorRepository;
use App\Infrastructure\Representation\Projector\RelationalModel\Model\Bottle;
use App\Infrastructure\Representation\Projector\RelationalModel\Model\Sailor;

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
        $event = $eventRecord->getEvent();

        if (!$event instanceof BottleReceived) {
            return;
        }

        $bottle = $this->bottleRepository->getEntity($eventRecord->getAggregateId());

        if (!$bottle instanceof Bottle) {
            throw new \RuntimeException("Bottle with id \"{$eventRecord->getAggregateId()}\" doesn't exist.");
        }

        $sailor = $this->sailorRepository->getEntity($event->getReceiverId());

        if (!$sailor instanceof Sailor) {
            throw new \RuntimeException("Sailor with id \"{$event->getReceiverId()}\" doesn't exist.");
        }

        $bottle->receive($sailor, $event->getReceiveDate());

        $this->bottleRepository->save($bottle);
    }
}
