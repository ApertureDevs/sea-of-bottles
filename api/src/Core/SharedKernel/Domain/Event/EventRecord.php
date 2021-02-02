<?php

namespace App\Core\SharedKernel\Domain\Event;

class EventRecord
{
    private string $id;
    private string $aggregateId;
    private int $playHead;
    private Event $event;
    private \DateTimeImmutable $recordDate;

    private function __construct(string $id, string $aggregateId, int $playhead, Event $event, \DateTimeImmutable $recordDate)
    {
        $this->id = $id;
        $this->aggregateId = $aggregateId;
        $this->playHead = $playhead;
        $this->event = $event;
        $this->recordDate = $recordDate;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getPlayHead(): int
    {
        return $this->playHead;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function getRecordDate(): \DateTimeImmutable
    {
        return $this->recordDate;
    }

    public static function record(Aggregate $aggregate, int $playHead, Event $event): self
    {
        $id = uuid_create(UUID_TYPE_RANDOM);

        return new self($id, $aggregate->getId(), $playHead, $event, new \DateTimeImmutable());
    }

    public static function create(string $id, string $aggregateId, int $playHead, Event $event, \DateTimeImmutable $recordDate): self
    {
        return new self($id, $aggregateId, $playHead, $event, $recordDate);
    }
}
