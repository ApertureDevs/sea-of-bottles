<?php

namespace App\Core\Component\Message\Application\DeleteSailor;

use App\Core\SharedKernel\Application\CommandInterface;

class DeleteSailorCommand implements CommandInterface
{
    public string $email;
    public string $deleteIp;

    public static function create(string $email, string $deleteIp): self
    {
        $command = new self();
        $command->email = $email;
        $command->deleteIp = $deleteIp;

        return $command;
    }
}
