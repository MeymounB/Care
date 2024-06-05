<?php

namespace App\Service;

use App\Entity\User;
use App\Message\MailMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class ResetPasswordService
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private EntityManagerInterface $entityManager,
        private UrlGeneratorInterface $generator,
        private UrlGeneratorInterface $router,
        private ContainerInterface $container,
        private TranslatorInterface $translator,
        private FlashBagInterface $flash
    ) {
    }

    public function processSendingPasswordResetEmail(string $emailFormData, MessageBusInterface $bus, $resetToken = null): RedirectResponse
    {
        $user = $this->entityManager
            ->getRepository(
                User::class
            )->findOneBy([
                'email' => $emailFormData,
            ]);

        // Do not reveal whether a user account was found or not.
        if (!$user) {
            return new RedirectResponse($this->router->generate('app_check_email'));
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            // If you want to tell the user why a reset email was not sent, uncomment
            // the lines below and change the redirect to 'app_forgot_password_request'.
            // Caution: This may reveal if a user is registered or not.
            //
            // $this->flash->add('reset_password_error', sprintf(
            //     '%s - %s',
            //     $this->translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_HANDLE, [], 'ResetPasswordBundle'),
            //     $this->translator->trans($e->getReason(), [], 'ResetPasswordBundle')
            // ));

            // return new RedirectResponse($this->router->generate('app_check_email'));
        }

        // $message = $emailService->create($emailFormData);

        // $message
        //     ->subject('Your password reset request')
        //     ->htmlTemplate('reset_password/email.html.twig')
        //     ->context([
        //         'username' => $user->getFirstName(),
        //         'resetToken' => $resetToken,
        //         'requestUrl' => $appRequestPasswordUrl,
        //     ]);

        // $emailService->send($message);
        $this->sendMail(
            $resetToken,
            $emailFormData,
            $user,
            $bus
        );

        // Store the token object in session for retrieval in check-email route.
        $this->setTokenObjectInSession($resetToken);

        return new RedirectResponse($this->router->generate('app_check_email'));
    }

    private function sendMail(ResetPasswordToken $resetToken, string $emailFormData, User $user, MessageBusInterface $bus): void
    {
        $appRequestPasswordUrl = $this->generator->generate('app_reset_password', ['token' => $resetToken->getToken()], UrlGenerator::ABSOLUTE_URL);

        $message = new MailMessage(
            'Your password reset request from dispatch',
            $emailFormData,
            'reset_password/email.html.twig',
            [
                'username' => $user->getFirstName(),
                'resetToken' => $resetToken,
                'requestUrl' => $appRequestPasswordUrl,
            ]
        );

        $bus->dispatch($message);
    }

    public function processFormSubmit(string $token, User $user, UserPasswordHasherInterface $passwordHasher, string $password): RedirectResponse
    {
        // A password reset token should be used only once, remove it.
        $this->resetPasswordHelper->removeResetRequest($token);

        // Encode(hash) the plain password, and set it.
        $encodedPassword = $passwordHasher->hashPassword(
            $user,
            $password
        );

        $user->setPassword($encodedPassword);
        $this->hashPassword($passwordHasher, $user, $password);
        $this->entityManager->flush();

        // The session is cleaned up after the password has been changed.
        $this->cleanSessionAfterReset();

        return new RedirectResponse($this->router->generate('app_login'));
    }

    private function hashPassword(UserPasswordHasherInterface $passwordEncoder, User $user, string $password): void
    {
        $user->setPassword(
            $passwordEncoder->hashPassword(
                $user,
                $password
            )
        );
    }
}
