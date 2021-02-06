<?php

namespace App\Core\Component\Message\Application\ShowSea;

use App\Core\SharedKernel\Application\QueryInterface;

class ShowSeaQuery implements QueryInterface
{
    public static function create(): self
    {
        return new self();
    }
}
