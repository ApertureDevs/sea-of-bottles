<?php

namespace App\Tests\PhpUnit\Core\Component\Message\Application\CreateSailor;

use App\Core\Component\Message\Application\CreateSailor\CreateSailorQuotaChecker;
use App\Core\Component\Message\Domain\Exception\QuotaException;
use App\Core\Component\Message\Port\SailorStoreInterface;
use App\Core\SharedKernel\Domain\Model\Ip;
use App\Tests\Factory\Message\SailorAggregateFactory;
use App\Tests\TestCase\ContainerTrait;
use App\Tests\TestCase\LockableClockTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @covers \App\Core\Component\Message\Application\CreateSailor\CreateSailorQuotaChecker
 *
 * @internal
 */
class CreateSailorQuotaCheckerTest extends KernelTestCase
{
    use ContainerTrait;
    use LockableClockTrait;

    public function testItShouldCheckCreationQuota(): void
    {
        $quotaChecker = $this->getContainer()->get(CreateSailorQuotaChecker::class);
        $this->lockClock(new \DateTimeImmutable('2021-01-01'));
        $quotaChecker->check(Ip::create('::1'));
        $this->createSailor(5);
        self::expectException(QuotaException::class);
        $quotaChecker->check(Ip::create('::1'));
    }

    protected function createSailor(int $count): void
    {
        $store = $this->getContainer()->get(SailorStoreInterface::class);

        while ($count > 0) {
            $sailor = SailorAggregateFactory::createSailor();
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
