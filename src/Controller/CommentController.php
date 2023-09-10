<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Service\OwnershipChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment')]
class CommentController extends AbstractController
{
    private OwnershipChecker $ownershipChecker;

    public function __construct(OwnershipChecker $ownershipChecker)
    {
        $this->ownershipChecker = $ownershipChecker;
    }

    #[Route('/{id}/edit', name: 'app_comment_edit', methods: ['POST'])]
    public function edit(Request $request, CommentRepository $commentRepository, int $id, Comment $comment): Response
    {
        $comment = $commentRepository->find($id);

        if (!$comment) {
            return new JsonResponse(['message' => 'The comment does not exist.'], 404);
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentRepository->save($comment, true);

            return new JsonResponse(['message' => 'Success!'], 200);
        } else {
            $errors = [];
            foreach ($form->getErrors(true, false) as $error) {
                $errors[] = $error->getMessage();
            }

            return new JsonResponse(['message' => 'Error!', 'errors' => $errors], 400);
        }
    }

    #[Route('/{id}/edit-form', name: 'app_comment_edit_form', methods: ['GET'])]
    public function editForm(CommentRepository $commentRepository, int $id, Comment $comment): Response
    {
        $comment = $commentRepository->find($id);

        if (!$comment) {
            return new JsonResponse(['message' => 'The comment does not exist.'], 404);
        }

        $form = $this->createForm(CommentType::class, $comment);

        $formView = $this->renderView('comment/_edit_form.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);

        return new JsonResponse(['form' => $formView], 200);
    }

    #[Route('/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(Request $request, Comment $comment, CommentRepository $commentRepository, int $id): Response
    {
        $ownershipCheck = $this->ownershipChecker->checkOwnership($comment);

        if ($ownershipCheck) {
            return $ownershipCheck;
        }

        if ($ownershipCheck) {
            return $this->redirectToRoute('app_advice_show', ['id' => $comment->getCommentAdvice()->getId()]);
        }

        $comment = $commentRepository->find($id);

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $commentRepository->remove($comment, true);
        }

        return $this->redirectToRoute('app_advice_show', ['id' => $comment->getCommentAdvice()->getId()]);
    }

    // private function checkCommentOwnership(Comment $comment): JsonResponse
    // {
    //     $user = $this->getUser();

    //     if (!$user || $user !== $comment->getUser()) {
    //         return new JsonResponse(['message' => 'An error has occured'], 403);
    //     }

    //     return null;
    // }
}
