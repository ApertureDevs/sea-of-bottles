<?php

namespace App\Infrastructure\Exception;

use App\Core\SharedKernel\Domain\Exception\DomainException;
use App\Core\SharedKernel\Domain\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Error
{
    private string $title;
    private string $description;
    private int $status;

    private function __construct(string $title, string $description, int $status)
    {
        $this->description = $description;
        $this->status = $status;
        $this->title = $title;
    }

    public static function createFromThrowable(\Throwable $throwable): self
    {
        if ($throwable instanceof DomainException) {
            return new self('Domain Error', $throwable->getMessage(), 400);
        }

        if ($throwable instanceof BadRequestHttpException) {
            return new self('Invalid Request', $throwable->getMessage(), 400);
        }

        if ($throwable instanceof ResourceNotFoundException) {
            return new self('Resource Not Found', $throwable->getMessage(), 404);
        }

        return new self('Unexpected Error', 'An error occur on the server.', 500);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): int
    {
        return $this->status;
    }
}
