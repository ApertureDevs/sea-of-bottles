<?php

namespace App\Tests\PhpUnit\Core\Component\Message\Application\CreateSailor;

use App\Core\Component\Message\Application\CreateSailor\CreateSailorCommand;
use App\Core\Component\Message\Application\CreateSailor\CreateSailorHandler;
use App\Core\Component\Message\Application\CreateSailor\CreateSailorResponse;
use App\Core\Component\Message\Domain\Exception\UncreatableSailorException;
use App\Core\SharedKernel\Domain\Exception\InvalidEmailException;
use App\Tests\TestCase\CommandHandlerTestCase;

/**
 * @covers \App\Core\Component\Message\Application\CreateSailor\CreateSailorHandler
 *
 * @internal
 */
class CreateSailorHandlerTest extends CommandHandlerTestCase
{
    public function testItShouldHandleValidCommand(): void
    {
        /** @var CreateSailorHandler $handler */
        $handler = $this->getCommandHandler();
        $command = CreateSailorCommand::create('newsailor@aperturedevs.com');

        $response = $handler($command);

        self::assertInstanceOf(CreateSailorResponse::class, $response);
    }

    public function testItShouldThrowExceptionOnAlreadyCreatedSailor(): void
    {
        /** @var CreateSailorHandler $handler */
        $handler = $this->getCommandHandler();
        $command = CreateSailorCommand::create('sailor1@aperturedevs.com');

        self::expectException(UncreatableSailorException::class);

        $handler($command);
    }

    public function testItShouldThrowExceptionOnInvalidEmailFormat(): void
    {
        /** @var CreateSailorHandler $handler */
        $handler = $this->getCommandHandler();
        $command = CreateSailorCommand::create('aperturedevs');

        self::expectException(InvalidEmailException::class);

        $handler($command);
    }

    protected function getCommandHandlerClass(): string
    {
        return CreateSailorHandler::class;
    }
}
