<?php

namespace App\Tests\PhpUnit\Infrastructure\Representation\Projector;

use App\Infrastructure\Representation\Projector\SeaProjector;
use App\Tests\TestCase\ProjectorTestCase;

/**
 * @covers \App\Infrastructure\Representation\Projector\SeaProjector
 *
 * @internal
 */
class SeaProjectorTest extends ProjectorTestCase
{
    public function testItShouldProjectSea(): void
    {
        /** @var SeaProjector $projectionManager */
        $projectionManager = $this->getProjector();

        $sea = $projectionManager->getSea();

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
