<?php

namespace App\Core\SharedKernel\Domain\Exception;

class InvalidIpException extends DomainException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function createInvalidFormatException(): self
    {
        $message = sprintf('Ip format invalid.');

        return new self($message);
    }
}
