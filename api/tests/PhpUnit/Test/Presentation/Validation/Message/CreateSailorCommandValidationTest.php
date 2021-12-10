<?php

namespace App\Tests\PhpUnit\Test\Presentation\Validation\Message;

use App\Core\Component\Message\Application\CreateSailor\CreateSailorCommand;
use App\Tests\PhpUnit\Framework\TestCase\ValidationTestCase;

/**
 * @internal
 * @coversNothing
 */
class CreateSailorCommandValidationTest extends ValidationTestCase
{
    public function provideValidationCases(): iterable
    {
        yield [
            CreateSailorCommand::create('test@aperturedevs.com', '127.0.0.1'),
            [],
        ];

        yield [
            new CreateSailorCommand(),
            [
                'email' => ['This value should not be blank.'],
                'createIp' => ['This value should not be blank.'],
            ],
        ];

        yield [
            CreateSailorCommand::create('test', '127.0.0.0.1'),
            [
                'email' => ['This value is not a valid email address.'],
                'createIp' => ['This is not a valid IP address.'],
            ],
        ];
    }
}
