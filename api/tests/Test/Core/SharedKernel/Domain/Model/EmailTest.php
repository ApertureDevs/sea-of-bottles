<?php

namespace App\Tests\Test\Core\SharedKernel\Domain\Model;

use App\Core\SharedKernel\Domain\Exception\InvalidEmailException;
use App\Core\SharedKernel\Domain\Model\Email;
use App\Tests\Framework\TestCase\ValueObjectTestCase;

/**
 * @covers \App\Core\SharedKernel\Domain\Model\Email
 * @group unit
 *
 * @internal
 */
class EmailTest extends ValueObjectTestCase
{
    public function testItShouldCreateAnEmail(): void
    {
        $email = Email::create('test@aperturedevs.com');

        self::assertSame('test@aperturedevs.com', $email->getAddress());
    }

    public function testItShouldThrowExceptionOnLengthLimitExceeded(): void
    {
        $this->expectException(InvalidEmailException::class);
        Email::create('loooooooooooooooooooooooooooooooon@aperturedevs.com');
    }

    /**
     * @dataProvider invalidFormatEmailProvider
     */
    public function testItShouldThrowExceptionOnInvalidFormat(string $invalidEmail): void
    {
        $this->expectException(InvalidEmailException::class);
        Email::create($invalidEmail);
    }

    public function invalidFormatEmailProvider(): iterable
    {
        yield 'without @ case' => ['aperturedevs.com'];
        yield 'with starting @ case' => ['@aperturedevs.com'];
        yield 'with finishing @ case' => ['test@'];
        yield 'without . case' => ['test@aperturedevscom'];
        yield 'with starting . case' => ['.test@aperturedevscom'];
        yield 'with finishing . case' => ['test@aperturedevscom.'];
    }

    public function testItShouldTrimStartingAndEndingSpaces(): void
    {
        $email = Email::create('  test@aperturedevs.com ');

        self::assertSame('test@aperturedevs.com', $email->getAddress());
    }
}
