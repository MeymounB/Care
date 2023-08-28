<?php

namespace App\Controller\Auth;

use App\Entity\Botanist;
use App\Entity\Particular;
use App\Service\RegistrationService;
use App\Form\Registration\ParticularFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register/{type?}', name: 'app_register')]
    public function register(?string $type, Request $request, RegistrationService $registrationService, UrlGeneratorInterface $router, MessageBusInterface $bus): Response
    {
        if (isset($type)) {
            $user = new Botanist();
            $formType = BotanistFormType::class;
            $template = 'auth/register_botanist.html.twig';
        } else {
            $user = new Particular();
            $formType = ParticularFormType::class;
            $template = 'auth/register.html.twig';
        }

        $form = $this->createForm($formType, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registrationService->processRegistration(
                $user,
                $form->get('password')->getData(),
                $form->has('certif') ? $form->get('certif')->getData() : null,
                $router->generate('app_login'),
                $bus
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render($template, [
            'form' => $form->createView(),
            'error' => $form->getErrors()->current(),
        ]);
    }
}
