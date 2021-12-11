<?php

namespace App\Tests\Framework\Builder\Message;

use App\Core\Component\Message\Domain\Model\Bottle;
use App\Core\Component\Message\Domain\Model\Sailor;
use App\Tests\Framework\LockedClock;

class BottleAggregateBuilder
{
    private ?string $message;

    private ?\DateTimeImmutable $createDate;

    private ?string $createIp;

    private ?\DateTimeImmutable $receiveDate;

    private ?Sailor $receiver;

    private bool $received = false;

    public static function new(): self
    {
        return new self();
    }

    public function build(): Bottle
    {
        $bottle = Bottle::create(
            $this->message ?? 'This is a test message!',
            $this->createIp ?? '::1',
            LockedClock::create($this->createDate ?? new \DateTimeImmutable('2021-01-01'))
        );

        if ($this->received) {
            $bottle->receive(
                $this->receiver ?? SailorAggregateBuilder::new()->build(),
                LockedClock::create($this->receiveDate ?? new \DateTimeImmutable('2021-01-01'))
            );
        }

        return $bottle;
    }

    public function wasCreated(string $message = null, string $createIp = null, \DateTimeImmutable $createDate = null): self
    {
        $this->message = $message;
        $this->createIp = $createIp;
        $this->createDate = $createDate;

        return $this;
    }

    public function wasReceived(\DateTimeImmutable $receiveDate = null, Sailor $sailor = null): self
    {
        $this->received = true;
        $this->receiveDate = $receiveDate;
        $this->receiver = $sailor;

        return $this;
    }
}
