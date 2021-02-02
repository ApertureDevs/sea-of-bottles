<?php

namespace App\Tests\PhpUnit\Core\Component\Message\Application\ShowSea;

use App\Core\Component\Message\Application\ShowSea\ShowSeaHandler;
use App\Core\Component\Message\Application\ShowSea\ShowSeaQuery;
use App\Core\Component\Message\Application\ShowSea\ShowSeaResponse;
use App\Tests\TestCase\QueryHandlerTestCase;

/**
 * @covers \App\Core\Component\Message\Application\ShowSea\ShowSeaHandler
 *
 * @internal
 */
class ShowSeaHandlerTest extends QueryHandlerTestCase
{
    public function testItShouldHandleValidQuery(): void
    {
        /** @var ShowSeaHandler $handler */
        $handler = $this->getQueryHandler();
        $query = new ShowSeaQuery();

        $response = $handler($query);

        self::assertInstanceOf(ShowSeaResponse::class, $response);
    }

    protected function getQueryHandlerClass(): string
    {
        return ShowSeaHandler::class;
    }
}
