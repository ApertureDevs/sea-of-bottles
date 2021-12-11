<?php

namespace App\Tests\Test\Infrastructure\Persistence\EventStore\Type;

use App\Infrastructure\Persistence\EventStore\Type\SerializeType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * @covers \App\Infrastructure\Persistence\EventStore\Type\SerializeType
 * @group unit
 *
 * @internal
 */
class SerializeTypeTest extends TestCase
{
    use ProphecyTrait;

    public function testConvertToDatabaseValue(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new SerializeType();

        self::assertSame('{"id":"7cb97c7e-3792-4766-aa88-3a1c92f9abbf"}', $type->convertToDatabaseValue('{"id":"7cb97c7e-3792-4766-aa88-3a1c92f9abbf"}', $platform->reveal()));
        self::assertNull($type->convertToDatabaseValue('', $platform->reveal()));
        self::assertNull($type->convertToDatabaseValue(null, $platform->reveal()));
    }

    public function testConvertToDatabaseValueShouldThrowExceptionOnInvalidValue(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new SerializeType();

        $this->expectException(\RuntimeException::class);
        $type->convertToDatabaseValue([], $platform->reveal());
    }

    public function testConvertToPHPValue(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new SerializeType();

        self::assertSame('{"id":"7cb97c7e-3792-4766-aa88-3a1c92f9abbf"}', $type->convertToPHPValue('{"id":"7cb97c7e-3792-4766-aa88-3a1c92f9abbf"}', $platform->reveal()));
        self::assertNull($type->convertToPHPValue('', $platform->reveal()));
        self::assertNull($type->convertToPHPValue(null, $platform->reveal()));
    }

    public function testConvertToPHPValueShouldThrowExceptionOnInvalidValue(): void
    {
        $platform = $this->prophesize(AbstractPlatform::class);
        $type = new SerializeType();

        $this->expectException(\RuntimeException::class);
        $type->convertToPHPValue([], $platform->reveal());
    }
}
