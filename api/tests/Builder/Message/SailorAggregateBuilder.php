<?php

namespace App\Tests\Builder\Message;

use App\Core\Component\Message\Domain\Model\Sailor;
use App\Tests\PhpUnit\Framework\LockedClock;

class SailorAggregateBuilder
{
    private ?string $email;

    private ?\DateTimeImmutable $createDate;

    private ?string $createIp;

    private bool $deleted = false;

    private ?\DateTimeImmutable $deleteDate;

    private ?string $deleteIp;

    public static function new(): self
    {
        return new self();
    }

    public function build(): Sailor
    {
        $sailor = Sailor::create(
            $this->email ?? 'test@aperturedevs.com',
            $this->createIp ?? '::1',
            LockedClock::create($this->createDate ?? new \DateTimeImmutable('2021-01-01'))
        );

        if ($this->deleted) {
            $sailor->delete(
                $this->deleteIp ?? '::1',
                LockedClock::create($this->deleteDate ?? new \DateTimeImmutable('2021-01-01'))
            );
        }

        return $sailor;
    }

    public function wasCreated(string $email = null, string $createIp = null, \DateTimeImmutable $createDate = null): self
    {
        $this->email = $email;
        $this->createIp = $createIp;
        $this->createDate = $createDate;

        return $this;
    }

    public function wasDeleted(string $deleteIp = null, \DateTimeImmutable $deleteDate = null): self
    {
        $this->deleted = true;
        $this->deleteIp = $deleteIp;
        $this->deleteDate = $deleteDate;

        return $this;
    }
}
