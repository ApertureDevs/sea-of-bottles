<?php

namespace App\Infrastructure\Representation\Model\RelationalModel;

class Bottle implements EntityInterface
{
    private string $id;
    private string $message;
    private string $createIp;
    private \DateTimeImmutable $createDate;
    private ?\DateTimeImmutable $receiveDate;
    private ?Sailor $receiver;

    private function __construct(string $id, string $message, string $createIp, \DateTimeImmutable $createDate)
    {
        $this->id = $id;
        $this->message = $message;
        $this->createIp = $createIp;
        $this->createDate = $createDate;
    }

    public static function create(string $id, string $message, string $createIp, \DateTimeImmutable $createDate): Bottle
    {
        return new self($id, $message, $createIp, $createDate);
    }

    public function receive(Sailor $receiver, \DateTimeImmutable $receiveDate): void
    {
        $this->receiver = $receiver;
        $this->receiveDate = $receiveDate;
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

    public function getReceiveDate(): ?\DateTimeImmutable
    {
        return $this->receiveDate;
    }

    public function getReceiver(): ?Sailor
    {
        return $this->receiver;
    }
}
