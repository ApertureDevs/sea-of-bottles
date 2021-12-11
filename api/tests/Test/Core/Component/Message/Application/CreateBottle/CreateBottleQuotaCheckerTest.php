<?php

namespace App\Tests\Test\Core\Component\Message\Application\CreateBottle;

use App\Core\Component\Message\Application\CreateBottle\CreateBottleQuotaChecker;
use App\Core\Component\Message\Domain\Exception\QuotaException;
use App\Core\Component\Message\Port\BottleStoreInterface;
use App\Core\SharedKernel\Domain\Model\Ip;
use App\Tests\Framework\Builder\Message\BottleAggregateBuilder;
use App\Tests\Framework\TestCase\ContainerTrait;
use App\Tests\Framework\TestCase\LockableClockTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @covers \App\Core\Component\Message\Application\CreateBottle\CreateBottleQuotaChecker
 * @group integration
 *
 * @internal
 */
class CreateBottleQuotaCheckerTest extends KernelTestCase
{
    use LockableClockTrait;
    use ContainerTrait;

    public function testItShouldCheckCreationQuota(): void
    {
        $quotaChecker = self::getContainer()->get(CreateBottleQuotaChecker::class);
        $this->lockClock(new \DateTimeImmutable('2021-01-01'));
        $quotaChecker->check(Ip::create('::1'));
        $this->createBottle(5);
        $this->expectException(QuotaException::class);
        $quotaChecker->check(Ip::create('::1'));
    }

    protected function createBottle(int $count): void
    {
        $store = self::getContainer()->get(BottleStoreInterface::class);

        while ($count > 0) {
            $bottle = BottleAggregateBuilder::new()->build();
            $store->store($bottle);
            --$count;
        }
    }

    protected function tearDown(): void
    {
        $this->unlockClock();
        parent::tearDown();
    }
}
