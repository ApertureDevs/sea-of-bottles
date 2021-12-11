<?php

namespace App\Tests\Test\Infrastructure\Persistence\EventStore\Repository\Message;

use App\Core\SharedKernel\Domain\Model\Email;
use App\Core\SharedKernel\Domain\Model\Ip;
use App\Infrastructure\Persistence\EventStore\Repository\Message\SailorStore;
use App\Tests\Framework\AggregatePersister;
use App\Tests\Framework\Builder\Message\SailorAggregateBuilder;
use App\Tests\Framework\LockedClock;
use App\Tests\Framework\TestCase\StoreTestCase;

/**
 * @covers \App\Infrastructure\Persistence\EventStore\Repository\Message\SailorStore
 * @group integration
 *
 * @internal
 */
class SailorStoreTest extends StoreTestCase
{
    private AggregatePersister $persister;

    protected function setUp(): void
    {
        parent::setUp();

        $this->persister = self::getContainer()->get(AggregatePersister::class);
    }

    public function testItShouldLoadAnAggregate(): void
    {
        $sailor = SailorAggregateBuilder::new()->build();
        $this->persister->persist($sailor);
        /** @var SailorStore $store */
        $store = $this->getStore();

        $storedAggregate = $store->load($sailor->getId());

        self::assertNotNull($storedAggregate);
    }

    public function testItShouldStoreAnAggregate(): void
    {
        /** @var SailorStore $store */
        $store = $this->getStore();
        $aggregate = SailorAggregateBuilder::new()->build();

        $store->store($aggregate);
        $storedAggregate = $store->load($aggregate->getId());

        self::assertSame($aggregate->getId(), $storedAggregate->getId());
    }

    public function testItShouldFindIdWithEmailAndNotDeleted(): void
    {
        /** @var SailorStore $store */
        $store = $this->getStore();
        $aggregate = SailorAggregateBuilder::new()->wasCreated(email: 'test@aperturedevs.com')->build();
        $this->persister->persist($aggregate);

        $id = $store->findIdWithEmailAndNotDeleted(Email::create('test@aperturedevs.com'));

        self::assertSame($aggregate->getId(), $id);

        $id = $store->findIdWithEmailAndNotDeleted(Email::create('unknown@aperturedevs.com'));

        self::assertNull($id);
    }

    public function testItShouldFindIdsActive(): void
    {
        /** @var SailorStore $store */
        $store = $this->getStore();
        $aggregate = SailorAggregateBuilder::new()->build();
        $this->persister->persist($aggregate);

        $ids = $store->findIdsActive();

        self::assertSame([$aggregate->getId()], $ids);
    }

    public function testItShouldCountCreatedBetweenDates(): void
    {
        /** @var SailorStore $store */
        $store = $this->getStore();
        $lockedCLock = LockedClock::create(new \DateTimeImmutable('2021-01-01'));
        $this->lockClock($lockedCLock->now());

        self::assertSame(0, $store->getCreatedBetweenDatesCount(Ip::create('::1'), $lockedCLock->today(), $lockedCLock->tomorrow()));
        $this->persister->persist(SailorAggregateBuilder::new()->wasCreated(createDate: $lockedCLock->now())->build());
        self::assertSame(1, $store->getCreatedBetweenDatesCount(Ip::create('::1'), $lockedCLock->today(), $lockedCLock->tomorrow()));
        $lockedCLock = LockedClock::create(new \DateTimeImmutable('2021-01-02'));
        self::assertSame(0, $store->getCreatedBetweenDatesCount(Ip::create('::1'), $lockedCLock->today(), $lockedCLock->tomorrow()));
    }

    public function testItShouldCountDeletedBetweenDates(): void
    {
        /** @var SailorStore $store */
        $store = $this->getStore();
        $lockedCLock = LockedClock::create(new \DateTimeImmutable('2021-01-01'));
        $this->lockClock($lockedCLock->now());

        self::assertSame(0, $store->getDeletedBetweenDatesCount(Ip::create('::1'), $lockedCLock->today(), $lockedCLock->tomorrow()));
        $this->persister->persist(SailorAggregateBuilder::new()->wasCreated(createDate: $lockedCLock->now())->wasDeleted()->build());
        self::assertSame(1, $store->getDeletedBetweenDatesCount(Ip::create('::1'), $lockedCLock->today(), $lockedCLock->tomorrow()));
        $lockedCLock = LockedClock::create(new \DateTimeImmutable('2021-01-02'));
        self::assertSame(0, $store->getDeletedBetweenDatesCount(Ip::create('::1'), $lockedCLock->today(), $lockedCLock->tomorrow()));
    }

    protected function getStoreClass(): string
    {
        return SailorStore::class;
    }
}
