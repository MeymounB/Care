<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    public function create(string $receiverEmail): TemplatedEmail
    {
        return (new TemplatedEmail())
            ->to($receiverEmail)
            ->embedFromPath('public/images/logo.png', 'greencare-logo', 'image/png');
    }

    public function send(TemplatedEmail $email): void
    {
        $this->mailer->send($email);
    }
}
