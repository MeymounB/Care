<?php

namespace App\Security;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(
        VerifyEmailHelperInterface $verifyEmailHelper,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ) {
        $this->verifyEmailHelper = $verifyEmailHelper;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function getConfirmationContext(string $verifyEmailRouteName, int $id, string $email, TemplatedEmail $message): array
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $id,
            $email,
            ['id' => $id] // add the user's id as an extra query param
        );

        $context = $message->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        return $context;
    }

    public function verifyEmail(string $uri, int $id)
    {
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
            $this->verifyEmailHelper->validateEmailConfirmation($uri, $user->getId(), $user->getEmail());
        } catch (VerifyEmailExceptionInterface $e) {
            throw new \Exception($e->getReason());
        }

        // TODO : Verify type of user before setting isVerified to true : only Botanist can be verified
        $user->setIsVerified(true);
        $this->entityManager->flush();
    }
}
