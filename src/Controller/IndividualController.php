<?php

namespace App\Controller;

use App\Entity\Particular;
use App\Service\AdviceService;
use App\Service\AppointmentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndividualController extends abstractController
{
    private AdviceService $adviceService;
    private AppointmentService $appointmentService;

    public function __construct(AdviceService $adviceService, AppointmentService $appointmentService)
    {
        $this->adviceService = $adviceService;
        $this->appointmentService = $appointmentService;
    }

    #[Route('/user_dashboard', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof Particular) {
            throw $this->createAccessDeniedException('Access denied');
        }

        $incomming_appointments = $this->appointmentService->getAppointmentsByIndividual($user->getId(), 5);
        $appointments = $this->appointmentService->getPendingAppointments(5);
        $appointment_count = $this->appointmentService->countPendingAppointments();
        $advices = $this->adviceService->getAdvicesWaitingByUser($user->getId());
        $recent_activity = $this->adviceService->getRecentActivityByUser($user, 10);
        $advice_count = $this->adviceService->countAdvicesByUser($user);

        return $this->render('user/home.html.twig', [
            'incomming_appointments' => $incomming_appointments,
            'advices' => $advices,
            'appointments' => $appointments,
            'appointment_count' => $appointment_count,
            'advice_count' => $advice_count,
            'recent_activity' => $recent_activity,
        ]);
    }
}
