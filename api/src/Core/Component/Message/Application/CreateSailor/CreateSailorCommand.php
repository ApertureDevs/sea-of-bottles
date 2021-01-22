<?php

namespace App\Core\Component\Message\Application\CreateSailor;

use App\Core\SharedKernel\Application\CommandInterface;

class CreateSailorCommand implements CommandInterface
{
    public string $email;
}
