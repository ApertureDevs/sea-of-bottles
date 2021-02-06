<?php

namespace App\Tests\PhpUnit\Infrastructure\Persistence\EventStore\Repository\Message;

use App\Core\Component\Message\Domain\Model\Sailor;
use App\Core\SharedKernel\Domain\Model\Email;
use App\Infrastructure\Persistence\EventStore\Repository\Message\SailorStore;
use App\Tests\Factory\Message\SailorAggregateFactory;
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

    protected function getStoreClass(): string
    {
        return SailorStore::class;
    }
}
