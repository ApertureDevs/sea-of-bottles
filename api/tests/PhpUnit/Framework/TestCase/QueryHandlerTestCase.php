<?php

namespace App\Tests\PhpUnit\Framework\TestCase;

use App\Core\SharedKernel\Application\QueryHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class QueryHandlerTestCase extends KernelTestCase
{
    use ContainerTrait;

    abstract public function testItShouldHandleValidQuery(): void;

    abstract protected function getQueryHandlerClass(): string;

    protected function getQueryHandler(): QueryHandlerInterface
    {
        $handler = self::getContainer()->get($this->getQueryHandlerClass());

        if (!$handler instanceof QueryHandlerInterface) {
            throw new \RuntimeException(sprintf('Invalid query handler given : "%s" should be an instance of "%s".', $this->getQueryHandlerClass(), QueryHandlerInterface::class));
        }

        return $handler;
    }
}
