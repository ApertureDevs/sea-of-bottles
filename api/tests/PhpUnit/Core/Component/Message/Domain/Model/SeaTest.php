<?php

namespace Tests\App\Core\Component\Message\Domain\Model;

use App\Core\Component\Message\Domain\Model\Sea;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Core\Component\Message\Domain\Model\Sea
 *
 * @internal
 */
class SeaTest extends TestCase
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
