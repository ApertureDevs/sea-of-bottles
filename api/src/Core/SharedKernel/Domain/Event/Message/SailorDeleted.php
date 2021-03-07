<?php

namespace App\Core\SharedKernel\Domain\Event\Message;

use App\Core\SharedKernel\Domain\Event\Event;

class SailorDeleted implements Event
{
    private string $deleteIp;
    private \DateTimeImmutable $deleteDate;

    public function __construct(string $deleteIp, \DateTimeImmutable $deleteDate)
    {
        $this->deleteIp = $deleteIp;
        $this->deleteDate = $deleteDate;
    }

    public static function create(string $deleteIp, \DateTimeImmutable $deleteDate): self
    {
        return new self($deleteIp, $deleteDate);
    }

    public function getDeleteIp(): string
    {
        return $this->deleteIp;
    }

    public function getDeleteDate(): \DateTimeImmutable
    {
        return $this->deleteDate;
    }
}
