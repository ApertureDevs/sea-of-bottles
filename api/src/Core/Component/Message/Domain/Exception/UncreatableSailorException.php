<?php

namespace App\Core\Component\Message\Domain\Exception;

use App\Core\SharedKernel\Domain\Exception\DomainException;

class UncreatableSailorException extends DomainException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function createAlreadyCreatedException(string $email): self
    {
        $message = sprintf('Sailor with email "%s" already exists.', $email);

        return new self($message);
    }
}
