<?php

namespace App\Infrastructure\Persistence\RelationalModel\Repository;

use App\Infrastructure\Representation\Model\RelationalModel\Bottle;

/**
 * @extends Repository<Bottle>
 */
class BottleRepository extends Repository
{
    protected function getEntityClass(): string
    {
        return Bottle::class;
    }

    public function getRemainingBottlesCount(): int
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('COUNT(bottle) as bottle_count')
            ->from(Bottle::class, 'bottle')
            ->where('bottle.receiveDate IS NULL')
        ;

        return (int) $queryBuilder->getQuery()->getSingleResult()['bottle_count'];
    }

    public function getDeliveredBottlesCount(): int
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('COUNT(bottle) as bottle_count')
            ->from(Bottle::class, 'bottle')
            ->where('bottle.receiveDate IS NOT NULL')
        ;

        return (int) $queryBuilder->getQuery()->getSingleResult()['bottle_count'];
    }
}
