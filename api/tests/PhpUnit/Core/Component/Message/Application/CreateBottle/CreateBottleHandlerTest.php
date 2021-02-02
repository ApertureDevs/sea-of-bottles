<?php

namespace App\Tests\PhpUnit\Core\Component\Message\Application\CreateBottle;

use App\Core\Component\Message\Application\CreateBottle\CreateBottleCommand;
use App\Core\Component\Message\Application\CreateBottle\CreateBottleHandler;
use App\Core\Component\Message\Application\CreateBottle\CreateBottleResponse;
use App\Core\Component\Message\Domain\Exception\InvalidMessageException;
use App\Tests\TestCase\CommandHandlerTestCase;

/**
 * @covers \App\Core\Component\Message\Application\CreateBottle\CreateBottleHandler
 *
 * @internal
 */
class CreateBottleHandlerTest extends CommandHandlerTestCase
{
    public function testItShouldHandleValidCommand(): void
    {
        /** @var CreateBottleHandler $handler */
        $handler = $this->getCommandHandler();
        $command = new CreateBottleCommand();
        $command->message = 'Hello!';

        $response = $handler($command);

        self::assertInstanceOf(CreateBottleResponse::class, $response);
    }

    public function testItShouldThrowExceptionOnEmptyMessage(): void
    {
        /** @var CreateBottleHandler $handler */
        $handler = $this->getCommandHandler();
        $command = new CreateBottleCommand();
        $command->message = '';

        self::expectException(InvalidMessageException::class);

        $handler($command);
    }

    protected function getCommandHandlerClass(): string
    {
        return CreateBottleHandler::class;
    }
}
