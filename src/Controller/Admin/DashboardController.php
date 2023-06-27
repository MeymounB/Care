<?php

namespace App\Controller\Admin;

use App\Entity\Plant;
use App\Entity\User;
use App\Entity\Particular;
use App\Entity\Botanist;
use App\Entity\Advice;
use App\Entity\Appointment;
use App\Entity\Comment;
use App\Entity\Address;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('GreenCare');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('User', 'fa fa-home', User::class);
        yield MenuItem::linkToCrud('Plant', 'fa fa-home', Plant::class);
        yield MenuItem::linkToCrud('Particular', 'fa fa-home', Particular::class);
        yield MenuItem::linkToCrud('Botanist', 'fa fa-home', Botanist::class);
        yield MenuItem::linkToCrud('appointmentRequest', 'fa fa-home', Appointment::class);
        yield MenuItem::linkToCrud('adviceRequest', 'fa fa-home', Advice::class);
        yield MenuItem::linkToCrud('Comment', 'fa fa-home', Comment::class);
        yield MenuItem::linkToCrud('Address', 'fa fa-home', Address::class);
        yield MenuItem::linkToLogout('Logout', 'fa fa-exit');
        // https://fontawesome.com/v6/search?o=r&m=free
    }
}
