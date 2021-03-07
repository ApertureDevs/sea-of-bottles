<?php

namespace App\Tests\PhpUnit\Core\Component\Message\Application\DeleteSailor;

use App\Core\Component\Message\Application\DeleteSailor\DeleteSailorCommand;
use App\Core\Component\Message\Application\DeleteSailor\DeleteSailorHandler;
use App\Core\Component\Message\Application\DeleteSailor\DeleteSailorResponse;
use App\Core\SharedKernel\Domain\Exception\InvalidEmailException;
use App\Core\SharedKernel\Domain\Exception\ResourceNotFoundException;
use App\Tests\TestCase\CommandHandlerTestCase;

/**
 * @covers \App\Core\Component\Message\Application\DeleteSailor\DeleteSailorHandler
 *
 * @internal
 */
class DeleteSailorHandlerTest extends CommandHandlerTestCase
{
    public function testItShouldHandleValidCommand(): void
    {
        /** @var DeleteSailorHandler $handler */
        $handler = $this->getCommandHandler();
        $command = DeleteSailorCommand::create('sailor1@aperturedevs.com', '::1');

        $response = $handler($command);

        self::assertInstanceOf(DeleteSailorResponse::class, $response);
    }

    public function testItShouldThrowExceptionOnAlreadyDeletedSailor(): void
    {
        /** @var DeleteSailorHandler $handler */
        $handler = $this->getCommandHandler();
        $command = DeleteSailorCommand::create('sailor2@aperturedevs.com', '::1');

        self::expectException(ResourceNotFoundException::class);

        $handler($command);
    }

    public function testItShouldThrowExceptionOnUnknownSailor(): void
    {
        /** @var DeleteSailorHandler $handler */
        $handler = $this->getCommandHandler();
        $command = DeleteSailorCommand::create('unknownsailor@aperturedevs.com', '::1');

        self::expectException(ResourceNotFoundException::class);

        $handler($command);
    }

    public function testItShouldThrowExceptionOnInvalidEmailFormat(): void
    {
        /** @var DeleteSailorHandler $handler */
        $handler = $this->getCommandHandler();
        $command = DeleteSailorCommand::create('invalid_email', '::1');

        self::expectException(InvalidEmailException::class);

        $handler($command);
    }

    protected function getCommandHandlerClass(): string
    {
        return DeleteSailorHandler::class;
    }
}
