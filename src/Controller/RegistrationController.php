<?php

namespace App\Controller;

use App\Entity\Botanist;
use App\Entity\Certificate;
use App\Entity\Particular;
use App\Form\Registration\BotanistFormType;
use App\Form\Registration\ParticularFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private SluggerInterface $slugger;

    public function __construct(EmailVerifier $emailVerifier, VerifyEmailHelperInterface $verifyEmailHelper, SluggerInterface $slugger)
    {
        $this->emailVerifier = $emailVerifier;
        $this->verifyEmailHelper = $verifyEmailHelper;
        $this->slugger = $slugger;
    }

    private function sendConfirmationEmail($user)
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->getParameter('mail_address'), 'GreenCare'))
            ->to($user->getEmail())
            ->subject('Welcome to GreenCare! Verify your email')
            ->htmlTemplate('auth/confirmation_email.html.twig')
            ->context([
                'username' => $user->getFirstName(),
            ]);

        $this->emailVerifier->sendEmailConfirmation(
            'app_verify_email',
            $user,
            $email
        );
    }

    private function processRegistration(Request $request, UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $entityManager, Botanist|Particular $user, string $formType, string $template): Response
    {
        $form = $this->createForm($formType, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            if (Botanist::class === get_class($user)) {
                $certifData = $form->get('certif')->getData();

                if ($certifData) {
                    $currentTime = time();
                    $newFilename = 'Document_certification_'.$user->getFullName().'_'.$currentTime;
                    $safeFilename = $this->slugger->slug($newFilename).'.'.$certifData->guessExtension();

                    try {
                        $certifData->move(
                            $this->getParameter('certif_directory'),
                            $safeFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    $certificate = new Certificate();

                    $certificate->setTitle($user->getFullName().' - '.$currentTime);
                    $certificate->setCertificateFile($safeFilename);
                    $user->addCertificate($certificate);

                    $entityManager->persist($certificate);
                }
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->sendConfirmationEmail($user);

            return $this->redirectToRoute('app_login');
        }

        return $this->render($template, [
            'form' => $form->createView(),
            'error' => $form->getErrors()->current(),
        ]);
    }

    #[Route('/', name: 'register_page')]
    public function register(Request $request, UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $entityManager): Response
    {
        return $this->processRegistration($request, $passwordEncoder, $entityManager, new Particular(), ParticularFormType::class, 'auth/register.html.twig');
    }

    #[Route('/botanist', name: 'register_page_doctor')]
    public function registerBot(Request $request, UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $entityManager): Response
    {
        return $this->processRegistration($request, $passwordEncoder, $entityManager, new Botanist(), BotanistFormType::class, 'auth/register_botanist.html.twig');
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('register_page');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout', methods: ['POST', 'GET'])]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $id = $request->get('id'); // retrieve the user id from the url

        // Verify the user id exists and is not null
        if (null === $id) {
            return $this->redirectToRoute('register_page');
        }

        $user = $userRepository->find($id);

        // Ensure the user exists in persistence
        if (null === $user) {
            return $this->redirectToRoute('register_page');
        }

        // Do not get the User's Id or Email Address from the Request object
        try {
            $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('verify_email_error', $e->getReason());

            return $this->redirectToRoute('register_page');
        }

        // TODO : Verify type of user before setting isVerified to true : only Botanist can be verified
        $user->setIsVerified(true);
        $entityManager->flush();

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre adresse email a été vérifiée avec succès ! Redirection en cours...');

        return $this->redirectToRoute('register_page');
    }
}
