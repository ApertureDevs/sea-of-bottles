<?php

namespace App\Core\Component\Message\Application\CreateSailor;

use App\Core\SharedKernel\Application\CommandInterface;

class CreateSailorCommand implements CommandInterface
{
    public string $email;

    public static function create(string $email): self
    {
        $command = new self();
        $command->email = $email;

        return $command;
    }
}
