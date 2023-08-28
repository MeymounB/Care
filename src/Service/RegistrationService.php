<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Botanist;
use App\Entity\Particular;
use App\Entity\Certificate;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordEncoder;
    private FileUploaderService $fileUploaderService;
    private MfaService $mfaService;
    private EmailService $emailService;
    private EmailVerifier $emailVerifier;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordEncoder,
        FileUploaderService $fileUploaderService,
        MfaService $mfaService,
        EmailService $emailService,
        EmailVerifier $emailVerifier,
    ) {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->fileUploaderService = $fileUploaderService;
        $this->mfaService = $mfaService;
        $this->emailService = $emailService;
        $this->emailVerifier = $emailVerifier;
    }

    public function processRegistration(Botanist|Particular $user, string $password, ?UploadedFile $certifData, string $route): ?Response
    {
        $this->hashPassword($user, $password);

        if (Botanist::class === get_class($user)) {
            $this->handleBotanistCertificate($user, $certifData);
        }

        $this->handleGoogleAuthSecret($user);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->sendConfirmationEmail($user->getEmail(), $user->getFirstName(), $user->getId());

        return new RedirectResponse($route);
    }

    private function hashPassword(User $user, string $password): void
    {
        $user->setPassword(
            $this->passwordEncoder->hashPassword(
                $user,
                $password
            )
        );
    }

    private function handleBotanistCertificate(Botanist $user, UploadedFile $file): void
    {
        if ($file) {
            $this->fileUploaderService->setType(FileType::CERTIFICATE);

            $safeFilename = $this->fileUploaderService->getFilename(null, $user->getFullName(), $file);

            $this->fileUploaderService->upload($safeFilename['file'], $file);

            $certificate = new Certificate();
            $certificate->setTitle($safeFilename['title']);
            $certificate->setCertificateFile($safeFilename['file']);
            $user->addCertificate($certificate);

            $this->entityManager->persist($certificate);
        }
    }

    private function handleGoogleAuthSecret(User $user): void
    {
        $secret = $this->mfaService->generateSecret();
        $user->setGoogleAuthenticatorSecret($secret);
    }

    private function sendConfirmationEmail(string $email, string $firstName, int $id)
    {
        $message = $this->emailService->create($email);

        $message
            ->subject('Welcome to GreenCare! Verify your email')
            ->htmlTemplate('auth/confirmation_email.html.twig');

        $context = $this->emailVerifier->getConfirmationContext('app_verify_email', $id, $email, $message);

        $message->context([
            ...$context,
            'username' => $firstName,
        ]);

        $this->emailService->send($message);
    }
}
