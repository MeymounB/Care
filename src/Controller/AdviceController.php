<?php

namespace App\Controller;

use App\Entity\Advice;
use App\Entity\Comment;
use App\Entity\Particular;
use App\Entity\User;
use App\Form\AdviceType;
use App\Form\CommentType;
use App\Repository\AdviceRepository;
use App\Repository\PlantRepository;
use App\Repository\StatusRepository;
use App\Service\AdviceService;
use App\Service\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/advice')]
class AdviceController extends AbstractController
{
    private AdviceService $adviceService;
    private PlantRepository $plantRepository;

    public function __construct(AdviceService $adviceService, PlantRepository $plantRepository)
    {
        $this->adviceService = $adviceService;
        $this->plantRepository = $plantRepository;
    }

    #[Route('/', name: 'app_advice_index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Access denied');
        }

        $groupedAdvices = $this->adviceService->getAdvicesWaitingByUser($user->getId(), true);

        return $this->render('advice/index.html.twig', [
            'groupedAdvices' => $groupedAdvices,
        ]);
    }

    #[Route('/new', name: 'app_advice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AdviceRepository $adviceRepository, StatusRepository $statusRepository): Response
    {
        $user = $this->getUser();

        if (!$user instanceof Particular) {
            throw $this->createAccessDeniedException('Access denied');
        }

        $advice = new Advice();

        $defaultStatus = $statusRepository->findOneBy(['name' => 'En attente']);
        if (null !== $defaultStatus) {
            $advice->setStatus($defaultStatus);
        }

        $form = $this->createForm(AdviceType::class, $advice, [
            'plants' => $this->plantRepository->findBy(['particular' => $user->getId()]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $advice->setParticular($user);
            $adviceRepository->save($advice, true);

            return $this->redirectToRoute('app_advice_index', [], Response::HTTP_SEE_OTHER);
        }

        $plants = $this->plantRepository->findBy(['particular' => $user->getId()], ['createdAt' => 'DESC']);

        return $this->renderForm('advice/new.html.twig', [
            'advice' => $advice,
            'plants' => $plants,
            'form' => $form,
            'error' => $form->getErrors()->current(),
        ]);
    }

    #[Route('/show/{id}', name: 'app_advice_show', methods: ['GET', 'POST'])]
    public function show(Request $request, AdviceRepository $adviceRepository, CommentService $commentService, Security $security, int $id): Response
    {
        $advice = $adviceRepository->find($id);
        // $advice = $adviceRepository->findWithSortedComments($id);

        $plants = $advice->getPlants();
        $firstPlant = $plants->first();

        $photo = null;
        if ($firstPlant) {
            $photos = $firstPlant->getPhotos();
            // dd($photos);
            $photo = $photos->first();
        }

        // Create form for adding a new comment
        $newComment = new Comment();
        $form = $this->createForm(CommentType::class, $newComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // get the current user logged in
            $user = $security->getUser();
            $commentService->addComment($form->get('content')->getData(), $advice, $user);

            return $this->redirectToRoute('app_advice_show', ['id' => $id]);
        }

        return $this->render('advice/show.html.twig', [
            'advice' => $advice,
            'form' => $form->createView(),
            'photo' => $photo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_advice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AdviceRepository $adviceRepository, int $id): Response
    {
        $advice = $adviceRepository->find($id);

        $form = $this->createForm(AdviceType::class, $advice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adviceRepository->save($advice, true);

            return $this->redirectToRoute('app_advice_show', ['id' => $advice->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('advice/edit.html.twig', [
            'advice' => $advice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_advice_delete', methods: ['DELETE'])]
    public function delete(Request $request, Advice $advice, AdviceRepository $adviceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$advice->getId(), $request->request->get('_token'))) {
            $adviceRepository->remove($advice, true);
        }

        return $this->redirectToRoute('app_advice_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/recent_advice', name: 'app_advice_list_recent_advice', methods: ['GET'])]
    public function show_recent_advice(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Access denied');
        }

        $groupedAdvices = $this->adviceService->getGroupedAdvicesByUser($user);

        return $this->render('advice/recent_advice.html.twig', [
            'groupedAdvices' => $groupedAdvices,
        ]);
    }

    #[Route('/my_advice', name: 'app_advice_list_my_advice', methods: ['GET'])]
    public function show_my_advice(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Access denied');
        }

        $groupedAdvices = $this->adviceService->getOwnAdviceByUser($user->getId());

        return $this->render('advice/index.html.twig', [
            'groupedAdvices' => $groupedAdvices,
        ]);
    }
}
