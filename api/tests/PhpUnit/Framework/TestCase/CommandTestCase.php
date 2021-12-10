<?php

namespace App\Tests\PhpUnit\Framework\TestCase;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;

abstract class CommandTestCase extends KernelTestCase
{
    use ContainerTrait;

    abstract protected function getCommandClass(): string;

    protected function getCommand(): Command
    {
        $command = self::getContainer()->get($this->getCommandClass());

        if (!$command instanceof Command) {
            throw new \RuntimeException(sprintf('Invalid command given : "%s" should be an instance of "%s".', $this->getCommandClass(), Command::class));
        }

        return $command;
    }
}
