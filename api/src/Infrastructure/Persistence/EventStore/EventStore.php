<?php

namespace App\Infrastructure\Persistence\EventStore;

use App\Core\SharedKernel\Domain\Event\EventRecords;
use Doctrine\ORM\EntityManagerInterface;

class EventStore
{
    private EntityManagerInterface $entityManager;
    private EventRecordTransformer $eventRecordTransformer;
    private EventMap $eventMap;

    public function __construct(EntityManagerInterface $eventStoreEntityManager, EventRecordTransformer $eventRecordTransformer, EventMap $eventMap)
    {
        $this->entityManager = $eventStoreEntityManager;
        $this->eventRecordTransformer = $eventRecordTransformer;
        $this->eventMap = $eventMap;
    }

    public function append(EventRecords $eventRecords): void
    {
        $this->entityManager->getConnection()->beginTransaction();

        try {
            foreach ($eventRecords as $eventRecord) {
                $serializedEventRecord = $this->eventRecordTransformer->convertIntoStorableEventRecord($eventRecord);
                $this->entityManager->persist($serializedEventRecord);
            }
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        } catch (\Exception $exception) {
            $this->entityManager->getConnection()->rollBack();

            throw new \RuntimeException("Error on storing event records: {$exception->getMessage()}", 0, $exception);
        }
    }

    public function loadAggregateEventRecords(string $aggregateId): EventRecords
    {
        $repository = $this->entityManager->getRepository(StorableEventRecord::class);
        $results = $repository->findBy(['aggregateId' => $aggregateId], ['playHead' => 'ASC']);

        $results = array_map(function ($storableEventRecord) {
            return $this->eventRecordTransformer->convertIntoDomainEventRecord($storableEventRecord);
        }, $results);

        return new EventRecords($results);
    }

    public function loadEventRecords(): EventRecords
    {
        $repository = $this->entityManager->getRepository(StorableEventRecord::class);
        $results = $repository->findBy([], ['recordDate' => 'ASC']);

        $results = array_map(function ($storableEventRecord) {
            return $this->eventRecordTransformer->convertIntoDomainEventRecord($storableEventRecord);
        }, $results);

        return new EventRecords($results);
    }
}
