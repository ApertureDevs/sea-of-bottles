<?php

namespace App\Core\Component\Message\Application\DeleteSailor;

use App\Core\SharedKernel\Application\CommandInterface;

class DeleteSailorCommand implements CommandInterface
{
    public string $email;

    public static function create(string $email): self
    {
        $command = new self();
        $command->email = $email;

        return $command;
    }
}
