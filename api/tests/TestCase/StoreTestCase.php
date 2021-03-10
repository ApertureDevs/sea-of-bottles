<?php

namespace App\Tests\TestCase;

use App\Infrastructure\Persistence\EventStore\Repository\AggregateRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class StoreTestCase extends KernelTestCase
{
    use ContainerTrait;
    use LockableClockTrait;

    abstract public function testItShouldLoadAnAggregate(): void;

    abstract public function testItShouldStoreAnAggregate(): void;

    abstract protected function getStoreClass(): string;

    protected function getStore(): AggregateRepository
    {
        $store = $this->getContainer()->get($this->getStoreClass());

        if (!$store instanceof AggregateRepository) {
            throw new \RuntimeException(sprintf('Invalid store given : "%s" should be an instance of "%s".', $this->getStoreClass(), AggregateRepository::class));
        }

        return $store;
    }
}
