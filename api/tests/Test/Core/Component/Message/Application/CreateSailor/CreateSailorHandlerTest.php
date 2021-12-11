<?php

namespace App\Tests\Test\Core\Component\Message\Application\CreateSailor;

use App\Core\Component\Message\Application\CreateSailor\CreateSailorCommand;
use App\Core\Component\Message\Application\CreateSailor\CreateSailorHandler;
use App\Core\Component\Message\Application\CreateSailor\CreateSailorResponse;
use App\Core\Component\Message\Domain\Exception\UncreatableSailorException;
use App\Core\SharedKernel\Domain\Exception\InvalidEmailException;
use App\Infrastructure\Persistence\EventStore\Repository\Message\SailorStore;
use App\Tests\Framework\Builder\Message\SailorAggregateBuilder;
use App\Tests\Framework\TestCase\CommandHandlerTestCase;

/**
 * @covers \App\Core\Component\Message\Application\CreateSailor\CreateSailorHandler
 * @group integration
 *
 * @internal
 */
class CreateSailorHandlerTest extends CommandHandlerTestCase
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
        /** @var CreateSailorHandler $handler */
        $handler = $this->getCommandHandler();
        $command = CreateSailorCommand::create($sailor->getEmail()->getAddress(), '::1');

        $response = $handler($command);

        self::assertInstanceOf(CreateSailorResponse::class, $response);
    }

    public function testItShouldThrowExceptionOnAlreadyCreatedSailor(): void
    {
        $sailor = SailorAggregateBuilder::new()->build();
        $this->sailorStore->store($sailor);
        /** @var CreateSailorHandler $handler */
        $handler = $this->getCommandHandler();
        $command = CreateSailorCommand::create($sailor->getEmail()->getAddress(), '::1');

        $this->expectException(UncreatableSailorException::class);

        $handler($command);
    }

    public function testItShouldThrowExceptionOnInvalidEmailFormat(): void
    {
        /** @var CreateSailorHandler $handler */
        $handler = $this->getCommandHandler();
        $command = CreateSailorCommand::create('aperturedevs', '::1');

        $this->expectException(InvalidEmailException::class);

        $handler($command);
    }

    protected function getCommandHandlerClass(): string
    {
        return CreateSailorHandler::class;
    }
}
