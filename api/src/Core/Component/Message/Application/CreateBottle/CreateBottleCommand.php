<?php

namespace App\Core\Component\Message\Application\CreateBottle;

use App\Core\SharedKernel\Application\CommandInterface;

class CreateBottleCommand implements CommandInterface
{
    public string $message;
    public string $createIp;

    public static function create(string $message, string $createIp): self
    {
        $command = new self();
        $command->message = $message;
        $command->createIp = $createIp;

        return $command;
    }
}
