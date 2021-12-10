<?php

namespace App\Tests\PhpUnit\Test\Core\Component\Message\Application\CreateSailor;

use App\Core\Component\Message\Application\CreateSailor\CreateSailorQuotaChecker;
use App\Core\Component\Message\Domain\Exception\QuotaException;
use App\Core\Component\Message\Port\SailorStoreInterface;
use App\Core\SharedKernel\Domain\Model\Ip;
use App\Tests\Builder\Message\SailorAggregateBuilder;
use App\Tests\PhpUnit\Framework\TestCase\ContainerTrait;
use App\Tests\PhpUnit\Framework\TestCase\LockableClockTrait;
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
        $quotaChecker = self::getContainer()->get(CreateSailorQuotaChecker::class);
        $this->lockClock(new \DateTimeImmutable('2021-01-01'));
        $quotaChecker->check(Ip::create('::1'));
        $this->createSailor(5);
        $this->expectException(QuotaException::class);
        $quotaChecker->check(Ip::create('::1'));
    }

    protected function createSailor(int $count): void
    {
        $store = self::getContainer()->get(SailorStoreInterface::class);

        while ($count > 0) {
            $sailor = SailorAggregateBuilder::new()->build();
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
