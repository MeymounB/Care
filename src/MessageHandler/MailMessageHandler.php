<?php

namespace App\MessageHandler;

use App\Message\MailMessage;
use App\Service\EmailService;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;


#[AsMessageHandler]
class MailMessageHandler implements MessageHandlerInterface
{
    private MailerInterface $mailer;
    private EmailService $emailService;

    public function __construct(MailerInterface $mailer, EmailService $emailService)
    {
        $this->mailer = $mailer;
        $this->emailService = $emailService;
    }

    public function __invoke(MailMessage $message)
    {
        $email = $this->emailService->create($message->getReceiver());

        $email
            ->subject($message->getSubject())
            ->context($message->getContext())
            ->htmlTemplate($message->getTemplate());

        $this->mailer->send($email);
    }
}
