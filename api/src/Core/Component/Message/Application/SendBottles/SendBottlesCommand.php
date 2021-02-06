<?php

namespace App\Core\Component\Message\Application\SendBottles;

use App\Core\SharedKernel\Application\CommandInterface;

class SendBottlesCommand implements CommandInterface
{
    public static function create(): self
    {
        return new self();
    }
}
