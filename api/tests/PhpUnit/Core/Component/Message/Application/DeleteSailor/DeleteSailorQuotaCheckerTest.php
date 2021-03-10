<?php

namespace App\Tests\PhpUnit\Core\Component\Message\Application\DeleteSailor;

use App\Core\Component\Message\Application\DeleteSailor\DeleteSailorQuotaChecker;
use App\Core\Component\Message\Domain\Exception\QuotaException;
use App\Core\Component\Message\Port\SailorStoreInterface;
use App\Core\SharedKernel\Domain\Model\Ip;
use App\Tests\Factory\Message\SailorAggregateFactory;
use App\Tests\TestCase\ContainerTrait;
use App\Tests\TestCase\LockableClockTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @covers \App\Core\Component\Message\Application\DeleteSailor\DeleteSailorQuotaChecker
 *
 * @internal
 */
class DeleteSailorQuotaCheckerTest extends KernelTestCase
{
    use ContainerTrait;
    use LockableClockTrait;

    public function testItShouldCheckDeletionQuota(): void
    {
        $quotaChecker = $this->getContainer()->get(DeleteSailorQuotaChecker::class);
        $this->lockClock(new \DateTimeImmutable('2021-01-01'));
        $quotaChecker->check(Ip::create('::1'));
        $this->deleteSailor(2);
        self::expectException(QuotaException::class);
        $quotaChecker->check(Ip::create('::1'));
    }

    protected function deleteSailor(int $count): void
    {
        $store = $this->getContainer()->get(SailorStoreInterface::class);

        while ($count > 0) {
            $sailor = SailorAggregateFactory::createDeletedSailor();
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
