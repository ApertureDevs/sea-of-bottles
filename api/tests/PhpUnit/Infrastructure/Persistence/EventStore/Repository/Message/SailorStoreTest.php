<?php

namespace App\Tests\PhpUnit\Infrastructure\Persistence\EventStore\Repository\Message;

use App\Core\Component\Message\Domain\Model\Sailor;
use App\Core\SharedKernel\Domain\Model\Email;
use App\Core\SharedKernel\Domain\Model\Ip;
use App\Infrastructure\Persistence\EventStore\Repository\Message\SailorStore;
use App\Tests\Factory\Message\SailorAggregateFactory;
use App\Tests\LockedClock;
use App\Tests\TestCase\StoreTestCase;

/**
 * @covers \App\Infrastructure\Persistence\EventStore\Repository\Message\SailorStore
 *
 * @internal
 */
class SailorStoreTest extends StoreTestCase
{
    public function testItShouldLoadAnAggregate(): void
    {
        /** @var SailorStore $store */
        $store = $this->getStore();

        $aggregate = $store->load('dadcd1ef-5654-4929-9a27-dd8dd46fa599');

        self::assertInstanceOf(Sailor::class, $aggregate);
    }

    public function testItShouldStoreAnAggregate(): void
    {
        /** @var SailorStore $store */
        $store = $this->getStore();
        $aggregate = SailorAggregateFactory::createSailor();
        $aggregateId = $aggregate->getId();

        $store->store($aggregate);

        $storedAggregate = $store->load($aggregateId);
        self::assertInstanceOf(Sailor::class, $storedAggregate);
    }

    public function testItShouldFindIdWithEmailAndNotDeleted(): void
    {
        /** @var SailorStore $store */
        $store = $this->getStore();

        $id = $store->findIdWithEmailAndNotDeleted(Email::create('sailor1@aperturedevs.com'));

        self::assertSame('dadcd1ef-5654-4929-9a27-dd8dd46fa599', $id);

        $id = $store->findIdWithEmailAndNotDeleted(Email::create('unknown@aperturedevs.com'));

        self::assertNull($id);
    }

    public function testItShouldFindIdsActive(): void
    {
        /** @var SailorStore $store */
        $store = $this->getStore();

        $ids = $store->findIdsActive();

        self::assertSame(['dadcd1ef-5654-4929-9a27-dd8dd46fa599'], $ids);
    }

    public function testItShouldCountCreatedBetweenDates(): void
    {
        /** @var SailorStore $store */
        $store = $this->getStore();
        $lockedCLock = LockedClock::create(new \DateTimeImmutable('2021-01-01'));
        $this->lockClock($lockedCLock->now());

        self::assertSame(0, $store->getCreatedBetweenDatesCount(Ip::create('::1'), $lockedCLock->today(), $lockedCLock->tomorrow()));
        $store->store(SailorAggregateFactory::createSailor($lockedCLock));
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
        $store->store(SailorAggregateFactory::createDeletedSailor($lockedCLock));
        self::assertSame(1, $store->getDeletedBetweenDatesCount(Ip::create('::1'), $lockedCLock->today(), $lockedCLock->tomorrow()));
        $lockedCLock = LockedClock::create(new \DateTimeImmutable('2021-01-02'));
        self::assertSame(0, $store->getDeletedBetweenDatesCount(Ip::create('::1'), $lockedCLock->today(), $lockedCLock->tomorrow()));
    }

    protected function getStoreClass(): string
    {
        return SailorStore::class;
    }
}
