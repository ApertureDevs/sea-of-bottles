<?php

namespace App\Core\Component\Message\Application\CreateBottle;

use App\Core\Component\Message\Domain\Model\Bottle;
use App\Core\SharedKernel\Application\CommandResponseInterface;

class CreateBottleResponse implements CommandResponseInterface
{
    public string $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function createFromBottle(Bottle $bottle): CreateBottleResponse
    {
        return new self($bottle->getId());
    }
}
