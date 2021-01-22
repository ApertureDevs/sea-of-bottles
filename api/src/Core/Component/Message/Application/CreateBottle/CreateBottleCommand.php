<?php

namespace App\Core\Component\Message\Application\CreateBottle;

use App\Core\SharedKernel\Application\CommandInterface;

class CreateBottleCommand implements CommandInterface
{
    public string $message;
}
