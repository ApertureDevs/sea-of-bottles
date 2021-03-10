<?php

namespace App\Core\Component\Message\Domain\Exception;

use App\Core\SharedKernel\Domain\Exception\DomainException;

class QuotaException extends DomainException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function createExceededBottleCreationLimit(): self
    {
        $message = 'Bottle creation limit reached, you must wait before create a new Bottle.';

        return new self($message);
    }

    public static function createExceededSailorCreationLimit(): self
    {
        $message = 'Sailor creation limit reached, you must wait before create a new Sailor.';

        return new self($message);
    }

    public static function createExceededSailorDeletionLimit(): self
    {
        $message = 'Sailor deletion limit reached, you must wait before delete a new Sailor.';

        return new self($message);
    }
}
