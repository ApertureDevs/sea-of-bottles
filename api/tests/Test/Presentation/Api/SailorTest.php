<?php

namespace App\Tests\Test\Presentation\Api;

use App\Tests\Framework\TestCase\ApiTestCase;

/**
 * @coversNothing
 * @group integration
 *
 * @internal
 */
class SailorTest extends ApiTestCase
{
    public function testCreateSailor(): void
    {
        $this->createSailor('test@aperturedevs.com');

        self::assertSame(201, $this->client->getResponse()->getStatusCode());
        self::assertResponseHeaderSame('content-type', 'application/json');
        $encodedJson = json_decode($this->client->getResponse()->getContent());
        self::assertObjectHasAttribute('id', $encodedJson);
        self::assertIsString($encodedJson->id);
    }

    public function testCreateSailorWithInvalidEmail(): void
    {
        $this->createSailor('test');

        self::assertSame(400, $this->client->getResponse()->getStatusCode());
        self::assertResponseHeaderSame('content-type', 'application/json');
        $encodedJson = json_decode($this->client->getResponse()->getContent());
        self::assertObjectHasAttribute('title', $encodedJson);
        self::assertSame('Invalid Request', $encodedJson->title);
        self::assertObjectHasAttribute('description', $encodedJson);
        self::assertSame('email : This value is not a valid email address.', $encodedJson->description);
        self::assertObjectHasAttribute('status', $encodedJson);
        self::assertSame(400, $encodedJson->status);
    }

    public function testCreateSailorWhichAlreadyExists(): void
    {
        $email = 'test@aperturedevs.com';
        $this->createSailor($email);
        $this->createSailor($email);

        self::assertSame(400, $this->client->getResponse()->getStatusCode());
        self::assertResponseHeaderSame('content-type', 'application/json');
        $encodedJson = json_decode($this->client->getResponse()->getContent());
        self::assertObjectHasAttribute('title', $encodedJson);
        self::assertSame('Domain Error', $encodedJson->title);
        self::assertObjectHasAttribute('description', $encodedJson);
        self::assertSame('Sailor with email "test@aperturedevs.com" already exists.', $encodedJson->description);
        self::assertObjectHasAttribute('status', $encodedJson);
        self::assertSame(400, $encodedJson->status);
    }

    public function testCreateSailorWithoutBody(): void
    {
        $this->client->request('POST', '/api/sailor');

        self::assertSame(400, $this->client->getResponse()->getStatusCode());
        self::assertResponseHeaderSame('content-type', 'application/json');
        $encodedJson = json_decode($this->client->getResponse()->getContent());
        self::assertObjectHasAttribute('title', $encodedJson);
        self::assertSame('Invalid Request', $encodedJson->title);
        self::assertObjectHasAttribute('description', $encodedJson);
        self::assertSame('email : This value should not be blank.', $encodedJson->description);
        self::assertObjectHasAttribute('status', $encodedJson);
        self::assertSame(400, $encodedJson->status);
    }

    public function testCreateSailorOutOfQuota(): void
    {
        for ($i = 0; $i <= 5; ++$i) {
            $this->createSailor($this->generateRandomEmail());
        }

        self::assertSame(400, $this->client->getResponse()->getStatusCode());
        self::assertResponseHeaderSame('content-type', 'application/json');
        $encodedJson = json_decode($this->client->getResponse()->getContent());
        self::assertObjectHasAttribute('title', $encodedJson);
        self::assertSame('Domain Error', $encodedJson->title);
        self::assertObjectHasAttribute('description', $encodedJson);
        self::assertSame('Sailor creation limit reached, you must wait before create a new Sailor.', $encodedJson->description);
        self::assertObjectHasAttribute('status', $encodedJson);
        self::assertSame(400, $encodedJson->status);
    }

