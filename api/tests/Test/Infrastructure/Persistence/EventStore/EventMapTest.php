<?php

namespace App\Tests\Test\Infrastructure\Persistence\EventStore;

use App\Core\SharedKernel\Domain\Event\Message\BottleCreated;
use App\Infrastructure\Persistence\EventStore\EventMap;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Infrastructure\Persistence\EventStore\EventMap
 * @group unit
 *
 * @internal
 */
class EventMapTest extends TestCase
{
    public function testItShouldReturnEventClassName(): void
    {
        self::assertSame(BottleCreated::class, EventMap::getClassName('bottle_created', 'message'));
    }

    public function testGetEventClassNameShouldThrowExceptionOnUnknownEventType(): void
    {
        $this->expectException(\RuntimeException::class);
        EventMap::getClassName('unknown', 'message');
    }

    public function testGetEventClassNameShouldThrowExceptionOnUnknownEventContext(): void
    {
        $this->expectException(\RuntimeException::class);
        EventMap::getClassName('bottle_created', 'unknown');
    }

    public function testItShouldReturnEventContext(): void
    {
        self::assertSame('message', EventMap::getContext(BottleCreated::class));
    }

    public function testGetEventContextShouldThrowExceptionOnUnknownEventClass(): void
    {
        $this->expectException(\RuntimeException::class);
        EventMap::getContext(self::class);
    }

    public function testItShouldReturnEventType(): void
    {
        self::assertSame('bottle_created', EventMap::getEventType(BottleCreated::class));
    }

    public function testGetEventTypeShouldThrowExceptionOnUnknownEventClass(): void
    {
        $this->expectException(\RuntimeException::class);
        EventMap::getEventType(self::class);
    }
}
