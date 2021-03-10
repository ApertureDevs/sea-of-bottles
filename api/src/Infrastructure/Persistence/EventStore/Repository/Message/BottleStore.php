<?php

namespace App\Infrastructure\Persistence\EventStore\Repository\Message;

use App\Core\Component\Message\Domain\Model\Bottle;
use App\Core\Component\Message\Port\BottleStoreInterface;
use App\Core\SharedKernel\Domain\Event\Message\BottleCreated;
use App\Core\SharedKernel\Domain\Model\Ip;
use App\Infrastructure\Event\AggregateFactory;
use App\Infrastructure\Persistence\EventStore\EventMap;
use App\Infrastructure\Persistence\EventStore\EventStore;
use App\Infrastructure\Persistence\EventStore\Repository\AggregateRepository;
use Doctrine\ORM\EntityManagerInterface;

final class BottleStore extends AggregateRepository implements BottleStoreInterface
{
    private EntityManagerInterface $entityManager;
    private EventMap $eventMap;

    public function __construct(EventStore $eventStore, AggregateFactory $aggregateFactory, EntityManagerInterface $entityManager, EventMap $eventMap)
    {
        $this->entityManager = $entityManager;
        $this->eventMap = $eventMap;
        parent::__construct($eventStore, $aggregateFactory);
    }

    public function load(string $id): ?Bottle
    {
        $bottle = $this->generateFromEventStore($id, Bottle::class);

        if (null === $bottle) {
            return null;
        }

        if (!$bottle instanceof Bottle) {
            throw new \RuntimeException('Invalid aggregate instance generated.');
        }

        return $bottle;
    }

    /**
     * @return array<string>
     */
    public function findIdsNotReceived(): array
    {
        $createdEventType = $this->eventMap::getEventType(BottleCreated::class);
        $connection = $this->entityManager->getConnection();
        $sql = 'SELECT DISTINCT(aggregate_id) FROM events WHERE event_type = :eventType';
        $statement = $connection->prepare($sql);
        $statement->execute([
            'eventType' => $createdEventType,
        ]);
        $ids = $statement->fetchFirstColumn();
        $results = [];

        foreach ($ids as $id) {
            if (!is_string($id)) {
                throw new \RuntimeException('Id should be a string.');
            }

            $bottle = $this->load($id);

            if (null === $bottle) {
                throw new \RuntimeException('Bottle should not be null.');
            }

            if (!$bottle->isReceive()) {
                $results[] = $id;
            }
        }

        return $results;
    }

    public function getCreatedBetweenDatesCount(Ip $createIp, \DateTimeImmutable $start, \DateTimeImmutable $end): int
    {
        $createdEventType = $this->eventMap::getEventType(BottleCreated::class);
        $connection = $this->entityManager->getConnection();
        $sql = 'SELECT DISTINCT(aggregate_id) FROM events WHERE event_type = :eventType AND event ->> \'create_ip\' = :createIp';
        $statement = $connection->prepare($sql);
        $statement->execute([
            'eventType' => $createdEventType,
            'createIp' => $createIp->getAddress(),
        ]);

        $ids = $statement->fetchFirstColumn();
        $createdBottleCount = 0;

        foreach ($ids as $id) {
            if (!is_string($id)) {
                throw new \RuntimeException('Id should be a string.');
            }

            $bottle = $this->load($id);

            if (null === $bottle) {
                throw new \RuntimeException('Bottle should not be null.');
            }

            if ($bottle->getCreateDate() >= $start && $bottle->getCreateDate() < $end) {
                ++$createdBottleCount;
            }
        }

        return $createdBottleCount;
    }
}
