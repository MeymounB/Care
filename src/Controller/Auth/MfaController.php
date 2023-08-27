<?php

namespace App\Controller\Auth;

use App\Service\MfaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class MfaController extends AbstractController
{
    public function __construct(private MfaService $mfaService)
    {
    }

    #[Route('/2fa', name: '2fa_login')]
    public function check2fa(Request $request, Security $security): Response
    {
        if (!$security->getUser() || !$security->isGranted('IS_AUTHENTICATED_2FA_IN_PROGRESS')) {
            return $this->redirectToRoute('app_login');
        }

        $url = $this->mfaService->getQRContent($security->getUser());

        $authenticationException = $this->getLastAuthenticationException($request->getSession());

        return $this->render('auth/2fa_form.html.twig', [
            'codeUrl' => $url,
            'checkPathUrl' => '/2fa_check',
            'authCodeParameterName' => '_auth_code',
            'authenticationError' => $authenticationException?->getMessage(),
            'authenticationErrorData' => $authenticationException?->getMessageData(),
        ]);
    }

    protected function getLastAuthenticationException(SessionInterface $session): ?AuthenticationException
    {
        $authException = $session->get(Security::AUTHENTICATION_ERROR);
        if ($authException instanceof AuthenticationException) {
            $session->remove(Security::AUTHENTICATION_ERROR);

            return $authException;
        }

        return null; // The value does not come from the security component.
    }
}
