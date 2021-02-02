<?php

namespace App\Core\Component\Message\Domain\Exception;

use App\Core\SharedKernel\Domain\Exception\DomainException;
use App\Core\SharedKernel\Domain\Model\Email;

class UncreatableSailorException extends DomainException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function createAlreadyCreatedException(Email $email): self
    {
        $message = sprintf('Sailor with email "%s" already exists.', $email->getAddress());

        return new self($message);
    }
}
