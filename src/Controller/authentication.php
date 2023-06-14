<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class authentication extends AbstractController
{
    #[Route('/', name: 'connection_page')]
    public function connectionRender(): Response
    {
        return $this->render('guest_template/connectionPage.html.twig');
    }
}
