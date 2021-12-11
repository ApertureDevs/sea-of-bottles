<?php

namespace App\Tests\Test\Infrastructure\Persistence\RelationalModel\Repository;

use App\Infrastructure\Persistence\RelationalModel\Repository\BottleRepository;
use App\Tests\Framework\AggregatePersister;
use App\Tests\Framework\Builder\Message\BottleAggregateBuilder;
use App\Tests\Framework\Builder\Message\SailorAggregateBuilder;
use App\Tests\Framework\TestCase\RepositoryTestCase;

/**
 * @covers \App\Infrastructure\Persistence\RelationalModel\Repository\BottleRepository
 * @group integration
 *
 * @internal
 */
class BottleRepositoryTest extends RepositoryTestCase
{
    private BottleRepository $bottleRepository;

    protected function setUp(): void
    {
        $this->bottleRepository = $this->getRepository();
        $persister = self::getContainer()->get(AggregatePersister::class);
        $persister->persist(BottleAggregateBuilder::new()->build());
        $sailor = SailorAggregateBuilder::new()->build();
        $persister->persist($sailor);
        $persister->persist(BottleAggregateBuilder::new()->wasReceived(sailor: $sailor)->build());
    }

    public function testItShouldReturnRemainingBottlesCount(): void
    {
        self::assertSame(1, $this->bottleRepository->getRemainingBottlesCount());
    }

    public function testItShouldReturnDeliveredBottlesCount(): void
    {
        self::assertSame(1, $this->bottleRepository->getDeliveredBottlesCount());
    }

    protected function getRepositoryClass(): string
    {
        return BottleRepository::class;
    }
}
