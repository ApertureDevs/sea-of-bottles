<?php

namespace App\Core\SharedKernel\Domain\Exception;

class InvalidEmailException extends DomainException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function createExceededContentLengthLimitException(int $limit): self
    {
        $message = sprintf('Email length limit (%s characters) exceeded.', $limit);

        return new self($message);
    }

    public static function createInvalidFormatException(): self
    {
        $message = sprintf('Email format invalid.');

        return new self($message);
    }
}
