<?php

namespace App\Core\Component\Message\Domain\Model;

use App\Core\Component\Message\Domain\Exception\UnreceivableBottleException;
use App\Core\SharedKernel\Domain\Event\Aggregate;
use App\Core\SharedKernel\Domain\Event\Message\BottleCreated;
use App\Core\SharedKernel\Domain\Event\Message\BottleReceived;

class Bottle extends Aggregate
{
    private string $id;
    private Message $message;
    private \DateTimeInterface $createDate;
    private ?\DateTimeInterface $receiveDate = null;
    private ?string $receiver = null;

    public static function create(string $message): Bottle
    {
        $bottle = new Bottle();
        $id = uuid_create(UUID_TYPE_RANDOM);

        if (null === $id) {
            throw new \RuntimeException('Bottle id cannot be null.');
        }

        $message = Message::create($message);

        $bottle->apply(BottleCreated::create($id, $message->getContent(), new \DateTimeImmutable()));

        return $bottle;
    }

    public function receive(Sailor $receiver): void
    {
        if ($this->wasReceived()) {
            throw UnreceivableBottleException::createAlreadyReceivedException($this->id);
        }

        $this->apply(BottleReceived::create($receiver->getId(), new \DateTimeImmutable()));
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    public function getCreateDate(): \DateTimeInterface
    {
        return $this->createDate;
    }

    public function wasReceived(): bool
    {
        return null !== $this->receiveDate;
    }

    public function getReceiveDate(): ?\DateTimeInterface
    {
        return $this->receiveDate;
    }

    public function getReceiver(): ?string
    {
        return $this->receiver;
    }

    public function isReceive(): bool
    {
        return null !== $this->receiver;
    }

    protected function applyBottleCreated(BottleCreated $event): void
    {
        $this->id = $event->getId();
        $this->message = Message::create($event->getMessage());
        $this->createDate = $event->getCreateDate();
    }

    protected function applyBottleReceived(BottleReceived $event): void
    {
        $this->receiver = $event->getReceiverId();
        $this->receiveDate = $event->getReceiveDate();
    }
}
