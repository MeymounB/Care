<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\User;
use App\Form\AppointmentType;
use App\Repository\AppointmentRepository;
use App\Repository\StatusRepository;
use App\Service\AppointmentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/appointment')]
class AppointmentController extends AbstractController
{
    private AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    #[Route('/', name: 'app_appointment_index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Access denied');
        }

        $groupedAppointments = $this->appointmentService->getGroupedAppointmentsByParticular($user->getId());

        return $this->render('appointment/index.html.twig', [
            'groupedAppointments' => $groupedAppointments,
        ]);
    }

    #[Route('/new', name: 'app_appointment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AppointmentRepository $appointmentRepository, StatusRepository $statusRepository): Response
    {
        $appointment = new Appointment();

        $defaultStatus = $statusRepository->findOneBy(['name' => 'En attente']);
        if (null !== $defaultStatus) {
            $appointment->setStatus($defaultStatus);
        }

        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appointmentRepository->save($appointment, true);

            return $this->redirectToRoute('app_appointment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appointment/new.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_appointment_show', methods: ['GET'])]
    public function show(int $id, AppointmentService $service): Response
    {
        $appointment = $service->getById($id);

        return $this->render('appointment/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_appointment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, AppointmentService $service, AppointmentRepository $appointmentRepository): Response
    {
        $appointment = $service->getById($id);

        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appointmentRepository->save($appointment, true);

            return $this->redirectToRoute('app_appointment_show', ['id' => $appointment->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appointment/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_appointment_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, AppointmentService $service, AppointmentRepository $appointmentRepository): Response
    {
        $appointment = $service->getById($id);

        if ($this->isCsrfTokenValid('delete'.$appointment->getId(), $request->request->get('_token'))) {
            $appointmentRepository->remove($appointment, true);
        }

        return $this->redirectToRoute('app_appointment_index', [], Response::HTTP_SEE_OTHER);
    }
}
