<?php

namespace App\Tests\Test\Core\Component\Message\Application\SendBottles;

use App\Core\Component\Message\Application\SendBottles\SendBottlesCommand;
use App\Core\Component\Message\Application\SendBottles\SendBottlesHandler;
use App\Core\Component\Message\Application\SendBottles\SendBottlesResponse;
use App\Tests\Framework\TestCase\CommandHandlerTestCase;

/**
 * @covers \App\Core\Component\Message\Application\SendBottles\SendBottlesHandler
 * @group integration
 *
 * @internal
 */
class SendBottlesHandlerTest extends CommandHandlerTestCase
{
    public function testItShouldHandleValidCommand(): void
    {
        /** @var SendBottlesHandler $handler */
        $handler = $this->getCommandHandler();
        $command = SendBottlesCommand::create();

        $response = $handler($command);

        self::assertInstanceOf(SendBottlesResponse::class, $response);
    }

    protected function getCommandHandlerClass(): string
    {
        return SendBottlesHandler::class;
    }
}
