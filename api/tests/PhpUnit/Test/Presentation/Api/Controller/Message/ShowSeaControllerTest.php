<?php

namespace App\Tests\PhpUnit\Test\Presentation\Api\Controller\Message;

use App\Presentation\Api\Controller\Message\ShowSeaController;
use App\Tests\PhpUnit\Framework\TestCase\QueryControllerTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @covers \App\Presentation\Api\Controller\Message\ShowSeaController
 *
 * @internal
 */
class ShowSeaControllerTest extends QueryControllerTestCase
{
    public function testItShouldHandleQuery(): void
    {
        $request = Request::create('test', Request::METHOD_GET);
        /** @var ShowSeaController $controller */
        $controller = $this->getQueryController();

        $response = $controller($request);

        $content = json_decode($response->getContent());
        self::assertIsInt($content->bottles_remaining);
        self::assertIsInt($content->bottles_recovered);
        self::assertIsInt($content->bottles_total);
        self::assertIsInt($content->sailors_total);
    }

    protected function getQueryControllerClass(): string
    {
        return ShowSeaController::class;
    }
}
