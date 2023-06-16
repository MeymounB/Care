<?php

namespace App\Controller;

use App\Entity\Particular;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private VerifyEmailHelperInterface $verifyEmailHelper;

    public function __construct(EmailVerifier $emailVerifier, VerifyEmailHelperInterface $verifyEmailHelper)
    {
        $this->emailVerifier = $emailVerifier;
        $this->verifyEmailHelper = $verifyEmailHelper;
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/', name: 'register_page')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer, MessageBusInterface $bus): Response
    {
        $user = new Particular();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            /*$this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('from@example.com', 'GreenCare'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );*/
            // do anything else you need here, like send an email

            $email = new TemplatedEmail();
            $email
                ->from(new Address($this->getParameter('mail_address'), 'GreenCare'))
                ->to($user->getEmail())
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->htmlTemplate('registration/confirmation_email.html.twig')
                ->context([
                    'username' => $user->getFirstName(),
                ]);

            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                $email
            );

            return $this->redirectToRoute('register_page');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
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

        $user->setIsVerified(true);
        $entityManager->flush();

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre adresse email a été vérifiée avec succès ! Redirection en cours...');

        return $this->redirectToRoute('register_page');
    }
}
