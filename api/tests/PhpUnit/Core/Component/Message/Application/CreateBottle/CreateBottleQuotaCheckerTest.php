<?php

namespace App\Tests\PhpUnit\Core\Component\Message\Application\CreateBottle;

use App\Core\Component\Message\Application\CreateBottle\CreateBottleQuotaChecker;
use App\Core\Component\Message\Domain\Exception\QuotaException;
use App\Core\Component\Message\Port\BottleStoreInterface;
use App\Core\SharedKernel\Domain\Model\Ip;
use App\Tests\Factory\Message\BottleAggregateFactory;
use App\Tests\TestCase\ContainerTrait;
use App\Tests\TestCase\LockableClockTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @covers \App\Core\Component\Message\Application\CreateBottle\CreateBottleQuotaChecker
 *
 * @internal
 */
class CreateBottleQuotaCheckerTest extends KernelTestCase
{
    use LockableClockTrait;
    use ContainerTrait;

    public function testItShouldCheckCreationQuota(): void
    {
        $quotaChecker = $this->getContainer()->get(CreateBottleQuotaChecker::class);
        $this->lockClock(new \DateTimeImmutable('2021-01-01'));
        $quotaChecker->check(Ip::create('::1'));
        $this->createBottle(5);
        self::expectException(QuotaException::class);
        $quotaChecker->check(Ip::create('::1'));
    }

    protected function createBottle(int $count): void
    {
        $store = $this->getContainer()->get(BottleStoreInterface::class);

        while ($count > 0) {
            $bottle = BottleAggregateFactory::createBottle();
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
