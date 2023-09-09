<?php

namespace App\Controller;

use App\Entity\Botanist;
use App\Repository\AppointmentRepository;
use App\Repository\StatusRepository;
use App\Service\AdviceService;
use App\Service\AppointmentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard', name: 'app_botanist_')]
class BotanistController extends AbstractController
{
    private $adviceService;
    private $appointmentService;

    public function __construct(AdviceService $adviceService, AppointmentService $appointmentService)
    {
        $this->adviceService = $adviceService;
        $this->appointmentService = $appointmentService;
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof Botanist) {
            throw $this->createAccessDeniedException('Access denied');
        }

        $incomming_appointments = $this->appointmentService->getAppointmentsByBotanist($user->getId(), 5);
        $appointments = $this->appointmentService->getPendingAppointments(5);
        $appointment_count = $this->appointmentService->countPendingAppointments();
        $advices = $this->adviceService->getRecentActivityByUser($user, 10);
        $advice_count = $this->adviceService->countAdvicesByUser($user);

        return $this->render('botanist/dashboard.html.twig', [
            'incomming_appointments' => $incomming_appointments,
            'advices' => $advices,
            'appointments' => $appointments,
            'appointment_count' => $appointment_count,
            'advice_count' => $advice_count,
        ]);
    }

    #[Route('/all_advice', name: 'list_all_advice', methods: ['GET'])]
    public function show_all_advice(): Response
    {
        $groupedAdvices = $this->adviceService->getGroupedAdvices();

        return $this->render('botanist/list_all_advice.html.twig', [
            'groupedAdvices' => $groupedAdvices,
        ]);
    }

    #[Route('/appointments', name: 'appointments', methods: ['GET'])]
    public function list_appointments(): Response
    {
        $groupedAppointments = $this->appointmentService->getPendingAppointmentsGroupedByStatus();

        return $this->render('botanist/appointments.html.twig', [
            'groupedAppointments' => $groupedAppointments,
        ]);
    }

    #[Route('/appointments/{id?}', name: 'accept_appointment', methods: ['POST'])]
    public function accept_appointment(?int $id, AppointmentRepository $appointmentRepository, StatusRepository $statusRepository): Response
    {
        $user = $this->getUser();

        if (!$user instanceof Botanist) {
            throw $this->createAccessDeniedException('Access denied');
        }

        $statusNames = ['En cours'];
        $status = $statusRepository->findOneBy(['name' => $statusNames]);

        $appointment = $appointmentRepository->find($id);

        if (!$appointment) {
            throw $this->createNotFoundException('Appointment introuvable');
        }

        $appointment->setStatus($status);
        $appointment->setBotanist($user);

        $appointmentRepository->save($appointment, true);

        return $this->redirectToRoute('app_botanist_index');
    }

    #[Route('/change_appointment_status', name: 'change_appointment_status', methods: ['GET'])]
    public function change_appointment_status(AppointmentRepository $appointmentRepository, StatusRepository $statusRepository, Request $request): Response
    {
        $status_name = $request->get('status_name');
        $appointment_id = $request->get('id');

        $status = $statusRepository->findOneBy(['name' => $status_name]);
        $appointment = $appointmentRepository->find($appointment_id);

        if (!$appointment) {
            throw $this->createNotFoundException('Appointment introuvable');
        }

        $appointment->setStatus($status);

        if ('En attente' === $status_name) {
            $appointment->setBotanist(null);
        }

        $appointmentRepository->save($appointment, true);

        return $this->redirectToRoute('app_appointement_incoming_appointment');
    }
}
