<?php

namespace App\Tests\TestCase;

use App\Core\SharedKernel\Application\CommandInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class ValidationTestCase extends KernelTestCase
{
    abstract public function provideValidationCases(): iterable;

    /**
     * @dataProvider provideValidationCases
     */
    public function testItShouldValidate(CommandInterface $command, array $expectedResults): void
    {
        $errors = [];
        foreach ($this->getValidator()->validate($command) as $constraintViolation) {
            $errors[$constraintViolation->getPropertyPath()][] = $constraintViolation->getMessage();
        }

        self::assertSame($expectedResults, $errors);
    }

    protected function getValidator(): ValidatorInterface
    {
        return $this->getContainer()->get(ValidatorInterface::class);
    }

    protected function getContainer(): ContainerInterface
    {
        if (false === self::$booted) {
            self::bootKernel();
        }

        return self::$kernel->getContainer()->get('test.service_container');
    }
}
