<?php

namespace App\Tests\TestCase;

use App\Core\SharedKernel\Application\CommandHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class CommandHandlerTestCase extends KernelTestCase
{
    abstract public function testItShouldHandleValidCommand(): void;

    abstract protected function getCommandHandlerClass(): string;

    protected function getCommandHandler(): CommandHandlerInterface
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();
        $handler = $container->get($this->getCommandHandlerClass());

        if (!$handler instanceof CommandHandlerInterface) {
            throw new \RuntimeException(sprintf('Invalid command handler given : "%s" should be an instance of "%s".', $this->getCommandHandlerClass(), CommandHandlerInterface::class));
        }

        return $handler;
    }
}
