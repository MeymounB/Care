<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'links' => [
                ['img' => 'calendar.svg', 'alt' => 'of a calendar', 'text' => 'Réservation'],
                ['img' => 'chat.svg', 'alt' => 'of a chat', 'text' => 'Discussion'],
                ['img' => 'share.svg', 'alt' => 'of share', 'text' => 'Partage'],
            ],
        ]);
    }

    #[Route('/legal_mentions', name: 'legal_mentions')]
    public function legal_mentions(): Response
    {
        return $this->render('default/cgu.html.twig');
    }
}
