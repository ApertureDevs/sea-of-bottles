<?php

namespace App\Infrastructure\Representation\Model\RelationalModel;

class Sailor implements EntityInterface
{
    private string $id;
    private string $email;
    private \DateTimeImmutable $createDate;

    private function __construct(string $id, string $email, \DateTimeImmutable $createDate)
    {
        $this->id = $id;
        $this->email = $email;
        $this->createDate = $createDate;
    }

    public static function create(string $id, string $email, \DateTimeImmutable $createDate): Sailor
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
