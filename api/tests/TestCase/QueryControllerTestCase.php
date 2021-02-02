<?php

namespace App\Tests\TestCase;

use App\Presentation\Api\Controller\QueryController;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class QueryControllerTestCase extends KernelTestCase
{
    abstract public function testItShouldHandleQuery(): void;

    abstract protected function getQueryControllerClass(): string;

    protected function getQueryController(): QueryController
    {
        self::bootKernel();
        $container = $this->getContainer();
        $controller = $container->get($this->getQueryControllerClass());

        if (!$controller instanceof QueryController) {
            throw new \RuntimeException(sprintf('Invalid command controller given : "%s" should be an instance of "%s".', $this->getQueryControllerClass(), QueryController::class));
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
