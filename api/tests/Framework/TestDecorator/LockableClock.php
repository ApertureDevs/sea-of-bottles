<?php

namespace App\Tests\Framework\TestDecorator;

use App\Core\SharedKernel\Port\ClockInterface;
use App\Infrastructure\Time\Clock;

class LockableClock implements ClockInterface
{
    private Clock $clock;
    private ?\DateTimeImmutable $lockedDate = null;

    public function __construct(Clock $clock)
    {
        $this->clock = $clock;
    }

    public function lock(\DateTimeImmutable $lockDate): void
    {
        $this->lockedDate = $lockDate;
    }

    public function unlock(): void
    {
        $this->lockedDate = null;
    }

    public function now(): \DateTimeImmutable
    {
        if (null !== $this->lockedDate) {
            return $this->lockedDate;
        }

        return $this->clock->now();
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
