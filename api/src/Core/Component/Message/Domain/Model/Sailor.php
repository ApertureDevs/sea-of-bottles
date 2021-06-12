<?php

namespace App\Core\Component\Message\Domain\Model;

use App\Core\Component\Message\Domain\Exception\UndeletableSailorException;
use App\Core\SharedKernel\Domain\Event\Aggregate;
use App\Core\SharedKernel\Domain\Event\Message\SailorCreated;
use App\Core\SharedKernel\Domain\Event\Message\SailorDeleted;
use App\Core\SharedKernel\Domain\Model\Email;
use App\Core\SharedKernel\Domain\Model\Ip;
use App\Core\SharedKernel\Port\ClockInterface;

class Sailor extends Aggregate
{
    private string $id;
    private Email $email;
    private Ip $createIp;
    private \DateTimeInterface $createDate;
    private ?Ip $deleteIp = null;
    private ?\DateTimeInterface $deleteDate = null;

    public static function create(string $email, string $createIp, ClockInterface $clock): Sailor
    {
        $sailor = new Sailor();
        $id = uuid_create(UUID_TYPE_RANDOM);

        if (!is_string($id)) {
            throw new \RuntimeException('Sailor id cannot be null.');
        }

        $email = Email::create($email);
        $createIp = Ip::create($createIp);
        $sailor->apply(SailorCreated::create($id, $email->getAddress(), $createIp->getAddress(), $clock->now()));

        return $sailor;
    }

    public function delete(string $deleteIp, ClockInterface $clock): void
    {
        if ($this->isDelete()) {
            throw UndeletableSailorException::createAlreadyDeletedException($this->id);
        }

        $deleteIp = Ip::create($deleteIp);

        $this->apply(SailorDeleted::create($deleteIp->getAddress(), $clock->now()));
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getCreateIp(): Ip
    {
        return $this->createIp;
    }

    public function getCreateDate(): \DateTimeInterface
    {
        return $this->createDate;
    }

    public function getDeleteIp(): ?Ip
    {
        return $this->deleteIp;
    }

    public function getDeleteDate(): ?\DateTimeInterface
    {
        return $this->deleteDate;
    }

    public function isDelete(): bool
    {
        return null !== $this->deleteDate;
    }

    public function isActive(): bool
    {
        return !$this->isDelete();
    }

    protected function applySailorCreated(SailorCreated $event): void
    {
        $this->id = $event->getId();
        $this->email = Email::create($event->getEmail());
        $this->createIp = Ip::create($event->getCreateIp());
        $this->createDate = $event->getCreateDate();
    }

    protected function applySailorDeleted(SailorDeleted $event): void
    {
        $this->deleteIp = Ip::create($event->getDeleteIp());
        $this->deleteDate = $event->getDeleteDate();
    }
}
