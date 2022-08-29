<?php

namespace App\Core\SharedKernel\Domain\Event;

use Traversable;

/**
 * @implements \IteratorAggregate<EventRecord>
 */
class EventRecords implements \IteratorAggregate, \Countable
{
    /** @var array<EventRecord> */
    private array $eventRecords;

    /**
     * @param array<EventRecord> $eventRecords
     */
    public function __construct(array $eventRecords)
    {
        $this->eventRecords = $eventRecords;
    }

    /**
     * @return Traversable<EventRecord>
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->eventRecords);
    }

    public function count(): int
    {
        return count($this->eventRecords);
    }
}
