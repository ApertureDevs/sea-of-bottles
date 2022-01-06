<?php

namespace App\Tests\Test\Presentation\Api;

use App\Tests\Framework\TestCase\ApiTestCase;

/**
 * @coversNothing
 * @group integration
 *
 * @internal
 */
class DocumentationTest extends ApiTestCase
{
    public function testHtmlApiDocumentationPage(): void
    {
        $this->client->request('GET', '/api/doc');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'text/html; charset=UTF-8');
        self::assertSelectorExists('#logo');
        self::assertSelectorExists('#swagger-ui');
    }

    public function testJsonApiDocumentationPage(): void
    {
        $this->client->request('GET', '/api/doc.json');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/json');
        $encodedJson = json_decode($this->client->getResponse()->getContent());
        self::assertSame('3.0.0', $encodedJson->openapi);
        self::assertSame('1.0.0', $encodedJson->info->version);
    }
}
