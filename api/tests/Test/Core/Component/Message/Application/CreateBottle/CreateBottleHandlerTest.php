<?php

namespace App\Tests\Test\Core\Component\Message\Application\CreateBottle;

use App\Core\Component\Message\Application\CreateBottle\CreateBottleCommand;
use App\Core\Component\Message\Application\CreateBottle\CreateBottleHandler;
use App\Core\Component\Message\Application\CreateBottle\CreateBottleResponse;
use App\Core\Component\Message\Domain\Exception\InvalidMessageException;
use App\Tests\Framework\TestCase\CommandHandlerTestCase;

/**
 * @covers \App\Core\Component\Message\Application\CreateBottle\CreateBottleHandler
 * @group integration
 *
 * @internal
 */
class CreateBottleHandlerTest extends CommandHandlerTestCase
{
    public function testItShouldHandleValidCommand(): void
    {
        /** @var CreateBottleHandler $handler */
        $handler = $this->getCommandHandler();
        $command = CreateBottleCommand::create('Hello!', '127.0.0.1');

        $response = $handler($command);

        self::assertInstanceOf(CreateBottleResponse::class, $response);
    }

    public function testItShouldThrowExceptionOnEmptyMessage(): void
    {
        /** @var CreateBottleHandler $handler */
        $handler = $this->getCommandHandler();
        $command = CreateBottleCommand::create('', '127.0.0.1');

        $this->expectException(InvalidMessageException::class);

        $handler($command);
    }

    protected function getCommandHandlerClass(): string
    {
        return CreateBottleHandler::class;
    }
}
