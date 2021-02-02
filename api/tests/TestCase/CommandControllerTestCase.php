<?php

namespace App\Tests\TestCase;

use App\Presentation\Api\Controller\CommandController;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class CommandControllerTestCase extends KernelTestCase
{
    abstract public function testItShouldHandleCommand(): void;

    abstract protected function getCommandControllerClass(): string;

    protected function getCommandController(): CommandController
    {
        self::bootKernel();
        $container = $this->getContainer();
        $controller = $container->get($this->getCommandControllerClass());

        if (!$controller instanceof CommandController) {
            throw new \RuntimeException(sprintf('Invalid command controller given : "%s" should be an instance of "%s".', $this->getCommandControllerClass(), CommandController::class));
        }

        return $controller;
    }

    protected function getContainer(): ContainerInterface
    {
        if (false === self::$booted) {
            self::bootKernel();
        }

        return self::$kernel->getContainer()->get('test.service_container');
    }
}
