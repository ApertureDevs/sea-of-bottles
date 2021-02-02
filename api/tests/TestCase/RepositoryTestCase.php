<?php

namespace App\Tests\TestCase;

use App\Infrastructure\Persistence\RelationalModel\Repository\Repository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class RepositoryTestCase extends KernelTestCase
{
    abstract protected function getRepositoryClass(): string;

    protected function getRepository(): Repository
    {
        self::bootKernel();
        $container = $this->getContainer();
        $repository = $container->get($this->getRepositoryClass());

        if (!$repository instanceof Repository) {
            throw new \RuntimeException(sprintf('Invalid repository given : "%s" should be an instance of "%s".', $this->getRepositoryClass(), Repository::class));
        }

        return $repository;
    }

    protected function getContainer(): ContainerInterface
    {
        if (false === self::$booted) {
            self::bootKernel();
        }

        return self::$kernel->getContainer()->get('test.service_container');
    }
}
