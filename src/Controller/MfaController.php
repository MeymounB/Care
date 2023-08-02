<?php

namespace App\Controller;

use App\Service\MfaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function check2fa(GoogleAuthenticatorInterface $authenticator, TokenStorageInterface $storage): Response
    {
        $code = $authenticator->getQRContent($storage->getToken()->getUser());

        $url = "https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=" . $code;

        return $this->render('auth/2fa_form.html.twig', [
            'codeUrl' => $url,
            'checkPathUrl' => '/2fa_check',
            'authCodeParameterName' => '_auth_code'
        ]);
    }
}
