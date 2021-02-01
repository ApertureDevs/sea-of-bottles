<?php

namespace App\Core\Component\Message\Port;

use App\Core\Component\Message\Domain\Model\Sea;

interface SeaProjectionInterface
{
    public function getSea(): Sea;
}
