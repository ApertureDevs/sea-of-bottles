<?php

namespace App\Infrastructure\Persistence\EventStore\Repository\Message;

use App\Core\Component\Message\Domain\Model\Sailor;
use App\Core\Component\Message\Port\SailorStoreInterface;
use App\Core\SharedKernel\Domain\Event\Message\SailorCreated;
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

    public function findIdWithEmailAndNotDeleted(string $email): ?string
    {
        $createdEventType = $this->eventMap::getEventType(SailorCreated::class);
        $connection = $this->entityManager->getConnection();
        $sql = 'SELECT aggregate_id FROM events WHERE event ->> \'email\' = :emailValue AND event_type = :eventType';
        $statement = $connection->prepare($sql);
        $statement->execute([
            'emailValue' => $email,
            'eventType' => $createdEventType,
        ]);
        $ids = $statement->fetchFirstColumn();

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
}
