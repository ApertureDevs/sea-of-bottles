<?php

namespace App\Core\Component\Message\Application\DeleteSailor;

use App\Core\Component\Message\Domain\Model\Sailor;
use App\Core\SharedKernel\Application\CommandResponseInterface;

class DeleteSailorResponse implements CommandResponseInterface
{
    public string $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function createFromSailor(Sailor $sailor): DeleteSailorResponse
    {
        return new self($sailor->getId());
    }
}
