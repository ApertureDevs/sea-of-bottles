<?php

namespace App\Tests\TestCase;

use App\Infrastructure\Persistence\EventStore\Repository\AggregateRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class StoreTestCase extends KernelTestCase
{
    abstract public function testItShouldLoadAnAggregate(): void;

    abstract public function testItShouldStoreAnAggregate(): void;

    abstract protected function getStoreClass(): string;

    protected function getStore(): AggregateRepository
    {
        self::bootKernel();
        $container = $this->getContainer();
        $store = $container->get($this->getStoreClass());

        if (!$store instanceof AggregateRepository) {
            throw new \RuntimeException(sprintf('Invalid store given : "%s" should be an instance of "%s".', $this->getStoreClass(), AggregateRepository::class));
        }

        return $store;
    }

    protected function getContainer(): ContainerInterface
    {
        if (false === self::$booted) {
            self::bootKernel();
        }

        return self::$kernel->getContainer()->get('test.service_container');
    }
}
