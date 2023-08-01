<?php

namespace App\Controller;

use App\Repository\AdviceRepository;
use App\Repository\AppointmentRepository;
use App\Repository\PlantRepository;
use App\Repository\StatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/botanist')]
class BotanistController extends AbstractController
{
    #[Route('/', name: 'app_botanist_index', methods: ['GET'])]
    public function index(AppointmentRepository $appointmentRepository, StatusRepository $statusRepository): Response
    {
        return $this->render('botanist/dashboard.html.twig');
    }

    #[Route('/all_advice', name: 'app_botanist_list_all_advice', methods: ['GET'])]
    public function show_all_advice(AdviceRepository $adviceRepository): Response
    {
        $advices = $adviceRepository->findAll();

        // Group advices by status
        $groupedAdvices = [];
        foreach ($advices as $advice) {
            $statusName = $advice->getStatus()->getName();
            if (!isset($groupedAdvices[$statusName])) {
                $groupedAdvices[$statusName] = [];
            }
            $groupedAdvices[$statusName][] = $advice;
        }

        return $this->render('botanist/list_all_advice.html.twig', [
            'groupedAdvices' => $groupedAdvices,
        ]);
    }
}