<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
        private ParameterBagInterface $params,
    ) {
    }

    public function create(string $receiverEmail): TemplatedEmail
    {
        return (new TemplatedEmail())
            ->from(new Address($this->params->get('mail_address'), 'GreenCare'))
            ->to($receiverEmail);
    }

    public function send(TemplatedEmail $email): void
    {
        $this->mailer->send($email);
    }
}
