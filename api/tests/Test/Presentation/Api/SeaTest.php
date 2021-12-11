<?php

namespace App\Tests\Test\Presentation\Api;

use App\Tests\Framework\TestCase\ApiTestCase;

/**
 * @coversNothing
 * @group integration
 *
 * @internal
 */
class SeaTest extends ApiTestCase
{
    public function testShowSea(): void
    {
        $this->client->request('GET', '/api/sea');

        self::assertSame(200, $this->client->getResponse()->getStatusCode());
        self::assertResponseHeaderSame('content-type', 'application/json');
        $encodedJson = json_decode($this->client->getResponse()->getContent());
        self::assertObjectHasAttribute('bottles_remaining', $encodedJson);
        self::assertIsInt($encodedJson->bottles_remaining);
        self::assertObjectHasAttribute('bottles_recovered', $encodedJson);
        self::assertIsInt($encodedJson->bottles_recovered);
        self::assertObjectHasAttribute('bottles_total', $encodedJson);
        self::assertIsInt($encodedJson->bottles_total);
        self::assertObjectHasAttribute('sailors_total', $encodedJson);
        self::assertIsInt($encodedJson->sailors_total);
    }
}
