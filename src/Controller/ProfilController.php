<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Botanist;
use App\Entity\Particular;
use App\Service\AdviceService;
use App\Repository\UserRepository;
use App\Repository\PlantRepository;
use App\Repository\AdviceRepository;
use App\Form\Registration\ParticularFormType;
use App\Repository\AppointmentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/mon-profil')]
class ProfilController extends AbstractController
{
    private UserPasswordHasherInterface $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

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

        $plants = $plantRepository->findBy(['particular' => $user->getId()]);
        $nombrePlantesPossedees = count($plants);

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
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
            $tokenStorage->setToken(null);
            $request->getSession()->invalidate();

            $this->addFlash('success', 'Votre compte a été supprimé avec succès');
        } else {
            $this->addFlash('danger', 'Une erreur est survenue lors de la suppression de votre compte');
        }

        return $this->redirectToRoute('app_default', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/edit-form', name: 'app_profil_edit_form', methods: ['GET'])]
    public function editForm(#[CurrentUser] ?User $user): Response
    {
        // $user = $userRepository->find($user->getId());
        $user = $this->getUser();
        $form = $this->createForm(ParticularFormType::class, $user);

        $formView = $this->renderView('profil/_form.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);

        return new JsonResponse(['form' => $formView], 200);
    }

    #[Route('/edit', name: 'app_profile_edit', methods: ['POST'])]
    public function editProfile(Request $request, User $user, UserRepository $userRepository): Response
    {
        // $user = $this->getUser();

        $form = $this->createForm(ParticularFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // if ($form->get('password')->getData()) {
            //     $user->setPassword(
            //         $this->passwordEncoder->hashPassword($user, $form->get('password')->getData())
            //     );
            // }

            $userRepository->save($user, true);
            return new JsonResponse(['message' => 'Success!'], 200);
        } else {
            $errors = [];
            foreach ($form->getErrors(true, false) as $error) {
                $errors[] = $error->getMessage();
            }

            return new JsonResponse(['message' => 'Error!', 'errors' => $errors], 400);
        }
    }
}
