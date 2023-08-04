<?php

namespace App\Controller;

use App\Entity\Botanist;
use App\Entity\User;
use App\Repository\AdviceRepository;
use App\Repository\AppointmentRepository;
use App\Repository\CommentRepository;
use App\Repository\StatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

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

    #[Route('/recent_advice', name: 'app_botanist_list_recent_advice', methods: ['GET'])]
    public function show_recent_advice(AdviceRepository $adviceRepository, CommentRepository $commentRepository): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $userId = $user->getId();

        $userComments = $commentRepository->findBy(['user' => $userId], ['createdAt' => 'DESC']);

        $adviceIds = [];

        // Parcourir les commentaires et récupérer les IDs des conseils associés
        foreach ($userComments as $comment) {
            $commentAdvice = $comment->getCommentAdvice();
            if (null !== $commentAdvice) {
                $adviceIds[] = $commentAdvice->getId();
            }
        }

        // Récupérer les conseils associés aux IDs récupérés
        $advices = $adviceRepository->findBy(['id' => $adviceIds]);

        // Grouper les conseils par statut
        $groupedAdvices = [];
        foreach ($advices as $advice) {
            $statusName = $advice->getStatus()->getName();

            if (!isset($groupedAdvices[$statusName])) {
                $groupedAdvices[$statusName] = [];
            }

            $groupedAdvices[$statusName][] = $advice;
        }

        // Trier les commentaires de chaque conseil par date de création (du plus récent au moins récent)
        foreach ($groupedAdvices as $statusName => &$adviceGroup) {
            usort($adviceGroup, function ($advice1, $advice2) {
                // Compare les dates de création du dernier commentaire de chaque conseil
                $lastComment1 = $advice1->getComments()->last();
                $lastComment2 = $advice2->getComments()->last();

                if ($lastComment1 && $lastComment2) {
                    return $lastComment2->getCreatedAt() <=> $lastComment1->getCreatedAt();
                } elseif ($lastComment1) {
                    return -1;
                } elseif ($lastComment2) {
                    return 1;
                } else {
                    return 0;
                }
            });
        }

        return $this->render('botanist/recent_advice.html.twig', [
            'groupedAdvices' => $groupedAdvices,
        ]);
    }

    #[Route('/appointments', name: 'app_botanist_appointments', methods: ['GET'])]
    public function list_appointments(AppointmentRepository $appointmentRepository): Response
    {
        //        Récupération des rendez-vous en attente (donc sans botaniste associé), trié par plannification de la plus proche à la plus éloignée
        $appointments = $appointmentRepository->findBy(['status' => 45], ['plannedAt' => 'ASC']);

        // Group appointments by status
        $groupedAppointments = [];
        foreach ($appointments as $appointment) {
            $statusName = $appointment->getStatus()->getName();
            if (!isset($groupedAppointments[$statusName])) {
                $groupedAppointments[$statusName] = [];
            }
            $groupedAppointments[$statusName][] = $appointment;
        }

        return $this->render('botanist/appointments.html.twig', [
            'groupedAppointments' => $groupedAppointments,
        ]);
    }

    #[Route('/appointments/{id}', name: 'app_botanist_accept_appointment', methods: ['GET'])]
    public function accept_appointment(AppointmentRepository $appointmentRepository, $id, StatusRepository $statusRepository, Security $security): Response
    {
        $user = $security->getUser();

        if (!$user instanceof Botanist) {
            throw $this->createAccessDeniedException('Access denied');
        }

        $status = $statusRepository->findOneBy(['name' => 'En cours']);

        $appointment = $appointmentRepository->find($id);

        if (!$appointment) {
            throw $this->createNotFoundException('Appointment introuvable');
        }

        $appointment->setStatus($status);
        $appointment->setBotanist($user);

        $appointmentRepository->save($appointment, true);

        return $this->render('botanist/dashboard.html.twig');
    }
}
