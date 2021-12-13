<?php

namespace App\Infrastructure\Persistence\RelationalModel\Repository;

use App\Infrastructure\Representation\Model\RelationalModel\Sailor;

/**
 * @extends Repository<Sailor>
 */
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

        $result = $queryBuilder->getQuery()->getSingleResult();

        if (!is_array($result)) {
            throw new \RuntimeException('Result should be an array.');
        }

        return (int) $result['sailor_count'];
    }
}
