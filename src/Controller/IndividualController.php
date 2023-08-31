<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Entity\Particular;
use App\Service\AdviceService;
use App\Service\AppointmentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndividualController extends abstractController
{
    private AdviceService $adviceService;
    private AppointmentService $appointmentService;

    public function __construct(AdviceService $adviceService, AppointmentService $appointmentService)
    {
        $this->adviceService = $adviceService;
        $this->appointmentService = $appointmentService;
    }

    #[Route('/user_dashboard', name: 'app.user.index', methods: ['GET'])]
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

    #[Route('/user_dashboard/address', name: 'app.user.edit.address', methods: ['GET', 'POST'])]
    public function editAddress(Request $request, #[CurrentUser] ?Particular $user, EntityManagerInterface $manager): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setParticular($user);

            $manager->persist($address);
            $manager->flush();

            $this->addFlash("success", "Votre adresse a bien été modifiée");

            return $this->redirectToRoute('app.user.index');
        }

        return $this->render('user/edit_address.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
