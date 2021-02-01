<?php

namespace App\Core\Component\Message\Port;

use App\Core\Component\Message\Domain\Model\Bottle;

interface BottleStoreInterface
{
    public function store(Bottle $bottle): void;

    public function load(string $id): ?Bottle;
}
