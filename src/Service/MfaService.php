<?php

namespace App\Service;

use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class MfaService
{
    public function __construct(
        private readonly GoogleAuthenticatorInterface $authenticator,
    ) {
    }

    public function generateSecret(): string
    {
        return $this->authenticator->generateSecret();
    }

    public function getQRContent(TwoFactorInterface|UserInterface $user): string
    {
        $code = $this->authenticator->getQRContent($user);

        return 'https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=' . $code;
    }
}
