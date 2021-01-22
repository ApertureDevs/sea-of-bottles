<?php

namespace App\Core\SharedKernel\Domain\Event\Message;

use App\Core\SharedKernel\Domain\Event\Event;

class SailorCreated implements Event
{
    private string $id;
    private string $email;
    private \DateTimeImmutable $createDate;

    public function __construct(string $id, string $email, \DateTimeImmutable $createDate)
    {
        $this->id = $id;
        $this->email = $email;
        $this->createDate = $createDate;
    }

    public static function create(string $id, string $email, \DateTimeImmutable $createDate): self
    {
        return new self($id, $email, $createDate);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCreateDate(): \DateTimeImmutable
    {
        return $this->createDate;
    }
}
