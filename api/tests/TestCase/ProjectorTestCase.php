<?php

namespace App\Tests\TestCase;

use App\Infrastructure\Representation\Projector\ProjectorInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ProjectorTestCase extends KernelTestCase
{
    abstract protected function getProjectorClass(): string;

    protected function getProjector(): ProjectorInterface
    {
        self::bootKernel();
        $container = $this->getContainer();
        $projector = $container->get($this->getProjectorClass());

        if (!$projector instanceof ProjectorInterface) {
            throw new \RuntimeException(sprintf('Invalid projector given : "%s" should be an instance of "%s".', $this->getProjectorClass(), ProjectorInterface::class));
        }

        return $projector;
    }

    protected function getContainer(): ContainerInterface
    {
        if (false === self::$booted) {
            self::bootKernel();
        }

        return self::$kernel->getContainer()->get('test.service_container');
    }
}
