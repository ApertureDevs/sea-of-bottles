<?php

namespace App\Core\Component\Message\Port;

use App\Core\Component\Message\Domain\Projection\Sea;

interface SeaProjectionInterface
{
    public function getSea(): Sea;
}
