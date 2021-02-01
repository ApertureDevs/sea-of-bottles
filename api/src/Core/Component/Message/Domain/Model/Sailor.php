<?php

namespace App\Core\Component\Message\Domain\Model;

use App\Core\Component\Message\Domain\Exception\UndeletableSailorException;
use App\Core\SharedKernel\Domain\Event\Aggregate;
use App\Core\SharedKernel\Domain\Event\Message\SailorCreated;
use App\Core\SharedKernel\Domain\Event\Message\SailorDeleted;
use App\Core\SharedKernel\Domain\Model\Email;

class Sailor extends Aggregate
{
    private string $id;
    private Email $email;
    private \DateTimeInterface $createDate;
    private ?\DateTimeInterface $deleteDate = null;

    public static function create(string $email): Sailor
    {
        $sailor = new Sailor();
        $id = uuid_create(UUID_TYPE_RANDOM);

        if (null === $id) {
            throw new \RuntimeException('Sailor id cannot be null.');
        }

        $email = Email::create($email);
        $sailor->apply(SailorCreated::create($id, $email->getAddress(), new \DateTimeImmutable()));

        return $sailor;
    }

    public function delete(): void
    {
        if ($this->isDelete()) {
            throw UndeletableSailorException::createAlreadyDeletedException($this->id);
        }

        $this->apply(SailorDeleted::create(new \DateTimeImmutable()));
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getCreateDate(): \DateTimeInterface
    {
        return $this->createDate;
    }

    public function getDeleteDate(): ?\DateTimeInterface
    {
        return $this->deleteDate;
    }

    public function isDelete(): bool
    {
        return null !== $this->deleteDate;
    }

    protected function applySailorCreated(SailorCreated $event): void
    {
        $this->id = $event->getId();
        $this->email = Email::create($event->getEmail());
        $this->createDate = $event->getCreateDate();
    }

    protected function applySailorDeleted(SailorDeleted $event): void
    {
        $this->deleteDate = $event->getDeleteDate();
    }
}
