<?php

namespace App\Core\Component\Message\Domain\Exception;

use App\Core\SharedKernel\Domain\Exception\DomainException;

class UnreceivableBottleException extends DomainException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function createAlreadyReceivedException(string $bottleId): self
    {
        $message = sprintf('Bottle with id "%s" already received.', $bottleId);

        return new self($message);
    }
}
