<?php

namespace App\Infrastructure\Event;

use App\Core\SharedKernel\Domain\Event\EventRecords;
use App\Core\SharedKernel\Port\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class EventDispatcher implements EventDispatcherInterface
{
    private MessageBusInterface $eventBus;
    private LoggerInterface $logger;

    public function __construct(MessageBusInterface $eventBus, LoggerInterface $logger)
    {
        $this->eventBus = $eventBus;
        $this->logger = $logger;
    }

    public function dispatch(EventRecords $eventRecords): void
    {
        foreach ($eventRecords as $eventRecord) {
            try {
                $this->eventBus->dispatch($eventRecord);
                $this->logger->info('Event Record dispatched successfully.', [
                    'event_record_id' => $eventRecord->getId(),
                    'aggregate_id' => $eventRecord->getAggregateId(),
                    'record_date' => $eventRecord->getRecordDate()->format('Y-m-d H:i:s'),
                ]);
            } catch (\Throwable $throwable) {
                $this->logger->error('Event Record failed to dispatch.', [
                    'event_record_id' => $eventRecord->getId(),
                    'aggregate_id' => $eventRecord->getAggregateId(),
                    'record_date' => $eventRecord->getRecordDate()->format('Y-m-d H:i:s'),
                    'exception_message' => $throwable->getMessage(),
                ]);
            }
        }
    }
}
