<?php

namespace App\Tests\Test\Infrastructure\Persistence\RelationalModel;

use App\Infrastructure\Persistence\RelationalModel\QueryNameGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Infrastructure\Persistence\RelationalModel\QueryNameGenerator
 * @group unit
 *
 * @internal
 */
class QueryNameGeneratorTest extends TestCase
{
    public function testItShouldGenerateAliasName(): void
    {
        $queryNameGenerator = new QueryNameGenerator();
        self::assertSame('bottle_alias_1', $queryNameGenerator->generateAliasName('bottle'));
        self::assertSame('bottle_alias_2', $queryNameGenerator->generateAliasName('bottle'));
        self::assertSame('sailor_alias_3', $queryNameGenerator->generateAliasName('sailor'));
        self::assertSame('bottle_alias_4', $queryNameGenerator->generateAliasName('bottle'));
    }

    public function testItShouldGenerateParameterName(): void
    {
        $queryNameGenerator = new QueryNameGenerator();
        self::assertSame('name_parameter_1', $queryNameGenerator->generateParameterName('name'));
        self::assertSame('name_parameter_2', $queryNameGenerator->generateParameterName('name'));
        self::assertSame('createDate_parameter_3', $queryNameGenerator->generateParameterName('createDate'));
        self::assertSame('name_parameter_4', $queryNameGenerator->generateParameterName('name'));
    }
}
