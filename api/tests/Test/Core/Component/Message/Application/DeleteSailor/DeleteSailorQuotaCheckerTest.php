<?php

namespace App\Tests\Test\Core\Component\Message\Application\DeleteSailor;

use App\Core\Component\Message\Application\DeleteSailor\DeleteSailorQuotaChecker;
use App\Core\Component\Message\Domain\Exception\QuotaException;
use App\Core\Component\Message\Port\SailorStoreInterface;
use App\Core\SharedKernel\Domain\Model\Ip;
use App\Tests\Framework\Builder\Message\SailorAggregateBuilder;
use App\Tests\Framework\TestCase\ContainerTrait;
use App\Tests\Framework\TestCase\LockableClockTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @covers \App\Core\Component\Message\Application\DeleteSailor\DeleteSailorQuotaChecker
 * @group integration
 *
 * @internal
 */
class DeleteSailorQuotaCheckerTest extends KernelTestCase
{
    use ContainerTrait;
    use LockableClockTrait;

    public function testItShouldCheckDeletionQuota(): void
    {
        $quotaChecker = self::getContainer()->get(DeleteSailorQuotaChecker::class);
        $this->lockClock(new \DateTimeImmutable('2021-01-01'));
        $quotaChecker->check(Ip::create('::1'));
        $this->deleteSailor(2);
        $this->expectException(QuotaException::class);
        $quotaChecker->check(Ip::create('::1'));
    }

    protected function deleteSailor(int $count): void
    {
        $store = self::getContainer()->get(SailorStoreInterface::class);

        while ($count > 0) {
            $sailor = SailorAggregateBuilder::new()->wasDeleted()->build();
            $store->store($sailor);
            --$count;
        }
    }

    protected function tearDown(): void
    {
        $this->unlockClock();
        parent::tearDown();
    }
}
