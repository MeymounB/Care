<?php

namespace App\Controller;

use App\Entity\Botanist;
use App\Entity\Particular;
use App\Entity\User;
use App\Repository\AdviceRepository;
use App\Repository\AppointmentRepository;
use App\Repository\PlantRepository;
use App\Repository\UserRepository;
use App\Service\AdviceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/mon-profil')]
class ProfilController extends AbstractController
{
    #[Route('', name: 'app_profil_index', methods: ['GET'])]
    public function index(
        PlantRepository $plantRepository,
        AdviceRepository $adviceRepository,
        AppointmentRepository $appointmentRepository,
        AdviceService $adviceService
    ): Response {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Access denied');
        }

        $plants = $plantRepository->findBy([], ['createdAt' => 'DESC']);

        $plantesPossedees = $plantRepository->findAll();
        $nombrePlantesPossedees = count($plantesPossedees);

        $conseilsDemandes = $adviceRepository->findAll();
        $nombreDeConseilsDemandes = count($conseilsDemandes);

        $nombreDeRendezVous = $appointmentRepository->findAll();
        $nombreDeRendezVous = count($nombreDeRendezVous);

        $nombreDeRendezVousEnCours = $appointmentRepository->countAppointmentsByStatus(2);
        $nombreDeRendezVousAnnules = $appointmentRepository->countAppointmentsByStatus(4);

        $nombreDeConseils = $adviceService->getRecentActivityByUser($user);
        $nombreDeConseilsDonnes = count($nombreDeConseils);

        if ($user instanceof Particular) {
            return $this->render('user/profil.html.twig', [
                'user' => $user,
                'plants' => $plants,
                'nombreDeRendezVous' => $nombreDeRendezVous,
                'plantesPossedees' => $nombrePlantesPossedees,
                'conseilsDemandes' => $nombreDeConseilsDemandes,
            ]);
        } elseif ($user instanceof Botanist) {
            return $this->render('botanist/profil.html.twig', [
                'user' => $user,
                'nombreDeRendezVousEnCours' => $nombreDeRendezVousEnCours,
                'nombreDeRendezVousAnnules' => $nombreDeRendezVousAnnules,
                'nombreDeConseilsDonnes' => $nombreDeConseilsDonnes,
            ]);
        } else {
            throw $this->createNotFoundException('Type d\'utilisateur non pris en charge.');
        }
    }

    #[Route('/{id}', name: 'app_profil_delete', methods: ['POST'])]
    public function delete(Request $request, #[CurrentUser] ?User $user, UserRepository $userRepository, TokenStorageInterface $tokenStorage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
            $tokenStorage->setToken(null);
            $request->getSession()->invalidate();

            $this->addFlash('success', 'Votre compte a été supprimé avec succès');
        } else {
            $this->addFlash('danger', 'Une erreur est survenue lors de la suppression de votre compte');
        }

        return $this->redirectToRoute('app_default', [], Response::HTTP_SEE_OTHER);
    }
}
