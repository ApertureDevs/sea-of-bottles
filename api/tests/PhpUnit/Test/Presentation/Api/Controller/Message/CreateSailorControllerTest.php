<?php

namespace App\Tests\PhpUnit\Test\Presentation\Api\Controller\Message;

use App\Presentation\Api\Controller\Message\CreateBottleController;
use App\Presentation\Api\Controller\Message\CreateSailorController;
use App\Tests\PhpUnit\Framework\TestCase\CommandControllerTestCase;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @covers \App\Presentation\Api\Controller\Message\CreateSailorController
 *
 * @internal
 */
class CreateSailorControllerTest extends CommandControllerTestCase
{
    public function testItShouldHandleCommand(): void
    {
        $request = Request::create('test', Request::METHOD_POST, [], [], [], [], '{"email": "newsailor@aperturedevs.com"}');
        /** @var CreateBottleController $controller */
        $controller = $this->getCommandController();

        $response = $controller($request);

        $content = json_decode($response->getContent());
        self::assertIsString($content->id);
    }

    public function testItShouldThrowExceptionOnInvalidRequest(): void
    {
        $request = Request::create('test', Request::METHOD_POST);
        /** @var CreateSailorController $controller */
        $controller = $this->getCommandController();

        $this->expectException(BadRequestException::class);
        $controller($request);
    }

    protected function getCommandControllerClass(): string
    {
        return CreateSailorController::class;
    }
}
