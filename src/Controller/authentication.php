<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class authentication extends AbstractController
{
    #[Route('/', name: 'landing_page')]
    public function landingRender(): Response
    {
        return $this->render('guest_template/landingPage.html.twig');
    }

    #[Route('/connection', name: 'connection_page')]
    public function connectionRender(): Response
    {
        return $this->render('guest_template/connectionPage.html.twig');
    }
}
