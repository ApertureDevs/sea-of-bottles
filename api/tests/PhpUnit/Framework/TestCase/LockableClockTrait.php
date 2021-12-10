<?php

namespace App\Tests\PhpUnit\Framework\TestCase;

use App\Core\SharedKernel\Port\ClockInterface;
use App\Tests\PhpUnit\Framework\TestDecorator\LockableClock;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait LockableClockTrait
{
    protected function lockClock(\DateTimeImmutable $lockedDate): void
    {
        $this->getLockableClock()->lock($lockedDate);
    }

    protected function unlockClock(): void
    {
        $this->getLockableClock()->unlock();
    }

    protected function getLockableClock(): LockableClock
    {
        if (false === self::$booted) {
            self::bootKernel();
        }

        /** @var ContainerInterface $container */
        $container = self::$kernel->getContainer()->get('test.service_container');
        $clock = $container->get(ClockInterface::class);

        if (!$clock instanceof LockableClock) {
            throw new \RuntimeException('Clock should be lockable.');
        }

        return $clock;
    }
}
