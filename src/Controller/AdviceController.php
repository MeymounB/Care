<?php

namespace App\Controller;

use App\Entity\Advice;
use App\Repository\AdviceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AdviceType;
use App\Repository\StatusRepository;

#[Route('/advice')]
class AdviceController extends AbstractController
{
    #[Route('/', name: 'app_advice_index', methods: ['GET'])]
    public function index(AdviceRepository $adviceRepository): Response
    {
        $advices = $adviceRepository->findAll();

        //Group advices by status
        $groupedAdvices = [];
        foreach ($advices as $advice) {
            $statusName = $advice->getStatus()->getName();
            if (!isset($groupedAdvices[$statusName])) {
                $groupedAdvices[$statusName] = [];
            }
            $groupedAdvices[$statusName][] = $advice;
        }

        return $this->render('advice/index.html.twig', [
            'groupedAdvices' => $groupedAdvices,
        ]);
    }

    #[Route('/new', name: 'app_advice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AdviceRepository $adviceRepository, StatusRepository $statusRepository): Response
    {
        $advice = new Advice();

        $defaultStatus = $statusRepository->findOneBy(['name' => 'En attente']);
        if (null !== $defaultStatus) {
            $advice->setStatus($defaultStatus);
        }

        $form = $this->createForm(AdviceType::class, $advice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adviceRepository->save($advice, true);

            return $this->redirectToRoute('app_advice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('advice/new.html.twig', [
            'advice' => $advice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_advice_show', methods: ['GET'])]
    public function show(AdviceRepository $adviceRepository, int $id): Response
    {
        $advice = $adviceRepository->find($id);

        return $this->render('advice/show.html.twig', [
            'advice' => $advice,
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

            return $this->redirectToRoute('app_advice_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('advice/edit.html.twig', [
            'advice' => $advice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_advice_delete', methods: ['POST'])]
    public function delete(Request $request, AdviceRepository $adviceRepository, int $id): Response
    {
        $advice = $adviceRepository->find($id);
        if ($this->isCsrfTokenValid('delete' . $advice->getId(), $request->request->get('_token'))) {
            $adviceRepository->remove($advice, true);
        }

        return $this->redirectToRoute('app_advice_index', [], Response::HTTP_SEE_OTHER);
    }
}
