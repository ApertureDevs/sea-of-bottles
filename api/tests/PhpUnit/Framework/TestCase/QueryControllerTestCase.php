<?php

namespace App\Tests\PhpUnit\Framework\TestCase;

use App\Presentation\Api\Controller\QueryController;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class QueryControllerTestCase extends KernelTestCase
{
    use ContainerTrait;

    abstract public function testItShouldHandleQuery(): void;

    abstract protected function getQueryControllerClass(): string;

    protected function getQueryController(): QueryController
    {
        $controller = self::getContainer()->get($this->getQueryControllerClass());

        if (!$controller instanceof QueryController) {
            throw new \RuntimeException(sprintf('Invalid command controller given : "%s" should be an instance of "%s".', $this->getQueryControllerClass(), QueryController::class));
        }

        return $controller;
    }
}
