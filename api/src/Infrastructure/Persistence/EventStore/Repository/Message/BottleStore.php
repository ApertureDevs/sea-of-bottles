<?php

namespace App\Infrastructure\Persistence\EventStore\Repository\Message;

use App\Core\Component\Message\Domain\Aggregate\Bottle;
use App\Core\Component\Message\Port\BottleStoreInterface;
use App\Infrastructure\Persistence\EventStore\Repository\AggregateRepository;

final class BottleStore extends AggregateRepository implements BottleStoreInterface
{
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
}
