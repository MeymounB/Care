<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailService
{
    private EmailVerifier $emailVerifier;
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(EmailVerifier $emailVerifier, VerifyEmailHelperInterface $verifyEmailHelper, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->emailVerifier = $emailVerifier;
        $this->verifyEmailHelper = $verifyEmailHelper;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function sendConfirmationEmail($user, $mailAddress): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($mailAddress, 'GreenCare'))
            ->to($user->getEmail())
            ->subject('Welcome to GreenCare! Verify your email')
            ->htmlTemplate('auth/confirmation_email.html.twig')
            ->context([
                'username' => $user->getFirstName(),
            ]);

        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user, $email);
    }

    public function validateEmailConfirmation(string $uri, int $userId, string $userEmail): void
    {
        try {
            $this->verifyEmailHelper->validateEmailConfirmation($uri, $userId, $userEmail);
        } catch (VerifyEmailExceptionInterface $e) {
            throw new \Exception($e->getReason());
        }
    }

    public function verifyUserEmail(Request $request): Response
    {
        // retrieve the user id from the url
        $id = $request->get('id');

        // Verify the user id exists and is not null
        if (null === $id) {
            throw new \Exception('Invalid user ID.');
        }

        $user = $this->userRepository->find($id);

        // Ensure the user exists in persistence
        if (null === $user) {
            throw new \Exception('User not found.');
        }

        // Do not get the User's Id or Email Address from the Request object
        try {
            $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());
        } catch (VerifyEmailExceptionInterface $e) {
            throw new \Exception($e->getReason());
        }

        // TODO : Verify type of user before setting isVerified to true : only Botanist can be verified
        $user->setIsVerified(true);
        $this->entityManager->flush();
        // return $user;
        return new Response('Email verification successful.');
    }
}
