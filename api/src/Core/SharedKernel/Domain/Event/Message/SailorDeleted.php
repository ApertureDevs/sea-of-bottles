<?php

namespace App\Core\SharedKernel\Domain\Event\Message;

use App\Core\SharedKernel\Domain\Event\Event;

class SailorDeleted implements Event
{
    private \DateTimeImmutable $deleteDate;

    public function __construct(\DateTimeImmutable $deleteDate)
    {
        $this->deleteDate = $deleteDate;
    }

    public static function create(\DateTimeImmutable $deleteDate): self
    {
        return new self($deleteDate);
    }

    public function getDeleteDate(): \DateTimeImmutable
    {
        return $this->deleteDate;
    }
}
