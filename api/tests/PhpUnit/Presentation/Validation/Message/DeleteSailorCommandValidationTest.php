<?php

namespace App\Tests\PhpUnit\Presentation\Validation\Message;

use App\Core\Component\Message\Application\DeleteSailor\DeleteSailorCommand;
use App\Tests\TestCase\ValidationTestCase;

/**
 * @internal
 * @coversNothing
 */
class DeleteSailorCommandValidationTest extends ValidationTestCase
{
    public function provideValidationCases(): iterable
    {
        yield [
            DeleteSailorCommand::create('test@aperturedevs.com', '127.0.0.1'),
            [],
        ];

        yield [
            new DeleteSailorCommand(),
            [
                'email' => ['This value should not be blank.'],
                'deleteIp' => ['This value should not be blank.'],
            ],
        ];

        yield [
            DeleteSailorCommand::create('test', '127.0.0.0.1'),
            [
                'email' => ['This value is not a valid email address.'],
                'deleteIp' => ['This is not a valid IP address.'],
            ],
        ];
    }
}
