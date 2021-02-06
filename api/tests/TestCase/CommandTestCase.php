<?php

namespace App\Tests\TestCase;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class CommandTestCase extends KernelTestCase
{
    abstract protected function getCommandClass(): string;

    protected function getCommand(): Command
    {
        self::bootKernel();
        $container = $this->getContainer();
        $command = $container->get($this->getCommandClass());

        if (!$command instanceof Command) {
            throw new \RuntimeException(sprintf('Invalid command given : "%s" should be an instance of "%s".', $this->getCommandClass(), Command::class));
        }

        return $command;
    }

    protected function getContainer(): ContainerInterface
    {
        if (false === self::$booted) {
            self::bootKernel();
        }

        return self::$kernel->getContainer()->get('test.service_container');
    }
}
