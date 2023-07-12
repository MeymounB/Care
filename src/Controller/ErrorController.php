<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController {
    #[Route(path: '/no_role', name: 'no_role')]
    public function no_role(): Response
    {
        return $this->render('error/no_role.html.twig');
    }
}