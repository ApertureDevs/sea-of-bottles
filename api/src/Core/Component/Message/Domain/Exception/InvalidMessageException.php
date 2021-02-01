<?php

namespace App\Core\Component\Message\Domain\Exception;

use App\Core\SharedKernel\Domain\Exception\DomainException;

class InvalidMessageException extends DomainException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function createEmptyContentException(): self
    {
        $message = 'Message content cannot be empty.';

        return new self($message);
    }

    public static function createExceededContentLengthLimitException(int $limit): self
    {
        $message = sprintf('Message content length limit (%s characters) exceeded.', $limit);

        return new self($message);
    }
}
