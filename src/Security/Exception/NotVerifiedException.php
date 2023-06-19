<?php

namespace App\Security\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class NotVerifiedException extends AuthenticationException
{
    public function __construct()
    {
        parent::__construct("Votre adresse e-mail n'a pas été vérifiée.");
    }
}
