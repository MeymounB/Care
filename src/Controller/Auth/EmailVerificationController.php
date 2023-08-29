<?php

namespace App\Controller\Auth;

use App\Security\EmailVerifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailVerificationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        try {
            $this->emailVerifier->verifyEmail($request->getUri(), $request->get('id'));
            $this->addFlash('success', 'Votre adresse email a été vérifiée avec succès !');

            return $this->redirectToRoute('app_login');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());

            return $this->redirectToRoute('app_register', ['type' => ""]);
        }
    }
}
