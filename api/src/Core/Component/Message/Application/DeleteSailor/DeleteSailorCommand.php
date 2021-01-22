<?php

namespace App\Core\Component\Message\Application\DeleteSailor;

use App\Core\SharedKernel\Application\CommandInterface;

class DeleteSailorCommand implements CommandInterface
{
    public string $email;
}
