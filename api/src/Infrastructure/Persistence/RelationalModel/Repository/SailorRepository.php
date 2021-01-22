<?php

namespace App\Infrastructure\Persistence\RelationalModel\Repository;

use App\Infrastructure\Representation\Projector\RelationalModel\Model\Sailor;

class SailorRepository extends Repository
{
    protected function getEntityClass(): string
    {
        return Sailor::class;
    }

    public function getSailorsCount(): int
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('COUNT(sailor) as sailor_count')
            ->from(Sailor::class, 'sailor')
        ;

        return (int) $queryBuilder->getQuery()->getSingleResult()['sailor_count'];
    }
}
