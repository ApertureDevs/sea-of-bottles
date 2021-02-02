<?php

namespace App\Core\SharedKernel\Domain\Model;

use App\Core\SharedKernel\Domain\Exception\InvalidEmailException;

class Email
{
    private const EMAIL_LIMIT = 50;
    private string $address;

    private function __construct(string $address)
    {
        $address = trim($address, ' ');

        if (strlen($address) > self::EMAIL_LIMIT) {
            throw InvalidEmailException::createExceededContentLengthLimitException(self::EMAIL_LIMIT);
        }

        if (false === \filter_var($address, FILTER_VALIDATE_EMAIL)) {
            throw InvalidEmailException::createInvalidFormatException();
        }

        $this->address = $address;
    }

    public static function create(string $email): Email
    {
        return new self($email);
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}
