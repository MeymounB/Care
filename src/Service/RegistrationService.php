<?php

namespace App\Service;

use App\Entity\Botanist;
use App\Entity\Certificate;
use App\Entity\Particular;
use App\Form\Registration\BotanistFormType;
use App\Form\Registration\ParticularFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// The use of AbstractController is not recommended in services, but it is used here for the sake of simplicity
// @TODO: refactor this service to avoid using AbstractController
class RegistrationService extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordEncoder;
    private FileUploaderService $fileUploaderService;
    private MfaService $mfaService;
    private EmailService $emailService;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordEncoder,
        FileUploaderService $fileUploaderService,
        MfaService $mfaService,
        EmailService $emailService
    ) {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->fileUploaderService = $fileUploaderService;
        $this->mfaService = $mfaService;
        $this->emailService = $emailService;
    }

    public function processRegistrationForParticular(Request $request): Response
    {
        return $this->processRegistration($request, new Particular(), ParticularFormType::class, 'auth/register.html.twig');
    }

    public function processRegistrationForBotanist(Request $request): Response
    {
        return $this->processRegistration($request, new Botanist(), BotanistFormType::class, 'auth/register_botanist.html.twig');
    }

    private function processRegistration(Request $request, $user, string $formType, string $template): Response
    {
        $form = $this->createForm($formType, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->passwordEncoder->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            if (Botanist::class === get_class($user)) {
                $certifData = $form->get('certif')->getData();

                if ($certifData) {
                    $this->fileUploaderService->setType(FileType::CERTIFICATE);

                    $safeFilename = $this->fileUploaderService->getFilename(null, $user->getFullName(), $certifData);

                    $this->fileUploaderService->upload($safeFilename['file'], $certifData);

                    $certificate = new Certificate();
                    $certificate->setTitle($safeFilename['title']);
                    $certificate->setCertificateFile($safeFilename['file']);
                    $user->addCertificate($certificate);

                    $this->entityManager->persist($certificate);
                }
            }

            $secret = $this->mfaService->generateSecret();
            $user->setGoogleAuthenticatorSecret($secret);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->emailService->sendConfirmationEmail($user, $this->getParameter('mail_address'));

            return $this->redirectToRoute('app_login');
        }

        return $this->render($template, [
            'form' => $form->createView(),
            'error' => $form->getErrors()->current(),
        ]);
    }
}
