<?php

namespace App\Infrastructure\Mailer;

use App\Core\SharedKernel\Port\MailerInterface;
use Symfony\Component\Mailer\MailerInterface as SymfonyMailerInterface;
use Symfony\Component\Mime\Email;

class Mailer implements MailerInterface
{
    private SymfonyMailerInterface $mailer;
    private string $contactEmail;

    public function __construct(SymfonyMailerInterface $mailer, string $contactEmail)
    {
        $this->mailer = $mailer;
        $this->contactEmail = $contactEmail;
    }

    public function send(string $to, string $subject, string $content): void
    {
        $email = new Email();
        $email->from($this->contactEmail)
            ->to($to)
            ->subject($subject)
            ->text($content)
        ;

        $this->mailer->send($email);
    }
}
