<?php

namespace App\Core\Component\Message\Application\SendBottles;

use App\Core\SharedKernel\Application\CommandResponseInterface;

class SendBottlesResponse implements CommandResponseInterface
{
    public int $bottlesSentCount;

    private function __construct(int $bottlesSentCount)
    {
        $this->bottlesSentCount = $bottlesSentCount;
    }

    public static function create(int $bottlesSentCount): SendBottlesResponse
    {
        return new self($bottlesSentCount);
    }
}
