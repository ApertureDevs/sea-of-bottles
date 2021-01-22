<?php

namespace App\Core\SharedKernel\Domain\Event\Message;

use App\Core\SharedKernel\Domain\Event\Event;

class BottleReceived implements Event
{
    private string $receiverId;
    private \DateTimeImmutable $receiveDate;

    public function __construct(string $receiverId, \DateTimeImmutable $receiveDate)
    {
        $this->receiverId = $receiverId;
        $this->receiveDate = $receiveDate;
    }

    public static function create(string $receiverId, \DateTimeImmutable $receiveDate): self
    {
        return new self($receiverId, $receiveDate);
    }

    public function getReceiverId(): string
    {
        return $this->receiverId;
    }

    public function getReceiveDate(): \DateTimeImmutable
    {
        return $this->receiveDate;
    }
}
