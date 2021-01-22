<?php

namespace App\Core\SharedKernel\Port;

use App\Core\SharedKernel\Domain\Event\EventRecords;

interface EventDispatcherInterface
{
    public function dispatch(EventRecords $eventRecords): void;
}
