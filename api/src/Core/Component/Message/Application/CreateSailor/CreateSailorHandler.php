<?php

namespace App\Core\Component\Message\Application\CreateSailor;

use App\Core\Component\Message\Domain\Exception\UncreatableSailorException;
use App\Core\Component\Message\Domain\Model\Sailor;
use App\Core\Component\Message\Port\SailorStoreInterface;
use App\Core\SharedKernel\Application\CommandHandlerInterface;
use App\Core\SharedKernel\Domain\Model\Email;
use App\Core\SharedKernel\Domain\Model\Ip;
use App\Core\SharedKernel\Port\ClockInterface;
use App\Core\SharedKernel\Port\EventDispatcherInterface;

class CreateSailorHandler implements CommandHandlerInterface
{
    private SailorStoreInterface $sailorStore;
    private EventDispatcherInterface $eventDispatcher;
    private CreateSailorQuotaChecker $quotaChecker;
    private ClockInterface $clock;

    public function __construct(
        SailorStoreInterface $sailorStore,
        EventDispatcherInterface $eventDispatcher,
        CreateSailorQuotaChecker $quotaChecker,
        ClockInterface $clock
    ) {
        $this->sailorStore = $sailorStore;
        $this->eventDispatcher = $eventDispatcher;
        $this->quotaChecker = $quotaChecker;
        $this->clock = $clock;
    }

    public function __invoke(CreateSailorCommand $command): CreateSailorResponse
    {
        $this->quotaChecker->check(Ip::create($command->createIp));
        $email = Email::create($command->email);
        $id = $this->sailorStore->findIdWithEmailAndNotDeleted($email);

        if (null !== $id) {
            throw UncreatableSailorException::createAlreadyCreatedException($email);
        }

        $sailor = Sailor::create($command->email, $command->createIp, $this->clock);
        $eventRecords = $sailor->getUncommittedEventRecords();
        $this->sailorStore->store($sailor);
        $this->eventDispatcher->dispatch($eventRecords);

        return CreateSailorResponse::createFromSailor($sailor);
    }
}
