<?php

namespace App\Tests\Test\Presentation\Api\Controller\Message;

use App\Presentation\Api\Controller\Message\CreateBottleController;
use App\Tests\Framework\TestCase\CommandControllerTestCase;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @covers \App\Presentation\Api\Controller\Message\CreateBottleController
 * @group integration
 *
 * @internal
 */
class CreateBottleControllerTest extends CommandControllerTestCase
{
    public function testItShouldHandleCommand(): void
    {
        $request = Request::create('test', Request::METHOD_POST, [], [], [], [], '{"message": "Hello-World"}');
        /** @var CreateBottleController $controller */
        $controller = $this->getCommandController();

        $response = $controller($request);

        $content = json_decode($response->getContent());
        self::assertIsString($content->id);
    }

    public function testItShouldThrowExceptionOnInvalidRequest(): void
    {
        $request = Request::create('test', Request::METHOD_POST);
        /** @var CreateBottleController $controller */
        $controller = $this->getCommandController();

        $this->expectException(BadRequestException::class);
        $controller($request);
    }

    protected function getCommandControllerClass(): string
    {
        return CreateBottleController::class;
    }
}
