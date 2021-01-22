<?php

namespace App\Core\Component\Message\Application\CreateSailor;

use App\Core\Component\Message\Domain\Aggregate\Sailor;
use App\Core\SharedKernel\Application\CommandResponseInterface;

class CreateSailorResponse implements CommandResponseInterface
{
    public string $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function createFromSailor(Sailor $sailor): CreateSailorResponse
    {
        return new self($sailor->getId());
    }
}