    public function testDeleteSailor(): void
    {
        $email = 'test@aperturedevs.com';
        $this->createSailor($email);
        $this->deleteSailor($email);

        self::assertSame(200, $this->client->getResponse()->getStatusCode());
        self::assertResponseHeaderSame('content-type', 'application/json');
        $encodedJson = json_decode($this->client->getResponse()->getContent());
        self::assertObjectHasAttribute('id', $encodedJson);
        self::assertIsString($encodedJson->id);
    }

    public function testDeleteSailorWithInvalidEmail(): void
    {
        $this->deleteSailor('test');

        self::assertSame(400, $this->client->getResponse()->getStatusCode());
        self::assertResponseHeaderSame('content-type', 'application/json');
        $encodedJson = json_decode($this->client->getResponse()->getContent());
        self::assertObjectHasAttribute('title', $encodedJson);
        self::assertSame('Invalid Request', $encodedJson->title);
        self::assertObjectHasAttribute('description', $encodedJson);
        self::assertSame('email : This value is not a valid email address.', $encodedJson->description);
        self::assertObjectHasAttribute('status', $encodedJson);
        self::assertSame(400, $encodedJson->status);
    }

    public function testDeleteSailorWhichAlreadyDeleted(): void
    {
        $email = 'test@aperturedevs.com';
        $this->createSailor($email);
        $this->deleteSailor($email);
        $this->deleteSailor($email);

        self::assertSame(404, $this->client->getResponse()->getStatusCode());
        self::assertResponseHeaderSame('content-type', 'application/json');
        $encodedJson = json_decode($this->client->getResponse()->getContent());
        self::assertObjectHasAttribute('title', $encodedJson);
        self::assertSame('Resource Not Found', $encodedJson->title);
        self::assertObjectHasAttribute('description', $encodedJson);
        self::assertSame('Resource "sailor" with property "email" and value "test@aperturedevs.com" not found.', $encodedJson->description);
        self::assertObjectHasAttribute('status', $encodedJson);
        self::assertSame(404, $encodedJson->status);
    }

    public function testDeleteSailorWithoutBody(): void
    {
        $this->client->request('DELETE', '/api/sailor');

        self::assertSame(400, $this->client->getResponse()->getStatusCode());
        self::assertResponseHeaderSame('content-type', 'application/json');
        $encodedJson = json_decode($this->client->getResponse()->getContent());
        self::assertObjectHasAttribute('title', $encodedJson);
        self::assertSame('Invalid Request', $encodedJson->title);
        self::assertObjectHasAttribute('description', $encodedJson);
        self::assertSame('email : This value should not be blank.', $encodedJson->description);
        self::assertObjectHasAttribute('status', $encodedJson);
        self::assertSame(400, $encodedJson->status);
    }

    public function testDeleteSailorOutOfQuota(): void
    {
        for ($i = 0; $i <= 3; ++$i) {
            $email = $this->generateRandomEmail();
            $this->createSailor($email);
            $this->deleteSailor($email);
        }

        self::assertSame(400, $this->client->getResponse()->getStatusCode());
        self::assertResponseHeaderSame('content-type', 'application/json');
        $encodedJson = json_decode($this->client->getResponse()->getContent());
        self::assertObjectHasAttribute('title', $encodedJson);
        self::assertSame('Domain Error', $encodedJson->title);
        self::assertObjectHasAttribute('description', $encodedJson);
        self::assertSame('Sailor deletion limit reached, you must wait before delete a new Sailor.', $encodedJson->description);
        self::assertObjectHasAttribute('status', $encodedJson);
        self::assertSame(400, $encodedJson->status);
    }

    private function createSailor(string $email): void
    {
        $content = <<<'EOD'
            {
                "email": "%s"
            }
            EOD;

        $this->client->request('POST', '/api/sailor', [], [], [], sprintf($content, $email));
    }

    private function deleteSailor(string $email): void
    {
        $content = <<<'EOD'
            {
                "email": "%s"
            }
            EOD;

        $this->client->request('DELETE', '/api/sailor', [], [], [], sprintf($content, $email));
    }

    private function generateRandomEmail(): string
    {
        return uniqid('test_', true).'@aperturedevs.com';
    }
}
