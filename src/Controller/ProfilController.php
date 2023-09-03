<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PlantRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AdviceRepository;
use App\Repository\AppointmentRepository;

#[Route('/mon-profil')]
class ProfilController extends AbstractController
{
    #[Route('', name: 'app_profil_index', methods: ['GET'])]
    public function index(
        PlantRepository $plantRepository,
        AdviceRepository $adviceRepository,
        AppointmentRepository $appointmentRepository
    ): Response {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Access denied');
        }

        $plants = $plantRepository->findBy([], ['createdAt' => 'DESC']);

        $plantesPossedees = $plantRepository->findAll();
        $nombrePlantesPossedees = count($plantesPossedees);

        $conseilsDemandes = $adviceRepository->findAll();
        $nombreDeConseilsDemandes = count($conseilsDemandes);

        $nombreDeRendezVous = $appointmentRepository->findAll();
        $nombreDeRendezVous = count($nombreDeRendezVous);

        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'plants' => $plants,
            'nombreDeRendezVous' => $nombreDeRendezVous,
            'plantesPossedees' => $nombrePlantesPossedees,
            'conseilsDemandes' => $nombreDeConseilsDemandes,
        ]);
    }


    #[Route('/{id}', name: 'app_profil_delete', methods: ['DELETE'])]
    public function delete(Request $request, User $user, userRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
            $this->addFlash('success', 'Votre compte a été supprimé avec succès');
        } else {
            $this->addFlash('danger', 'Une erreur est survenue lors de la suppression de votre compte');
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
