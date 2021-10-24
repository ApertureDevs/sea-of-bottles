<?php

namespace App\Tests\PhpUnit\Presentation\Validation\Message;

use App\Core\Component\Message\Application\CreateBottle\CreateBottleCommand;
use App\Tests\TestCase\ValidationTestCase;

/**
 * @internal
 * @coversNothing
 */
class CreateBottleCommandValidationTest extends ValidationTestCase
{
    public function provideValidationCases(): iterable
    {
        yield [
            CreateBottleCommand::create('This is a test.', '127.0.0.1'),
            [],
        ];

        yield [
            new CreateBottleCommand(),
            [
                'message' => ['This value should not be blank.'],
                'createIp' => ['This value should not be blank.'],
            ],
        ];

        yield [
            CreateBottleCommand::create(str_repeat('a', 501), '127.0.0.0.1'),
            [
                'message' => ['This value is too long. It should have 500 character or less.'],
                'createIp' => ['This is not a valid IP address.'],
            ],
        ];
    }
}
