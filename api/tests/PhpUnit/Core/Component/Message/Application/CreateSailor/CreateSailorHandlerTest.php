<?php

namespace Tests\App\Core\Component\Message\Application\CreateSailor;

use App\Core\Component\Message\Application\CreateSailor\CreateSailorCommand;
use App\Core\Component\Message\Application\CreateSailor\CreateSailorHandler;
use App\Core\Component\Message\Domain\Exception\UncreatableSailorException;
use App\Core\Component\Message\Port\SailorStoreInterface;
use App\Core\SharedKernel\Port\EventDispatcherInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

/**
 * @covers \App\Core\Component\Message\Application\CreateSailor\CreateSailorHandler
 *
 * @internal
 */
class CreateSailorHandlerTest extends TestCase
{
    public function testItShouldThrowExceptionOnAlreadyCreatedSailor(): void
    {
        $sailorStore = $this->prophesize(SailorStoreInterface::class);
        $eventDispatcher = $this->prophesize(EventDispatcherInterface::class);
        $sailorStore->findIdWithEmailAndNotDeleted(Argument::any())->willReturn('be225bf9-6339-497f-8247-30a6372daf26');
        $handler = new CreateSailorHandler($sailorStore->reveal(), $eventDispatcher->reveal());
        $command = new CreateSailorCommand();
        $command->email = 'sailor@aperturedevs.com';

        self::expectException(UncreatableSailorException::class);

        $handler($command);
    }
}
