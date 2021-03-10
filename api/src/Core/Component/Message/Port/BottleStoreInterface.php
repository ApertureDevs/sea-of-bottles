<?php

namespace App\Core\Component\Message\Port;

use App\Core\Component\Message\Domain\Model\Bottle;
use App\Core\SharedKernel\Domain\Model\Ip;

interface BottleStoreInterface
{
    public function store(Bottle $bottle): void;

    public function load(string $id): ?Bottle;

    /**
     * @return array<string>
     */
    public function findIdsNotReceived(): array;

    public function getCreatedBetweenDatesCount(Ip $createIp, \DateTimeImmutable $start, \DateTimeImmutable $end): int;
}
