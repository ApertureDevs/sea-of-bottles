<?php

namespace App\Infrastructure\Representation\Model\RelationalModel;

class Sailor implements EntityInterface
{
    private string $id;
    private string $email;
    private string $createIp;
    private \DateTimeImmutable $createDate;

    private function __construct(string $id, string $email, string $createIp, \DateTimeImmutable $createDate)
    {
        $this->id = $id;
        $this->email = $email;
        $this->createIp = $createIp;
        $this->createDate = $createDate;
    }

    public static function create(string $id, string $email, string $createIp, \DateTimeImmutable $createDate): Sailor
    {
        return new self($id, $email, $createIp, $createDate);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
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
