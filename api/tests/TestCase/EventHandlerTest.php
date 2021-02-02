<?php

namespace App\Tests\TestCase;

use App\Core\SharedKernel\Application\EventHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class EventHandlerTest extends KernelTestCase
{
    abstract public function testItShouldHandleEvent(): void;

    abstract public function testItShouldSkipUnsupportedEvent(): void;

    abstract protected function getEventHandlerClass(): string;

    protected function getEventHandler(): EventHandlerInterface
    {
        $handler = $this->getContainer()->get($this->getEventHandlerClass());

        if (!$handler instanceof EventHandlerInterface) {
            throw new \RuntimeException(sprintf('Invalid event handler given : "%s" should be an instance of "%s".', $this->getEventHandlerClass(), EventHandlerInterface::class));
        }

        return $handler;
    }

    protected function getContainer(): ContainerInterface
    {
        if (false === self::$booted) {
            self::bootKernel();
        }

        return self::$kernel->getContainer()->get('test.service_container');
    }
}
