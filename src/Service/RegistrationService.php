<?php

namespace App\Service;

use App\Entity\Botanist;
use App\Entity\Certificate;
use App\Entity\Particular;
use App\Entity\User;
use App\Message\MailMessage;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService
{
    // Dependency injection for services and components
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordEncoder;
    private FileUploaderService $fileUploaderService;
    private MfaService $mfaService;
    private EmailVerifier $emailVerifier;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordEncoder,
        FileUploaderService $fileUploaderService,
        MfaService $mfaService,
        EmailVerifier $emailVerifier,
    ) {
        // Initialize dependencies through constructor
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->fileUploaderService = $fileUploaderService;
        $this->mfaService = $mfaService;
        $this->emailVerifier = $emailVerifier;
    }

    // Method to process user registration (botanist or individual)
    public function processRegistration(Botanist|Particular $user, string $password, ?UploadedFile $certifData, string $route, MessageBusInterface $bus): ?Response
    {
        // Hash the password before storing it
        $this->hashPassword($user, $password);

        // If the user is a botanist, handle their certificate
        if (Botanist::class === get_class($user)) {
            $this->handleBotanistCertificate($user, $certifData);
        }

        // Handle the Google Authenticator secret for two-factor authentication (MFA)
        $this->handleGoogleAuthSecret($user);

        // Persist the user to the database
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Send a confirmation email
        $this->sendConfirmationEmail(
            $user->getEmail(),
            $user->getFirstName(),
            $user->getId(),
            $bus
        );

        // Redirect the user to the specified route
        return new RedirectResponse($route);
    }

    // Private method to hash the user's password
    private function hashPassword(User $user, string $password): void
    {
        $user->setPassword(
            $this->passwordEncoder->hashPassword(
                $user,
                $password
            )
        );
    }

    // Private method to handle the botanist's certificate
    private function handleBotanistCertificate(Botanist $user, UploadedFile $file): void
    {
        if ($file) {
            // Set the file type as CERTIFICATE
            $this->fileUploaderService->setType(FileType::CERTIFICATE);

            // Get a secure filename based on the user's full name
            $safeFilename = $this->fileUploaderService->getFilename(null, $user->getFullName(), $file);

            // Upload the file
            $this->fileUploaderService->upload($safeFilename['file'], $file);

            // Create a new certificate instance and associate it with the user
            $certificate = new Certificate();
            $certificate->setTitle($safeFilename['title']);
            $certificate->setCertificateFile($safeFilename['file']);
            $user->addCertificate($certificate);

            // Persist the certificate in the database
            $this->entityManager->persist($certificate);
        }
    }

    // Private method to handle the Google Authenticator secret
    private function handleGoogleAuthSecret(User $user): void
    {
        // Generate a new secret key
        $secret = $this->mfaService->generateSecret();
        $user->setGoogleAuthenticatorSecret($secret);
    }

    // Private method to send a confirmation email
    private function sendConfirmationEmail(string $email, string $firstName, int $id, MessageBusInterface $bus)
    {
        // Get the context for the confirmation email
        $context = $this->emailVerifier->getConfirmationContext('app_verify_email', $id, $email);
        $context['username'] = $firstName;

        // Create an email message
        $message = new MailMessage(
            'Welcome to GreenCare! Verify your email',
            $email,
            'auth/confirmation_email.html.twig',
            $context
        );

        // Send the message via the message bus
        $bus->dispatch($message);
    }
}