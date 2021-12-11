<?php

namespace App\Tests\Framework\TestCase;

use App\Presentation\Api\Controller\CommandController;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class CommandControllerTestCase extends KernelTestCase
{
    use ContainerTrait;

    abstract public function testItShouldHandleCommand(): void;

    abstract protected function getCommandControllerClass(): string;

    protected function getCommandController(): CommandController
    {
        self::bootKernel();
        $container = self::getContainer();
        $controller = $container->get($this->getCommandControllerClass());

        if (!$controller instanceof CommandController) {
            throw new \RuntimeException(sprintf('Invalid command controller given : "%s" should be an instance of "%s".', $this->getCommandControllerClass(), CommandController::class));
        }

        return $controller;
    }
}
