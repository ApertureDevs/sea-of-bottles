<?php

namespace App\Tests\PhpUnit\Test\Infrastructure\Persistence\RelationalModel\Repository;

use App\Infrastructure\Persistence\RelationalModel\Repository\BottleRepository;
use App\Tests\PhpUnit\Framework\TestCase\RepositoryTestCase;

/**
 * @covers \App\Infrastructure\Persistence\RelationalModel\Repository\BottleRepository
 *
 * @internal
 */
class BottleRepositoryTest extends RepositoryTestCase
{
    public function testItShouldReturnRemainingBottlesCount(): void
    {
        /** @var BottleRepository $repository */
        $repository = $this->getRepository();

        self::assertSame(1, $repository->getRemainingBottlesCount());
    }

    public function testItShouldReturnDeliveredBottlesCount(): void
    {
        /** @var BottleRepository $repository */
        $repository = $this->getRepository();

        self::assertSame(1, $repository->getDeliveredBottlesCount());
    }

    protected function getRepositoryClass(): string
    {
        return BottleRepository::class;
    }
}
