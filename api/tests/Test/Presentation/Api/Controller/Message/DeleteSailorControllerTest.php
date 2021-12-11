<?php

namespace App\Tests\Test\Presentation\Api\Controller\Message;

use App\Infrastructure\Persistence\EventStore\Repository\Message\SailorStore;
use App\Presentation\Api\Controller\Message\CreateBottleController;
use App\Presentation\Api\Controller\Message\DeleteSailorController;
use App\Tests\Framework\Builder\Message\SailorAggregateBuilder;
use App\Tests\Framework\TestCase\CommandControllerTestCase;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @covers \App\Presentation\Api\Controller\Message\DeleteSailorController
 * @group integration
 *
 * @internal
 */
class DeleteSailorControllerTest extends CommandControllerTestCase
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

    public function testItShouldHandleCommand(): void
    {
        $sailor = SailorAggregateBuilder::new()->build();
        $this->sailorStore->store($sailor);
        $content = new \stdClass();
        $content->email = $sailor->getEmail()->getAddress();
        $content = json_encode($content);
        $request = Request::create('test', Request::METHOD_DELETE, [], [], [], [], $content);
        /** @var CreateBottleController $controller */
        $controller = $this->getCommandController();

        $response = $controller($request);

        $content = json_decode($response->getContent());
        self::assertIsString($content->id);
    }

    public function testItShouldThrowExceptionOnInvalidRequest(): void
    {
        $request = Request::create('test', Request::METHOD_POST);
        /** @var DeleteSailorController $controller */
        $controller = $this->getCommandController();

        $this->expectException(BadRequestException::class);
        $controller($request);
    }

    protected function getCommandControllerClass(): string
    {
        return DeleteSailorController::class;
    }
}
