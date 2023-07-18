<?php

namespace App\Controller\Mailer;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class AppointmentMailer
{
    //     private $mailer;
    //
    //     public function __construct(MailerInterface $mailer)
    //     {
    //         $this->mailer = $mailer;
    //     }
    //
    //     public function sendAppointmentConfirmation($appointment)
    //     {
    //         $email = (new TemplatedEmail())
    //             ->from('hello@example.com')
    //             ->to($appointment->getUser()->getEmail())
    //             ->subject('Confirmation de votre demande')
    //             ->htmlTemplate('emails/appt_confirmation.html.twig')
    //             ->context([
    //                 'Appointment' => $appointment,
    //             ]);
    //
    //         $this->mailer->send($email);
    //     }
}
