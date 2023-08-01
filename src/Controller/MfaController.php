<?php

namespace App\Controller;

use App\Entity\User;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class MfaController extends AbstractController
{
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
