<?php

namespace App\Controller\Auth;

use App\Service\RegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    private RegistrationService $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    #[Route('/register', name: 'register_particular')]
    public function registerParticular(Request $request): Response
    {
        return $this->registrationService->processRegistrationForParticular($request);
    }

    #[Route('/register/botanist', name: 'register_botanist')]
    public function registerBotanist(Request $request): Response
    {
        return $this->registrationService->processRegistrationForBotanist($request);
    }
}
