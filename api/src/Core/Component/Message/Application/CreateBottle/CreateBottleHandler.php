<?php

namespace App\Core\Component\Message\Application\CreateBottle;

use App\Core\Component\Message\Domain\Model\Bottle;
use App\Core\Component\Message\Port\BottleStoreInterface;
use App\Core\SharedKernel\Application\CommandHandlerInterface;
use App\Core\SharedKernel\Domain\Model\Ip;
use App\Core\SharedKernel\Port\ClockInterface;
use App\Core\SharedKernel\Port\EventDispatcherInterface;

class CreateBottleHandler implements CommandHandlerInterface
{
    private BottleStoreInterface $bottleStore;
    private EventDispatcherInterface $eventDispatcher;
    private CreateBottleQuotaChecker $quotaChecker;
    private ClockInterface $clock;

    public function __construct(
        BottleStoreInterface $bottleStore,
        EventDispatcherInterface $eventDispatcher,
        CreateBottleQuotaChecker $quotaChecker,
        ClockInterface $clock
    ) {
        $this->bottleStore = $bottleStore;
        $this->eventDispatcher = $eventDispatcher;
        $this->quotaChecker = $quotaChecker;
        $this->clock = $clock;
    }

    public function __invoke(CreateBottleCommand $command): CreateBottleResponse
    {
        $this->quotaChecker->check(Ip::create($command->createIp));
        $bottle = Bottle::create($command->message, $command->createIp, $this->clock);
        $eventRecords = $bottle->getUncommittedEventRecords();
        $this->bottleStore->store($bottle);
        $this->eventDispatcher->dispatch($eventRecords);

        return CreateBottleResponse::createFromBottle($bottle);
    }
}
