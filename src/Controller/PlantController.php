<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\Plant;
use App\Form\PlantType;
use App\Entity\Particular;
use App\Service\FileType;
use App\Service\OwnershipChecker;
use App\Repository\PlantRepository;
use App\Service\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/plant')]
class PlantController extends AbstractController
{

    private OwnershipChecker $ownershipChecker;
    private FileUploaderService $fileUploaderService;

    public function __construct(OwnershipChecker $ownershipChecker, FileUploaderService $fileUploaderService)
    {
        $this->ownershipChecker = $ownershipChecker;
    }

    #[Route('/', name: 'app_plant_index', methods: ['GET'])]
    public function index(PlantRepository $plantRepository): Response
    {
        // Display plants from the most recent to the oldest
        $plants = $plantRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('plant/index.html.twig', [
            'plants' => $plants,
        ]);
    }

    // #[Route('/new', name: 'app_plant_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager, #[CurrentUser] ?Particular $user): Response
    // {
    //     $plant = new Plant();

    //     $form = $this->createForm(PlantType::class, $plant);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $certifData = $form->get('photos')->getData();

    //         if ($certifData) {
    //             foreach ($certifData as $key => $certif) {
    //                 $this->fileUploaderService->setType(FileType::PHOTO);

    //                 $safeFilename = $this->fileUploaderService->getFilename($key, $user->getFullName(), $certif)['file'];
    //                 $this->fileUploaderService->upload($safeFilename, $certif);
    //                 $photo = new Photo();
    //                 $photo->setPhoto($safeFilename);
    //                 $plant->addPhoto($photo);
    //                 $entityManager->persist($photo);
    //             }
    //         }
    //         $plant->setParticular($this->getUser());

    //         $entityManager->persist($plant);

    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_plant_index');
    //     }

    //     return $this->renderForm('plant/new.html.twig', [
    //         'plant' => $plant,
    //         'form' => $form,
    //         'error' => $form->getErrors()->current(),
    //     ]);
    // }


    #[Route('/{id}/edit', name: 'app_plant_edit', methods: ['POST'])]
    public function edit(Request $request, PlantRepository $plantRepository, int $id, Plant $plant): Response
    {
        $plant = $plantRepository->find($id);

        if (!$plant) {
            return new JsonResponse(['message' => 'The plant does not exist.'], 404);
        }

        $ownershipCheck = $this->ownershipChecker->checkOwnership($plant);

        if ($ownershipCheck) {
            return $ownershipCheck;
        }

        $form = $this->createForm(plantType::class, $plant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plantRepository->save($plant, true);
            return new JsonResponse(['message' => 'Success!'], 200);
        } else {
            $errors = [];
            foreach ($form->getErrors(true, false) as $error) {
                $errors[] = $error->getMessage();
            }

            return new JsonResponse(['message' => 'Error!', 'errors' => $errors], 400);
        }
    }

    #[Route('/{id}/edit-form', name: 'app_plant_edit_form', methods: ['GET'])]
    public function editForm(PlantRepository $PlantRepository, int $id, plant $plant): Response
    {
        $plant = $PlantRepository->find($id);

        if (!$plant) {
            return new JsonResponse(['message' => 'The plant does not exist.'], 404);
        }

        $ownershipCheck = $this->ownershipChecker->checkOwnership($plant);

        if ($ownershipCheck) {
            return $ownershipCheck;
        }

        $form = $this->createForm(plantType::class, $plant);

        $formView = $this->renderView('plant/_plant_form_popup.html.twig', [
            'plant' => $plant,
            'form' => $form->createView(),
        ]);

        return new JsonResponse(['form' => $formView], 200);
    }

    #[Route('/{id}', name: 'app_plant_delete', methods: ['POST'])]
    public function delete(Request $request, $id, PlantRepository $plantRepository): Response
    {
        $plant = $plantRepository->find($id);

        if (!$plant) {
            throw $this->createNotFoundException('Plant not found');
        }

        if ($this->isCsrfTokenValid('delete' . $plant->getId(), $request->request->get('_token'))) {
            $plantRepository->remove($plant, true);
        }

        return $this->redirectToRoute('app_profil_index', [], Response::HTTP_SEE_OTHER);
    }
}
