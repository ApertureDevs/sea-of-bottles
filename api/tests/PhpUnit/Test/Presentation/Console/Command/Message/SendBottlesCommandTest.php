<?php

namespace App\Tests\PhpUnit\Test\Presentation\Console\Command\Message;

use App\Presentation\Console\Command\Message\SendBottlesCommand;
use App\Tests\PhpUnit\Framework\TestCase\CommandTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output\NullOutput;

/**
 * @covers \App\Presentation\Console\Command\Message\SendBottlesCommand
 *
 * @internal
 */
class SendBottlesCommandTest extends CommandTestCase
{
    public function testItShouldRunCommandSuccessfully(): void
    {
        /** @var SendBottlesCommand $command */
        $command = $this->getCommand();
        $input = new Input\ArgvInput(['app:message:send-bottles']);
        $output = new NullOutput();

        $result = $command->run($input, $output);

        self::assertSame(Command::SUCCESS, $result);
    }

    protected function getCommandClass(): string
    {
        return SendBottlesCommand::class;
    }
}
