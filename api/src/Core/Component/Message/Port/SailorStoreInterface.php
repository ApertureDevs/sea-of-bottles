<?php

namespace App\Core\Component\Message\Port;

use App\Core\Component\Message\Domain\Model\Sailor;
use App\Core\SharedKernel\Domain\Model\Email;
use App\Core\SharedKernel\Domain\Model\Ip;

interface SailorStoreInterface
{
    public function store(Sailor $sailor): void;

    public function load(string $id): ?Sailor;

    public function findIdWithEmailAndNotDeleted(Email $email): ?string;

    /**
     * @return array<string>
     */
    public function findIdsActive(): array;

    public function getCreatedBetweenDatesCount(Ip $createIp, \DateTimeImmutable $start, \DateTimeImmutable $end): int;

    public function getDeletedBetweenDatesCount(Ip $deleteIp, \DateTimeImmutable $start, \DateTimeImmutable $end): int;
}
