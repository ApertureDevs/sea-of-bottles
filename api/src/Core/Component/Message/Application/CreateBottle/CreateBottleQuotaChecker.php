<?php

namespace App\Core\Component\Message\Application\CreateBottle;

use App\Core\Component\Message\Domain\Exception\QuotaException;
use App\Core\Component\Message\Port\BottleStoreInterface;
use App\Core\SharedKernel\Domain\Model\Ip;
use App\Core\SharedKernel\Port\ClockInterface;

class CreateBottleQuotaChecker
{
    private BottleStoreInterface $bottleStore;
    private ClockInterface $clock;

    public function __construct(
        BottleStoreInterface $bottleStore,
        ClockInterface $clock
    ) {
        $this->bottleStore = $bottleStore;
        $this->clock = $clock;
    }

    private function isLimitReached(Ip $ip): bool
    {
        return $this->bottleStore->getCreatedBetweenDatesCount($ip, $this->clock->today(), $this->clock->tomorrow()) >= 5;
    }

    public function check(Ip $ip): void
    {
        if ($this->isLimitReached($ip)) {
            throw QuotaException::createExceededBottleCreationLimit();
        }
    }
}
