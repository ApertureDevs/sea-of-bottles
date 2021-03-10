<?php

namespace App\Tests\TestCase;

use App\Infrastructure\Representation\Projector\ProjectorInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class ProjectorTestCase extends KernelTestCase
{
    use ContainerTrait;

    abstract protected function getProjectorClass(): string;

    protected function getProjector(): ProjectorInterface
    {
        $projector = $this->getContainer()->get($this->getProjectorClass());

        if (!$projector instanceof ProjectorInterface) {
            throw new \RuntimeException(sprintf('Invalid projector given : "%s" should be an instance of "%s".', $this->getProjectorClass(), ProjectorInterface::class));
        }

        return $projector;
    }
}
