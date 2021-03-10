<?php

namespace App\Tests;

use App\Core\SharedKernel\Port\ClockInterface;

class LockedClock implements ClockInterface
{
    private \DateTimeImmutable $lockedDate;

    private function __construct(\DateTimeImmutable $lockedDate)
    {
        $this->lockedDate = $lockedDate;
    }

    public static function create(\DateTimeImmutable $lockDate): self
    {
        return new self($lockDate);
    }

    public function now(): \DateTimeImmutable
    {
        return $this->lockedDate;
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
