<?php

namespace App\Controller;

use App\Entity\Botanist;
use App\Service\AdviceService;
use App\Service\AppointmentService;
use App\Repository\StatusRepository;
use App\Repository\AppointmentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        return $this->render('botanist/dashboard.html.twig');
    }

    #[Route('/all_advice', name: 'list_all_advice', methods: ['GET'])]
    public function show_all_advice(): Response
    {
        $groupedAdvices = $this->adviceService->getGroupedAdvices();

        return $this->render('botanist/list_all_advice.html.twig', [
            'groupedAdvices' => $groupedAdvices,
        ]);
    }

    #[Route('/recent_advice', name: 'list_recent_advice', methods: ['GET'])]
    public function show_recent_advice(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $groupedAdvices = $this->adviceService->getGroupedAdvicesByUser($user);

        return $this->render('botanist/recent_advice.html.twig', [
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


    #[Route('/incoming_appointment', name: 'incoming_appointment', methods: ['GET'])]
    public function incoming_appointment(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof Botanist) {
            throw $this->createAccessDeniedException('Access denied');
        }

        $groupedAppointments = $this->appointmentService->getGroupedAppointmentsByBotanist($user->getId());

        return $this->render('botanist/incoming_appointments.html.twig', [
            'groupedAppointments' => $groupedAppointments,
        ]);
    }

    #[Route('/appointments/{id}', name: 'accept_appointment', methods: ['GET'])]
    public function accept_appointment(AppointmentRepository $appointmentRepository, $id, StatusRepository $statusRepository): Response
    {
        $user = $this->getUser();

        if (!$user instanceof Botanist) {
            throw $this->createAccessDeniedException('Access denied');
        }

        $statusNames = ['En cours', 'Terminé'];
        $status = $statusRepository->findOneBy(['name' => $statusNames]);

        $appointment = $appointmentRepository->find($id);

        if (!$appointment) {
            throw $this->createNotFoundException('Appointment introuvable');
        }

        $appointment->setStatus($status);
        $appointment->setBotanist($user);

        $appointmentRepository->save($appointment, true);

        return $this->redirectToRoute('index');
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

        return $this->redirectToRoute('incoming_appointment');
    }
}
