<?php

namespace App\Tests\PhpUnit\Core\SharedKernel\Domain\Model;

use App\Core\SharedKernel\Domain\Exception\InvalidIpException;
use App\Core\SharedKernel\Domain\Model\Ip;
use App\Tests\TestCase\ValueObjectTestCase;

/**
 * @covers \App\Core\SharedKernel\Domain\Model\Ip
 *
 * @internal
 */
class IpTest extends ValueObjectTestCase
{
    public function testItShouldCreateAnIpV4(): void
    {
        $ip = Ip::create('127.0.0.1');

        self::assertSame('127.0.0.1', $ip->getAddress());
    }

    public function testItShouldCreateAnIpV6(): void
    {
        $ip = Ip::create('::1');

        self::assertSame('::1', $ip->getAddress());
    }

    /**
     * @dataProvider invalidFormatIpProvider
     */
    public function testItShouldThrowExceptionOnInvalidFormat(string $invalidIp): void
    {
        self::expectException(InvalidIpException::class);
        Ip::create($invalidIp);
    }

    public function invalidFormatIpProvider(): iterable
    {
        yield 'ip v4 without dot case' => ['127001'];
        yield 'ip v4 with hyphen case' => ['127-0-0-1'];
        yield 'ip v4 too short case' => ['127.0'];
        yield 'ip v4 too long case' => ['127.0.0.0.1'];
        yield 'ip v6 with too short case' => ['1'];
        yield 'ip v6 with too long case' => ['2001:0db8:85a3:0000:0000:8a2e:0370:7334:6666'];
    }

    public function testItShouldTrimStartingAndEndingSpaces(): void
    {
        $ip = Ip::create('  127.0.0.1 ');

        self::assertSame('127.0.0.1', $ip->getAddress());
    }
}
