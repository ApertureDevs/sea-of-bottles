<?php

namespace App\Tests\Test\Core\Component\Message\Application\ShowSea;

use App\Core\Component\Message\Application\ShowSea\ShowSeaHandler;
use App\Core\Component\Message\Application\ShowSea\ShowSeaQuery;
use App\Core\Component\Message\Application\ShowSea\ShowSeaResponse;
use App\Tests\Framework\TestCase\QueryHandlerTestCase;

/**
 * @covers \App\Core\Component\Message\Application\ShowSea\ShowSeaHandler
 * @group integration
 *
 * @internal
 */
class ShowSeaHandlerTest extends QueryHandlerTestCase
{
    public function testItShouldHandleValidQuery(): void
    {
        /** @var ShowSeaHandler $handler */
        $handler = $this->getQueryHandler();
        $query = ShowSeaQuery::create();

        $response = $handler($query);

        self::assertInstanceOf(ShowSeaResponse::class, $response);
    }

    protected function getQueryHandlerClass(): string
    {
        return ShowSeaHandler::class;
    }
}
