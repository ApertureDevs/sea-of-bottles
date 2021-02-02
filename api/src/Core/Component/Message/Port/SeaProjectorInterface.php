<?php

namespace App\Core\Component\Message\Port;

use App\Core\Component\Message\Domain\Model\Sea;

interface SeaProjectorInterface
{
    public function getSea(): Sea;
}
