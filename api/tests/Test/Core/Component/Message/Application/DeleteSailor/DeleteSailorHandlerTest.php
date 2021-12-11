<?php

namespace App\Tests\Test\Core\Component\Message\Application\DeleteSailor;

use App\Core\Component\Message\Application\DeleteSailor\DeleteSailorCommand;
use App\Core\Component\Message\Application\DeleteSailor\DeleteSailorHandler;
use App\Core\Component\Message\Application\DeleteSailor\DeleteSailorResponse;
use App\Core\SharedKernel\Domain\Exception\InvalidEmailException;
use App\Core\SharedKernel\Domain\Exception\ResourceNotFoundException;
use App\Infrastructure\Persistence\EventStore\Repository\Message\SailorStore;
use App\Tests\Framework\Builder\Message\SailorAggregateBuilder;
use App\Tests\Framework\TestCase\CommandHandlerTestCase;

/**
 * @covers \App\Core\Component\Message\Application\DeleteSailor\DeleteSailorHandler
 * @group integration
 *
 * @internal
 */
class DeleteSailorHandlerTest extends CommandHandlerTestCase
{
    private SailorStore $sailorStore;

    protected function setUp(): void
    {
        parent::setUp();

        $sailorStore = self::getContainer()->get(SailorStore::class);

        if (!$sailorStore instanceof SailorStore) {
            throw new \RuntimeException('Invalid instance.');
        }

        $this->sailorStore = $sailorStore;
    }

    public function testItShouldHandleValidCommand(): void
    {
        $sailor = SailorAggregateBuilder::new()->build();
        $this->sailorStore->store($sailor);
        /** @var DeleteSailorHandler $handler */
        $handler = $this->getCommandHandler();
        $command = DeleteSailorCommand::create($sailor->getEmail()->getAddress(), '::1');

        $response = $handler($command);

        self::assertInstanceOf(DeleteSailorResponse::class, $response);
    }

    public function testItShouldThrowExceptionOnAlreadyDeletedSailor(): void
    {
        $sailor = SailorAggregateBuilder::new()->wasDeleted()->build();
        $this->sailorStore->store($sailor);
        /** @var DeleteSailorHandler $handler */
        $handler = $this->getCommandHandler();
        $command = DeleteSailorCommand::create($sailor->getEmail()->getAddress(), '::1');

        $this->expectException(ResourceNotFoundException::class);

        $handler($command);
    }

    public function testItShouldThrowExceptionOnUnknownSailor(): void
    {
        /** @var DeleteSailorHandler $handler */
        $handler = $this->getCommandHandler();
        $command = DeleteSailorCommand::create('unknownsailor@aperturedevs.com', '::1');

        $this->expectException(ResourceNotFoundException::class);

        $handler($command);
    }

    public function testItShouldThrowExceptionOnInvalidEmailFormat(): void
    {
        /** @var DeleteSailorHandler $handler */
        $handler = $this->getCommandHandler();
        $command = DeleteSailorCommand::create('invalid_email', '::1');

        $this->expectException(InvalidEmailException::class);

        $handler($command);
    }

    protected function getCommandHandlerClass(): string
    {
        return DeleteSailorHandler::class;
    }
}
