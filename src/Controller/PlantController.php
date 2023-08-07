<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\Plant;
use App\Entity\User;
use App\Form\PlantType;
use App\Repository\PlantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/plant')]
class PlantController extends abstractController
{
	public function __construct(private SluggerInterface $slugger)
	{
	}
    #[Route('/', name: 'app_plant_index', methods: ['GET'])]
    public function index(PlantRepository $plantRepository): Response
    {
        //        Affichage des plantes de la plus récente à la plus ancienne
        $plants = $plantRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('plant/index.html.twig', [
            'plants' => $plants,
        ]);
    }

    #[Route('/new', name: 'app_plant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $plant = new Plant();

        $form = $this->createForm(PlantType::class, $plant);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

	        $certifData = $form->get('photos')->getData();


	        if ($certifData) {

				foreach ($certifData as $certif) {
					$currentTime = time();
					$newFilename = 'Photo_'.$this->getUser()->getFullName().'_'.$currentTime;
					$safeFilename = $this->slugger->slug($newFilename).'.'.$certif->guessExtension();

					try {
						$certif->move(
							$this->getParameter('photos_directory'),
							$safeFilename
						);
					} catch (FileException $e) {
						// ... handle exception if something happens during file upload
					}

					$photo = new Photo();

					$photo->setPhoto($safeFilename);

					$plant->addPhoto($photo);

					$entityManager->persist($photo);
				}
	        }

			$entityManager->persist($plant);

			$entityManager->flush();

            return $this->redirectToRoute('app_plant_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('plant/new.html.twig', [
            'plant' => $plant,
            'form' => $form,
	        'error' => $form->getErrors()->current(),
        ]);
    }

    #[Route('/{id}', name: 'app_plant_show', methods: ['GET'])]
    public function show(Plant $plant): Response
    {
        return $this->render('plant/show.html.twig', [
            'plant' => $plant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_plant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plant $plant, PlantRepository $plantRepository): Response
    {
        $form = $this->createForm(PlantType::class, $plant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plantRepository->save($plant, true);

            return $this->redirectToRoute('app_plant_show', ['id' => $plant->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('plant/edit.html.twig', [
            'plant' => $plant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_plant_delete', methods: ['POST'])]
    public function delete(Request $request, Plant $plant, PlantRepository $plantRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plant->getId(), $request->request->get('_token'))) {
            $plantRepository->remove($plant, true);
        }

        return $this->redirectToRoute('app_plant_index', [], Response::HTTP_SEE_OTHER);
    }
}
