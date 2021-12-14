<?php

namespace App\Infrastructure\Persistence\EventStore\Repository\Message;

use App\Core\Component\Message\Domain\Model\Sailor;
use App\Core\Component\Message\Port\SailorStoreInterface;
use App\Core\SharedKernel\Domain\Event\Message\SailorCreated;
use App\Core\SharedKernel\Domain\Event\Message\SailorDeleted;
use App\Core\SharedKernel\Domain\Model\Email;
use App\Core\SharedKernel\Domain\Model\Ip;
use App\Infrastructure\Event\AggregateFactory;
use App\Infrastructure\Persistence\EventStore\EventMap;
use App\Infrastructure\Persistence\EventStore\EventStore;
use App\Infrastructure\Persistence\EventStore\Repository\AggregateRepository;
use Doctrine\ORM\EntityManagerInterface;

final class SailorStore extends AggregateRepository implements SailorStoreInterface
{
    private EntityManagerInterface $entityManager;
    private EventMap $eventMap;

    public function __construct(EventStore $eventStore, AggregateFactory $aggregateFactory, EntityManagerInterface $entityManager, EventMap $eventMap)
    {
        $this->entityManager = $entityManager;
        $this->eventMap = $eventMap;
        parent::__construct($eventStore, $aggregateFactory);
    }

    public function load(string $id): ?Sailor
    {
        $sailor = $this->generateFromEventStore($id, Sailor::class);

        if (null === $sailor) {
            return null;
        }

        if (!$sailor instanceof Sailor) {
            throw new \RuntimeException('Invalid aggregate instance generated.');
        }

        return $sailor;
    }

    public function findIdWithEmailAndNotDeleted(Email $email): ?string
    {
        $createdEventType = $this->eventMap::getEventType(SailorCreated::class);
        $connection = $this->entityManager->getConnection();
        $sql = 'SELECT aggregate_id FROM events WHERE event ->> \'email\' = :emailValue AND event_type = :eventType';
        $statement = $connection->prepare($sql);
        $statement->bindValue('emailValue', $email->getAddress());
        $statement->bindValue('eventType', $createdEventType);

        $ids = $statement->executeQuery()->fetchFirstColumn();

        foreach ($ids as $id) {
            if (!is_string($id)) {
                throw new \RuntimeException('Id should be a string.');
            }

            $sailor = $this->load($id);

            if (null === $sailor) {
                throw new \RuntimeException('Sailor should not be null.');
            }

            if (!$sailor->isDelete()) {
                return $id;
            }
        }

        return null;
    }

    /**
     * @return array<string>
     */
    public function findIdsActive(): array
    {
        $createdEventType = $this->eventMap::getEventType(SailorCreated::class);
        $connection = $this->entityManager->getConnection();
        $sql = 'SELECT DISTINCT(aggregate_id) FROM events WHERE event_type = :eventType';
        $statement = $connection->prepare($sql);
        $statement->bindValue('eventType', $createdEventType);

        $ids = $statement->executeQuery()->fetchFirstColumn();
        $results = [];

        foreach ($ids as $id) {
            if (!is_string($id)) {
                throw new \RuntimeException('Id should be a string.');
            }

            $sailor = $this->load($id);

            if (null === $sailor) {
                throw new \RuntimeException('Sailor should not be null.');
            }

            if ($sailor->isActive()) {
                $results[] = $id;
            }
        }

        return $results;
    }

    public function getCreatedBetweenDatesCount(Ip $createIp, \DateTimeImmutable $start, \DateTimeImmutable $end): int
    {
        $createdEventType = $this->eventMap::getEventType(SailorCreated::class);
        $connection = $this->entityManager->getConnection();
        $sql = 'SELECT DISTINCT(aggregate_id) FROM events WHERE event_type = :eventType AND event ->> \'create_ip\' = :createIp';
        $statement = $connection->prepare($sql);
        $statement->bindValue('eventType', $createdEventType);
        $statement->bindValue('createIp', $createIp->getAddress());

        $ids = $statement->executeQuery()->fetchFirstColumn();
        $createdSailorCount = 0;

        foreach ($ids as $id) {
            if (!is_string($id)) {
                throw new \RuntimeException('Id should be a string.');
            }

            $sailor = $this->load($id);

            if (null === $sailor) {
                throw new \RuntimeException('Sailor should not be null.');
            }

            if ($sailor->getCreateDate() >= $start && $sailor->getCreateDate() < $end) {
                ++$createdSailorCount;
            }
        }

        return $createdSailorCount;
    }

    public function getDeletedBetweenDatesCount(Ip $deleteIp, \DateTimeImmutable $start, \DateTimeImmutable $end): int
    {
        $deletedEventType = $this->eventMap::getEventType(SailorDeleted::class);
        $connection = $this->entityManager->getConnection();
        $sql = 'SELECT DISTINCT(aggregate_id) FROM events WHERE event_type = :eventType AND event ->> \'delete_ip\' = :deleteIp';
        $statement = $connection->prepare($sql);
        $statement->bindValue('eventType', $deletedEventType);
        $statement->bindValue('deleteIp', $deleteIp->getAddress());

        $ids = $statement->executeQuery()->fetchFirstColumn();
        $deletedSailorCount = 0;

        foreach ($ids as $id) {
            if (!is_string($id)) {
                throw new \RuntimeException('Id should be a string.');
            }

            $sailor = $this->load($id);

            if (null === $sailor) {
                throw new \RuntimeException('Sailor should not be null.');
            }

            if ($sailor->getDeleteDate() >= $start && $sailor->getDeleteDate() < $end) {
                ++$deletedSailorCount;
            }
        }

        return $deletedSailorCount;
    }
}
