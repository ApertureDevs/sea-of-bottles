<?php

namespace App\Core\SharedKernel\Domain\Model;

use App\Core\SharedKernel\Domain\Exception\InvalidIpException;

class Ip
{
    private string $ip;

    private function __construct(string $ip)
    {
        $ip = trim($ip, ' ');

        if (false === \filter_var($ip, FILTER_VALIDATE_IP)) {
            throw InvalidIpException::createInvalidFormatException();
        }

        $this->ip = $ip;
    }

    public static function create(string $ip): Ip
    {
        return new self($ip);
    }

    public function getAddress(): string
    {
        return $this->ip;
    }
}
