<?php

namespace App\Core\SharedKernel\Domain\Event\Message;

use App\Core\SharedKernel\Domain\Event\Event;

class BottleCreated implements Event
{
    private string $id;
    private string $message;
    private string $createIp;
    private \DateTimeImmutable $createDate;

    public function __construct(string $id, string $message, string $createIp, \DateTimeImmutable $createDate)
    {
        $this->id = $id;
        $this->message = $message;
        $this->createIp = $createIp;
        $this->createDate = $createDate;
    }

    public static function create(string $id, string $message, string $createIp, \DateTimeImmutable $createDate): self
    {
        return new self($id, $message, $createIp, $createDate);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCreateIp(): string
    {
        return $this->createIp;
    }

    public function getCreateDate(): \DateTimeImmutable
    {
        return $this->createDate;
    }
}
