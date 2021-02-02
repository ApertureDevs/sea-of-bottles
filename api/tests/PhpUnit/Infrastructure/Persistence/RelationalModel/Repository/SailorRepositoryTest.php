<?php

namespace App\Tests\PhpUnit\Infrastructure\Persistence\RelationalModel\Repository;

use App\Infrastructure\Persistence\RelationalModel\Repository\SailorRepository;
use App\Tests\TestCase\RepositoryTestCase;

/**
 * @covers \App\Infrastructure\Persistence\RelationalModel\Repository\SailorRepository
 *
 * @internal
 */
class SailorRepositoryTest extends RepositoryTestCase
{
    public function testItShouldReturnSailorsCount(): void
    {
        /** @var SailorRepository $repository */
        $repository = $this->getRepository();

        self::assertSame(1, $repository->getSailorsCount());
    }

    protected function getRepositoryClass(): string
    {
        return SailorRepository::class;
    }
}
