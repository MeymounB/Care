<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use App\Entity\Advice;
use App\Entity\Appointment;
use App\Entity\Botanist;
use App\Entity\Certificate;
use App\Entity\Comment;
use App\Entity\Particular;
use App\Entity\Plant;
use App\Entity\Request;
use App\Entity\User;
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

        return $this->redirect($adminUrlGenerator->setController(PlantCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('GreenCare');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Plants', 'fa fa-leaf', Plant::class);

        yield MenuItem::subMenu('Users', 'fa fa-users')->setSubItems([
            MenuItem::linkToCrud('All Users', 'fa fa-users', User::class),
            MenuItem::linkToCrud('Individuals', 'fas fa-user', Particular::class),
            MenuItem::linkToCrud('Botanists', 'fas fa-user-tie', Botanist::class),
        ]);

        yield MenuItem::subMenu('Requests', 'fa fa-paper-plane')->setSubItems([
            MenuItem::linkToCrud('All requests', 'fa fa-paper-plane', Request::class),
            MenuItem::linkToCrud('Appointment requests', 'fa fa-calendar-check', Appointment::class),
            MenuItem::linkToCrud('Advice requests', 'fa fa-comments', Advice::class),
        ]);

        yield MenuItem::linkToCrud('Comments', 'fa fa-comment-dots', Comment::class);
        yield MenuItem::linkToCrud('Certificates', 'fa fa-certificate', Certificate::class);
        // yield MenuItem::linkToCrud('Addresses', 'fa fa-home', Address::class);
        yield MenuItem::linkToLogout('Logout', 'fa fa-right-from-bracket');
        // https://fontawesome.com/v6/search?o=r&m=free
    }
}
