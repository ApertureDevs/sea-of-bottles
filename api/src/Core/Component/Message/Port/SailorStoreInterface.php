<?php

namespace App\Core\Component\Message\Port;

use App\Core\Component\Message\Domain\Model\Sailor;

interface SailorStoreInterface
{
    public function store(Sailor $sailor): void;

    public function load(string $id): ?Sailor;

    public function findIdWithEmailAndNotDeleted(string $email): ?string;
}
