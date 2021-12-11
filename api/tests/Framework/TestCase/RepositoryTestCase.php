<?php

namespace App\Tests\Framework\TestCase;

use App\Infrastructure\Persistence\RelationalModel\Repository\Repository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class RepositoryTestCase extends KernelTestCase
{
    use ContainerTrait;

    abstract protected function getRepositoryClass(): string;

    protected function getRepository(): Repository
    {
        $repository = self::getContainer()->get($this->getRepositoryClass());

        if (!$repository instanceof Repository) {
            throw new \RuntimeException(sprintf('Invalid repository given : "%s" should be an instance of "%s".', $this->getRepositoryClass(), Repository::class));
        }

        return $repository;
    }
}
