<?php

namespace App\Tests\PhpUnit\Framework\TestCase;

use App\Core\SharedKernel\Application\CommandInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class ValidationTestCase extends KernelTestCase
{
    use ContainerTrait;

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
        return self::getContainer()->get(ValidatorInterface::class);
    }
}
