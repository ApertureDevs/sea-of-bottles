<?php

namespace App\Core\Component\Message\Domain\Exception;

use App\Core\SharedKernel\Domain\Exception\DomainException;

class UndeletableSailorException extends DomainException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function createAlreadyDeletedException(string $id): self
    {
        $message = sprintf('Sailor with id "%s" already deleted.', $id);

        return new self($message);
    }
}
