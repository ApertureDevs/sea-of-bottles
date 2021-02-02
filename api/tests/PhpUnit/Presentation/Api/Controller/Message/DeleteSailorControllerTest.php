<?php

namespace App\Tests\PhpUnit\Presentation\Api\Controller\Message;

use App\Presentation\Api\Controller\Message\CreateBottleController;
use App\Presentation\Api\Controller\Message\DeleteSailorController;
use App\Tests\TestCase\CommandControllerTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @covers \App\Presentation\Api\Controller\Message\DeleteSailorController
 *
 * @internal
 */
class DeleteSailorControllerTest extends CommandControllerTestCase
{
    public function testItShouldHandleCommand(): void
    {
        $request = Request::create('test', Request::METHOD_DELETE, [], [], [], [], '{"email": "sailor1@aperturedevs.com"}');
        /** @var CreateBottleController $controller */
        $controller = $this->getCommandController();

        $response = $controller($request);

        $content = json_decode($response->getContent());
        self::assertIsString($content->id);
    }

    protected function getCommandControllerClass(): string
    {
        return DeleteSailorController::class;
    }
}
