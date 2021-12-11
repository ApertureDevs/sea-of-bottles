<?php

namespace App\Tests\Test\Infrastructure\Persistence\EventStore\Repository\Message;

use App\Core\SharedKernel\Domain\Model\Ip;
use App\Infrastructure\Persistence\EventStore\Repository\Message\BottleStore;
use App\Tests\Framework\AggregatePersister;
use App\Tests\Framework\Builder\Message\BottleAggregateBuilder;
use App\Tests\Framework\Builder\Message\SailorAggregateBuilder;
use App\Tests\Framework\LockedClock;
use App\Tests\Framework\TestCase\StoreTestCase;

/**
 * @covers \App\Infrastructure\Persistence\EventStore\Repository\Message\BottleStore
 * @group integration
 *
 * @internal
 */
class BottleStoreTest extends StoreTestCase
{
    private AggregatePersister $persister;

    protected function setUp(): void
    {
        parent::setUp();

        $this->persister = self::getContainer()->get(AggregatePersister::class);
    }

    public function testItShouldLoadAnAggregate(): void
    {
        $bottle = BottleAggregateBuilder::new()->build();
        $this->persister->persist($bottle);

        /** @var BottleStore $store */
        $store = $this->getStore();

        $storedAggregate = $store->load($bottle->getId());

        self::assertNotNull($storedAggregate);
    }

    public function testItShouldStoreAnAggregate(): void
    {
        /** @var BottleStore $store */
        $store = $this->getStore();
        $aggregate = BottleAggregateBuilder::new()->build();

        $store->store($aggregate);
        $storedAggregate = $store->load($aggregate->getId());

        self::assertSame($aggregate->getId(), $storedAggregate->getId());
    }

    public function testItShouldFindIdsNotReceived(): void
    {
        /** @var BottleStore $store */
        $store = $this->getStore();
        $notReceivedBottle = BottleAggregateBuilder::new()->build();
        $receivedBottle = BottleAggregateBuilder::new()->wasReceived(sailor: SailorAggregateBuilder::new()->build())->build();
        $this->persister->persist($notReceivedBottle);
        $this->persister->persist($receivedBottle);

        $ids = $store->findIdsNotReceived();

        self::assertSame([$notReceivedBottle->getId()], $ids);
    }

    public function testItShouldCountCreatedBetweenDates(): void
    {
        /** @var BottleStore $store */
        $store = $this->getStore();
        $lockedCLock = LockedClock::create(new \DateTimeImmutable('2021-01-01'));
        $this->lockClock($lockedCLock->now());

        self::assertSame(0, $store->getCreatedBetweenDatesCount(Ip::create('::1'), $lockedCLock->today(), $lockedCLock->tomorrow()));
        $this->persister->persist(BottleAggregateBuilder::new()->wasCreated(createDate: $lockedCLock->now())->build());
        self::assertSame(1, $store->getCreatedBetweenDatesCount(Ip::create('::1'), $lockedCLock->today(), $lockedCLock->tomorrow()));
        $lockedCLock = LockedClock::create(new \DateTimeImmutable('2021-01-02'));
        self::assertSame(0, $store->getCreatedBetweenDatesCount(Ip::create('::1'), $lockedCLock->today(), $lockedCLock->tomorrow()));
    }

    protected function getStoreClass(): string
    {
        return BottleStore::class;
    }
}
