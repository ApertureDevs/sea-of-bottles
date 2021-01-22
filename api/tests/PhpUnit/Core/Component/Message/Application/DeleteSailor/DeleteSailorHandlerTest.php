<?php

namespace Tests\App\Core\Component\Message\Application\DeleteSailor;

use App\Core\Component\Message\Application\DeleteSailor\DeleteSailorCommand;
use App\Core\Component\Message\Application\DeleteSailor\DeleteSailorHandler;
use App\Core\Component\Message\Port\SailorStoreInterface;
use App\Core\SharedKernel\Domain\Exception\ResourceNotFoundException;
use App\Core\SharedKernel\Port\EventDispatcherInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

/**
 * @covers \App\Core\Component\Message\Application\DeleteSailor\DeleteSailorHandler
 *
 * @internal
 */
class DeleteSailorHandlerTest extends TestCase
{
    public function testItShouldThrowExceptionOnAlreadyDeletedSailor(): void
    {
        $sailorStore = $this->prophesize(SailorStoreInterface::class);
        $eventDispatcher = $this->prophesize(EventDispatcherInterface::class);
        $sailorStore->findIdWithEmailAndNotDeleted(Argument::any())->willReturn(null);
        $handler = new DeleteSailorHandler($sailorStore->reveal(), $eventDispatcher->reveal());
        $command = new DeleteSailorCommand();
        $command->email = 'sailor@aperturedevs.com';

        self::expectException(ResourceNotFoundException::class);

        $handler($command);
    }
}
