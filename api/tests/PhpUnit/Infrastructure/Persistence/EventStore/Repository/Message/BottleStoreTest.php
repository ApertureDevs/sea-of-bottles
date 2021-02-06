<?php

namespace App\Tests\PhpUnit\Infrastructure\Persistence\EventStore\Repository\Message;

use App\Core\Component\Message\Domain\Model\Bottle;
use App\Infrastructure\Persistence\EventStore\Repository\Message\BottleStore;
use App\Tests\Factory\Message\BottleAggregateFactory;
use App\Tests\TestCase\StoreTestCase;

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
        $aggregate = BottleAggregateFactory::createBottle();
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

    protected function getStoreClass(): string
    {
        return BottleStore::class;
    }
}
