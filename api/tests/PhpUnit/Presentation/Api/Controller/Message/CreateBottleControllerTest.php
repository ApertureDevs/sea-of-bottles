<?php

namespace App\Tests\PhpUnit\Presentation\Api\Controller\Message;

use App\Presentation\Api\Controller\Message\CreateBottleController;
use App\Tests\TestCase\CommandControllerTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @covers \App\Presentation\Api\Controller\Message\CreateBottleController
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

    protected function getCommandControllerClass(): string
    {
        return CreateBottleController::class;
    }
}
