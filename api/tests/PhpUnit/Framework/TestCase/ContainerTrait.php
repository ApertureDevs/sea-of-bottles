<?php

namespace App\Tests\PhpUnit\Framework\TestCase;

use Symfony\Component\DependencyInjection\ContainerInterface;

trait ContainerTrait
{
    protected static function getContainer(): ContainerInterface
    {
        if (false === self::$booted) {
            self::bootKernel();
        }

        return self::$kernel->getContainer()->get('test.service_container');
    }
}
