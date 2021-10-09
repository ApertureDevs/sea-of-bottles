<?php

namespace App\Infrastructure\Persistence\RelationalModel\Repository;

use App\Infrastructure\Persistence\RelationalModel\QueryNameGenerator;
use App\Infrastructure\Representation\Model\RelationalModel\EntityInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @template T of EntityInterface
 */
abstract class Repository
{
    protected EntityManagerInterface $entityManager;

    protected QueryNameGenerator $queryNameGenerator;

    public function __construct(EntityManagerInterface $relationalModelEntityManager, QueryNameGenerator $queryNameGenerator)
    {
        $this->entityManager = $relationalModelEntityManager;
        $this->queryNameGenerator = $queryNameGenerator;
    }

    /**
     * @param T $entity
     */
    public function save(EntityInterface $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @param T $entity
     */
    public function remove(EntityInterface $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    /**
     * @return T|null
     */
    public function findById(string $id): ?EntityInterface
    {
        $entity = $this->entityManager->find($this->getEntityClass(), $id);

        if (null === $entity) {
            return null;
        }

        if (!$entity instanceof EntityInterface) {
            throw new \RuntimeException('Invalid entity instance generated.');
        }

        return $entity;
    }

    /**
     * @return class-string<T>
     */
    abstract protected function getEntityClass(): string;
}
