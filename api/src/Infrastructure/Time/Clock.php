<?php

namespace App\Infrastructure\Time;

use App\Core\SharedKernel\Port\ClockInterface;

class Clock implements ClockInterface
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }

    public function today(): \DateTimeImmutable
    {
        return $this->now()->setTime(0, 0);
    }

    public function tomorrow(): \DateTimeImmutable
    {
        return $this->today()->modify('+1 day');
    }
}
