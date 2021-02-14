<?php

namespace App\Core\Component\Message\Application\SendBottles;

use App\Core\Component\Message\Port\BottleStoreInterface;
use App\Core\Component\Message\Port\MailerInterface;
use App\Core\Component\Message\Port\SailorStoreInterface;
use App\Core\SharedKernel\Application\CommandHandlerInterface;
use App\Core\SharedKernel\Port\EventDispatcherInterface;

class SendBottlesHandler implements CommandHandlerInterface
{
    private BottleStoreInterface $bottleStore;
    private SailorStoreInterface $sailorStore;
    private EventDispatcherInterface $eventDispatcher;
    private MailerInterface $mailer;

    public function __construct(
        BottleStoreInterface $bottleStore,
        SailorStoreInterface $sailorStore,
        EventDispatcherInterface $eventDispatcher,
        MailerInterface $mailer
    ) {
        $this->bottleStore = $bottleStore;
        $this->sailorStore = $sailorStore;
        $this->eventDispatcher = $eventDispatcher;
        $this->mailer = $mailer;
    }

    public function __invoke(SendBottlesCommand $command): SendBottlesResponse
    {
        $bottleNotReceivedIds = $this->bottleStore->findIdsNotReceived();
        $sailorActiveIds = $this->sailorStore->findIdsActive();
        $bottlesSentCount = 0;

        if (0 === count($sailorActiveIds)) {
            return SendBottlesResponse::create($bottlesSentCount);
        }

        foreach ($bottleNotReceivedIds as $bottleId) {
            if ($this->isLotteryWinner()) {
                $bottle = $this->bottleStore->load($bottleId);

                if (null === $bottle) {
                    throw new \RuntimeException('Bottle found cannot be loaded.');
                }

                $sailor = $this->sailorStore->load($this->getRandomSailorId($sailorActiveIds));

                if (null === $sailor) {
                    throw new \RuntimeException('Sailor found cannot be loaded.');
                }

                $this->mailer->sendBottleReceivedNotification($sailor, $bottle);
                $bottle->receive($sailor);
                $eventRecords = $bottle->getUncommittedEventRecords();
                $this->bottleStore->store($bottle);
                $this->eventDispatcher->dispatch($eventRecords);
                ++$bottlesSentCount;
            }
        }

        return SendBottlesResponse::create($bottlesSentCount);
    }

    private function isLotteryWinner(): bool
    {
        return 0 === rand(0, 365);
    }

    /**
     * @param array<string> $sailorIds
     */
    private function getRandomSailorId(array $sailorIds): string
    {
        $id = $sailorIds[array_rand($sailorIds)];

        if (!is_string($id)) {
            throw new \RuntimeException('Id should be a string.');
        }

        return $id;
    }
}
