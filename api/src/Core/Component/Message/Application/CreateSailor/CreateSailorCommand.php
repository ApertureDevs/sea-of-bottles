<?php

namespace App\Core\Component\Message\Application\CreateSailor;

use App\Core\SharedKernel\Application\CommandInterface;

class CreateSailorCommand implements CommandInterface
{
    public string $email;
    public string $createIp;

    public static function create(string $email, string $createIp): self
    {
        $command = new self();
        $command->email = $email;
        $command->createIp = $createIp;

        return $command;
    }
}
