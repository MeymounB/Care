<?php

namespace App\Controller;

use App\Entity\Advice;
use App\Entity\Comment;
use App\Form\AdviceType;
use App\Form\CommentType;
use App\Repository\AdviceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\StatusRepository;
use App\Service\CommentService;
use App\Repository\CommentRepository;
use Symfony\Component\Security\Core\Security;


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

            $comment = new Comment();
            $comment->setContent($form->get('new_comment')->get('content')->getData());
            $comment->setCreatedAt(new \DateTimeImmutable());
            // $comment->setUser($this->getUser());
            $comment->setCommentAdvice($advice);

            $advice->addComment($comment);

            $adviceRepository->save($advice, true);

            return $this->redirectToRoute('app_advice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('advice/new.html.twig', [
            'advice' => $advice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_advice_show', methods: ['GET', 'POST'])]
    public function show(Request $request, AdviceRepository $adviceRepository, CommentService $commentService, CommentRepository $commentRepository, Security $security, int $id): Response
    {
        $advice = $adviceRepository->find($id);

        // Create form for adding a new comment
        $newComment = new Comment();
        $form = $this->createForm(CommentType::class, $newComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentService->addComment($form->get('content')->getData(), $advice);
            return $this->redirectToRoute('app_advice_show', ['id' => $id]);
        }

        // Create forms for editing and deleting existing comments
        $comments = $commentRepository->findBy(['commentAdvice' => $advice]);
        $editForms = [];
        $deleteForms = [];
        foreach ($comments as $comment) {
            if ($security->getUser() === $comment->getUser()) {
                $editForms[$comment->getId()] = $this->createForm(CommentType::class, $comment)->createView();
                $deleteForms[$comment->getId()] = $this->createDeleteForm($comment)->createView();
            }
        }

        return $this->render('advice/show.html.twig', [
            'advice' => $advice,
            'form' => $form->createView(),
            'editForms' => $editForms,
            'deleteForms' => $deleteForms,
        ]);
    }

    private function createDeleteForm(Comment $comment)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_comment_delete', ['id' => $comment->getId()]))
            ->setMethod('DELETE')
            ->getForm();
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

    #[Route('/{id}', name: 'app_advice_delete', methods: ['POST'])]
    public function delete(Request $request, AdviceRepository $adviceRepository, int $id): Response
    {
        $advice = $adviceRepository->find($id);
        if ($this->isCsrfTokenValid('delete' . $advice->getId(), $request->request->get('_token'))) {
            $adviceRepository->remove($advice, true);
        }

        return $this->redirectToRoute('app_advice_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function deleteComment(Request $request, CommentRepository $commentRepository, int $id): Response
    {
        $comment = $commentRepository->find($id);
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $commentRepository->remove($comment, true);
        }

        return $this->redirectToRoute('app_advice_index', [], Response::HTTP_SEE_OTHER);
    }
}
