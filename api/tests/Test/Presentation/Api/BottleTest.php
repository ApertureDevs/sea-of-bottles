<?php

namespace App\Tests\Test\Presentation\Api;

use App\Tests\Framework\TestCase\ApiTestCase;

/**
 * @coversNothing
 * @group integration
 *
 * @internal
 */
class BottleTest extends ApiTestCase
{
    public function testCreateBottle(): void
    {
        $this->createBottle('Hello-World');

        self::assertSame(201, $this->client->getResponse()->getStatusCode());
        self::assertResponseHeaderSame('content-type', 'application/json');
        $encodedJson = json_decode($this->client->getResponse()->getContent());
        self::assertObjectHasAttribute('id', $encodedJson);
        self::assertIsString($encodedJson->id);
    }

    public function testCreateBottleWithInvalidMessage(): void
    {
        $this->createBottle('');

        self::assertSame(400, $this->client->getResponse()->getStatusCode());
        self::assertResponseHeaderSame('content-type', 'application/json');
        $encodedJson = json_decode($this->client->getResponse()->getContent());
        self::assertObjectHasAttribute('title', $encodedJson);
        self::assertSame('Invalid Request', $encodedJson->title);
        self::assertObjectHasAttribute('description', $encodedJson);
        self::assertSame('message : This value should not be blank.', $encodedJson->description);
        self::assertObjectHasAttribute('status', $encodedJson);
        self::assertSame(400, $encodedJson->status);
    }

    public function testCreateBottleWithoutBody(): void
    {
        $this->client->request('POST', '/api/bottle');

        self::assertSame(400, $this->client->getResponse()->getStatusCode());
        self::assertResponseHeaderSame('content-type', 'application/json');
        $encodedJson = json_decode($this->client->getResponse()->getContent());
        self::assertObjectHasAttribute('title', $encodedJson);
        self::assertSame('Invalid Request', $encodedJson->title);
        self::assertObjectHasAttribute('description', $encodedJson);
        self::assertSame('message : This value should not be blank.', $encodedJson->description);
        self::assertObjectHasAttribute('status', $encodedJson);
        self::assertSame(400, $encodedJson->status);
    }

    public function testCreateBottleOutOfQuota(): void
    {
        for ($i = 0; $i <= 5; ++$i) {
            $this->createBottle($this->generateRandomMessage());
        }

        self::assertSame(400, $this->client->getResponse()->getStatusCode());
        self::assertResponseHeaderSame('content-type', 'application/json');
        $encodedJson = json_decode($this->client->getResponse()->getContent());
        self::assertObjectHasAttribute('title', $encodedJson);
        self::assertSame('Domain Error', $encodedJson->title);
        self::assertObjectHasAttribute('description', $encodedJson);
        self::assertSame('Bottle creation limit reached, you must wait before create a new Bottle.', $encodedJson->description);
        self::assertObjectHasAttribute('status', $encodedJson);
        self::assertSame(400, $encodedJson->status);
    }

    private function createBottle(string $message): void
    {
        $content = <<<'EOD'
            {
                "message": "%s"
            }
            EOD;

        $this->client->request('POST', '/api/bottle', [], [], [], sprintf($content, $message));
    }

    private function generateRandomMessage(): string
    {
        return 'This is a random message with generated value '.uniqid('test_', true);
    }
}
