<?php

namespace App\Tests\PhpUnit\Test\Core\Component\Message\Domain\Model;

use App\Core\Component\Message\Domain\Model\Sea;
use App\Tests\PhpUnit\Framework\TestCase\ProjectionTestCase;

/**
 * @covers \App\Core\Component\Message\Domain\Model\Sea
 *
 * @internal
 */
class SeaTest extends ProjectionTestCase
{
    public function testItShouldCreateASea(): void
    {
        $sea = Sea::create(10, 20, 30);

        self::assertSame(10, $sea->getBottlesRemaining());
        self::assertSame(20, $sea->getBottlesRecovered());
        self::assertSame(30, $sea->getBottlesTotal());
        self::assertSame(30, $sea->getSailorsTotal());
    }
}
