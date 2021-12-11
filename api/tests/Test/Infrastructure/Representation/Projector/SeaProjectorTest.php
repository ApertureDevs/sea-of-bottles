<?php

namespace App\Tests\Test\Infrastructure\Representation\Projector;

use App\Infrastructure\Representation\Projector\SeaProjector;
use App\Tests\Framework\AggregatePersister;
use App\Tests\Framework\Builder\Message\BottleAggregateBuilder;
use App\Tests\Framework\Builder\Message\SailorAggregateBuilder;
use App\Tests\Framework\TestCase\ProjectorTestCase;

/**
 * @covers \App\Infrastructure\Representation\Projector\SeaProjector
 * @group integration
 *
 * @internal
 */
class SeaProjectorTest extends ProjectorTestCase
{
    private SeaProjector $seaProjector;

    protected function setUp(): void
    {
        $this->seaProjector = $this->getProjector();
        $persister = self::getContainer()->get(AggregatePersister::class);
        $persister->persist(BottleAggregateBuilder::new()->build());
        $sailor = SailorAggregateBuilder::new()->build();
        $persister->persist($sailor);
        $persister->persist(BottleAggregateBuilder::new()->wasReceived(sailor: $sailor)->build());
    }

    public function testItShouldProjectSea(): void
    {
        $sea = $this->seaProjector->getSea();

        self::assertSame(1, $sea->getBottlesRecovered());
        self::assertSame(1, $sea->getBottlesRemaining());
        self::assertSame(2, $sea->getBottlesTotal());
        self::assertSame(1, $sea->getSailorsTotal());
    }

    protected function getProjectorClass(): string
    {
        return SeaProjector::class;
    }
}
