<?php

namespace App\Tests\TestCase;

use App\Core\SharedKernel\Application\EventHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class EventHandlerTest extends KernelTestCase
{
    use ContainerTrait;

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
}
