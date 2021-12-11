<?php

namespace App\Tests\Test\Infrastructure\Persistence\RelationalModel\Repository;

use App\Infrastructure\Persistence\RelationalModel\Repository\SailorRepository;
use App\Tests\Framework\AggregatePersister;
use App\Tests\Framework\Builder\Message\SailorAggregateBuilder;
use App\Tests\Framework\TestCase\RepositoryTestCase;

/**
 * @covers \App\Infrastructure\Persistence\RelationalModel\Repository\SailorRepository
 * @group integration
 *
 * @internal
 */
class SailorRepositoryTest extends RepositoryTestCase
{
    private SailorRepository $sailorRepository;

    protected function setUp(): void
    {
        $this->sailorRepository = $this->getRepository();
        $persister = self::getContainer()->get(AggregatePersister::class);
        $sailor = SailorAggregateBuilder::new()->build();
        $persister->persist($sailor);
    }

    public function testItShouldReturnSailorsCount(): void
    {
        self::assertSame(1, $this->sailorRepository->getSailorsCount());
    }

    protected function getRepositoryClass(): string
    {
        return SailorRepository::class;
    }
}
