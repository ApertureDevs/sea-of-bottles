<?php

namespace App\Core\Component\Message\Port;

use App\Core\Component\Message\Domain\Model\Bottle;
use App\Core\Component\Message\Domain\Model\Sailor;

interface MailerInterface
{
    public function sendBottleReceivedNotification(Sailor $receiver, Bottle $bottle): void;
}
