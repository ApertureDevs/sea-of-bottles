<?php

namespace App\Core\SharedKernel\Application;

use App\Core\SharedKernel\Domain\Event\EventRecord;

interface EventHandlerInterface
{
    public function support(EventRecord $eventRecord): bool;
}
