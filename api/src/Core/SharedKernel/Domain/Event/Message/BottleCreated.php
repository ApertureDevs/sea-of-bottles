<?php

namespace App\Core\SharedKernel\Domain\Event\Message;

use App\Core\SharedKernel\Domain\Event\Event;

class BottleCreated implements Event
{
    private string $id;
    private string $message;
    private \DateTimeImmutable $createDate;

    public function __construct(string $id, string $message, \DateTimeImmutable $createDate)
    {
        $this->id = $id;
        $this->message = $message;
        $this->createDate = $createDate;
    }

    public static function create(string $id, string $message, \DateTimeImmutable $createDate): self
    {
        return new self($id, $message, $createDate);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCreateDate(): \DateTimeImmutable
    {
        return $this->createDate;
    }
}
