<?php

namespace App\Core\SharedKernel\Port;

interface ClockInterface
{
    public function now(): \DateTimeImmutable;

    public function today(): \DateTimeImmutable;

    public function tomorrow(): \DateTimeImmutable;
}
