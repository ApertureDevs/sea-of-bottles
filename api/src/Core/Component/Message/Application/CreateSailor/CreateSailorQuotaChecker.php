<?php

namespace App\Core\Component\Message\Application\CreateSailor;

use App\Core\Component\Message\Domain\Exception\QuotaException;
use App\Core\Component\Message\Port\SailorStoreInterface;
use App\Core\SharedKernel\Domain\Model\Ip;
use App\Core\SharedKernel\Port\ClockInterface;

class CreateSailorQuotaChecker
{
    private SailorStoreInterface $sailorStore;
    private ClockInterface $clock;

    public function __construct(
        SailorStoreInterface $sailorStore,
        ClockInterface $clock
    ) {
        $this->sailorStore = $sailorStore;
        $this->clock = $clock;
    }

    private function isLimitReached(Ip $ip): bool
    {
        return $this->sailorStore->getCreatedBetweenDatesCount($ip, $this->clock->today(), $this->clock->tomorrow()) >= 5;
    }

    public function check(Ip $ip): void
    {
        if ($this->isLimitReached($ip)) {
            throw QuotaException::createExceededSailorCreationLimit();
        }
    }
}
