<?php

namespace App\Infrastructure\Mailer;

use App\Core\Component\Message\Domain\Model\Bottle;
use App\Core\Component\Message\Domain\Model\Sailor;
use App\Core\Component\Message\Port\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface as SymfonyMailerInterface;

class Mailer implements MailerInterface
{
    private SymfonyMailerInterface $mailer;
    private string $contactEmail;

    public function __construct(SymfonyMailerInterface $mailer, string $contactEmail)
    {
        $this->mailer = $mailer;
        $this->contactEmail = $contactEmail;
    }

    public function sendBottleReceivedNotification(Sailor $receiver, Bottle $bottle): void
    {
        $email = new TemplatedEmail();
        $email->from($this->contactEmail)
            ->to($receiver->getEmail()->getAddress())
            ->subject('You have received a Bottle!')
            ->htmlTemplate('emails/bottle-received.html.twig')
            ->textTemplate('emails/bottle-received.txt.twig')
            ->context(
                [
                    'subject' => 'You have received a Bottle from the Sea!',
                    'message' => $bottle->getMessage()->getContent(),
                ]
            )
        ;

        $this->mailer->send($email);
    }
}
