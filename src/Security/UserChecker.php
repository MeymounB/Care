<?php

namespace App\Security;

use App\Entity\User;
use App\Security\Exception\NotVerifiedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        // TODO: Implement checkPreAuth() method.
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }
        if (!$user->isVerified()) {
            throw new NotVerifiedException();
        }
    }
}
