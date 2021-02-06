<?php

namespace App\Core\SharedKernel\Port;

interface MailerInterface
{
    public function send(string $to, string $subject, string $content): void;
}
