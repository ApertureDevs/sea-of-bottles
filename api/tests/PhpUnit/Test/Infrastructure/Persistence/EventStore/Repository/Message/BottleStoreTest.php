<?php

namespace App\Tests\PhpUnit\Test\Infrastructure\Persistence\EventStore\Repository\Message;

use App\Core\Component\Message\Domain\Model\Bottle;
use App\Core\SharedKernel\Domain\Model\Ip;
use App\Infrastructure\Persistence\EventStore\Repository\Message\BottleStore;
use App\Tests\Builder\Message\BottleAggregateBuilder;
use App\Tests\PhpUnit\Framework\LockedClock;
use App\Tests\PhpUnit\Framework\TestCase\StoreTestCase;

/**
 * @covers \App\Infrastructure\Persistence\EventStore\Repository\Message\BottleStore
 *
 * @internal
 */
class BottleStoreTest extends StoreTestCase
{
    public function testItShouldLoadAnAggregate(): void
    {
        /** @var BottleStore $store */
        $store = $this->getStore();

        $aggregate = $store->load('da36c552-533e-4d28-8b4f-dcdf59191650');

        self::assertInstanceOf(Bottle::class, $aggregate);
    }

    public function testItShouldStoreAnAggregate(): void
    {
        /** @var BottleStore $store */
        $store = $this->getStore();
        $aggregate = BottleAggregateBuilder::new()->build();
        $aggregateId = $aggregate->getId();

        $store->store($aggregate);

        $storedAggregate = $store->load($aggregateId);
        self::assertInstanceOf(Bottle::class, $storedAggregate);
    }

    public function testItShouldFindIdsNotReceived(): void
    {
        /** @var BottleStore $store */
        $store = $this->getStore();

        $ids = $store->findIdsNotReceived();

        self::assertSame(['da36c552-533e-4d28-8b4f-dcdf59191650'], $ids);
    }

    public function testItShouldCountCreatedBetweenDates(): void
    {
        /** @var BottleStore $store */
        $store = $this->getStore();
        $lockedCLock = LockedClock::create(new \DateTimeImmutable('2021-01-01'));
        $this->lockClock($lockedCLock->now());

        self::assertSame(0, $store->getCreatedBetweenDatesCount(Ip::create('::1'), $lockedCLock->today(), $lockedCLock->tomorrow()));
        $store->store(BottleAggregateBuilder::new()->wasCreated(createDate: $lockedCLock->now())->build());
        self::assertSame(1, $store->getCreatedBetweenDatesCount(Ip::create('::1'), $lockedCLock->today(), $lockedCLock->tomorrow()));
        $lockedCLock = LockedClock::create(new \DateTimeImmutable('2021-01-02'));
        self::assertSame(0, $store->getCreatedBetweenDatesCount(Ip::create('::1'), $lockedCLock->today(), $lockedCLock->tomorrow()));
    }

    protected function getStoreClass(): string
    {
        return BottleStore::class;
    }
}
