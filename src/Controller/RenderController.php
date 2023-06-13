<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RenderController extends AbstractController
{
    #[Route('/', name: 'landing_page')]
    public function landingRender(): Response
    {
        return $this->render('landingPage.html.twig');
    }
}
