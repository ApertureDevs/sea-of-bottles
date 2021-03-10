<?php

namespace App\Tests\TestCase;

use Symfony\Component\DependencyInjection\ContainerInterface;

trait ContainerTrait
{
    protected function getContainer(): ContainerInterface
    {
        if (false === self::$booted) {
            self::bootKernel();
        }

        return self::$kernel->getContainer()->get('test.service_container');
    }
}
