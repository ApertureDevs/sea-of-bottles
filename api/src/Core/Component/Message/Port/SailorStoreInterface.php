<?php

namespace App\Core\Component\Message\Port;

use App\Core\Component\Message\Domain\Model\Sailor;
use App\Core\SharedKernel\Domain\Model\Email;

interface SailorStoreInterface
{
    public function store(Sailor $sailor): void;

    public function load(string $id): ?Sailor;

    public function findIdWithEmailAndNotDeleted(Email $email): ?string;

    /**
     * @return array<string>
     */
    public function findIdsActive(): array;
}
