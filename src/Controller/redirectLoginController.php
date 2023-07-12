<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Botanist;
use App\Entity\Particular;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class redirectLoginController implements AuthenticationSuccessHandlerInterface
{
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $user = $token->getUser();

        if ($user instanceof Botanist) {
            return new RedirectResponse('/admin'); #TODO mettre vers les bonnes url une fois créé
        } elseif ($user instanceof Particular) {
            return new RedirectResponse('/admin'); #TODO mettre vers les bonnes url une fois créé
        } elseif ($user instanceof Admin) {
            return new RedirectResponse('/admin');
        }

        // Redirection par défaut si aucun rôle ne correspond
        return new RedirectResponse('/no_role');
    }
}
